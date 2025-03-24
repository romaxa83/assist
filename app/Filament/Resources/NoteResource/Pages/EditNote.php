<?php

namespace App\Filament\Resources\NoteResource\Pages;

use App\Dto\Notes\NoteDto;
use App\Filament\Resources\NoteResource;
use App\Models\Notes\Note;
use App\Services\Notes\NoteService;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

class EditNote extends EditRecord
{
    protected static string $resource = NoteResource::class;

    public function getTitle(): string | Htmlable
    {
        return __('system.notes.edit.page.title', [
            'title' => $this->getRecord()?->title,
        ]);
    }

    public function getBreadcrumb(): string
    {
        return __('system.notes.edit.page.breadcrumb');
    }

    public static function getNavigationLabel(): string
    {
        return __('system.notes.edit.page.navigation_label');
    }

    public function getSubheading(): string|Htmlable|null
    {

        return '🔸 🔹 ❗️ ✔️ ✅ ⚠️ ';
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function handleRecordUpdate($record, array $data): Model
    {
        /** @var $record Note */

        $dto = NoteDto::byArgs($data);

        $record = app(NoteService::class)
            ->update($record, $dto);

        return $record;

    }

    protected function getRedirectUrl(): ?string
    {
        return static::getResource()::getUrl('view', [$this->record]);
    }
}
