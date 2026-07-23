<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\MikrotikDevice;
use App\Services\MikrotikService;
use Illuminate\Http\Request;

class MikrotikHotspotController extends Controller
{
    // Handle redirect dari login.html MikroTik
    public function login(Request $request)
    {
        // Simpan parameter MikroTik ke session
        if ($request->get('mac')) {
            session([
                'mac' => $request->get('mac'),
                'ip' => $request->get('ip'),
                'link_login' => $request->get('link-login'),
                'chap_id' => $request->get('chap-id'),
                'chap_challenge' => $request->get('chap-challenge'),
            ]);
        }

        // Redirect ke portal beli paket
        return redirect()->route('portal.index');
    }

    // Halaman status koneksi user
    public function status(Request $request)
    {
        $mac = $request->get('mac', session('mac'));
        $ip = $request->get('ip', session('ip'));
        $uptime = $request->get('uptime', '0s');
        $sessionTimeLeft = $request->get('session-time-left', '');
        $loginBy = $request->get('login-by', '');

        $customer = null;
        $activeData = null;

        if ($mac) {
            session(['mac' => $mac, 'ip' => $ip]);

            // Ambil data dari MikroTik
            try {
                $device = MikrotikDevice::where('is_active', true)->first();
                if ($device) {
                    $service = new MikrotikService($device);
                    $actives = $service->getActiveUsers();
                    foreach ($actives as $active) {
                        if (isset($active['mac-address']) &&
                            strtoupper($active['mac-address']) === strtoupper($mac)) {
                            $activeData = $active;
                            $uptime = $active['uptime'] ?? $uptime;
                            $sessionTimeLeft = $active['session-time-left'] ?? $sessionTimeLeft;
                            break;
                        }
                    }
                }
            } catch (\Exception $e) {}

            // Cari customer di database
            $customer = Customer::where('is_active', true)
                ->where(function($q) use ($mac) {
                    $macClean = str_replace(':', '', strtoupper($mac));
                    $q->where('username', 'T-' . implode(':', str_split($macClean, 2)))
                      ->orWhere('username', 'like', '%' . substr($macClean, -6) . '%');
                })->with('package')->first();
        }

        $isTrial = !$customer || !$customer->package;

        return view('hotspot.status', compact(
            'customer', 'activeData', 'uptime',
            'sessionTimeLeft', 'loginBy', 'mac', 'ip', 'isTrial'
        ));
    }

    // Konfirmasi logout
    public function logout(Request $request)
    {
        $mac = $request->get('mac', session('mac'));
        return view('hotspot.logout', compact('mac'));
    }
}