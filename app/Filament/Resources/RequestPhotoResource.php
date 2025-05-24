<?php
namespace App\Filament\Resources;

use App\Filament\Resources\RequestPhotoResource\Pages;
use App\Filament\Resources\RequestPhotoResource\RelationManagers;
use App\Models\RequestPhoto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RequestPhotoResource extends Resource
{
    protected static ?string $model = RequestPhoto::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Управління Допомогою';

    protected static ?string $navigationLabel = 'Фото запитів';

    protected static ?string $modelLabel = 'Фото запиту';

    protected static ?string $pluralModelLabel = 'Фото запитів';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Основна інформація')
                    ->schema([
                        Forms\Components\Select::make('help_request_id')
                            ->label('ID запиту допомоги')
                            ->relationship('helpRequest', 'id')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->helperText('Оберіть запит допомоги, до якого належить це фото'),

                        Forms\Components\Select::make('user_id')
                            ->label('Користувач')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->helperText('Користувач, який завантажив фото'),
                    ])->columns(2),

                Forms\Components\Section::make('Фото та опис')
                    ->schema([
                        Forms\Components\FileUpload::make('photo_path')
                            ->label('Фото')
                            ->image()
                            ->directory('request-photos')
                            ->disk('public')
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                null,
                                '16:9',
                                '4:3',
                                '1:1',
                            ])
                            ->maxSize(5120) // 5MB
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->required()
                            ->helperText('Завантажте фото (максимум 5MB, формати: JPG, PNG, WebP)')
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('caption')
                            ->label('Підпис до фото')
                            ->placeholder('Введіть опис фото...')
                            ->maxLength(500)
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('is_completion_photo')
                            ->label('Фото завершення')
                            ->helperText('Позначте, якщо це фото підтверджує завершення запиту')
                            ->inline(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo_path')
                    ->label('Фото')
                    ->disk('public')
                    ->size(60)
                    ->square(),

                Tables\Columns\TextColumn::make('helpRequest.id')
                    ->label('ID запиту')
                    ->numeric()
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Користувач')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('caption')
                    ->label('Підпис')
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 50 ? $state : null;
                    })
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_completion_photo')
                    ->label('Фото завершення')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Створено')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Оновлено')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_completion_photo')
                    ->label('Фото завершення')
                    ->placeholder('Усі фото')
                    ->trueLabel('Тільки фото завершення')
                    ->falseLabel('Без фото завершення'),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Створено з'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Створено до'),
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Переглянути'),
                Tables\Actions\EditAction::make()
                    ->label('Редагувати'),
                Tables\Actions\DeleteAction::make()
                    ->label('Видалити'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Видалити вибрані'),
                ]),
            ])
            ->emptyStateHeading('Фото не знайдено')
            ->emptyStateDescription('Поки що немає завантажених фото для запитів допомоги.')
            ->emptyStateIcon('heroicon-o-photo');
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
            'index' => Pages\ListRequestPhotos::route('/'),
            'create' => Pages\CreateRequestPhoto::route('/create'),
            'view' => Pages\ViewRequestPhoto::route('/{record}'),
            'edit' => Pages\EditRequestPhoto::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
