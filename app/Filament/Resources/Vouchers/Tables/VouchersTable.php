<?php

namespace App\Filament\Resources\Vouchers\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VouchersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Kode')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('package.name')
                    ->label('Paket'),
                IconColumn::make('is_used')
                    ->label('Sudah Dipakai')
                    ->boolean(),
                TextColumn::make('customer.name')
                    ->label('Dipakai Oleh')
                    ->placeholder('Belum dipakai'),
                TextColumn::make('used_at')
                    ->label('Waktu Pakai')
                    ->dateTime()
                    ->placeholder('-'),
                TextColumn::make('expired_at')
                    ->label('Expired')
                    ->dateTime()
                    ->since()
                    ->placeholder('Tidak ada'),
            ]);
    }
}