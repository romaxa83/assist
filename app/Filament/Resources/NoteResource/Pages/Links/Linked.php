<?php

namespace App\Filament\Resources\NoteResource\Pages\Links;

use App\Filament\Resources\NoteResource;
use App\Models\Notes\Link;
use App\Models\Notes\Note;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;

class Linked extends ManageRelatedRecords
{
    protected static string $resource = NoteResource::class;

    protected static string $relationship = 'linkeds';

    protected static ?string $navigationIcon = 'heroicon-o-link';

    public function getTitle(): string | Htmlable
    {
        return __('system.note_linked.page.title');
    }

    public function getSubheading(): string | Htmlable
    {
        $recordTitle = $this->getRecordTitle();

        $recordTitle = $recordTitle instanceof Htmlable ? $recordTitle->toHtml() : $recordTitle;

        return __('system.note_linked.page.sub_title', [
            'title' => $recordTitle,
        ]);
    }

    public function getBreadcrumb(): string
    {
        return __('system.note_linked.page.breadcrumb');
    }

    public static function getNavigationLabel(): string
    {
        return __('system.note_linked.page.navigation_label');
    }

    public static function getNavigationBadge(): string
    {
        $recordId = request()->route('record');
        if (!$recordId) {
            return '';
        }
        $model = Note::query()
            ->select('id')
            ->withCount('linkeds')
            ->where('id',  $recordId)
            ->first();

        return $model->linkeds_count ?? 0;
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(1)
            ->schema([
                TextEntry::make('name'),
                TextEntry::make('link'),
                IconEntry::make('active'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name/link')
                    ->sortable()
                    ->description(fn (Link $record): string => $record->link)
                ,
                Tables\Columns\TextColumn::make('note.title')
                    ->label('Linked note title')
                    ->url(function (Link $record) {
                        return NoteResource::getUrl('view', ['record' => $record->note]);
                    })
                ,
                Tables\Columns\IconColumn::make('active')
                    ->sortable(),
            ])
            ->filters([])
            ->actions([])
            ;
    }
}

