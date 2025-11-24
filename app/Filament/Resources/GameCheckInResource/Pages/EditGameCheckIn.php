<?php

namespace App\Filament\Resources\GameCheckInResource\Pages;

use App\Filament\Resources\GameCheckInResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGameCheckIn extends EditRecord
{
    protected static string $resource = GameCheckInResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
