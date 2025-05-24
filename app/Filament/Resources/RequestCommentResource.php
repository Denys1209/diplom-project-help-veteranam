<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RequestCommentResource\Pages;
use App\Models\RequestComment;
use App\Models\HelpRequest;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class RequestCommentResource extends Resource
{
    protected static ?string $model = RequestComment::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationGroup = 'Управління Допомогою';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationLabel = 'Коментарі';
    protected static ?string $modelLabel = 'Коментарії';
    protected static ?string $pluralModelLabel = 'Коментарі';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('help_request_id')
                    ->label('Запит на допомогу')
                    ->options(
                        HelpRequest::all()->pluck('title', 'id')
                    )
                    ->searchable()
                    ->required(),
                Select::make('user_id')
                    ->label('Користувач')
                    ->options(
                        User::pluck('name', 'id')
                    )
                    ->searchable()
                    ->required(),
                Textarea::make('comment')
                    ->label('Коментар')
                    ->placeholder('Введіть коментар')
                    ->required()
                    ->rows(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('helpRequest.title')
                    ->label('Запит на допомогу')
                    ->limit(30)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Користувач')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('comment')
                    ->label('Коментар')
                    ->limit(50)
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Створено')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Оновлено')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('help_request_id')
                    ->label('Запит на допомогу')
                    ->relationship('helpRequest', 'title')
                    ->searchable(),
                SelectFilter::make('user_id')
                    ->label('Користувач')
                    ->relationship('user', 'name')
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListRequestComments::route('/'),
            'create' => Pages\CreateRequestComment::route('/create'),
            'edit' => Pages\EditRequestComment::route('/{record}/edit'),
        ];
    }
}
