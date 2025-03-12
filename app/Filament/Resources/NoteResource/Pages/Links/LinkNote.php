<?php

namespace App\Filament\Resources\NoteResource\Pages\Links;

use App\Filament\Resources\NoteResource;
use App\Models\Notes\Link;
use App\Models\Notes\Note;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Infolists\Components;

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

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(2)
            ->schema([
                TextEntry::make('name'),
                TextEntry::make('link'),
                IconEntry::make('active'),
                IconEntry::make('is_external')
                    ->label('External'),
                TextEntry::make('reasons')
                    ->color('danger')
                    ->size(TextEntry\TextEntrySize::Large)
                    ->listWithLineBreaks()
                    ->formatStateUsing(fn (string $state): string => __($state))
                ,
                KeyValueEntry::make('attributes')
                ,
            ])
            ;
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
                        if($record->linkedNote){
                            return NoteResource::getUrl('view', ['record' => $record->linkedNote]);
                        }
                        return '';
                    })
                    ->openUrlInNewTab()
                ,

                Tables\Columns\IconColumn::make('active')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_external')
                    ->label('External')
                    ->sortable(),
                Tables\Columns\TextColumn::make('reasons')
                    ->label('Reasons is inactive')
                    ->formatStateUsing(function ($state): string {
                        if (!empty($state)) {
                            if(strpos($state, ',')){
                                $tmp = explode(', ', $state);
                                return __(current($tmp));
                            }
                        }
                        return __($state);
                    }),
                Tables\Columns\TextColumn::make('last_check_at')
                    ->label('Last Check At')
                    ->since()
            ])
            ->headerActions([
                Tables\Actions\Action::make('check all links')
                    ->action(function (): void {
                        /** @var $note Note */
                        $note = $this->getRecord();
                        app(\App\Services\Notes\NoteLinkService::class)
                            ->checkLinks($note);

                        Notification::make()
                            ->title('Все ссылки проверены!')
                            ->success()
                            ->send();
                    })
                    ->color('success')
                    ->icon('heroicon-o-check-circle'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->iconButton(),
            ])
            ;
    }
}

