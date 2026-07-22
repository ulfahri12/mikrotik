<?php

namespace App\Models;

use App\Services\MikrotikService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Package extends Model
{
    protected $fillable = [
        'mikrotik_device_id',
        'name',
        'mikrotik_profile',
        'duration_days',
        'price',
        'speed_upload',
        'speed_download',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'integer',
    ];

    public function mikrotikDevice()
    {
        return $this->belongsTo(MikrotikDevice::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function vouchers()
    {
        return $this->hasMany(Voucher::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    protected static function booted(): void
    {
        // Otomatis sync ke MikroTik saat paket dibuat/diupdate
        static::saved(function (Package $package) {
            if (!$package->is_active) return;
            if (!$package->mikrotikDevice) return;

            try {
                $service = new MikrotikService($package->mikrotikDevice);
                $rateLimit = ($package->speed_upload ?? 1) . 'M/' . ($package->speed_download ?? 1) . 'M';
                $service->addProfile(
                    $package->mikrotik_profile,
                    $rateLimit,
                    1
                );
            } catch (\Exception $e) {
                Log::error('Sync profile gagal: ' . $e->getMessage());
            }
        });

        // Hapus profile di MikroTik saat paket dihapus
        static::deleting(function (Package $package) {
            if (!$package->mikrotikDevice) return;

            try {
                $service = new MikrotikService($package->mikrotikDevice);
                $service->removeProfile($package->mikrotik_profile);
            } catch (\Exception $e) {
                Log::error('Remove profile gagal: ' . $e->getMessage());
            }
        });
    }
}
