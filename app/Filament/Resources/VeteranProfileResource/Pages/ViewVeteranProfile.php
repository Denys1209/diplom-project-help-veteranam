<?php
namespace App\Filament\Resources\VeteranProfileResource\Pages;

use App\Filament\Resources\VeteranProfileResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewVeteranProfile extends ViewRecord
{
    protected static string $resource = VeteranProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];

    }
}

