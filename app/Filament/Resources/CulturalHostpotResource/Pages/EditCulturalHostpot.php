<?php

namespace App\Filament\Resources\CulturalHostpotResource\Pages;

use App\Filament\Resources\CulturalHostpotResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCulturalHostpot extends EditRecord
{
    protected static string $resource = CulturalHostpotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
