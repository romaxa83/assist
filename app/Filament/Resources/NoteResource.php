<?php

namespace App\Filament\Resources;

use App\Enums\Notes\NoteStatus;
use App\Filament\Resources\NoteResource\Pages;
use App\Filament\Resources\NoteResource\RelationManagers;
use App\Models\Notes\Note;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action as TableAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class NoteResource extends Resource
{
    protected static ?string $model = Note::class;

    protected static ?string $navigationGroup = 'Notes';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

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
                            ->unique(Note::class, 'slug', ignoreRecord: true)
                        ,

                        Forms\Components\Select::make('tags')
                            ->label('Tags')
//                            ->searchable()
                            ->multiple()
                            ->relationship(name: 'tags', titleAttribute: 'name')
                            ->preload()
//                            ->options(Tag::all()->pluck('id', 'name')->toArray())
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
                    ->extraAttributes(['class' => 'bg-danger-300 dark:bg-danger-600'])
//                    ->alignCenter()
//                    ->extraAttributes(function (Note $record) {
//                        if (!$record->getMetaPrivate()['warning']['has']) {
//                            return ['class' => 'bg-danger-300 dark:bg-danger-600'];
//                        }
//
//                        return [];
//                    })
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
//            ->contentGrid([
//                'md' => 2,
//            ])
            ->recordClasses(function (Note $record) {
                if($record->status == NoteStatus::DRAFT){
                    return '!text-green-500';
                }

                return null;
            })
            ->filtersTriggerAction(
                fn (TableAction $action) => $action
                    ->button()
                    ->label('Filter'),
            )
            ->filtersFormColumns(2)
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->multiple()
                    ->options(NoteStatus::forSelect()),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->placeholder(fn ($state): string => 'Dec 18, ' . now()->subYear()->format('Y')),
                        Forms\Components\DatePicker::make('created_until')
                            ->placeholder(fn ($state): string => now()->format('M d, Y')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['created_from'] ?? null) {
                            $indicators['created_from'] = 'Order from ' . Carbon::parse($data['created_from'])->toFormattedDateString();
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators['created_until'] = 'Order until ' . Carbon::parse($data['created_until'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),
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
            ])
            ->groups([
                Tables\Grouping\Group::make('created_at')
                    ->label('Note Date')
                    ->date()
                    ->collapsible(),
            ])

            ;
    }

    public static function getNavigationBadge(): ?string
    {
        /** @var class-string<Model> $modelClass */
        $modelClass = static::$model;

        return (string) $modelClass::whereIn(
            'status', [
                NoteStatus::DRAFT(),
                NoteStatus::MODERATION()]
        )
            ->count();
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewNote::class,
            Pages\EditNote::class,
            Pages\Links\LinkNote::class,
            Pages\Links\Linked::class,
            Pages\Blocks\TextBlocks::class,
        ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make()
                    ->schema([
                        Components\Split::make([
                            Components\Grid::make(2)
                                ->schema([
                                    Components\Group::make([
                                        Components\TextEntry::make('title'),
                                        Components\TextEntry::make('slug'),

                                    ]),
                                ])
                            ,
                            Components\Grid::make(2)
                                ->schema([
                                    Components\Group::make([
                                        Components\TextEntry::make('created_at')
                                            ->badge()
                                            ->date()
                                            ->color('success')
                                        ,

                                        Components\TextEntry::make('tags.name')
                                            ->badge()
                                        ,
                                    ]),
                                ])
                            ,
                        ]),
                    ])
                ,
                Components\Section::make('Text as Markdown')
                    ->schema([
                        Components\TextEntry::make('text')
                            ->markdown()
                    ])
                    ->collapsed()
                ,
                Components\Section::make('Text as HTML')
                    ->schema([
                        Components\TextEntry::make('text_html')
                            ->html()
                            ->formatStateUsing(function ($state) {
                                // здесь можно перехватить текст
                                return $state;
                            })
                    ])
                    ->collapsed()
                ,
                Components\Section::make('Text as HTML RAW')
                    ->schema([
                        Components\TextEntry::make('text_html')
                            ->formatStateUsing(function ($state) {
                                // приводит теги в верхний регистр
                                return preg_replace_callback('/<[^>]+>/', function ($matches) {
                                    return mb_strtoupper($matches[0]); // Преобразуем тег в верхний регистр
                                }, $state);

                            })

                    ])
                    ->collapsed()
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
            'view' => Pages\ViewNote::route('/{record}'),
            'links' => Pages\Links\LinkNote::route('/{record}/links'),
            'linkeds' => Pages\Links\Linked::route('/{record}/linkeds'),
            'blocks' => Pages\Blocks\TextBlocks::route('/{record}/blocks'),
        ];
    }
}
