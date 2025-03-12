<?php

namespace App\Filament\Resources\NoteResource\Pages\Links;

use App\Filament\Resources\NoteResource;
use App\Models\Notes\Link;
use App\Models\Notes\Note;
use Filament\Forms;
use Filament\Forms\Form;
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
        $recordTitle = $this->getRecordTitle();

        $recordTitle = $recordTitle instanceof Htmlable ? $recordTitle->toHtml() : $recordTitle;

        return "Manage {$recordTitle} linked ??";
    }

    public function getBreadcrumb(): string
    {
        return 'Linked';
    }

    public static function getNavigationLabel(): string
    {
        return 'Linked notes';
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

