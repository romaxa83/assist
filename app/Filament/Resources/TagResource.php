<?php

namespace App\Filament\Resources;

use App\Dto\Tags\TagDto;
use App\Filament\Resources\TagResource\Pages;
use App\Filament\Resources\TagResource\RelationManagers;
use App\Models\Tags\Tag;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\CreateAction;

class TagResource extends Resource
{
    protected static ?string $model = Tag::class;

    protected static ?string $navigationGroup = 'Notes';

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->columnSpan('full')
                    ->maxLength(255)
                ,
            ])
            ;
    }


    public static function table(Table $table): Table
    {
        return $table

//            ->query(function (Builder $query) {
//                return $query;
//            })

            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug'),
                Tables\Columns\TextColumn::make('public_attached')
                    ->sortable()
                    ->label('Public attached'),
                Tables\Columns\TextColumn::make('private_attached')
                    ->label('Private attached')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->iconButton()
                    ->modalHeading('Edit tag')
                    ->modalButton('Сохранить изменения')
                    ->form([
                        TextInput::make('name')
                            ->required()
                            ->label('Name')
                            ->maxLength(255),
                    ])
                    ->using(function ($record, array $data) {
                        $service = app(\App\Services\Tags\TagService::class);
                        $record = $service->update($record, TagDto::byArgs($data));

                        return $record;
                    })
                ,
                Tables\Actions\DeleteAction::make()
                    ->iconButton()
                    ->using(function ($record) {


                        $service = app(\App\Services\Tags\TagService::class);
                        $service->delete($record);
                    })
                ,
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
        return [];
    }
}
