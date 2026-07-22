<?php

namespace App\Filament\Resources\Packages\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PackagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Paket')
                    ->searchable(),
                TextColumn::make('mikrotikDevice.name')
                    ->label('Device'),
                TextColumn::make('mikrotik_profile')
                    ->label('Profile'),
                TextColumn::make('duration_days')
                    ->label('Durasi')
                    ->suffix(' hari'),
                TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR'),
                TextColumn::make('speed_upload')
                    ->label('Upload')
                    ->suffix(' Mbps'),
                TextColumn::make('speed_download')
                    ->label('Download')
                    ->suffix(' Mbps'),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ]);
    }
}