<?php

namespace App\Filament\Pages;

use App\Models\Voucher;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class PrintVoucherPage extends Page
{
    protected string $view = 'filament.pages.print-voucher-page';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPrinter;
    protected static ?string $navigationLabel = 'Print Voucher';
    protected static bool $shouldRegisterNavigation = false;

    public array $voucherIds = [];
    public mixed $vouchers;

    public function mount(): void
{
    $ids = request()->query('ids', '');
    $this->voucherIds = $ids ? explode(',', $ids) : [];
    $this->vouchers = Voucher::with('package')
        ->whereIn('id', $this->voucherIds)
        ->get();
}
}