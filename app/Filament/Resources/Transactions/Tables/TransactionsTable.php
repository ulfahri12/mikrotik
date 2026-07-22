<?php

namespace App\Filament\Resources\Transactions\Tables;

use App\Models\Transaction;
use App\Services\MikrotikService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice')
                    ->label('Invoice')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('customer.name')
                    ->label('Pelanggan')
                    ->searchable(),
                TextColumn::make('package.name')
                    ->label('Paket'),
                TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money('IDR'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'pending' => 'warning',
                        'failed', 'expired' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('payment_method')
                    ->label('Metode'),
                TextColumn::make('paid_at')
                    ->label('Waktu Bayar')
                    ->dateTime()
                    ->placeholder('-'),
            ])
            ->recordActions([
                Action::make('mark_paid')
                    ->label('Tandai Lunas')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Transaction $record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->action(function (Transaction $record) {
                        try {
                            $record->update([
                                'status' => 'paid',
                                'paid_at' => now(),
                            ]);

                            // Aktifkan customer di MikroTik
                            $customer = $record->customer;
                            $device = $record->package?->mikrotikDevice;

                            if ($customer && $device) {
                                $service = new MikrotikService($device);
                                $service->addUser(
                                    $customer->username,
                                    $customer->password,
                                    $record->package->mikrotik_profile
                                );
                                $customer->update([
                                    'is_active' => true,
                                    'package_id' => $record->package_id,
                                    'expired_at' => now()->addDays($record->package->duration_days),
                                ]);
                            }

                            Notification::make()
                                ->title('Transaksi Lunas!')
                                ->body('Pelanggan berhasil diaktifkan.')
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