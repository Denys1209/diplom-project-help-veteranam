<?php
namespace App\Filament\Widgets;

use App\Models\HelpRequest;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use App\Enums\HelpRequestStatus;
use App\Enums\HelpRequestUrgency;

class LatestHelpRequests extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';


    protected static ?string $heading = 'Остані прохання про допомогу';


    public function table(Table $table): Table
    {
        return $table
            ->query(
                HelpRequest::latest()->limit(5)
            )
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->label('Заголовок')
                    ->limit(30),
                TextColumn::make('veteran.name')
                    ->label('Ветеран'),
                TextColumn::make('category.name')
                    ->label('Категорія'),
                BadgeColumn::make('status')
                    ->label('Статус')
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
                TextColumn::make('created_at')
                    ->label('Створено')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Переглянути')
                    ->url(fn (HelpRequest $record): string => route('filament.admin.resources.help-requests.view', $record))
                    ->icon('heroicon-m-eye'),
            ]);
    }
}
