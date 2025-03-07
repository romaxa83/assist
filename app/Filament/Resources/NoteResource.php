<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NoteResource\Pages;
use App\Filament\Resources\NoteResource\RelationManagers;
use App\Models\Notes\Note;
use App\Models\Tags\Tag;
use Filament\Forms;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Infolists\Components;

class NoteResource extends Resource
{
    protected static ?string $model = Note::class;

    protected static ?string $navigationGroup = 'Notes';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->live(onBlur: true)
                            ->maxLength(255)
                            ->afterStateUpdated(
                                fn (string $operation, $state, Forms\Set $set) => $operation === 'create'
                                    ? $set('slug', Str::slug($state))
                                    : null
                            ),
                        Forms\Components\TextInput::make('slug')
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->maxLength(255)
                            ->unique(Note::class, 'slug', ignoreRecord: true),


                        Forms\Components\Select::make('tags')
                            ->label('Tags')
//                            ->searchable()
                            ->preload()

                            ->multiple()
                            ->relationship(name: 'tags', titleAttribute: 'name')
                            ->preload()


//                            ->options(Tag::all()->pluck('name', 'id')->toArray())
                        ,

                        Forms\Components\MarkdownEditor::make('text')
                            ->required()
                            ->columnSpan('full'),

                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->numeric()
                    ->sortable()
                    ->searchable()
                    ->extraAttributes(['class' => 'w-16'])
                    ->alignCenter()
                ,
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->alignLeft()
                    ->description(fn (Note $record): string => $record->slug)
                ,
                Tables\Columns\SelectColumn::make('status')
                    ->options(function (Note $model) {
                        return $model->getMetaPrivate()['statuses'] ?? [];
                    })
                    ->beforeStateUpdated(function (Note $model, string $state) {

                        $service = app(\App\Services\Notes\NoteStatusService::class);
                        $service->setStatus($model, $state);
                    })
                ,
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created Date')
                    ->sortable()
                    ->date()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->iconButton(),
                Tables\Actions\EditAction::make()
                    ->iconButton()

                ,
                Tables\Actions\DeleteAction::make()->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

//    public static function infolist(Infolist $infolist): Infolist
//    {
//        return $infolist
//            ->schema([
//                Components\Section::make()
//                    ->schema([
//                        Components\Split::make([
//                            Components\Grid::make(2)
//                                ->schema([
//                                    Components\Group::make([
//                                        Components\TextEntry::make('title'),
//                                        Components\TextEntry::make('slug'),
//                                        Components\TextEntry::make('created_at')
//                                            ->badge()
//                                            ->date()
//                                            ->color('success'),
//                                    ]),
//                                ]),
//                                ]),
//                    ]),
//                Components\Section::make('Text as HTML')
//                    ->schema([
//                        Components\TextEntry::make('text_html')
//                            ->html()
//                    ])
//                    ->collapsible(),
//            ]);
//    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNotes::route('/'),
            'create' => Pages\CreateNote::route('/create'),
            'edit' => Pages\EditNote::route('/{record}/edit'),
            'view' => Pages\ViewNote::route('/{record}'),
        ];
    }
}
