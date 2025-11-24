<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GameCheckinResource\Pages;
use App\Models\GameCheckin;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GameCheckinResource extends Resource
{
    protected static ?string $model = GameCheckin::class;
    protected static ?string $navigationIcon = 'heroicon-o-camera'; // Icon kamera
    protected static ?string $navigationGroup = 'Gamification';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Check-in')
                    ->schema([
                        // Kita disable select user & hotspot karena biasanya ini data masuk dari API/HP
                        // Admin cuma tugasnya validasi.
                        Forms\Components\Select::make('userId')
                            ->relationship('user', 'username')
                            ->disabled(),
                            
                        Forms\Components\Select::make('hotspotId')
                            ->relationship('hotspot', 'name')
                            ->disabled(),

                        Forms\Components\FileUpload::make('imageUrl')
                            ->label('Bukti Foto')
                            ->image()
                            ->disk('cloudinary')
                            ->directory('rekaloka_checkins')
                            ->disabled() // Admin cuma boleh liat, gak boleh ganti foto user
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('isValidated')
                            ->label('Validasi Check-in ini?')
                            ->onColor('success')
                            ->offColor('danger')
                            ->helperText('Aktifkan jika foto sesuai lokasi.'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('imageUrl')
                    ->label('Bukti')
                    ->disk('cloudinary')
                    ->size(80), // Gedean dikit biar jelas
                
                Tables\Columns\TextColumn::make('user.username')->label('User')->searchable(),
                Tables\Columns\TextColumn::make('hotspot.name')->label('Lokasi'),
                
                Tables\Columns\IconColumn::make('isValidated')
                    ->boolean()
                    ->label('Valid?'),
                
                Tables\Columns\TextColumn::make('timestamp')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('timestamp', 'desc') // Yang baru checkin paling atas
            ->actions([
                Tables\Actions\EditAction::make()->label('Review'), // Ganti nama tombol jadi Review
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGameCheckins::route('/'),
            'edit' => Pages\EditGameCheckin::route('/{record}/edit'),
        ];
    }
}