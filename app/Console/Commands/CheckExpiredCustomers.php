<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Services\MikrotikService;
use Illuminate\Console\Command;

class CheckExpiredCustomers extends Command
{
    protected $signature = 'customers:check-expired';
    protected $description = 'Cek dan nonaktifkan pelanggan yang sudah expired';

    public function handle()
    {
        $expiredCustomers = Customer::where('is_active', true)
            ->where('expired_at', '<', now())
            ->with('package.mikrotikDevice')
            ->get();

        foreach ($expiredCustomers as $customer) {
            try {
                $device = $customer->package?->mikrotikDevice;

                if ($device) {
                    $service = new MikrotikService($device);
                    // Hapus user dari hotspot
                    $service->removeUser($customer->username);
                }

                // Update status di database
                $customer->update(['is_active' => false]);

                $this->info("Customer {$customer->name} dinonaktifkan.");

            } catch (\Exception $e) {
                $this->error("Gagal nonaktifkan {$customer->name}: " . $e->getMessage());
            }
        }

        $this->info('Selesai cek expired customers.');
    }
}