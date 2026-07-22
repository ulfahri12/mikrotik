<?php

namespace App\Filament\Resources\Customers\Tables;

use App\Models\Customer;
use App\Services\MikrotikService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CustomersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                TextColumn::make('username')
                    ->label('Username')
                    ->searchable(),
                TextColumn::make('package.name')
                    ->label('Paket'),
                TextColumn::make('expired_at')
                    ->label('Expired')
                    ->dateTime()
                    ->since()
                    ->placeholder('Tidak ada'),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->recordActions([
                Action::make('activate')
                    ->label('Aktifkan')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Customer $record) => !$record->is_active)
                    ->action(function (Customer $record) {
                        try {
                            $device = $record->package?->mikrotikDevice;
                            if (!$device) throw new \Exception('Device tidak ditemukan');

                            $service = new MikrotikService($device);
                            $service->addUser(
                                $record->username,
                                $record->password,
                                $record->package->mikrotik_profile
                            );
                            $record->update([
                                'is_active' => true,
                                'expired_at' => now()->addDays($record->package->duration_days),
                            ]);
                            Notification::make()
                                ->title('Pelanggan Diaktifkan!')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Gagal!')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
                Action::make('deactivate')
                    ->label('Nonaktifkan')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Customer $record) => $record->is_active)
                    ->action(function (Customer $record) {
                        try {
                            $device = $record->package?->mikrotikDevice;
                            if (!$device) throw new \Exception('Device tidak ditemukan');

                            $service = new MikrotikService($device);
                            $service->removeUser($record->username);
                            $record->update(['is_active' => false]);
                            Notification::make()
                                ->title('Pelanggan Dinonaktifkan!')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Gagal!')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
            ]);
    }
}