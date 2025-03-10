<?php

namespace App\Filament\Resources\NoteResource\Pages;

use App\Enums\Notes\NoteStatus;
use App\Filament\Resources\NoteResource;
use App\Models\Notes\Note;
use Filament\Actions;
use Filament\Resources\Components\Tab;
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


    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All'),
            NoteStatus::DRAFT() => Tab::make()->query(fn ($query) => $query->where('status', NoteStatus::DRAFT())),
            NoteStatus::MODERATION() => Tab::make()->query(fn ($query) => $query->where('status', NoteStatus::MODERATION())),
            NoteStatus::PUBLIC() => Tab::make()->query(fn ($query) => $query->where('status', NoteStatus::PUBLIC())),
            NoteStatus::PRIVATE() => Tab::make()->query(fn ($query) => $query->where('status', NoteStatus::PRIVATE())),
        ];
    }

}
