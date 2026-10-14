@extends('user.layouts.app')

@section('title', 'Invoice #' . $order->tx_id)

@section('content')

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

@php
    // Calculate totals with GST
    $subtotal = 0;
    $totalGST = 0;
    $gstBreakdown = [];
    
    foreach($order->items as $item) {
        $product = $item->product;
        $gstPercent = $product->gst_percent ?? 18;
        $price = $item->price; // Price inclusive of GST
        $qty = $item->qty;
        
        // GST Calculation Logic
        $gstAmount = ($price * $gstPercent) / (100 + $gstPercent);
        $basePrice = $price - $gstAmount;
        
        $itemTotal = $price * $qty;
        $itemGST = $gstAmount * $qty;
        $itemBaseTotal = $basePrice * $qty;
        
        $subtotal += $itemBaseTotal;
        $totalGST += $itemGST;
        
        // Store GST breakdown by percentage
        if (!isset($gstBreakdown[$gstPercent])) {
            $gstBreakdown[$gstPercent] = [
                'gst_percent' => $gstPercent,
                'cgst' => $gstPercent / 2,
                'sgst' => $gstPercent / 2,
                'total_gst' => 0,
                'taxable_value' => 0
            ];
        }
        $gstBreakdown[$gstPercent]['total_gst'] += $itemGST;
        $gstBreakdown[$gstPercent]['taxable_value'] += $itemBaseTotal;
    }
    
    $grandTotal = $subtotal + $totalGST;
    if(isset($order->shipping_charge)) {
        $grandTotal += $order->shipping_charge;
    }
@endphp

<div class="row">
    <div class="col-12">
        <div class="card" id="invoice-print-area">
            <!-- Invoice Header -->
            <div class="card-header bg-white border-bottom">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h3 class="mb-0 text-success">TAX INVOICE</h3>
                        <p class="text-muted mb-0">Order Confirmation & Payment Receipt</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <h5 class="mb-0">{{ company_info('company_name') }}</h5>
                        <small class="text-muted d-block">{{ company_info('company_email') }}</small>
                        @if(company_info('gst_no'))
                            <small class="text-muted d-block">GSTIN: {{ company_info('gst_no') }}</small>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card-body">
                <!-- Action Buttons (Top) -->
                <div class="row mb-3">
                    <div class="col-12 text-end">
                 
                          <a href="{{ route('admin.orders.print-invoice', $order->id) }}" class="btn btn-sm btn-success">  <i class="bx bx-printer me-1"></i> Download Invoice & Print </a>
                    
                        <a href="{{ route('dashboard.user') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </div>

                <!-- Info Section -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="border rounded p-3 bg-light">
                            <h6 class="text-success border-bottom pb-2">Order Information</h6>
                            <div class="d-flex justify-content-between py-1">
                                <span class="text-muted">Order ID:</span>
                                <span class="fw-semibold">{{ $order->tx_id }}</span>
                            </div>
                            <div class="d-flex justify-content-between py-1">
                                <span class="text-muted">Order Date:</span>
                                <span class="fw-semibold">{{ \Carbon\Carbon::parse($order->added_on)->format('d M Y, h:i A') }}</span>
                            </div>
                            <div class="d-flex justify-content-between py-1">
                                <span class="text-muted">Invoice No:</span>
                                <span class="fw-semibold">INV-{{ $order->id }}-{{ date('Ymd') }}</span>
                            </div>
                            <div class="d-flex justify-content-between py-1">
                                <span class="text-muted">Status:</span>
                                <span>
                                    @if($order->payment_status == 1)
                                        <span class="badge bg-success">Paid</span>
                                    @else
                                        <span class="badge bg-warning">Pending</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="border rounded p-3 bg-light">
                            <h6 class="text-success border-bottom pb-2">Delivery Details</h6>
                            <div class="d-flex justify-content-between py-1">
                                <span class="text-muted">Delivery Type:</span>
                                <span class="fw-semibold">{{ $order->package_name ?? 'Standard' }}</span>
                            </div>
                            <div class="d-flex justify-content-between py-1">
                                <span class="text-muted">Address:</span>
                                <span class="fw-semibold text-end">{{ $order->order_address }}</span>
                            </div>
                            <div class="d-flex justify-content-between py-1">
                                <span class="text-muted">Contact:</span>
                                <span class="fw-semibold">{{ $order->order_details }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="border rounded p-3 bg-light">
                            <h6 class="text-success border-bottom pb-2">Payment Details</h6>
                            <div class="d-flex justify-content-between py-1">
                                <span class="text-muted">Payment Method:</span>
                                <span class="fw-semibold">{{ ucfirst($order->payment_method ?? 'Wallet') }}</span>
                            </div>
                            <div class="d-flex justify-content-between py-1">
                                <span class="text-muted">Total BV Points:</span>
                                <span class="fw-semibold">{{ number_format($order->order_bv, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between py-1">
                                <span class="text-muted">Payment Status:</span>
                                <span>
                                    @if($order->payment_status == 1)
                                        <span class="badge bg-success">Completed</span>
                                    @else
                                        <span class="badge bg-danger">Pending</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products Table -->
                <div class="table-responsive mb-4">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="table-success">
                            <tr>
                                <th style="width: 3%;">#</th>
                                <th style="width: 8%;">Image</th>
                                <th style="width: 22%;">Product</th>
                                <th style="width: 8%;">HSN Code</th>
                                <th style="width: 8%;">MRP</th>
                                <th style="width: 10%;">Price</th>
                                <th style="width: 6%;">GST%</th>
                                <th style="width: 6%;">Qty</th>
                                <th style="width: 12%;">Taxable</th>
                                <th style="width: 12%;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $index => $item)
                            @php
                                $product = $item->product;
                                $gstPercent = $product->gst_percent ?? 18;
                                $price = $item->price;
                                $qty = $item->qty;
                                
                                $gstAmount = ($price * $gstPercent) / (100 + $gstPercent);
                                $basePrice = $price - $gstAmount;
                                
                                $itemTotal = $price * $qty;
                                $itemBaseTotal = $basePrice * $qty;
                            @endphp
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    <img class="img-thumbnail" 
                                         src="{{ asset('storage/' . $product->imgs) }}" 
                                         alt="{{ $product->name }}"
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                </td>
                                <td>
                                    <strong>{{ $product->name }}</strong>
                                    @if($product->p_code)
                                        <br><small class="text-muted">Code: {{ $product->p_code }}</small>
                                    @endif
                                </td>
                                <td>{{ $product->hsn_code ?? 'NA' }}</td>
                                <td>{{ company_info('currancy', '₹') }}{{ number_format($product->mrp ?? $price, 2) }}</td>
                                <td>{{ company_info('currancy', '₹') }}{{ number_format($price, 2) }}</td>
                                <td class="text-center">{{ $gstPercent }}%</td>
                                <td class="text-center">{{ $qty }}</td>
                                <td class="text-end">{{ company_info('currancy', '₹') }}{{ number_format($itemBaseTotal, 2) }}</td>
                                <td class="text-end">{{ company_info('currancy', '₹') }}{{ number_format($itemTotal, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- GST Summary -->
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0">GST Summary</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>GST Rate</th>
                                        <th class="text-end">Taxable Value</th>
                                        <th class="text-end">CGST</th>
                                        <th class="text-end">SGST</th>
                                        <th class="text-end">Total GST</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($gstBreakdown as $gst)
                                    <tr>
                                        <td>{{ $gst['gst_percent'] }}%</td>
                                        <td class="text-end">{{ company_info('currancy', '₹') }}{{ number_format($gst['taxable_value'], 2) }}</td>
                                        <td class="text-end">{{ company_info('currancy', '₹') }}{{ number_format($gst['total_gst'] / 2, 2) }}</td>
                                        <td class="text-end">{{ company_info('currancy', '₹') }}{{ number_format($gst['total_gst'] / 2, 2) }}</td>
                                        <td class="text-end">{{ company_info('currancy', '₹') }}{{ number_format($gst['total_gst'], 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Total Section -->
                <div class="row">
                    <div class="col-md-6 offset-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between py-1 border-bottom">
                                    <span class="text-muted">Subtotal (Taxable Value):</span>
                                    <span class="fw-semibold">{{ company_info('currancy', '₹') }}{{ number_format($subtotal, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between py-1 border-bottom">
                                    <span class="text-muted">Total GST:</span>
                                    <span class="fw-semibold">{{ company_info('currancy', '₹') }}{{ number_format($totalGST, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between py-1 border-bottom">
                                    <span class="text-muted">Total BV Points:</span>
                                    <span class="fw-semibold">{{ number_format($order->order_bv, 2) }}</span>
                                </div>
                                @if(isset($order->shipping_charge) && $order->shipping_charge > 0)
                                <div class="d-flex justify-content-between py-1 border-bottom">
                                    <span class="text-muted">Shipping Charge:</span>
                                    <span class="fw-semibold">{{ company_info('currancy', '₹') }}{{ number_format($order->shipping_charge, 2) }}</span>
                                </div>
                                @endif
                                <div class="d-flex justify-content-between py-2 border-bottom border-2 border-success">
                                    <span class="fw-bold h5 mb-0">Grand Total:</span>
                                    <span class="fw-bold h5 mb-0 text-success">{{ company_info('currancy', '₹') }}{{ number_format($order->order_amount, 2) }}</span>
                                </div>
                                @if(\App\Helpers\NumberToWords::class)
                                <div class="d-flex justify-content-between py-1 mt-2">
                                    <span class="text-muted small">Amount in words:</span>
                                    <span class="text-muted small text-end">{{ \App\Helpers\NumberToWords::convert($order->order_amount) }} Rupees Only</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Notes -->
                @if($order->order_notes && $order->order_notes != 'Note: ')
                <!--<div class="card mt-4">-->
                <!--    <div class="card-body bg-light">-->
                <!--        <strong class="text-success"><i class="fas fa-sticky-note me-1"></i> Order Notes:</strong>-->
                <!--        <p class="mb-0 mt-1 text-muted">{{ str_replace('Note: ', '', $order->order_notes) }}</p>-->
                <!--    </div>-->
                <!--</div>-->
                @endif

                <!-- Terms & Conditions -->
                <div class="card mt-3">
                    <div class="card-body bg-light">
                        <strong class="text-muted">Terms & Conditions:</strong>
                        <ul class="mb-0 mt-1 text-muted small">
                            <li>Goods once sold will not be taken back.</li>
                            <li>This is a computer generated invoice and does not require signature.</li>
                            <li>Subject to local jurisdiction.</li>
                        </ul>
                    </div>
                </div>

                <!-- Footer -->
                <div class="text-center mt-4 pt-3 border-top">
                    <p class="text-muted mb-0">
                        <i class="fas fa-heart text-danger"></i> 
                        Thank you for your business! For any queries, please contact our support team.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function downloadPDF() {
    // Hide action buttons for PDF
    const element = document.getElementById('invoice-print-area');
    const actionButtons = document.querySelector('.row.mb-3');
    
    if(actionButtons) actionButtons.style.display = 'none';
    
    const opt = {
        margin: [0.3, 0.3, 0.3, 0.3],
        filename: 'invoice-{{ $order->tx_id }}.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { 
            scale: 2,
            letterRendering: true,
            useCORS: true,
            logging: false
        },
        jsPDF: { 
            unit: 'in', 
            format: 'a4', 
            orientation: 'portrait'
        }
    };
    
    html2pdf().set(opt).from(element).save().then(() => {
        if(actionButtons) actionButtons.style.display = 'flex';
    });
}

// Print function
function printInvoice() {
    window.print();
}
</script>

<style>
@media print {
    .btn, .row.mb-3, .card-header .btn {
        display: none !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    .card-body {
        padding: 20px !important;
    }
    .table-bordered {
        border: 1px solid #dee2e6 !important;
    }
    .table-bordered th,
    .table-bordered td {
        border: 1px solid #dee2e6 !important;
    }
}
</style>

@endsection