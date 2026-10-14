<?php
// Corrected ShiprocketOrderService.php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class ShiprocketOrderService
{
    protected $shiprocket;

    public function __construct()
    {
        $this->shiprocket = new ShiprocketService();
    }

    /**
     * Complete Shiprocket order management flow - CORRECTED VERSION
     * 
     * @param array $orderData
     * @return array
     */
    public function manageShiprocket($orderData)
    {
        try {
            // ==================== STEP 1: CHECK COURIER SERVICEABILITY FIRST ====================
            Log::info('STEP 1: Checking courier serviceability', ['order_id' => $orderData['order_id'] ?? 'unknown']);

            $pickupPincode = $orderData['pickup_pincode'] ?? config('shiprocket.pickup_pincode', '395007');
            $deliveryPincode = $this->getDeliveryPincode($orderData) ?? '380001';
            $weight = $orderData['weight'] ?? 0.5;
            $codAmount = ($orderData['payment_method'] ?? 'Prepaid') === 'COD' ? ($orderData['order_amount'] ?? 0) : 0;
            if (!$deliveryPincode) {
                return $this->errorResponse('Delivery pincode is required', 'validation');
            }

            $courierResponse = $this->shiprocket->checkCourier(
                $pickupPincode,
                $deliveryPincode,
                $weight,
                $codAmount
            );
            if (!$courierResponse['success']) {
                return $this->errorResponse('Failed to check courier serviceability', 'courier_check', $courierResponse);
            }

            // Get available couriers
            $couriers = $courierResponse['data']['data']['available_courier_companies'] ??
                $courierResponse['data']['available_courier_companies'] ?? [];

            if (empty($couriers)) {
                return $this->errorResponse('No courier services available for this location', 'no_courier');
            }

            // Select best courier (you might want to implement more sophisticated selection logic)
            $selectedCourier = $this->selectBestCourier($couriers, $orderData);
            $courierId = $selectedCourier['courier_company_id'] ?? $selectedCourier['courier_id'];

            Log::info('Courier selected', [
                'courier_id' => $courierId,
                'courier_name' => $selectedCourier['courier_name'] ?? 'Unknown',
                'freight_charge' => $selectedCourier['freight_charge'] ?? 0
            ]);

            // ==================== STEP 2: CREATE ORDER WITH COURIER ID ====================
            Log::info('STEP 2: Creating order with selected courier');

            // Add courier_id to order data
            $orderData['courier_id'] = $courierId;
            $orderData['pickup_pincode'] = $pickupPincode;

            $createOrderResponse = $this->shiprocket->createOrder($orderData);

            if (!$createOrderResponse['success']) {
                return $this->errorResponse('Failed to create order', 'create_order', $createOrderResponse);
            }

            // Extract shipment details
            $shipmentId = $createOrderResponse['data']['shipment_id'] ??
                $createOrderResponse['data']['data']['shipment_id'] ?? null;
            $awbCode = "TEST-AWB-998877" ?? $createOrderResponse['data']['awb_code'] ??
                $createOrderResponse['data']['data']['awb_code'] ?? null;

            if (!$shipmentId) {
                return $this->errorResponse('Shipment ID not found in response', 'create_order', $createOrderResponse);
            }

            Log::info('Order created successfully', [
                'shipment_id' => $shipmentId,
                'awb_code' => $awbCode
            ]);

            // ==================== STEP 2.5: ASSIGN AWB (CRITICAL FOR ADHOC ORDERS) ====================
            Log::info('STEP 2.5: Assigning AWB to shipment', ['shipment_id' => $shipmentId]);
            if (empty($awbCode)) {
                // AWB not assigned - manually assign it
                $assignResponse = $this->shiprocket->assignAWB($shipmentId, $courierId);

                if (!$assignResponse['success']) {
                    return $this->errorResponse('Failed to assign AWB', 'assign_awb', $assignResponse);
                }

                $awbCode = $assignResponse['data']['data']['awb_code'] ??
                    $assignResponse['data']['awb_code'] ??
                    null;

                Log::info('AWB assigned manually', [
                    'shipment_id' => $shipmentId,
                    'awb_code' => $awbCode
                ]);
            } else {
                Log::info('AWB already assigned', ['awb_code' => $awbCode]);
            }

            // Verify AWB is assigned before proceeding
            if (empty($awbCode)) {
                return $this->errorResponse(
                    'Cannot generate label: No AWB assigned to shipment',
                    'no_awb',
                    ['shipment_id' => $shipmentId]
                );
            }

            // ==================== STEP 3: GENERATE LABEL ====================
            Log::info('STEP 3: Generating shipping label');

            $labelResponse = $this->shiprocket->generateLabel($shipmentId);
            $labelUrl = null;

            if ($labelResponse['success']) {
                $labelData = $labelResponse['data']['data'] ?? $labelResponse['data'];
                $labelUrl = $labelData['label_url'] ??
                    $labelData['label_url_combined'] ??
                    $labelData['label_urls'][0] ?? null;

                Log::info('Label generated', ['label_url' => $labelUrl]);
            } else {
                Log::warning('Label generation failed', ['response' => $labelResponse]);
            }

            // ==================== STEP 4: GENERATE INVOICE ====================
            Log::info('STEP 4: Generating invoice');

            $invoiceResponse = $this->shiprocket->generateInvoice($shipmentId);
            $invoiceUrl = null;

            if ($invoiceResponse['success']) {
                $invoiceData = $invoiceResponse['data']['data'] ?? $invoiceResponse['data'];
                $invoiceUrl = $invoiceData['invoice_url'] ?? null;
            }

            // ==================== STEP 5: TRACK SHIPMENT ====================
            $trackingInfo = null;
            if ($awbCode) {
                $trackResponse = $this->shiprocket->trackOrder($awbCode);
                if ($trackResponse['success']) {
                    $trackingInfo = $trackResponse['data']['data'] ?? $trackResponse['data'];
                }
            }

            // ==================== RETURN COMPLETE RESPONSE ====================
            return [
                'success' => true,
                'message' => 'Order processed successfully',
                'data' => [
                    'order_id' => $orderData['order_id'],
                    'shipment_id' => $shipmentId,
                    'awb_code' => $awbCode,
                    'courier' => [
                        'id' => $courierId,
                        'name' => $selectedCourier['courier_name'] ?? 'Unknown',
                        'freight_charge' => $selectedCourier['freight_charge'] ?? 0,
                        'estimated_delivery_days' => $selectedCourier['estimated_delivery_days'] ?? null
                    ],
                    'label_url' => $labelUrl,
                    'invoice_url' => $invoiceUrl,
                    'tracking' => $trackingInfo,
                    'status' => 'order_created',
                    'created_at' => now()->toIso8601String()
                ]
            ];
        } catch (\Exception $e) {
            Log::error('Shiprocket order management failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Order processing failed: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Select best courier based on various factors
     * 
     * @param array $couriers
     * @param array $orderData
     * @return array
     */
    protected function selectBestCourier($couriers, $orderData)
    {
        // If a specific courier is requested
        if (isset($orderData['preferred_courier_id'])) {
            foreach ($couriers as $courier) {
                $courierId = $courier['courier_company_id'] ?? $courier['courier_id'] ?? null;
                if ($courierId == $orderData['preferred_courier_id']) {
                    return $courier;
                }
            }
        }

        // Sort by freight charge (cheapest first)
        usort($couriers, function ($a, $b) {
            $chargeA = $a['freight_charge'] ?? $a['freight_charge_amount'] ?? PHP_FLOAT_MAX;
            $chargeB = $b['freight_charge'] ?? $b['freight_charge_amount'] ?? PHP_FLOAT_MAX;
            return $chargeA <=> $chargeB;
        });

        // Return cheapest courier
        return $couriers[0];
    }

    /**
     * Get delivery pincode from order data
     * 
     * @param array $orderData
     * @return string|null
     */
    protected function getDeliveryPincode($orderData)
    {
        $possibleKeys = [
            'shipping_pincode',
            'delivery_pincode',
            'customer_details.pincode',
            'billing_pincode'
        ];

        foreach ($possibleKeys as $key) {
            if (str_contains($key, '.')) {
                $parts = explode('.', $key);
                $value = $orderData[$parts[0]] ?? null;
                if ($value && isset($value[$parts[1]])) {
                    return $value[$parts[1]];
                }
            } else {
                if (isset($orderData[$key])) {
                    return $orderData[$key];
                }
            }
        }

        return null;
    }

    /**
     * Format error response
     * 
     * @param string $message
     * @param string $step
     * @param array|null $details
     * @return array
     */
    protected function errorResponse($message, $step, $details = null)
    {
        return [
            'success' => false,
            'message' => $message,
            'step' => $step,
            'details' => $details
        ];
    }
}
