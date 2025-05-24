<?php

namespace App\Filament\Resources\RequestPhotoResource\Pages;

use App\Filament\Resources\RequestPhotoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRequestPhotos extends ListRecords
{
    protected static string $resource = RequestPhotoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
