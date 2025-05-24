<?php
namespace App\Filament\Widgets;

use App\Models\HelpRequest;
use App\Enums\HelpRequestStatus;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class HelpRequestsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Заявки в очікуванні', HelpRequest::where('status', HelpRequestStatus::PENDING->value)->count())
                ->description('Очікує призначення волонтера')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
            Stat::make('В процесі виконання', HelpRequest::where('status', HelpRequestStatus::IN_PROGRESS->value)->count())
                ->description('В процесі виконання')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('primary'),
            Stat::make('Виконано', HelpRequest::where('status', HelpRequestStatus::COMPLETED->value)->count())
                ->description('Успішно виконано')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            Stat::make('Відмінено', HelpRequest::where('status', HelpRequestStatus::CANCELLED->value)->count())
                ->description('Відмінено')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),
        ];
    }
}
