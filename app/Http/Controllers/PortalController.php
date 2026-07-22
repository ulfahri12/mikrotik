<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Package;
use App\Models\Transaction;
use App\Services\MidtransService;
use App\Services\MikrotikService;
use App\Models\MikrotikDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PortalController extends Controller
{
    public function index(Request $request)
    {
        $packages = Package::where('is_active', true)->get();

        // Simpan parameter MikroTik ke session kalau ada
        if ($request->input('mac')) {
            session([
                'mac' => $request->input('mac'),
                'ip' => $request->input('ip'),
                'link_login' => $request->input('link-login'),
                'chap_id' => $request->input('chap-id'),
                'chap_challenge' => $request->input('chap-challenge'),
            ]);
        }

        return view('portal.index', compact('packages'));
    }

    public function login(Request $request)
    {
        $request->validate(['phone' => 'required|string']);

        $customer = Customer::where('phone', $request->phone)
            ->where('is_active', true)
            ->first();

        if (!$customer) {
            return back()->withErrors(['phone' => 'Nomor HP tidak ditemukan atau paket belum aktif.']);
        }

        if ($customer->expired_at && $customer->expired_at->isPast()) {
            return redirect()->route('portal.index')
                ->with('warning', 'Paket kamu sudah habis. Silakan beli paket baru.');
        }

        return redirect()->route('portal.success', $customer->id);
    }

    public function redeemVoucher(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'voucher_code' => 'required|string',
        ]);

        $voucher = \App\Models\Voucher::where('code', strtoupper($request->voucher_code))
            ->where('is_used', false)
            ->with('package')
            ->first();

        if (!$voucher) {
            return back()->withErrors(['voucher_code' => 'Kode voucher tidak valid atau sudah dipakai.']);
        }

        $customer = Customer::firstOrCreate(
            ['phone' => $request->phone],
            [
                'name' => 'User ' . $request->phone,
                'username' => 'user_' . Str::random(6),
                'password' => Str::random(8),
            ]
        );

        $device = MikrotikDevice::where('is_active', true)->first();
        if ($device) {
            try {
                $mikrotik = new MikrotikService($device);
                $mikrotik->addUser(
                    $customer->username,
                    $customer->password,
                    $voucher->package->mikrotik_profile
                );
            } catch (\Exception $e) {
                Log::error('Voucher redeem MikroTik error: ' . $e->getMessage());
            }
        }

        $customer->update([
            'is_active' => true,
            'package_id' => $voucher->package_id,
            'expired_at' => now()->addDays($voucher->package->duration_days),
        ]);

        $voucher->update([
            'is_used' => true,
            'customer_id' => $customer->id,
            'used_at' => now(),
        ]);

        return redirect()->route('portal.success', $customer->id);
    }

    public function buyPackage(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:packages,id',
            'name' => 'required|string',
            'phone' => 'required|string',
            'email' => 'nullable|email',
        ]);

        $package = Package::findOrFail($request->package_id);

        $customer = Customer::firstOrCreate(
            ['phone' => $request->phone],
            [
                'name' => $request->name,
                'email' => $request->email,
                'username' => 'user_' . Str::random(6),
                'password' => Str::random(8),
            ]
        );

        $customer->update([
            'name' => $request->name,
            'email' => $request->email ?? $customer->email,
        ]);

        $transaction = Transaction::create([
            'invoice' => 'INV-' . strtoupper(Str::random(8)),
            'customer_id' => $customer->id,
            'package_id' => $package->id,
            'amount' => $package->price,
            'status' => 'pending',
        ]);

        // Buka internet dulu sebelum tampilkan halaman payment
        $mac = session('mac');
        if ($mac) {
            try {
                $device = MikrotikDevice::where('is_active', true)->first();
                if ($device) {
                    $service = new MikrotikService($device);
                    $username = 'T-' . strtoupper(str_replace('-', ':', $mac));
                    $service->changeUserProfile($username, 'payment-temp');
                    // Tunggu sebentar agar internet aktif
                    sleep(1);
                }
            } catch (\Exception $e) {
                Log::error('Open internet on buy failed: ' . $e->getMessage());
            }
        }

        try {
            $midtrans = new MidtransService();
            $snapToken = $midtrans->createTransaction($transaction);
            return view('portal.payment', compact('transaction', 'package', 'customer', 'snapToken'));
        } catch (\Exception $e) {
            $transaction->delete();
            return back()->withErrors(['error' => 'Gagal membuat transaksi: ' . $e->getMessage()]);
        }
    }

    public function openInternet(Request $request)
    {
        $mac = $request->get('mac', session('mac'));

        if (!$mac) {
            return response()->json(['status' => 'error', 'message' => 'MAC not found']);
        }

        try {
            $device = MikrotikDevice::where('is_active', true)->first();
            if (!$device) throw new \Exception('No active device');

            $service = new MikrotikService($device);
            $username = 'T-' . strtoupper(str_replace('-', ':', $mac));
            $service->changeUserProfile($username, 'payment-temp');

            return response()->json(['status' => 'ok']);
        } catch (\Exception $e) {
            Log::error('Open internet failed: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function success(mixed $customerId)
    {
        $customer = Customer::with('package')->findOrFail($customerId);
        return view('portal.success', compact('customer'));
    }
}
