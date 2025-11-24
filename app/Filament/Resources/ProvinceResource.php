<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProvinceResource\Pages;
use App\Models\Province;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProvinceResource extends Resource
{
    protected static ?string $model = Province::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';
    protected static ?string $navigationGroup = 'Master Data';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Utama')
                    ->description('Data dasar provinsi')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Provinsi')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            // Logic ID generation sederhana
                            ->afterStateUpdated(function ($state, Forms\Set $set, $operation) {
                                // Manual handling for ID if needed
                            }),
                        
                        Forms\Components\TextInput::make('id')
                            ->default(fn () => (string) Str::uuid())
                            ->disabled()
                            ->dehydrated()
                            ->required(),

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(5)
                            ->columnSpanFull()
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Media & Cloudinary')
                    ->description('File akan otomatis ter-upload ke Cloudinary sesuai folder Express.')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                // LOGO
                                Forms\Components\FileUpload::make('logoUrl')
                                    ->label('Logo Provinsi')
                                    ->image()
                                    // PENTING: Pake disk cloudinary
                                    ->disk('cloudinary') 
                                    // PENTING: Samain folder sama Express
                                    ->directory('rekaloka_provinces/logos') 
                                    ->visibility('public')
                                    ->maxSize(2048), // 2MB

                                // BACKGROUND
                                Forms\Components\FileUpload::make('backgroundUrl')
                                    ->label('Background Image')
                                    ->image()
                                    ->disk('cloudinary')
                                    ->directory('rekaloka_provinces/backgrounds')
                                    ->visibility('public')
                                    ->maxSize(5120), // 5MB
                                    
                                // AUDIO
                                Forms\Components\FileUpload::make('backsoundUrl')
                                    ->label('Backsound Audio')
                                    ->acceptedFileTypes(['audio/mpeg', 'audio/wav', 'audio/mp3'])
                                    ->disk('cloudinary')
                                    ->directory('rekaloka_provinces/audio')
                                    ->visibility('public')
                                    ->maxSize(10240), // 10MB
                            ]),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('latitude')
                                    ->numeric()
                                    ->inputMode('decimal'),
                                Forms\Components\TextInput::make('longitude')
                                    ->numeric()
                                    ->inputMode('decimal'),
                            ]),
                    ]),

                Forms\Components\Section::make('Data Budaya (JSON)')
                    ->schema([
                        Forms\Components\Textarea::make('iconicInfoJson')
                            ->label('Iconic Info (JSON Format)')
                            ->rows(15)
                            ->columnSpanFull()
                            ->formatStateUsing(fn ($state) => json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES))
                            ->dehydrateStateUsing(fn ($state) => json_decode($state, true)),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // ImageColumn otomatis support disk cloudinary kalau key-nya bener
                Tables\Columns\ImageColumn::make('logoUrl')
                    ->label('Logo')
                    ->disk('cloudinary') // Kasih tau table juga kalau ini di cloudinary
                    ->circular(),
                
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('description')
                    ->limit(50)
                    ->tooltip(fn (Province $record): string => $record->description),

                Tables\Columns\TextColumn::make('latitude')
                    ->label('Lat'),
                
                Tables\Columns\TextColumn::make('longitude')
                    ->label('Long'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListProvinces::route('/'),
            'create' => Pages\CreateProvince::route('/create'),
            'edit' => Pages\EditProvince::route('/{record}/edit'),
        ];
    }
}