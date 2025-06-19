<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\RatingResource\Pages;
use App\Models\Rating as ModelsRating;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Card;
use Mokhosh\FilamentRating\Components\Rating;


class RatingResource extends Resource
{
    protected static ?string $model = ModelsRating::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Forms\Components\Select::make('produk_id')
                            ->options(function (): array {
                                return \App\Models\Produk::all()->pluck('nama_produk', 'id')->all();
                            })
                            ->label('Product')
                            ->searchable()
                            ->required(),

                        Forms\Components\Select::make('pembeli_id')
                            ->label('Nama Pembeli')
                            ->options(function (): array {
                                return \App\Models\Pembeli::all()->pluck('username', 'id')->all();
                            })
                            ->searchable()
                            ->required(),

                        Forms\Components\Select::make('transaction_id')
                            ->label('Transaksi')
                            ->options(function (): array {
                                return \App\Models\Transaction::all()->pluck('invoice', 'id')->all();
                            })
                            ->searchable()
                            ->required(),

                        Rating::make('rating')
                            ->label('Rating')
                            ->stars(10)  // Set maximum stars to 10
                            ->allowZero() // Allow 0 stars rating
                            ->color('warning') // Use warning color (yellow/gold)
                            ->required(),
                        
                        Forms\Components\Textarea::make('review')
                            ->nullable(),

                        Forms\Components\FileUpload::make('foto_review')
                            ->label('Foto Review')
                            ->placeholder('Foto Review')
                            ->multiple(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer.username')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('product.nama_produk')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('transaction.invoice')->sortable()->searchable(),
                \Mokhosh\FilamentRating\Columns\RatingColumn::make('rating')
                    ->label('Rating')
                    ->stars(10)
                    ->color('warning'),
                Tables\Columns\TextColumn::make('review')->limit(50),
                Tables\Columns\ImageColumn::make('foto_review')
                    ->url(fn ($record) => is_array($record->foto) ? (isset($record->foto[0]) ? \Illuminate\Support\Facades\Storage::url($record->foto[0]) : null) : ($record->foto ? \Illuminate\Support\Facades\Storage::url($record->foto) : null))
                    ->circular(),
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

    public static function canCreate(): bool
    {
        return false ;
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRatings::route('/'),
            // 'create' => Pages\CreateRating::route('/create'),
            // 'edit' => Pages\EditRating::route('/{record}/edit'),
        ];
    }
}
