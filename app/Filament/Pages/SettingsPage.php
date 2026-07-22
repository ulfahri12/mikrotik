<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section as ComponentsSection;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class SettingsPage extends Page
{
    protected string $view = 'filament.pages.settings-page';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;
    protected static ?string $navigationLabel = 'Pengaturan';
    protected static ?string $title = 'Pengaturan Sistem';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'app_name' => Setting::get('app_name', 'RT/RW Net'),
            'app_address' => Setting::get('app_address', ''),
            'app_phone' => Setting::get('app_phone', ''),
            'tripay_api_key' => Setting::get('tripay_api_key', ''),
            'tripay_private_key' => Setting::get('tripay_private_key', ''),
            'tripay_merchant_code' => Setting::get('tripay_merchant_code', ''),
            'tripay_sandbox' => Setting::get('tripay_sandbox', '1') === '1',
            'wa_gateway_url' => Setting::get('wa_gateway_url', ''),
            'wa_gateway_token' => Setting::get('wa_gateway_token', ''),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                ComponentsSection::make('Informasi Bisnis')
                    ->schema([
                        TextInput::make('app_name')
                            ->label('Nama Usaha')
                            ->required(),
                        TextInput::make('app_address')
                            ->label('Alamat'),
                        TextInput::make('app_phone')
                            ->label('No HP / WhatsApp')
                            ->tel(),
                    ]),

                ComponentsSection::make('Payment Gateway (Tripay)')
                    ->schema([
                        TextInput::make('tripay_api_key')
                            ->label('API Key')
                            ->password()
                            ->revealable(),
                        TextInput::make('tripay_private_key')
                            ->label('Private Key')
                            ->password()
                            ->revealable(),
                        TextInput::make('tripay_merchant_code')
                            ->label('Merchant Code'),
                        Toggle::make('tripay_sandbox')
                            ->label('Mode Sandbox (Testing)')
                            ->default(true),
                    ]),

                ComponentsSection::make('WhatsApp Gateway')
                    ->schema([
                        TextInput::make('wa_gateway_url')
                            ->label('URL Gateway')
                            ->placeholder('https://api.fonnte.com/send')
                            ->url(),
                        TextInput::make('wa_gateway_token')
                            ->label('Token')
                            ->password()
                            ->revealable(),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            Setting::set($key, is_bool($value) ? ($value ? '1' : '0') : $value);
        }

        Notification::make()
            ->title('Pengaturan Disimpan!')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan')
                ->icon('heroicon-o-check')
                ->action('save'),
        ];
    }
}