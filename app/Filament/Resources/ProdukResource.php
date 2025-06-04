<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Produk;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProdukResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProdukResource\RelationManagers;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class ProdukResource extends Resource
{
    protected static ?string $model = Produk::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationLabel = 'Produk';

    protected static ?string $navigationGroup = 'Master Data';

    public static function getNavigationSort(): ?int
    {
        return 2;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('nama_produk')
                            ->label('Nama Produk')
                            ->placeholder('Nama Produk')
                            ->required(),

                        Forms\Components\Textarea::make('deskripsi_produk')
                            ->label('Deskripsi Produk')
                            ->placeholder('Deskripsi Produk')
                            ->required(),

                        Forms\Components\TextInput::make('stok_tersedia')
                            ->label('Stok Tersedia')
                            ->placeholder('Stok Tersedia')
                            ->required(),

                        Forms\Components\TextInput::make('harga')
                            ->label('Harga Produk')
                            ->placeholder('Harga Produk')
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(0)
                            ->required(),

                        Forms\Components\Select::make('kategori_produk')
                            ->label('Kategori Produk')
                            ->options(Category::pluck('name', 'name'))
                            ->searchable()
                            ->required(),

                        Forms\Components\Select::make('ulasan_id')
                            ->label('Ulasan Produk')
                            ->options(function (): array {
                                return \App\Models\Rating::all()->pluck('review', 'id')->all();
                            })
                            ->searchable()
                            ->required(),

                        Forms\Components\FileUpload::make('foto')
                            ->label('Foto Produk')
                            ->placeholder('Foto Produk')
                            ->multiple(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_produk'),
                Tables\Columns\TextColumn::make('stok_tersedia'),
                Tables\Columns\TextColumn::make('kategori_produk'),
                Tables\Columns\TextColumn::make('ulasan_id')
                    ->label('Ulasan Produk')
                    ->formatStateUsing(function ($state) {
                        return \App\Models\Rating::find($state)?->review ?? '-';
                    }),
                Tables\Columns\TextColumn::make('harga'),
                Tables\Columns\ImageColumn::make('foto')
                    ->url(fn ($record) => is_array($record->foto) ? (isset($record->foto[0]) ? Storage::url($record->foto[0]) : null) : ($record->foto ? Storage::url($record->foto) : null))
                    ->circular(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d-m-Y H:i:s'),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProduks::route('/'),
            'create' => Pages\CreateProduk::route('/create'),
            'edit' => Pages\EditProduk::route('/{record}/edit'),
        ];
    }
}
