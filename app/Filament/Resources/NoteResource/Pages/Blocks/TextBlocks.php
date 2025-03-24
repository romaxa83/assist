<?php

namespace App\Filament\Resources\NoteResource\Pages\Blocks;

use App\Filament\Resources\NoteResource;
use App\Models\Notes\Note;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;

class TextBlocks extends ManageRelatedRecords
{
    protected static string $resource = NoteResource::class;

    protected static string $relationship = 'blocks';

    protected static ?string $navigationIcon = 'heroicon-o-bars-4';

    public function getTitle(): string | Htmlable
    {
        $recordTitle = $this->getRecordTitle();

        $recordTitle = $recordTitle instanceof Htmlable ? $recordTitle->toHtml() : $recordTitle;

        return __('system.text_block.page.title', [
            'title' => $recordTitle,
        ]);
    }

    public function getBreadcrumb(): string
    {
        return __('system.text_block.page.breadcrumb');
    }

    public static function getNavigationLabel(): string
    {
        return __('system.text_block.page.navigation_label');
    }

    public static function getNavigationBadge(): string
    {
        $recordId = request()->route('record');
        if (!$recordId) {
            return '';
        }
        $model = Note::query()
            ->select('id')
            ->withCount('blocks')
            ->where('id',  $recordId)
            ->first();

        return $model->blocks_count ?? 0;
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('position')
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\Layout\Split::make([
                        Tables\Columns\TextColumn::make('type')
                            ->sortable(),
                        Tables\Columns\TextColumn::make('lang'),
                        Tables\Columns\TextColumn::make('position'),
                        Tables\Columns\ToggleColumn::make('is_collapse')
                        ,
                    ]),
                    Tables\Columns\Layout\Panel::make([
                        Tables\Columns\Layout\Stack::make([
                            Tables\Columns\TextColumn::make('content')
                            ->markdown()
                            ,
                        ]),
                    ]),
                ])->space(3),
            ])
            ->defaultSort('position')
            ->filters([])
            ->actions([])
            ;
    }
}

