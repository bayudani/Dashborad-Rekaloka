<?php

namespace App\Filament\Resources\CulturalHostpotResource\Pages;

use App\Filament\Resources\CulturalHostpotResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCulturalHostpots extends ListRecords
{
    protected static string $resource = CulturalHostpotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
