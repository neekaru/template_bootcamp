<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use App\Models\Cart;
use Filament\Tables;
use App\Models\Produk;
use App\Models\Pembeli;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\CartsResource\Pages;
use App\Filament\Admin\Resources\CartsResource\RelationManagers;

class CartsResource extends Resource
{
    protected static ?string $model = Cart::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Select::make('pembeli_id')
                            ->options(function (): array {
                                return Pembeli::all()->pluck('username', 'id')->all();
                            })
                            ->label('Kostumer')
                            ->searchable()
                            ->required(),
                        Forms\Components\Select::make('produk_id')
                            ->options(Produk::all()->pluck('nama_produk', 'id')->all())
                            ->label('Produk')
                            ->searchable()
                            ->required(),
                        Forms\Components\TextInput::make('qty')
                            ->label('Quantity')
                            ->numeric()
                            ->minValue(1)
                            ->required(),
                    ])
                    
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pembeli.username')->label('Pembeli')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('produk.nama_produk')->label('Produk')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('qty')->label('Quantity')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function canCreate(): bool
    {
        return false ;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCarts::route('/'),
            'create' => Pages\CreateCarts::route('/create'),
            'edit' => Pages\EditCarts::route('/{record}/edit'),
        ];
    }
}
