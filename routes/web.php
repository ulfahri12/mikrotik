<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PortalController;
use App\Services\MikrotikService;
use App\Models\MikrotikDevice;
use App\Models\Transaction;
use App\Services\MidtransService;
use App\Mail\TransactionSuccessMail;
use Illuminate\Support\Facades\Mail;

// Portal routes
Route::prefix('portal')->name('portal.')->group(function () {
    Route::get('/', [PortalController::class, 'index'])->name('index');
    Route::post('/login', [PortalController::class, 'login'])->name('login.post');
    Route::post('/redeem', [PortalController::class, 'redeemVoucher'])->name('redeem');
    Route::post('/buy', [PortalController::class, 'buyPackage'])->name('buy');
    Route::post('/open-internet', [PortalController::class, 'openInternet'])->name('open-internet');
    Route::get('/success/{customer}', [PortalController::class, 'success'])->name('success');
});

// Webhook Midtrans
Route::post('/webhook/midtrans', function () {
    $midtrans = new MidtransService();
    $midtrans->handleCallback(request()->all());
    return response()->json(['status' => 'ok']);
});

// Proxy Snap.js
Route::get('/proxy/snap', function () {
    try {
        $snapJs = file_get_contents('https://app.sandbox.midtrans.com/snap/snap.js');
        return response($snapJs)->header('Content-Type', 'application/javascript');
    } catch (\Exception $e) {
        return response('// Error: ' . $e->getMessage(), 500)
            ->header('Content-Type', 'application/javascript');
    }
});

// Development only
Route::get('/test-service', function () {
    $device = MikrotikDevice::first();
    if (!$device) return response()->json(['error' => 'No device']);
    $service = new MikrotikService($device);
    return response()->json([
        'identity' => $service->getIdentity(),
        'resource' => $service->getResource(),
    ]);
});

Route::get('/test-email', function () {
    $transaction = Transaction::with(['customer', 'package'])->first();
    Mail::to('ulfahri1212@gmail.com')->send(new TransactionSuccessMail($transaction));
    return 'Email terkirim!';
});

Route::get('/vouchers/print', function () {
    return app(\App\Filament\Pages\PrintVoucherPage::class);
})->name('vouchers.print');

Route::get('/redeem/{code}', function ($code) {
    return redirect()->route('portal.index', ['voucher' => $code]);
})->name('portal.redeem.qr');

Route::get('/', function () {
    return redirect('/portal');
});