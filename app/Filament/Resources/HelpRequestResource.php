<?php
namespace App\Filament\Resources;

use App\Filament\Resources\HelpRequestResource\Pages;
use App\Filament\Resources\HelpRequestResource\RelationManagers;
use App\Models\HelpRequest;
use App\Models\User;
use App\Models\HelpCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use App\Enums\HelpRequestStatus;
use App\Enums\HelpRequestUrgency;
use App\Enums\UserRole;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Group;
use Filament\Forms\Set;
use Filament\Forms\Get;
use Dotswan\MapPicker\Fields\Map;

class HelpRequestResource extends Resource
{
    protected static ?string $model = HelpRequest::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Управління Допомогою';

    protected static ?string $navigationLabel = "Прохання про допомогу";
    protected static ?string $modelLabel = 'Прохання про допомогу';
    protected static ?string $pluralModelLabel = 'Прохання про допомогу';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make('Основна Інформація')
                            ->schema([
                                Select::make('veteran_id')
                                    ->label('Ветеран')
                                    ->options(
                                        User::where('role', UserRole::VETERAN->value)
                                            ->pluck('name', 'id')
                                    )
                                    ->searchable()
                                    ->required(),
                                Select::make('category_id')
                                    ->label('Категорія')
                                    ->options(
                                        HelpCategory::pluck('name', 'id')
                                    )
                                    ->searchable()
                                    ->required(),
                                TextInput::make('title')
                                    ->label('Назва')
                                    ->required()
                                    ->maxLength(255),
                                Textarea::make('description')
                                    ->label('Опис')
                                    ->required()
                                    ->rows(4),
                                Select::make('status')
                                    ->label('Статус')
                                    ->options([
                                        HelpRequestStatus::PENDING->value => HelpRequestStatus::PENDING->label(),
                                        HelpRequestStatus::IN_PROGRESS->value => HelpRequestStatus::IN_PROGRESS->label(),
                                        HelpRequestStatus::COMPLETED->value => HelpRequestStatus::COMPLETED->label(),
                                        HelpRequestStatus::CANCELLED->value => HelpRequestStatus::CANCELLED->label(),
                                    ])
                                    ->required(),
                                Select::make('urgency')
                                    ->label('Терміновість')
                                    ->options([
                                        HelpRequestUrgency::LOW->value => HelpRequestUrgency::LOW->label(),
                                        HelpRequestUrgency::MEDIUM->value => HelpRequestUrgency::MEDIUM->label(),
                                        HelpRequestUrgency::HIGH->value => HelpRequestUrgency::HIGH->label(),
                                        HelpRequestUrgency::CRITICAL->value => HelpRequestUrgency::CRITICAL->label(),
                                    ])
                                    ->required(),
                            ])
                            ->columns(2),

                        Section::make('Локація')
                            ->schema([
                                // Hidden fields to store coordinates
                                TextInput::make('latitude')
                                    ->label('Широта')
                                    ->numeric()
                                    ->hidden()
                                    ->dehydrateStateUsing(fn (Get $get): ?float => $get('location')['lat'] ?? null),

                                TextInput::make('longitude')
                                    ->label('Довгота')
                                    ->numeric()
                                    ->hidden()
                                    ->dehydrateStateUsing(fn (Get $get): ?float => $get('location')['lng'] ?? null),

                                // Interactive map component
                                Map::make('location')
                                    ->label('Оберіть місце розташування на карті')
                                    ->columnSpanFull()
                                    ->reactive()
                                    ->defaultLocation(latitude: 50.4501, longitude: 30.5234) // Kyiv coordinates
                                    ->draggable(true)
                                    ->clickable(true)
                                    ->zoom(10)
                                    ->minZoom(0)
                                    ->maxZoom(20)
                                    ->tilesUrl("https://tile.openstreetmap.de/{z}/{x}/{y}.png")
                                    ->detectRetina(true)
                                    ->afterStateHydrated(function ($state, $record, Set $set): void {
                                        if ($record && $record->latitude && $record->longitude) {
                                            $set('location', ['lat' => $record->latitude, 'lng' => $record->longitude]);
                                            $set('latitude', $record->latitude);
                                            $set('longitude', $record->longitude);
                                        }
                                    })
                                    ->afterStateUpdated(function ($state, Set $set): void {
                                        if (is_array($state) && isset($state['lat']) && isset($state['lng'])) {
                                            $set('latitude', $state['lat']);
                                            $set('longitude', $state['lng']);
                                        }
                                    })
                                    ->helperText('Натисніть на карту або перетягніть маркер для вибору місця розташування'),
                            ]),

                        Section::make('Графік та Призначення')
                            ->schema([
                                DateTimePicker::make('deadline')
                                    ->label('Термін виконання')
                                    ->displayFormat('d/m/Y H:i'),
                                Select::make('volunteer_id')
                                    ->label('Назначений Волонтер')
                                    ->options(
                                        User::where('role', UserRole::VOLUNTEER->value)
                                            ->pluck('name', 'id')
                                    )
                                    ->searchable()
                                    ->nullable(),
                                DateTimePicker::make('completed_at')
                                    ->label('Дата Виконання')
                                    ->displayFormat('d/m/Y H:i')
                                    ->nullable(),
                            ])
                            ->columns(3),
                    ])
                    ->columnSpan(['lg' => 2]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable(),
                TextColumn::make('title')
                    ->label('Назва')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                TextColumn::make('veteran.name')
                    ->label('Ветеран')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label('Категорія')
                    ->searchable()
                    ->sortable(),
                BadgeColumn::make('status')
                    ->label("Статус")
                    ->formatStateUsing(fn ($state) => match($state) {
                        HelpRequestStatus::PENDING->value => HelpRequestStatus::PENDING->label(),
                        HelpRequestStatus::IN_PROGRESS->value => HelpRequestStatus::IN_PROGRESS->label(),
                        HelpRequestStatus::COMPLETED->value => HelpRequestStatus::COMPLETED->label(),
                        HelpRequestStatus::CANCELLED->value => HelpRequestStatus::CANCELLED->label(),
                        default => $state,
                    })
                    ->colors([
                        'warning' => HelpRequestStatus::PENDING->value,
                        'primary' => HelpRequestStatus::IN_PROGRESS->value,
                        'success' => HelpRequestStatus::COMPLETED->value,
                        'danger' => HelpRequestStatus::CANCELLED->value,
                    ]),
                BadgeColumn::make('urgency')
                    ->label('Терміновість')
                    ->formatStateUsing(fn ($state) => match($state) {
                        HelpRequestUrgency::LOW->value => HelpRequestUrgency::LOW->label(),
                        HelpRequestUrgency::MEDIUM->value => HelpRequestUrgency::MEDIUM->label(),
                        HelpRequestUrgency::HIGH->value => HelpRequestUrgency::HIGH->label(),
                        HelpRequestUrgency::CRITICAL->value => HelpRequestUrgency::CRITICAL->label(),
                        default => $state,
                    })
                    ->colors([
                        'info' => HelpRequestUrgency::LOW->value,
                        'secondary' => HelpRequestUrgency::MEDIUM->value,
                        'warning' => HelpRequestUrgency::HIGH->value,
                        'danger' => HelpRequestUrgency::CRITICAL->value,
                    ]),
                TextColumn::make('volunteer.name')
                    ->label('Волонтер')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('location_display')
                    ->label('Місцезнаходження')
                    ->getStateUsing(function ($record) {
                        if ($record->latitude && $record->longitude) {
                            return number_format($record->latitude, 4) . ', ' . number_format($record->longitude, 4);
                        }
                        return 'Не вказано';
                    })
                    ->toggleable(),
                TextColumn::make('deadline')
                    ->label("Термін виконання")
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('completed_at')
                    ->label("Час завершення")
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label("Час створення")
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Статус')
                    ->options([
                        HelpRequestStatus::PENDING->value => HelpRequestStatus::PENDING->label(),
                        HelpRequestStatus::IN_PROGRESS->value => HelpRequestStatus::IN_PROGRESS->label(),
                        HelpRequestStatus::COMPLETED->value => HelpRequestStatus::COMPLETED->label(),
                        HelpRequestStatus::CANCELLED->value => HelpRequestStatus::CANCELLED->label(),
                    ]),
                SelectFilter::make('urgency')
                    ->label('Терміновість')
                    ->options([
                        HelpRequestUrgency::LOW->value => HelpRequestUrgency::LOW->label(),
                        HelpRequestUrgency::MEDIUM->value => HelpRequestUrgency::MEDIUM->label(),
                        HelpRequestUrgency::HIGH->value => HelpRequestUrgency::HIGH->label(),
                        HelpRequestUrgency::CRITICAL->value => HelpRequestUrgency::CRITICAL->label(),
                    ]),
                SelectFilter::make('category_id')
                    ->label('Категорія')
                    ->relationship('category', 'name'),
                SelectFilter::make('volunteer_id')
                    ->label('Волонтер')
                    ->relationship('volunteer', 'name')
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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
            RelationManagers\CommentsRelationManager::class,
            RelationManagers\PhotosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHelpRequests::route('/'),
            'create' => Pages\CreateHelpRequest::route('/create'),
            'edit' => Pages\EditHelpRequest::route('/{record}/edit'),
            'view' => Pages\ViewHelpRequest::route('/{record}'),
        ];
    }
}
