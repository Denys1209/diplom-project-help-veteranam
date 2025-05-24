<?php

namespace App\Filament\Resources\RequestPhotoResource\Pages;

use App\Filament\Resources\RequestPhotoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRequestPhoto extends EditRecord
{
    protected static string $resource = RequestPhotoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
