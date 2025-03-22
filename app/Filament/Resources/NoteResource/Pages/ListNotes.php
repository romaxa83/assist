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
        $orderDirection = request('tableSortDirection') ?? Note::DEFAULT_SORT_TYPE;
        $orderField = request('tableSortColumn') ?? Note::DEFAULT_SORT_FIELD;

        return parent::getTableQuery()
            ->with([
                'tags',
                'links',
                'blocks',
            ])
            ->orderBy($orderField, $orderDirection)
            ;
    }

    public function getTabs(): array
    {
        $statusCounts = Note::select('status', \DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return [
            'all' => Tab::make('All')
                ->badge(array_sum($statusCounts))
            ,
            NoteStatus::DRAFT->value => Tab::make()
                ->query(fn ($query) => $query->where('status', NoteStatus::DRAFT()))
                ->badge($statusCounts[NoteStatus::DRAFT()] ?? 0)
            ,
            NoteStatus::MODERATION->value => Tab::make()
                ->query(fn ($query) => $query->where('status', NoteStatus::MODERATION()))
                ->badge($statusCounts[NoteStatus::MODERATION()] ?? 0)
            ,
            NoteStatus::PUBLIC->value => Tab::make()
                ->query(fn ($query) => $query->where('status', NoteStatus::PUBLIC()))
                ->badge($statusCounts[NoteStatus::PUBLIC->value] ?? 0)
            ,
            NoteStatus::PRIVATE->value => Tab::make()
                ->query(fn ($query) => $query->where('status', NoteStatus::PRIVATE()))
                ->badge($statusCounts[NoteStatus::PRIVATE->value] ?? 0)
            ,
        ];
    }

}
