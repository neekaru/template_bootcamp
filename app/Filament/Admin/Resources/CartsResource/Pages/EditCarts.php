<?php

namespace App\Filament\Admin\Resources\CartsResource\Pages;

use App\Filament\Admin\Resources\CartsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCarts extends EditRecord
{
    protected static string $resource = CartsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
