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

class LinkNote extends ManageRelatedRecords
{
    protected static string $resource = NoteResource::class;

    protected static string $relationship = 'links';

    protected static ?string $navigationIcon = 'heroicon-o-link';

    public function getTitle(): string | Htmlable
    {
        $recordTitle = $this->getRecordTitle();

        $recordTitle = $recordTitle instanceof Htmlable ? $recordTitle->toHtml() : $recordTitle;

        return "Manage {$recordTitle} links";
    }

    public function getBreadcrumb(): string
    {
        return 'Links';
    }

    public static function getNavigationLabel(): string
    {
        return 'Note links';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\TextInput::make('link')
                    ->required(),

                Forms\Components\Toggle::make('active')
                    ->default(true),
            ])
            ->columns(1);
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(1)
            ->schema([
                TextEntry::make('name'),
                TextEntry::make('link'),
                IconEntry::make('active'),
                IconEntry::make('is_external')
                    ->label('External'),
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
                Tables\Columns\TextColumn::make('linkedNote.title')
                    ->label('Linked note title')
                    ->url(function (Link $record) {
                        return NoteResource::getUrl('view', ['record' => $record->linkedNote]);
                    })
                ,

                Tables\Columns\IconColumn::make('active')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_external')
                    ->label('External')
                    ->sortable(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('check all links')
                    ->action(function (): void {
                    })
                    ->color('success')
                    ->icon('heroicon-o-check-circle'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->iconButton(),
                Tables\Actions\EditAction::make()->iconButton(),
                Tables\Actions\DeleteAction::make()->iconButton(),
            ])
            ;
    }
}

