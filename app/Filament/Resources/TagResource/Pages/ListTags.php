<?php

namespace App\Filament\Resources\TagResource\Pages;

use App\Dto\Tags\TagDto;
use App\Filament\Resources\TagResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTags extends ListRecords
{
    protected static string $resource = TagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->using(function ($data) {
                    $service = app(\App\Services\Tags\TagService::class);
                    return $service->create(TagDto::byArgs($data));
            }),
        ];
    }
}
