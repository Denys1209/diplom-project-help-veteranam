<?php

namespace App\Filament\Resources\VeteranProfileResource\Pages;

use App\Filament\Resources\VeteranProfileResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVeteranProfile extends EditRecord
{
    protected static string $resource = VeteranProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
             Actions\DeleteAction::make(),
            Actions\ViewAction::make(),
        ];
    }
}
