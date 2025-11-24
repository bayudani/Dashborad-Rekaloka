<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BadgeResource\Pages;
use App\Models\Badge;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class BadgeResource extends Resource
{
    protected static ?string $model = Badge::class;
    protected static ?string $navigationIcon = 'heroicon-o-trophy';
    protected static ?string $navigationGroup = 'Gamification';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('userId')
                    ->label('Pilih User')
                    ->relationship('user', 'username')
                    ->searchable() // Biar gampang nyari user
                    ->preload()
                    ->required(),

                Forms\Components\TextInput::make('name')
                    ->label('Nama Badge')
                    ->placeholder('Contoh: Penjelajah Jawa')
                    ->required(),
                
                Forms\Components\TextInput::make('id')
                    ->default(fn () => (string) Str::uuid())
                    ->disabled()
                    ->dehydrated()
                    ->required(),

                Forms\Components\Textarea::make('description')
                    ->required(),

                Forms\Components\FileUpload::make('iconUrl')
                    ->label('Icon Badge')
                    ->image()
                    ->disk('cloudinary')
                    ->directory('rekaloka_badges')
                    ->visibility('public'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('iconUrl')->disk('cloudinary'),
                Tables\Columns\TextColumn::make('name')->weight('bold')->searchable(),
                Tables\Columns\TextColumn::make('user.username')->label('Pemilik'),
                Tables\Columns\TextColumn::make('createdAt')->date(),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(), // Admin bisa hapus badge kalau salah kasih
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBadges::route('/'),
            'create' => Pages\CreateBadge::route('/create'),
        ];
    }
}