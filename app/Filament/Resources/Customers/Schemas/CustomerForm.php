<?php

namespace App\Filament\Resources\Customers\Schemas;

use App\Models\Package;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->required()
                ->label('Nama Pelanggan'),
            TextInput::make('phone')
                ->label('No HP')
                ->tel(),
            TextInput::make('email')
                ->label('Email')
                ->email()
                ->nullable(),
            TextInput::make('address')
                ->label('Alamat'),
            TextInput::make('username')
                ->required()
                ->label('Username Hotspot')
                ->unique(ignoreRecord: true),
            TextInput::make('password')
                ->required()
                ->label('Password Hotspot'),
            Select::make('package_id')
                ->label('Paket')
                ->options(Package::where('is_active', true)->pluck('name', 'id'))
                ->nullable(),
            DateTimePicker::make('expired_at')
                ->label('Expired'),
            Toggle::make('is_active')
                ->label('Aktif')
                ->default(false),
        ]);
    }
}
