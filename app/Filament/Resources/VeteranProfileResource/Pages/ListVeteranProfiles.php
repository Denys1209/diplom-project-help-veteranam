<?php

namespace App\Filament\Resources\VeteranProfileResource\Pages;

use App\Filament\Resources\VeteranProfileResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVeteranProfiles extends ListRecords
{
    protected static string $resource = VeteranProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
