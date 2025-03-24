<?php

namespace App\Filament\Resources\NoteResource\Pages;

use App\Dto\Notes\NoteDto;
use App\Filament\Resources\NoteResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

class CreateNote extends CreateRecord
{
    protected static string $resource = NoteResource::class;

    protected function handleRecordCreation(array $data): Model
    {

        $dto = NoteDto::byArgs($data);

        $service = app(\App\Services\Notes\NoteService::class);
        $model = $service->create($dto);

        return $model;
    }

    public function getSubheading(): string|Htmlable|null
    {

        return '🔸 🔹 ❗️ ✔️ ✅ ⚠️ ';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

}
