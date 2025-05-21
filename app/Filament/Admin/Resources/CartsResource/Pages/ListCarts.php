<?php

namespace App\Filament\Admin\Resources\CartsResource\Pages;

use App\Filament\Admin\Resources\CartsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCarts extends ListRecords
{
    protected static string $resource = CartsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
