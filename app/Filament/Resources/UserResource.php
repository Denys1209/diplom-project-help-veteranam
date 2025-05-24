<?php
namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\UserRole;
use App\Enums\ApprovalStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use App\Notifications\UserApprovedNotification;
use App\Notifications\UserRejectedNotification;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Управління користувачами';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Користувачі';
    protected static ?string $modelLabel = 'Користувач';
    protected static ?string $pluralModelLabel = 'Користувачі';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Деталі користувача')
                    ->schema([
                        TextInput::make('name')
                            ->label('Ім\'я')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(User::class, 'email', ignoreRecord: true),
                        TextInput::make('password')
                            ->label('Пароль')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => $state ? bcrypt($state) : null)
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create'),
                        TextInput::make('phone')
                            ->label('Телефон')
                            ->tel()
                            ->maxLength(255),
                        TextInput::make('address')
                            ->label('Адреса')
                            ->maxLength(255),
                        Select::make('role')
                            ->label('Роль')
                            ->options([
                                UserRole::VETERAN->value => UserRole::VETERAN->label(),
                                UserRole::VOLUNTEER->value => UserRole::VOLUNTEER->label(),
                                UserRole::ADMIN->value => UserRole::ADMIN->label(),
                            ])
                            ->required(),
                        Select::make('approval_status')
                            ->label('Статус підтвердження')
                            ->options([
                                ApprovalStatus::WAITING->value => ApprovalStatus::WAITING->label(),
                                ApprovalStatus::APPROVED->value => ApprovalStatus::APPROVED->label(),
                                ApprovalStatus::REJECTED->value => ApprovalStatus::REJECTED->label(),
                            ])
                            ->default(ApprovalStatus::WAITING)
                            ->required()
                            ->live(),
                        Textarea::make('rejection_reason')
                            ->label('Причина відхилення')
                            ->rows(3)
                            ->maxLength(500)
                            ->visible(fn (Forms\Get $get) => $get('approval_status') === ApprovalStatus::REJECTED->value)
                            ->helperText('Вкажіть причину відхилення (буде відправлена користувачу)'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Ім\'я')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                BadgeColumn::make('role')
                    ->label('Роль')
                    ->color(function ($state): string {
                        // Handle both string and enum cases
                        $roleValue = $state instanceof UserRole ? $state->value : $state;
                        return match ($roleValue) {
                            'admin' => 'danger',
                            'veteran' => 'warning',
                            'volunteer' => 'success',
                            default => 'gray',
                        };
                    })
                    ->formatStateUsing(function ($state): string {
                        // Handle both string and enum cases
                        if ($state instanceof UserRole) {
                            return $state->label();
                        }
                        return match ($state) {
                            'admin' => UserRole::ADMIN->label(),
                            'veteran' => UserRole::VETERAN->label(),
                            'volunteer' => UserRole::VOLUNTEER->label(),
                            default => $state,
                        };
                    }),
                BadgeColumn::make('approval_status')
                    ->label('Статус')
                    ->color(function ($state): string {
                        // Handle both string and enum cases
                        $statusValue = $state instanceof ApprovalStatus ? $state->value : $state;
                        return match ($statusValue) {
                            'approved' => 'success',
                            'waiting' => 'warning',
                            'rejected' => 'danger',
                            default => 'gray',
                        };
                    })
                    ->formatStateUsing(function ($state): string {
                        // Handle both string and enum cases
                        if ($state instanceof ApprovalStatus) {
                            return $state->label();
                        }
                        return match ($state) {
                            'approved' => ApprovalStatus::APPROVED->label(),
                            'waiting' => ApprovalStatus::WAITING->label(),
                            'rejected' => ApprovalStatus::REJECTED->label(),
                            default => $state,
                        };
                    }),
                TextColumn::make('rejection_reason')
                    ->label('Причина відхилення')
                    ->limit(50)
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
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
                SelectFilter::make('role')
                    ->label('Роль')
                    ->options([
                        UserRole::VETERAN->value => UserRole::VETERAN->label(),
                        UserRole::VOLUNTEER->value => UserRole::VOLUNTEER->label(),
                        UserRole::ADMIN->value => UserRole::ADMIN->label(),
                    ]),
                SelectFilter::make('approval_status')
                    ->label('Статус підтвердження')
                    ->options([
                        ApprovalStatus::WAITING->value => ApprovalStatus::WAITING->label(),
                        ApprovalStatus::APPROVED->value => ApprovalStatus::APPROVED->label(),
                        ApprovalStatus::REJECTED->value => ApprovalStatus::REJECTED->label(),
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Підтвердити')
                    ->icon('heroicon-m-check')
                    ->color('success')
                    ->visible(fn (User $record) => $record->approval_status !== ApprovalStatus::APPROVED)
                    ->action(function (User $record) {
                        $record->update([
                            'approval_status' => ApprovalStatus::APPROVED,
                            'rejection_reason' => null
                        ]);
                        // $record->notify(new UserApprovedNotification());
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Підтвердити користувача')
                    ->modalDescription('Користувач отримає email про підтвердження облікового запису.'),

                Tables\Actions\Action::make('reject')
                    ->label('Відхилити')
                    ->icon('heroicon-m-x-mark')
                    ->color('danger')
                    ->visible(fn (User $record) => $record->approval_status !== ApprovalStatus::REJECTED)
                    ->form([
                        Textarea::make('rejection_reason')
                            ->label('Причина відхилення')
                            ->required()
                            ->rows(3)
                            ->maxLength(500)
                            ->helperText('Ця причина буде відправлена користувачу на email'),
                    ])
                    ->action(function (User $record, array $data) {
                        $record->update([
                            'approval_status' => ApprovalStatus::REJECTED,
                            'rejection_reason' => $data['rejection_reason']
                        ]);
                        // $record->notify(new UserRejectedNotification($data['rejection_reason']));
                    })
                    ->modalHeading('Відхилити користувача')
                    ->modalDescription('Користувач отримає email з причиною відхилення.'),

                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('approve_selected')
                        ->label('Підтвердити вибраних')
                        ->icon('heroicon-m-check')
                        ->color('success')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                if ($record->approval_status !== ApprovalStatus::APPROVED) {
                                    $record->update([
                                        'approval_status' => ApprovalStatus::APPROVED,
                                        'rejection_reason' => null
                                    ]);
                                    // $record->notify(new UserApprovedNotification());
                                }
                            }
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Підтвердити вибраних користувачів')
                        ->modalDescription('Всі вибрані користувачі отримають email про підтвердження.'),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'view' => Pages\ViewUser::route('/{record}'),
        ];
    }
}
