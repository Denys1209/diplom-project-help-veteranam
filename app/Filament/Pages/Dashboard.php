<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\HelpRequestsOverview::class,
            \App\Filament\Widgets\UserStatsWidget::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            \App\Filament\Widgets\PendingUsersWidget::class,
            \App\Filament\Widgets\LatestHelpRequests::class,
        ];
    }
}
