<?php

namespace App\Filament\Resources\NoteResource\Pages;


use App\Enums\Notes\NoteStatus;
use App\Filament\Resources\NoteResource;
use App\Models\Notes\Note;
use Filament\Infolists\Infolist;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Actions;
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

        $icon = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffbf12" class="w-4 h-4 inline-block">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>';

        $result = '';
        if($meta['warning']['has']){
            foreach ($meta['warning']['reasons'] as $reason){
                $result .= $icon . ' <span>'. $reason . '</span><br>';
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
                ->badge($statusCounts[NoteStatus::PUBLIC()] ?? 0)
            ,
            NoteStatus::PRIVATE->value => Tab::make()
                ->query(fn ($query) => $query->where('status', NoteStatus::PRIVATE()))
                ->badge($statusCounts[NoteStatus::PRIVATE()] ?? 0)
            ,
        ];
    }

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
