<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CulturalHostpotResource\Pages;
use App\Models\CulturalHostpot;
use App\Models\CulturalHotspot;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CulturalHotspotResource extends Resource
{
    protected static ?string $model = CulturalHostpot::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-library';
    protected static ?string $navigationGroup = 'Master Data';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detail Lokasi')
                    ->schema([
                        Forms\Components\Select::make('provinceId')
                            ->label('Provinsi')
                            ->relationship('province', 'name') // Magic! Ambil nama dari relasi
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('type')
                            ->options([
                                'Candi' => 'Candi',
                                'Museum' => 'Museum',
                                'Rumah Adat' => 'Rumah Adat',
                                'Taman Budaya' => 'Taman Budaya',
                                'Masjid Kuno' => 'Masjid Kuno',
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('id')
                            ->default(fn () => (string) Str::uuid())
                            ->disabled()
                            ->dehydrated()
                            ->required(),
                            
                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull()
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Lokasi & Media')
                    ->schema([
                        Forms\Components\FileUpload::make('imageUrl')
                            ->label('Foto Lokasi')
                            ->image()
                            ->disk('cloudinary') // Integrasi Cloudinary
                            ->directory('rekaloka_hotspots') // Folder khusus hotspot
                            ->visibility('public'),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('latitude')->numeric()->inputMode('decimal'),
                                Forms\Components\TextInput::make('longitude')->numeric()->inputMode('decimal'),
                            ]),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('imageUrl')->disk('cloudinary')->circular(),
                Tables\Columns\TextColumn::make('name')->searchable()->weight('bold'),
                Tables\Columns\TextColumn::make('type')->badge(), // Tampilan badge warna-warni otomatis
                Tables\Columns\TextColumn::make('province.name')->label('Provinsi')->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCulturalHostpots::route('/'),
            'create' => Pages\CreateCulturalHostpot::route('/create'),
            'edit' => Pages\EditCulturalHostpot::route('/{record}/edit'),
        ];
    }
}