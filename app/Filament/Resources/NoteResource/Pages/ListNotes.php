<?php

namespace App\Filament\Resources\NoteResource\Pages;

use App\Filament\Resources\NoteResource;
use App\Models\Notes\Note;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNotes extends ListRecords
{
    protected static string $resource = NoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTableQuery(): ?\Illuminate\Database\Eloquent\Builder
    {
        return parent::getTableQuery()
            ->orderBy(Note::DEFAULT_SORT_FIELD, Note::DEFAULT_SORT_TYPE);
    }


//    protected function query()
//    {
//        dd('D');
//        return parent::query()->orderBy('creatd_at', 'asc');
//    }

}
