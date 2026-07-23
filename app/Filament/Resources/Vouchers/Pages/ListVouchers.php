<?php

namespace App\Filament\Resources\Vouchers\Pages;

use App\Filament\Resources\Vouchers\VoucherResource;
use App\Models\Package;
use App\Models\Voucher;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ListVouchers extends ListRecords
{
    protected static string $resource = VoucherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('generate_batch')
                ->label('Generate Voucher')
                ->icon('heroicon-o-sparkles')
                ->form([
                    Select::make('package_id')
                        ->label('Paket')
                        ->options(Package::where('is_active', true)->pluck('name', 'id'))
                        ->required(),
                    TextInput::make('quantity')
                        ->label('Jumlah Voucher')
                        ->numeric()
                        ->default(10)
                        ->minValue(1)
                        ->maxValue(100)
                        ->required(),
                ])
                ->action(function (array $data) {
                    $package = Package::find($data['package_id']);

                    $device = \App\Models\MikrotikDevice::where('is_active', true)->first();
                    $mikrotik = null;

                    if (!$device) {
                        Log::error('No active MikroTik device found');
                    } else {
                        try {
                            $mikrotik = new \App\Services\MikrotikService($device);
                            Log::info('MikroTik connected: ' . $device->host);
                        } catch (\Exception $e) {
                            Log::error('MikroTik connect failed: ' . $e->getMessage());
                        }
                    }

                    for ($i = 0; $i < $data['quantity']; $i++) {
                        $code = strtoupper(\Illuminate\Support\Str::random(8));

                        Voucher::create([
                            'package_id' => $data['package_id'],
                            'code' => $code,
                        ]);

                        if ($mikrotik && $package) {
                            try {
                                $mikrotik->addUser($code, $code, $package->mikrotik_profile);
                                Log::info('User created in MikroTik: ' . $code);
                            } catch (\Exception $e) {
                                Log::error('Add user failed for ' . $code . ': ' . $e->getMessage());
                            }
                        } else {
                            Log::warning('Skipped MikroTik for code: ' . $code . ' - mikrotik=' . ($mikrotik ? 'ok' : 'null') . ' package=' . ($package ? 'ok' : 'null'));
                        }
                    }
                }),
            Action::make('print_selected')
                ->label('Print Voucher')
                ->icon('heroicon-o-printer')
                ->color('gray')
                ->action(function () {
                    $ids = \App\Models\Voucher::where('is_used', false)
                        ->pluck('id')
                        ->implode(',');
                    $this->redirect('/admin/print-voucher-page?ids=' . $ids);
                }),
        ];
    }
}
