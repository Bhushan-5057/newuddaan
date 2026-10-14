<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CashfreeHelper
{
    protected $appId;
    protected $secretKey;
    protected $apiUrl;
    protected $environment;

    public function __construct()
    {
        $this->appId = config('cashfree.app_id');
        $this->secretKey = config('cashfree.secret_key');
        $this->environment = config('cashfree.environment', 'sandbox');
        $this->apiUrl = $this->environment == 'production' 
            ? 'https://api.cashfree.com/pg' 
            : 'https://sandbox.cashfree.com/pg';
    }

    /**
     * Create a new order in Cashfree
     */
    public function createOrder($orderData)
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'x-api-version' => '2022-09-01',
                'x-client-id' => $this->appId,
                'x-client-secret' => $this->secretKey,
            ])->post($this->apiUrl . '/orders', $orderData);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Cashfree Order Creation Failed: ' . $response->body());
            return null;

        } catch (\Exception $e) {
            Log::error('Cashfree Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get order status from Cashfree
     */
    public function getOrderStatus($orderId)
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'x-api-version' => '2022-09-01',
                'x-client-id' => $this->appId,
                'x-client-secret' => $this->secretKey,
            ])->get($this->apiUrl . '/orders/' . $orderId);

            if ($response->successful()) {
                return $response->json();
            }

            return null;

        } catch (\Exception $e) {
            Log::error('Cashfree Status Check Failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Verify payment signature
     */
    public function verifySignature($orderId, $orderAmount, $referenceId, $txStatus, $paymentMode, $txMsg, $txTime, $signature)
    {
        $data = $orderId . $orderAmount . $referenceId . $txStatus . $paymentMode . $txMsg . $txTime;
        $generatedSignature = hash_hmac('sha256', $data, $this->secretKey);
        
        return hash_equals($generatedSignature, $signature);
    }
}