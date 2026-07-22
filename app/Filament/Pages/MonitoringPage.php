<?php

namespace App\Filament\Pages;

use App\Models\MikrotikDevice;
use App\Services\MikrotikService;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use BackedEnum;

class MonitoringPage extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSignal;
    protected static ?string $navigationLabel = 'Monitoring';
    protected static ?string $title = 'Monitoring User Aktif';

    public array $activeUsers = [];
    public string $selectedDevice = '';
    public array $devices = [];

    public function mount(): void
    {
        $this->devices = MikrotikDevice::where('is_active', true)
            ->pluck('name', 'id')
            ->toArray();

        if (!empty($this->devices)) {
            $this->selectedDevice = array_key_first($this->devices);
            $this->loadUsers();
        }
    }

    public function loadUsers(): void
    {
        try {
            $device = MikrotikDevice::find($this->selectedDevice);
            if (!$device) return;

            $service = new MikrotikService($device);
            $this->activeUsers = $service->getActiveUsers();
        } catch (\Exception $e) {
            $this->activeUsers = [];
        }
    }

    public function updatedSelectedDevice(): void
    {
        $this->loadUsers();
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\ActiveUsersWidget::class,
        ];
    }
}
