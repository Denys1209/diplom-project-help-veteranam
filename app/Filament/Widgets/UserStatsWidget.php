<?php
namespace App\Filament\Widgets;

use App\Models\User;
use App\Enums\UserRole;
use App\Enums\ApprovalStatus;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserStatsWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected function getStats(): array
    {
        return [
            Stat::make('Всього ветеранів', User::where('role', UserRole::VETERAN->value)
                    ->where('approval_status', ApprovalStatus::APPROVED->value)
                    ->count())
                ->description('Підтверджені ветерани')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('warning'),

            Stat::make('Всього волонтерів', User::where('role', UserRole::VOLUNTEER->value)
                    ->where('approval_status', ApprovalStatus::APPROVED->value)
                    ->count())
                ->description('Активні волонтери')
                ->descriptionIcon('heroicon-m-heart')
                ->color('success'),

            Stat::make('Адміністратори', User::where('role', UserRole::ADMIN->value)
                    ->where('approval_status', ApprovalStatus::APPROVED->value)
                    ->count())
                ->description('Системні адміністратори')
                ->descriptionIcon('heroicon-m-shield-check')
                ->color('danger'),

            Stat::make('Очікують підтвердження', User::where('approval_status', ApprovalStatus::WAITING->value)->count())
                ->description('Користувачі на розгляді')
                ->descriptionIcon('heroicon-m-clock')
                ->color('gray'),

            Stat::make('Відхилено', User::where('approval_status', ApprovalStatus::REJECTED->value)->count())
                ->description('Відхилені заявки')
                ->descriptionIcon('heroicon-m-x-mark')
                ->color('red'),
        ];
    }
}
