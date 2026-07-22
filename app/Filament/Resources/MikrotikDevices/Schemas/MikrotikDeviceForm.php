<?php

namespace App\Filament\Resources\MikrotikDevices\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class MikrotikDeviceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->required()
                ->label('Nama Device'),
            TextInput::make('host')
                ->required()
                ->label('IP Address'),
            TextInput::make('port')
                ->numeric()
                ->default(8728)
                ->required(),
            TextInput::make('username')
                ->required()
                ->default('admin'),
            TextInput::make('password')
                ->password()
                ->revealable()
                ->default(''),
            Toggle::make('is_active')
                ->label('Aktif')
                ->default(true),
        ]);
    }
}