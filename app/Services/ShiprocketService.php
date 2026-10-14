<?php
//  shiprocketService.php (Shiprocket API Integration Service)
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ShiprocketService
{
    protected $baseUrl;
    protected $email;
    protected $password;
    protected $environment;
    protected $tokenCacheKey = 'shiprocket_api_token';

    // API Endpoints
    const ENDPOINT_LOGIN = '/auth/login';
    const ENDPOINT_CREATE_ORDER = '/orders/create/adhoc';
    const ENDPOINT_COURIER_SERVICEABILITY = '/courier/serviceability';
    const ENDPOINT_ASSIGN_AWB = '/courier/assign/awb';
    const ENDPOINT_GENERATE_LABEL = '/courier/generate/label';
    const ENDPOINT_TRACK_AWB = '/courier/track/awb';
    const ENDPOINT_CANCEL_ORDER = '/orders/cancel';
    const ENDPOINT_GENERATE_INVOICE = '/orders/print/invoice';

    public function __construct()
    {
        $this->baseUrl = rtrim(env('SHIPROCKET_BASE_URL'), '/');
        $this->email = env('SHIPROCKET_EMAIL');
        $this->password = env('SHIPROCKET_PASSWORD');
        $this->environment = env('SHIPROCKET_ENV', 'testing');
    }

    /**
     * Authenticate and get token with caching
     * 
     * @return string|null
     */
    public function authenticate()
    {
        // Check if token exists in cache
        if (Cache::has($this->tokenCacheKey)) {
            $cachedToken = Cache::get($this->tokenCacheKey);
            if ($this->isTokenValid($cachedToken)) {
                return $cachedToken;
            }
            $this->clearTokenCache();
        }

        try {
            $response = Http::timeout(30)
                ->post($this->baseUrl . self::ENDPOINT_LOGIN, [
                    'email' => $this->email,
                    'password' => $this->password,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $token = $data['token'] ?? null;

                if ($token) {
                    $ttl = env('SHIPROCKET_TOKEN_CACHE_TTL', 3600);
                    Cache::put($this->tokenCacheKey, $token, $ttl);

                    Log::info('Shiprocket authentication successful', [
                        'environment' => $this->environment
                    ]);

                    return $token;
                }
            }

            Log::error('Shiprocket authentication failed', [
                'status' => $response->status(),
                'response' => $response->json()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Shiprocket authentication exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return null;
        }
    }

    /**
     * Check if token is still valid
     * 
     * @param string $token
     * @return bool
     */
    protected function isTokenValid($token)
    {
        // Basic validation - you can implement more sophisticated check if needed
        return !empty($token) && strlen($token) > 10;
    }

    /**
     * Clear cached token
     * 
     * @return void
     */
    public function clearTokenCache()
    {
        Cache::forget($this->tokenCacheKey);
        Log::info('Shiprocket token cache cleared');
    }

    /**
     * Create Order in Shiprocket
     * 
     * @param array $data
     * @return array
     */
    public function createOrder($data)
    {
        try {
            // Validate required fields
            $validation = $this->validateOrderData($data);
            if (!$validation['valid']) {
                return $validation['response'];
            }

            $payload = $this->formatShiprocketOrderData($data);
            $token = $this->authenticate();

            if (!$token) {
                return $this->errorResponse('Shiprocket token generation failed', 401);
            }

            $response = Http::withToken($token)
                ->timeout(60)
                ->retry(3, 100)
                ->post($this->baseUrl . self::ENDPOINT_CREATE_ORDER, $payload);

            if ($response->successful()) {
                $responseData = $response->json();
                Log::info('Shiprocket order created successfully', [
                    'order_id' => $data['order_id'],
                    'shiprocket_order_id' => $responseData['order_id'] ?? null,
                    'shipment_id' => $responseData['shipment_id'] ?? null
                ]);

                return $this->successResponse($responseData, ['payload' => $payload]);
            }

            return $this->errorResponse(
                $this->getErrorMessage($response),
                $response->status(),
                $response->json()['errors'] ?? []
            );
        } catch (\Exception $e) {
            Log::error('Shiprocket create order exception', [
                'order_id' => $data['order_id'] ?? 'unknown',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->exceptionResponse($e, 'creating the order');
        }
    }

    /**
     * Check Courier Serviceability
     * 
     * @param string $pickupPincode
     * @param string $deliveryPincode
     * @param float $weight
     * @param int $cod
     * @return array
     */
    public function checkCourier($pickupPincode, $deliveryPincode, $weight, $cod = 0)
    {
        try {
            $token = $this->authenticate();
            if (!$token) {
                return $this->errorResponse('Authentication failed', 401);
            }

            $response = Http::withToken($token)
                ->timeout(30)
                ->get($this->baseUrl . self::ENDPOINT_COURIER_SERVICEABILITY, [
                    'pickup_postcode' => $pickupPincode,
                    'delivery_postcode' => $deliveryPincode,
                    'cod' => $cod,
                    'weight' => $weight,
                ]);

            if ($response->successful()) {
                return $this->successResponse($response->json());
            }

            return $this->errorResponse($this->getErrorMessage($response), $response->status());
        } catch (\Exception $e) {
            Log::error('Shiprocket courier check failed', ['error' => $e->getMessage()]);
            return $this->exceptionResponse($e, 'checking courier serviceability');
        }
    }

    /**
     * Assign AWB to shipment
     * 
     * @param int $shipmentId
     * @param int $courierCompanyId
     * @return array
     */
    public function assignAWB($shipmentId, $courierCompanyId)
    {
        try {
            $token = $this->authenticate();
            if (!$token) {
                return $this->errorResponse('Authentication failed', 401);
            }

            $response = Http::withToken($token)
                ->timeout(30)
                ->acceptJson()
                ->post($this->baseUrl . self::ENDPOINT_ASSIGN_AWB, [
                    'shipment_id' => [(int) $shipmentId],
                    'courier_id' => (int) $courierCompanyId,
                ]);

            if ($response->successful()) {
                $responseData = $response->json();
                Log::info('AWB assigned successfully', [
                    'shipment_id' => $shipmentId,
                    'courier_id' => $courierCompanyId,
                    'awb_code' => $responseData['data']['awb_code'] ?? null
                ]);

                return $this->successResponse($responseData);
            }

            return $this->errorResponse($this->getErrorMessage($response), $response->status());
        } catch (\Exception $e) {
            Log::error('Shiprocket AWB assignment failed', ['error' => $e->getMessage()]);
            return $this->exceptionResponse($e, 'assigning AWB');
        }
    }

    /**
     * Generate shipping label
     * 
     * @param int $shipmentId
     * @return array
     */
    public function generateLabel($shipmentId)
    {
        try {
            $token = $this->authenticate();
            if (!$token) {
                return $this->errorResponse('Authentication failed', 401);
            }

            $response = Http::withToken($token)
                ->timeout(30)
                ->post($this->baseUrl . self::ENDPOINT_GENERATE_LABEL, [
                    'shipment_id' => [(int) $shipmentId]
                ]);

            if ($response->successful()) {
                $responseData = $response->json();
                Log::info('Label generated successfully', [
                    'shipment_id' => $shipmentId,
                    'label_url' => $responseData['data']['label_url'] ?? null
                ]);

                return $this->successResponse($responseData);
            }

            return $this->errorResponse($this->getErrorMessage($response), $response->status());
        } catch (\Exception $e) {
            Log::error('Shiprocket label generation failed', ['error' => $e->getMessage()]);
            return $this->exceptionResponse($e, 'generating label');
        }
    }

    /**
     * Generate invoice for shipment
     * 
     * @param int $shipmentId
     * @return array
     */
    public function generateInvoice($shipmentId)
    {
        try {
            $token = $this->authenticate();
            if (!$token) {
                return $this->errorResponse('Authentication failed', 401);
            }

            $response = Http::withToken($token)
                ->timeout(30)
                ->post($this->baseUrl . self::ENDPOINT_GENERATE_INVOICE, [
                    'shipment_id' => [(int) $shipmentId]
                ]);

            if ($response->successful()) {
                $responseData = $response->json();
                Log::info('Invoice generated successfully', ['shipment_id' => $shipmentId]);

                return $this->successResponse($responseData);
            }

            return $this->errorResponse($this->getErrorMessage($response), $response->status());
        } catch (\Exception $e) {
            Log::error('Shiprocket invoice generation failed', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to generate invoice: ' . $e->getMessage());
        }
    }

    /**
     * Track shipment by AWB number
     * 
     * @param string $awb
     * @return array
     */
    public function trackOrder($awb)
    {
        try {
            $token = $this->authenticate();
            if (!$token) {
                return $this->errorResponse('Authentication failed', 401);
            }

            $response = Http::withToken($token)
                ->timeout(30)
                ->get($this->baseUrl . self::ENDPOINT_TRACK_AWB . '/' . $awb);

            if ($response->successful()) {
                return $this->successResponse($response->json());
            }

            return $this->errorResponse($this->getErrorMessage($response), $response->status());
        } catch (\Exception $e) {
            Log::error('Shiprocket tracking failed', ['error' => $e->getMessage()]);
            return $this->exceptionResponse($e, 'tracking order');
        }
    }

    /**
     * Cancel order in Shiprocket
     * 
     * @param int $shipmentId
     * @return array
     */
    public function cancelOrder($shipmentId)
    {
        try {
            $token = $this->authenticate();
            if (!$token) {
                return $this->errorResponse('Authentication failed', 401);
            }

            $response = Http::withToken($token)
                ->timeout(30)
                ->post($this->baseUrl . self::ENDPOINT_CANCEL_ORDER, [
                    'ids' => [(int) $shipmentId]
                ]);

            if ($response->successful()) {
                Log::info('Order cancelled successfully', ['shipment_id' => $shipmentId]);
                return $this->successResponse($response->json());
            }

            return $this->errorResponse($this->getErrorMessage($response), $response->status());
        } catch (\Exception $e) {
            Log::error('Shiprocket order cancellation failed', ['error' => $e->getMessage()]);
            return $this->exceptionResponse($e, 'cancelling order');
        }
    }

    /**
     * Validate order data before sending to Shiprocket
     * 
     * @param array $data
     * @return array
     */
    protected function validateOrderData($data)
    {
        if (empty($data['order_id'])) {
            return [
                'valid' => false,
                'response' => $this->errorResponse('Order ID is required', 422)
            ];
        }

        if (empty($data['customer_details']['customer_phone'])) {
            return [
                'valid' => false,
                'response' => $this->errorResponse('Customer phone number is required', 422)
            ];
        }

        if (empty($data['order_amount']) && empty($data['order_items'])) {
            return [
                'valid' => false,
                'response' => $this->errorResponse('Either order amount or order items are required', 422)
            ];
        }

        return ['valid' => true, 'response' => null];
    }

    /**
     * Format order data for Shiprocket API
     * 
     * @param array $data
     * @return array
     */
    protected function formatShiprocketOrderData($data)
    {
        $defaultAddress = $this->getDefaultAddress($data);

        return [
            // ORDER DETAILS
            "order_id" => (string) $data['order_id'],
            "order_date" => $data['order_date'] ?? now()->format('Y-m-d H:i:s'),
            "pickup_location" => $data['pickup_location'] ?? 'work',
            "channel_id" => $data['channel_id'] ?? null,
            "comment" => $data['order_note'] ?? $data['comment'] ?? null,

            // BILLING DETAILS
            "billing_customer_name" => $data['customer_details']['customer_name'] ?? $data['billing_customer_name'] ?? '',
            "billing_last_name" => $data['billing_last_name'] ?? '',
            "billing_address" => $defaultAddress['billing_address'],
            "billing_address_2" => $data['billing_address_2'] ?? '',
            "billing_city" => $defaultAddress['billing_city'],
            "billing_pincode" => (int) $defaultAddress['billing_pincode'] ?? '380001',
            "billing_state" => $defaultAddress['billing_state'],
            "billing_country" => $defaultAddress['billing_country'],
            "billing_email" => $data['customer_details']['customer_email'] ?? $data['billing_email'] ?? '',
            "billing_phone" => $data['customer_details']['customer_phone'] ?? $data['billing_phone'] ?? '',

            // SHIPPING DETAILS
            "shipping_is_billing" => $data['shipping_is_billing'] ?? 1,
            "shipping_customer_name" => $data['customer_details']['customer_name'] ?? $data['shipping_customer_name'] ?? '',
            "shipping_last_name" => $data['shipping_last_name'] ?? '',
            "shipping_address" => $defaultAddress['shipping_address'],
            "shipping_address_2" => $data['shipping_address_2'] ?? '',
            "shipping_city" => $defaultAddress['shipping_city'],
            "shipping_pincode" => (int) $defaultAddress['shipping_pincode'] ?? '380001',
            "shipping_state" => $defaultAddress['shipping_state'],
            "shipping_country" => $defaultAddress['shipping_country'],
            "shipping_email" => $data['customer_details']['customer_email'] ?? $data['shipping_email'] ?? '',
            "shipping_phone" => $data['customer_details']['customer_phone'] ?? $data['shipping_phone'] ?? '',

            // ORDER ITEMS
            "order_items" => $this->formatOrderItems($data['order_items'] ?? [], $data['order_amount'] ?? 0),

            // PAYMENT DETAILS
            "payment_method" => $data['payment_method'] ?? 'Prepaid',
            "shipping_charges" => (float) ($data['shipping_charges'] ?? 0),
            "giftwrap_charges" => (float) ($data['giftwrap_charges'] ?? 0),
            "transaction_charges" => (float) ($data['transaction_charges'] ?? 0),
            "total_discount" => (float) ($data['total_discount'] ?? 0),
            "sub_total" => (float) ($data['order_amount'] ?? 0),

            // PACKAGE DETAILS
            "length" => (float) ($data['length'] ?? 10),
            "breadth" => (float) ($data['breadth'] ?? 10),
            "height" => (float) ($data['height'] ?? 10),
            "weight" => (float) ($data['weight'] ?? 0.5),

            // OPTIONAL FIELDS
            "ewaybill_no" => $data['ewaybill_no'] ?? null,
            "customer_gstin" => $data['customer_gstin'] ?? null,
            "invoice_number" => $data['invoice_number'] ?? null,
            "order_type" => $data['order_type'] ?? null,
        ];
    }

    /**
     * Get default address from data
     * 
     * @param array $data
     * @return array
     */
    protected function getDefaultAddress($data)
    {
        return [
            'billing_address' => $data['billing_address'] ?? 'Default Address',
            'billing_city' => $data['billing_city'] ?? 'Ahmedabad',
            'billing_pincode' => $data['billing_pincode'] ?? '380001',
            'billing_state' => $data['billing_state'] ?? 'Gujarat',
            'billing_country' => $data['billing_country'] ?? 'India',
            'shipping_address' => $data['shipping_address'] ?? ($data['billing_address'] ?? 'Default Address'),
            'shipping_city' => $data['shipping_city'] ?? ($data['billing_city'] ?? 'Ahmedabad'),
            'shipping_pincode' => $data['shipping_pincode'] ?? ($data['billing_pincode'] ?? '380001'),
            'shipping_state' => $data['shipping_state'] ?? ($data['billing_state'] ?? 'Gujarat'),
            'shipping_country' => $data['shipping_country'] ?? 'India',
        ];
    }

    /**
     * Format order items for Shiprocket
     * 
     * @param array $items
     * @param float $defaultAmount
     * @return array
     */
    protected function formatOrderItems($items, $defaultAmount = 0)
    {
        if (empty($items)) {
            return [[
                "name" => "Default Product",
                "sku" => "DEFAULT_" . uniqid(),
                "units" => 1,
                "selling_price" => $defaultAmount > 0 ? $defaultAmount : 0,
                "discount" => 0,
                "tax" => 0,
            ]];
        }

        return collect($items)->map(function ($item) {
            $formattedItem = [
                "name" => $item['name'] ?? 'Product',
                "sku" => $item['sku'] ?? 'SKU_' . uniqid(),
                "units" => (int) ($item['units'] ?? 1),
                "selling_price" => (float) ($item['selling_price'] ?? 0),
                "discount" => (float) ($item['discount'] ?? 0),
                "tax" => (float) ($item['tax'] ?? 0),
            ];

            if (!empty($item['hsn']) && is_numeric($item['hsn'])) {
                $formattedItem['hsn'] = (int) $item['hsn'];
            }

            return $formattedItem;
        })->values()->toArray();
    }

    /**
     * Get error message from response
     * 
     * @param \Illuminate\Http\Client\Response $response
     * @return string
     */
    protected function getErrorMessage($response)
    {
        $responseData = $response->json();

        if (isset($responseData['message'])) {
            return $responseData['message'];
        }

        if (isset($responseData['error'])) {
            return is_string($responseData['error']) ? $responseData['error'] : json_encode($responseData['error']);
        }

        return 'Shiprocket API error: ' . $response->status();
    }

    /**
     * Format success response
     * 
     * @param mixed $data
     * @param array $extra
     * @return array
     */
    protected function successResponse($data, $extra = [])
    {
        $response = [
            'success' => true,
            'data' => $data
        ];

        return array_merge($response, $extra);
    }

    /**
     * Format error response
     * 
     * @param string $message
     * @param int $statusCode
     * @param array $errors
     * @return array
     */
    protected function errorResponse($message, $statusCode = 400, $errors = [])
    {
        $response = [
            'success' => false,
            'message' => $message,
            'status_code' => $statusCode
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return $response;
    }

    /**
     * Format exception response
     * 
     * @param \Exception $e
     * @param string $action
     * @return array
     */
    protected function exceptionResponse($e, $action)
    {
        return [
            'success' => false,
            'message' => 'An error occurred while ' . $action . ': ' . $e->getMessage(),
            'exception' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ];
    }
}
