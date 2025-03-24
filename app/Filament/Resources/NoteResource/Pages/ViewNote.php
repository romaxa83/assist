<?php

namespace App\Filament\Resources\NoteResource\Pages;

use App\Enums\Notes\NoteStatus;
use App\Filament\Resources\NoteResource;
use App\Models\Notes\Note;
use Filament\Actions\Action;
use Filament\Infolists\Infolist;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Infolists\Components;
use Illuminate\Support\HtmlString;

class ViewNote extends ViewRecord
{
    protected static string $resource = NoteResource::class;

    public function getTitle(): string|Htmlable
    {
        /** @var $record  Note*/
        $record = $this->getRecord();

        return $record->title;
    }

    public function getSubheading(): string|Htmlable|null
    {
        /** @var $record  Note*/
        $record = $this->getRecord();
        $meta = $record->getMetaPrivate();

        $result = '';
        if($meta['warning']['has']){
            foreach ($meta['warning']['reasons'] as $reason){
                $result .= '⚠️ <span>'. $reason . '</span><br>';
            }
        }

        return new HtmlString($result);
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

//    protected function getActions(): array
//    {
//        return [
//            Action::make('setToDraft')
//                ->label('Перевести в черновик')
//                ->action(function () {
//                    $this->record->update(['status' => 'draft']);
//
//                }),
//
//        ];
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
                                        Components\TextEntry::make('status')
                                            ->badge()
                                        ,

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
                                        Components\TextEntry::make('weight')
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
