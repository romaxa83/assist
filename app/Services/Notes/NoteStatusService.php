<?php

namespace App\Services\Notes;

use App\Enums\Notes\NoteStatus;
use App\Exceptions\Notes\ChangeStatusException;
use App\Models\Notes\Note;
use Illuminate\Http\Response;

final class NoteStatusService
{
    public function __construct()
    {}

    public function setStatus(
        Note $model,
        NoteStatus|string $status,
        bool $save = true,
        bool $throwException = true,
    ): ?Note
    {
        $status = $status instanceof NoteStatus
            ? $status
            : NoteStatus::from($status);

        return make_transaction(function() use ($model, $status, $save, $throwException) {
            $model = $this->toggleStatus($model, $status, $save, $throwException);

            return $model;
        });
    }

    private function toggleStatus(
        Note $model,
        NoteStatus $status,
        bool $save = true,
        bool $throwException = true,
    ): ?Note
    {
        if($model->status->isDraft()){
            return match ($status) {
                NoteStatus::MODERATION => $this->toModerationFromDraft($model, $save),
                default => $throwException
                    ? throw new ChangeStatusException(code:Response::HTTP_UNPROCESSABLE_ENTITY)
                    : null
            };
        }

        if($model->status->isModeration()){
            return match ($status) {
                NoteStatus::DRAFT => $this->toDraftFromModeration($model, $save),
                NoteStatus::PUBLIC => $this->toPublicFromModeration($model, $save),
                NoteStatus::PRIVATE => $this->toPrivateFromModeration($model, $save),
                default => $throwException
                    ? throw new ChangeStatusException(code:Response::HTTP_UNPROCESSABLE_ENTITY)
                    : null
            };
        }

        if($model->status->isPublic()){
            return match ($status) {
                NoteStatus::PRIVATE => $this->toPrivateFromPublic($model, $save),
                NoteStatus::MODERATION => $this->toModerationFromPublic($model, $save),
                default => $throwException
                    ? throw new ChangeStatusException(code:Response::HTTP_UNPROCESSABLE_ENTITY)
                    : null
            };
        }

        if($model->status->isPrivate()){
            return match ($status) {
                NoteStatus::PUBLIC => $this->toPublicFromPrivate($model, $save),
                NoteStatus::MODERATION => $this->toModerationFromPrivate($model, $save),
                default => $throwException
                    ? throw new ChangeStatusException(code:Response::HTTP_UNPROCESSABLE_ENTITY)
                    : null
            };
        }

        return $throwException
            ? throw new ChangeStatusException(code:Response::HTTP_UNPROCESSABLE_ENTITY)
            : null;
    }

    private function toModerationFromDraft(Note $model, bool $save = true): Note
    {
        $model->status = NoteStatus::MODERATION;

        return $this->saveAndReturn($model, $save);
    }

    private function toDraftFromModeration(Note $model, bool $save = true): Note
    {
        $model->status = NoteStatus::DRAFT;

        return $this->saveAndReturn($model, $save);
    }

    private function toPublicFromModeration(Note $model, bool $save = true): Note
    {
        $model->status = NoteStatus::PUBLIC;

        $model->tags->increasePublicAttached($save);

        return $this->saveAndReturn($model, $save);
    }

    private function toPrivateFromModeration(Note $model, bool $save = true): Note
    {
        $model->status = NoteStatus::PRIVATE;

        $model->tags->increasePublicAttached($save);
        $model->tags->increasePrivateAttached($save);

        return $this->saveAndReturn($model, $save);
    }

    private function toPrivateFromPublic(Note $model, bool $save = true): Note
    {
        $model->status = NoteStatus::PRIVATE;

        $model->tags->increasePrivateAttached($save);

        return $this->saveAndReturn($model, $save);
    }

    private function toModerationFromPublic(Note $model, bool $save = true): Note
    {
        $model->status = NoteStatus::MODERATION;

        $model->tags->decreasePublicAttached($save);

        // проверяем и деактивируем ссылки на эту заметку в других заметках
        $service = app(NoteLinkService::class);
        $service->linkedNoteRemovePublic($model);

        return $this->saveAndReturn($model, $save);
    }

    private function toPublicFromPrivate(Note $model, bool $save = true): Note
    {
        $model->status = NoteStatus::PUBLIC;

        $model->tags->decreasePrivateAttached($save);

        return $this->saveAndReturn($model, $save);
    }

    private function toModerationFromPrivate(Note $model, bool $save = true): Note
    {
        $model->status = NoteStatus::MODERATION;

        $model->tags->decreasePublicAttached($save);
        $model->tags->decreasePrivateAttached($save);

        return $this->saveAndReturn($model, $save);
    }

    private function saveAndReturn(Note $model, bool $save = true): Note
    {
        if($save) $model->save();
        return $model;
    }
}
