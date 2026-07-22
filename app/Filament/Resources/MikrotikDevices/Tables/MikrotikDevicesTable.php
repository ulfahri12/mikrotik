<?php

namespace App\Filament\Resources\MikrotikDevices\Tables;

use App\Models\MikrotikDevice;
use App\Services\MikrotikService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MikrotikDevicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                TextColumn::make('host')
                    ->label('IP Address'),
                TextColumn::make('port'),
                TextColumn::make('username'),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                TextColumn::make('last_connected_at')
                    ->label('Terakhir Konek')
                    ->dateTime()
                    ->since()
                    ->placeholder('Belum pernah'),
            ])
            ->recordAction(null)
            ->recordActions([
                Action::make('test_connection')
                    ->label('Test Koneksi')
                    ->icon(Heroicon::OutlinedSignal)
                    ->action(function (MikrotikDevice $record) {
                        try {
                            $service = new MikrotikService($record);
                            $identity = $service->getIdentity();
                            $record->update(['last_connected_at' => now()]);
                            Notification::make()
                                ->title('Koneksi Berhasil!')
                                ->body("Terhubung ke: $identity")
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Koneksi Gagal!')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
            ]);
    }
}