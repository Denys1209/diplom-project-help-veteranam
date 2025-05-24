<?php
namespace App\Filament\Resources;

use App\Filament\Resources\VeteranProfileResource\Pages;
use App\Models\VeteranProfile;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use App\Enums\UserRole;

class VeteranProfileResource extends Resource
{
    protected static ?string $model = VeteranProfile::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';
    protected static ?string $navigationGroup = 'Управління користувачами';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Профіль ветерана';
    protected static ?string $modelLabel = 'Профіль ветерана';
    protected static ?string $pluralModelLabel = 'Профілі ветеранів';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('Ветеран')
                    ->options(
                        User::where('role', UserRole::VETERAN->value)
                            ->pluck('name', 'id')
                    )
                    ->searchable()
                    ->required(),
                Textarea::make('needs_description')
                    ->label('Опис потреб')
                    ->rows(3),
                TextInput::make('military_unit')
                    ->label('Військова частина')
                    ->maxLength(255),
                TextInput::make('service_period')
                    ->label('Період служби')
                    ->maxLength(255),
                Textarea::make('medical_conditions')
                    ->label('Медичні умови')
                    ->rows(3),
                Toggle::make('is_visible')
                    ->label('Профіль видимий')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Ім\'я ветерана')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('military_unit')
                    ->label('Військова частина')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('service_period')
                    ->label('Період служби')
                    ->searchable(),
                ToggleColumn::make('is_visible')
                    ->label('Профіль видимий'),
                TextColumn::make('created_at')
                    ->label('Створено')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Оновлено')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('is_visible')
                    ->label('Профіль видимий')
                    ->options([
                        '1' => 'Так',
                        '0' => 'Ні',
                    ]),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVeteranProfiles::route('/'),
            'create' => Pages\CreateVeteranProfile::route('/create'),
            'edit' => Pages\EditVeteranProfile::route('/{record}/edit'),
            'view' => Pages\ViewVeteranProfile::route('/{record}'),
        ];
    }
}
