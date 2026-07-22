<?php

namespace App\Filament\Resources\Transactions\Schemas;

use App\Models\Customer;
use App\Models\Package;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('invoice')
                ->label('No Invoice')
                ->required()
                ->default(fn () => 'INV-' . strtoupper(\Illuminate\Support\Str::random(8)))
                ->unique(ignoreRecord: true),
            Select::make('customer_id')
                ->label('Pelanggan')
                ->options(Customer::pluck('name', 'id'))
                ->searchable()
                ->required(),
            Select::make('package_id')
                ->label('Paket')
                ->options(Package::where('is_active', true)->pluck('name', 'id'))
                ->required(),
            TextInput::make('amount')
                ->label('Jumlah Bayar')
                ->numeric()
                ->prefix('Rp')
                ->required(),
            Select::make('status')
                ->label('Status')
                ->options([
                    'pending' => 'Pending',
                    'paid' => 'Paid',
                    'failed' => 'Failed',
                    'expired' => 'Expired',
                ])
                ->default('pending')
                ->required(),
            Select::make('payment_method')
                ->label('Metode Bayar')
                ->options([
                    'manual' => 'Manual',
                    'tripay' => 'Tripay',
                    'midtrans' => 'Midtrans',
                ])
                ->nullable(),
            DateTimePicker::make('paid_at')
                ->label('Waktu Bayar'),
        ]);
    }
}