<?php

namespace App\Filament\Resources;

use App\Enums\Notes\NoteStatus;
use App\Filament\Resources\NoteResource\Pages;
use App\Filament\Resources\NoteResource\RelationManagers;
use App\Models\Blog\Post;
use App\Models\Notes\Note;
use Filament\Forms;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

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

                        Forms\Components\MarkdownEditor::make('text')
                            ->required()
                            ->columnSpan('full'),


//                        Forms\Components\Select::make('blog_category_id')
//                            ->relationship('category', 'name')
//                            ->searchable()
//                            ->required(),

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
//                        ->size('10px')

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

//                Tables\Columns\TextColumn::make('status')
//                    ->size('xs')
////                    ->size('sm')
//                    ->badge()
//                    ->sortable()
//                    ->aligncenter()
//                    ->color(fn (Note $record): string => match ($record->status) {
//                        NoteStatus::DRAFT => 'gray',
//                        NoteStatus::MODERATION => 'warning',
//                        NoteStatus::PUBLIC => 'success',
//                        NoteStatus::PRIVATE => 'danger',
//                    })
////                    ->color(function (Note $record) {
////                        dd($record->status->value);
////                    })
//                ,


//                Tables\Columns\Layout\Split::make([
//                    Tables\Columns\TextColumn::make('id')
//                        ->label('ID')
//                        ->sortable()
//                        ->searchable()
//                        ->extraAttributes(['class' => 'w-16'])
////                        ->size('10px')
//
//                        ->alignCenter()
//
//
//                    ,
//
//                    Tables\Columns\TextColumn::make('created_at')
//                        ->label('Created Date')
//                        ->sortable()
//                        ->date()
//                        ->description(fn (Note $record): string => $record->updated_at)
//                    ,
//
//
//                    Tables\Columns\Layout\Stack::make([
//                        Tables\Columns\TextColumn::make('title')
//                            ->searchable()
//                            ->sortable()
//                            ->weight('medium')
//                            ->alignLeft(),
//
//                        Tables\Columns\TextColumn::make('slug')
//                            ->label('Email address')
//                            ->searchable()
//                            ->sortable()
//                            ->color('gray')
//                            ->alignLeft(),
//                    ])->space(),

//                ])
//                    ->from()



//                Tables\Columns\TextColumn::make('id'),
//                Tables\Columns\TextColumn::make('title')
//                    ->searchable()
//                    ->sortable(),
////                Tables\Columns\TextColumn::make('slug'),
//                Tables\Columns\TextColumn::make('created_at')
//                    ->label('Created Date')
//                    ->sortable()
//                    ->date()
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
        ];
    }
}
