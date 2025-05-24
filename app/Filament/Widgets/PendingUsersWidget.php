<?php
namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use App\Enums\UserRole;
use App\Enums\ApprovalStatus;
use App\Notifications\UserApprovedNotification;
use App\Notifications\UserRejectedNotification;
use Filament\Forms\Components\Textarea;

class PendingUsersWidget extends BaseWidget
{
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'Користувачі, що очікують підтвердження';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::where('approval_status', ApprovalStatus::WAITING)->latest()->limit(10)
            )
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->label("Ім'я")
                    ->limit(25),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->limit(30),
                BadgeColumn::make('role')
                    ->label('Роль')
                    ->formatStateUsing(fn ($state) => match($state) {
                        UserRole::VETERAN->value => UserRole::VETERAN->label(),
                        UserRole::VOLUNTEER->value => UserRole::VOLUNTEER->label(),
                        UserRole::ADMIN->value => UserRole::ADMIN->label(),
                        default => $state,
                    })
                    ->colors([
                        'warning' => UserRole::VETERAN->value,
                        'success' => UserRole::VOLUNTEER->value,
                        'danger' => UserRole::ADMIN->value,
                    ]),
                TextColumn::make('phone')
                    ->label('Телефон')
                    ->limit(15)
                    ->placeholder('Не вказано'),
                BadgeColumn::make('approval_status')
                    ->label('Статус')
                    ->formatStateUsing(fn ($state) => match($state) {
                        ApprovalStatus::WAITING->value => ApprovalStatus::WAITING->label(),
                        ApprovalStatus::APPROVED->value => ApprovalStatus::APPROVED->label(),
                        ApprovalStatus::REJECTED->value => ApprovalStatus::REJECTED->label(),
                        default => $state,
                    })
                    ->colors([
                        'warning' => ApprovalStatus::WAITING->value,
                        'success' => ApprovalStatus::APPROVED->value,
                        'danger' => ApprovalStatus::REJECTED->value,
                    ]),
                TextColumn::make('created_at')
                    ->label('Зареєстровано')
                    ->dateTime()
                    ->sortable()
                    ->since(),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Підтвердити')
                    ->icon('heroicon-m-check')
                    ->color('success')
                    ->action(function (User $record) {
                        $record->update([
                            'approval_status' => ApprovalStatus::APPROVED,
                            'rejection_reason' => null
                        ]);
                        // $record->notify(new UserApprovedNotification());
                        $this->dispatch('$refresh');
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Підтвердити користувача')
                    ->modalDescription('Користувач отримає email про підтвердження облікового запису.'),

                Tables\Actions\Action::make('reject')
                    ->label('Відхилити')
                    ->icon('heroicon-m-x-mark')
                    ->color('danger')
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
                        $this->dispatch('$refresh');
                    })
                    ->modalHeading('Відхилити користувача')
                    ->modalDescription('Користувач отримає email з причиною відхилення.'),

                Tables\Actions\Action::make('view')
                    ->label('Переглянути')
                    ->url(fn (User $record): string => route('filament.admin.resources.users.view', $record))
                    ->icon('heroicon-m-eye')
                    ->color('gray'),
                Tables\Actions\Action::make('edit')
                    ->label('Редагувати')
                    ->url(fn (User $record): string => route('filament.admin.resources.users.edit', $record))
                    ->icon('heroicon-m-pencil')
                    ->color('primary'),
            ])
            ->emptyStateHeading('Немає користувачів на розгляді')
            ->emptyStateDescription('Всі користувачі підтверджені або немає нових реєстрацій.')
            ->emptyStateIcon('heroicon-o-check-circle');
    }
}
