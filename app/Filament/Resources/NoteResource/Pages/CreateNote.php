<?php

namespace App\Filament\Resources\NoteResource\Pages;

use App\Dto\Notes\NoteDto;
use App\Filament\Resources\NoteResource;
use Filament\Resources\Pages\CreateRecord;
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

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

}
