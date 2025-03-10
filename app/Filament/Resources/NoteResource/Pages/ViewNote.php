<?php

namespace App\Filament\Resources\NoteResource\Pages;


use App\Filament\Resources\NoteResource;
use App\Models\Notes\Note;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Actions;
use Filament\Infolists\Components;

class ViewNote extends ViewRecord
{
    protected static string $resource = NoteResource::class;

    public function getTitle(): string | Htmlable
    {
        /** @var $record  Note*/
        $record = $this->getRecord();

        return $record->title;
    }

//    protected function getActions(): array
//    {
//        return [
//            Actions\EditAction::make(),
//        ];
//    }

//    public static function getRecordSubNavigation(Page $page): array
//    {
//        return $page->generateNavigationItems([
//            EditNote::class,
//            CreateNote::class,
//        ]);
//    }

    public function infolist(Infolist $infolist): Infolist
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
}
