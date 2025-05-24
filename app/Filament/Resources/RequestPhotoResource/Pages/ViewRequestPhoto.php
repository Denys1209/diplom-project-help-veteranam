<?php

namespace App\Filament\Resources\RequestPhotoResource\Pages;

use App\Filament\Resources\RequestPhotoResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Support\Enums\FontWeight;

class ViewRequestPhoto extends ViewRecord
{
    protected static string $resource = RequestPhotoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Редагувати'),
            Actions\DeleteAction::make()
                ->label('Видалити'),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Фото')
                    ->schema([
                        Infolists\Components\ImageEntry::make('photo_path')
                            ->label('')
                            ->disk('public')
                            ->height(400)
                            ->width('100%')
                            ->extraAttributes([
                                'class' => 'rounded-lg shadow-lg',
                                'style' => 'object-fit: contain; background: #f8f9fa;'
                            ]),
                    ])
                    ->columnSpan('full'),

                Infolists\Components\Section::make('Деталі фото')
                    ->schema([
                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('helpRequest.id')
                                    ->label('ID запиту допомоги')
                                    ->weight(FontWeight::Bold)
                                    ->color('primary')
                                    ->copyable()
                                    ->copyMessage('ID скопійовано!')
                                    ->copyMessageDuration(1500),

                                Infolists\Components\TextEntry::make('user.name')
                                    ->label('Завантажено користувачем')
                                    ->weight(FontWeight::Medium)
                                    ->icon('heroicon-o-user'),
                            ]),

                        Infolists\Components\TextEntry::make('caption')
                            ->label('Підпис до фото')
                            ->placeholder('Підпис відсутній')
                            ->columnSpanFull()
                            ->markdown()
                            ->prose(),

                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\IconEntry::make('is_completion_photo')
                                    ->label('Фото завершення')
                                    ->boolean()
                                    ->trueIcon('heroicon-o-check-circle')
                                    ->falseIcon('heroicon-o-x-circle')
                                    ->trueColor('success')
                                    ->falseColor('gray')
                                    ->size(Infolists\Components\IconEntry\IconEntrySize::Large),

                                Infolists\Components\TextEntry::make('created_at')
                                    ->label('Дата завантаження')
                                    ->dateTime('d.m.Y H:i')
                                    ->icon('heroicon-o-calendar'),

                                Infolists\Components\TextEntry::make('updated_at')
                                    ->label('Останнє оновлення')
                                    ->dateTime('d.m.Y H:i')
                                    ->icon('heroicon-o-clock'),
                            ]),
                    ])
                    ->columnSpan('full'),

                Infolists\Components\Section::make('Інформація про запит')
                    ->schema([
                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('helpRequest.title')
                                    ->label('Назва запиту')
                                    ->weight(FontWeight::Bold)
                                    ->placeholder('Назва недоступна'),

                                Infolists\Components\TextEntry::make('helpRequest.status')
                                    ->label('Статус запиту')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'pending' => 'warning',
                                        'in_progress' => 'info',
                                        'completed' => 'success',
                                        'cancelled' => 'danger',
                                        default => 'gray',
                                    }),
                            ]),

                        Infolists\Components\TextEntry::make('helpRequest.description')
                            ->label('Опис запиту')
                            ->columnSpanFull()
                            ->markdown()
                            ->prose()
                            ->placeholder('Опис недоступний'),

                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('helpRequest.created_at')
                                    ->label('Дата створення запиту')
                                    ->dateTime('d.m.Y H:i')
                                    ->icon('heroicon-o-calendar-days'),

                                Infolists\Components\TextEntry::make('helpRequest.deadline')
                                    ->label('Термін виконання')
                                    ->date('d.m.Y')
                                    ->icon('heroicon-o-exclamation-triangle')
                                    ->color(fn ($state) => $state && $state < now() ? 'danger' : 'gray'),
                            ]),
                    ])
                    ->columnSpan('full')
                    ->collapsible()
                    ->persistCollapsed(),

                Infolists\Components\Section::make('Технічна інформація')
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('photo_path')
                                    ->label('Шлях до файлу')
                                    ->copyable()
                                    ->copyMessage('Шлях скопійовано!')
                                    ->icon('heroicon-o-folder'),

                                Infolists\Components\TextEntry::make('file_size')
                                    ->label('Розмір файлу')
                                    ->state(function ($record) {
                                        $path = storage_path('app/public/' . $record->photo_path);
                                        if (file_exists($path)) {
                                            $size = filesize($path);
                                            return $this->formatBytes($size);
                                        }
                                        return 'Невідомо';
                                    })
                                    ->icon('heroicon-o-document'),

                                Infolists\Components\TextEntry::make('file_type')
                                    ->label('Тип файлу')
                                    ->state(function ($record) {
                                        $path = storage_path('app/public/' . $record->photo_path);
                                        if (file_exists($path)) {
                                            return strtoupper(pathinfo($path, PATHINFO_EXTENSION));
                                        }
                                        return 'Невідомо';
                                    })
                                    ->badge()
                                    ->icon('heroicon-o-photo'),
                            ]),
                    ])
                    ->columnSpan('full')
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    protected function formatBytes($size, $precision = 2): string
    {
        $units = ['Б', 'КБ', 'МБ', 'ГБ', 'ТБ'];

        for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
            $size /= 1024;
        }

        return round($size, $precision) . ' ' . $units[$i];
    }

    public function getTitle(): string
    {
        return 'Перегляд фото #' . $this->getRecord()->id;
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // You can add custom widgets here if needed
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            // You can add custom widgets here if needed
        ];
    }
}
