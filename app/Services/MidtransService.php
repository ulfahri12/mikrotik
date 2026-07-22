<?php

namespace App\Services;

use App\Models\MikrotikDevice;
use App\Models\Transaction;
use App\Mail\TransactionSuccessMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');
    }

    public function createTransaction(Transaction $transaction): string
    {
        $params = [
            'transaction_details' => [
                'order_id' => $transaction->invoice,
                'gross_amount' => $transaction->amount,
            ],
            'customer_details' => [
                'first_name' => $transaction->customer->name,
                'phone' => $transaction->customer->phone ?? '',
            ],
            'item_details' => [
                [
                    'id' => $transaction->package_id,
                    'price' => $transaction->amount,
                    'quantity' => 1,
                    'name' => $transaction->package->name,
                ]
            ],
            'expiry' => [
                'unit' => 'hours',
                'duration' => 24,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        $transaction->update([
            'payment_method' => 'midtrans',
            'payment_url' => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/' . $snapToken,
        ]);

        return $snapToken;
    }

    public function handleCallback(array $payload): void
    {
        $notification = new Notification();

        $orderId = $notification->order_id;
        $statusCode = $notification->status_code;
        $grossAmount = $notification->gross_amount;
        $signatureKey = $notification->signature_key;

        // Verifikasi signature
        $serverKey = config('services.midtrans.server_key');
        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($signatureKey !== $expectedSignature) {
            throw new \Exception('Invalid signature key');
        }

        $transactionStatus = $notification->transaction_status;
        $paymentType = $notification->payment_type;
        $fraudStatus = $notification->fraud_status ?? null;

        $transaction = Transaction::where('invoice', $orderId)
            ->with(['customer', 'package'])
            ->firstOrFail();

        if ($transactionStatus === 'capture') {
            if ($fraudStatus === 'accept') {
                $this->markAsPaid($transaction, $paymentType);
            }
        } elseif ($transactionStatus === 'settlement') {
            $this->markAsPaid($transaction, $paymentType);
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $transaction->update(['status' => 'failed']);
        } elseif ($transactionStatus === 'pending') {
            $transaction->update(['status' => 'pending']);
        }
    }

    private function markAsPaid(Transaction $transaction, string $paymentType): void
    {
        if ($transaction->status === 'paid') return;

        $transaction->update([
            'status' => 'paid',
            'paid_at' => now(),
            'payment_channel' => $paymentType,
        ]);

        // Aktifkan customer di MikroTik
        $customer = $transaction->customer;
        $device = MikrotikDevice::where('is_active', true)->first();

        if ($customer && $device) {
            try {
                $mikrotik = new MikrotikService($device);
                $mikrotik->addUser(
                    $customer->username,
                    $customer->password,
                    $transaction->package->mikrotik_profile
                );
                $customer->update([
                    'is_active' => true,
                    'package_id' => $transaction->package_id,
                    'expired_at' => now()->addDays($transaction->package->duration_days),
                ]);
            } catch (\Exception $e) {
                Log::error('MikroTik activation failed: ' . $e->getMessage());
            }
        }

        // Kirim email
        if ($customer->email ?? null) {
            Mail::to($customer->email)->send(new TransactionSuccessMail($transaction));
        }
    }
}