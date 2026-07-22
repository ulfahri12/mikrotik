<?php

namespace App\Filament\Resources\Vouchers\Schemas;

use App\Models\Package;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class VoucherForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('package_id')
                ->label('Paket')
                ->options(Package::where('is_active', true)->pluck('name', 'id'))
                ->required(),
            TextInput::make('code')
                ->label('Kode Voucher')
                ->required()
                ->unique(ignoreRecord: true)
                ->default(fn () => strtoupper(\Illuminate\Support\Str::random(8))),
            DateTimePicker::make('expired_at')
                ->label('Expired'),
        ]);
    }
}