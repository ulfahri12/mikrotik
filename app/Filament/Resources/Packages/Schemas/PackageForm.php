<?php

namespace App\Filament\Resources\Packages\Schemas;

use App\Models\MikrotikDevice;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PackageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('mikrotik_device_id')
                ->label('MikroTik Device')
                ->options(MikrotikDevice::where('is_active', true)->pluck('name', 'id'))
                ->required(),
            TextInput::make('name')
                ->required()
                ->label('Nama Paket'),
            TextInput::make('mikrotik_profile')
                ->required()
                ->label('Profile MikroTik')
                ->default(fn() => 'paket-' . \Illuminate\Support\Str::slug(request()->input('name', 'baru')))
                ->unique(table: 'packages', column: 'mikrotik_profile', ignoreRecord: true)
                ->helperText('Nama unik profile di MikroTik, contoh: paket-1hari, paket-1bulan')
                ->placeholder('contoh: paket-1hari'),
            TextInput::make('duration_days')
                ->numeric()
                ->required()
                ->label('Durasi (hari)'),
            TextInput::make('price')
                ->numeric()
                ->required()
                ->label('Harga (Rp)')
                ->prefix('Rp'),
            TextInput::make('speed_upload')
                ->numeric()
                ->label('Upload (Mbps)'),
            TextInput::make('speed_download')
                ->numeric()
                ->label('Download (Mbps)'),
            Toggle::make('is_active')
                ->label('Aktif')
                ->default(true),
        ]);
    }
}
