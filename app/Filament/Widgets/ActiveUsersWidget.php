<?php

namespace App\Filament\Widgets;

use App\Models\MikrotikDevice;
use App\Services\MikrotikService;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class ActiveUsersWidget extends BaseWidget
{
    protected static ?string $heading = '';
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $activeUsers = $this->getActiveUsers();

        return $table
            ->query(fn (): Builder => \App\Models\Customer::query()
                ->where('is_active', true)
            )
            ->columns([
                TextColumn::make('username')
                    ->label('Username')
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Nama'),
                TextColumn::make('package.name')
                    ->label('Paket'),
                TextColumn::make('expired_at')
                    ->label('Expired')
                    ->dateTime()
                    ->since(),
            ]);
    }

    private function getActiveUsers(): array
    {
        try {
            $device = MikrotikDevice::where('is_active', true)->first();
            if (!$device) return [];
            $service = new MikrotikService($device);
            return $service->getActiveUsers();
        } catch (\Exception $e) {
            return [];
        }
    }
}