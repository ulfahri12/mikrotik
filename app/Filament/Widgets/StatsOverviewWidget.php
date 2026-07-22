<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Package;
use App\Models\Transaction;
use App\Models\MikrotikDevice;
use App\Services\MikrotikService;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalCustomers = Customer::count();
        $activeCustomers = Customer::where('is_active', true)->count();
        $totalPackages = Package::where('is_active', true)->count();
        $monthlyRevenue = Transaction::where('status', 'paid')
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('amount');

        $onlineUsers = 0;
        try {
            $device = MikrotikDevice::where('is_active', true)->first();
            if ($device) {
                $service = new MikrotikService($device);
                $onlineUsers = count($service->getActiveUsers());
            }
        } catch (\Exception $e) {}

        return [
            Stat::make('Total Pelanggan', $totalCustomers)
                ->description('Semua pelanggan terdaftar')
                ->icon('heroicon-o-users')
                ->color('primary'),

            Stat::make('Pelanggan Aktif', $activeCustomers)
                ->description('Sedang berlangganan')
                ->icon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('User Online', $onlineUsers)
                ->description('Sedang terhubung')
                ->icon('heroicon-o-signal')
                ->color('warning'),

            Stat::make('Total Paket', $totalPackages)
                ->description('Paket tersedia')
                ->icon('heroicon-o-cube')
                ->color('info'),

            Stat::make('Pendapatan Bulan Ini', 'Rp ' . number_format($monthlyRevenue, 0, ',', '.'))
                ->description(now()->format('F Y'))
                ->icon('heroicon-o-banknotes')
                ->color('success'),
        ];
    }
}