@extends('admin.layouts.app')

@section('content')

<style>
/* Print Styles */
@media print {
    body * {
        visibility: hidden;
    }
    
    .print-label-only,
    .print-label-only * {
        visibility: visible;
    }
    
    .print-label-only {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        margin-top: 30px !important;
        padding: 20px !important;
        /*background: #fff;*/
    }
    
    .print-invoice-only,
    .print-invoice-only * {
        visibility: visible;
    }
    
    .print-invoice-only {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        /*background: #fff;*/
        padding: 20px;
    }
    
    .no-print, 
    .btn, 
    hr, 
    .navbar, 
    .layout-menu, 
    .action-buttons, 
    .btn-group,
    .card-header,
    .update-status-card {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    
    .packaging-sticker {
        border: 1px solid #ddd !important;
        page-break-after: avoid;
    }
    
    #full-invoice {
        margin-top: 20px;
    }
    
    body {
        /*background: #fff !important;*/
    }
    
    @page {
        margin: 1.5cm;
    }
}

/* Normal Screen Styles */
.packaging-sticker {
    border-radius: 8px;
    border: 2px dashed #333;
    transition: all 0.3s;
}

#full-invoice {
    border-radius: 12px;
}

.invoice-table th {
    background: #f8f9fa;
    font-weight: 600;
}

.status-badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.status-pending { background: #fff3cd; color: #856404; }
.status-packing { background: #cce5ff; color: #004085; }
.status-shipped { background: #d4edda; color: #155724; }
.status-delivered { background: #28a745; color: #fff; }
.status-cancelled { background: #dc3545; color: #fff; }
.status-returned { background: #6c757d; color: #fff; }

.gst-summary {
    /*background: #f8f9fa;*/
    border-radius: 8px;
    padding: 15px;
}

.btn-print-label {
    background: #17a2b8;
    color: #fff;
}

.btn-print-label:hover {
    background: #138496;
    color: #fff;
}

.btn-print-invoice {
    background: #28a745;
    color: #fff;
}

.btn-print-invoice:hover {
    background: #1e7e34;
    color: #fff;
}

.table:not(.table-dark) thead:not(.table-dark) th {
    color: #0f0f10;
}
</style>

@php
    $subtotal = 0;
    $totalGST = 0;
    $totalDiscount = 0;
    $gstBreakdown = [];
    
    foreach($order->items as $item) {
        $product = $item->product;
        $productName = $product?->name ?? 'Product Unavailable';
        $gstPercent = $product?->gst_percent ?? 18;
        $price = $item->price;
        $qty = $item->qty;
        
        $gstAmount = ($price * $gstPercent) / (100 + $gstPercent);
        $basePrice = $price - $gstAmount;
        $cgstAmount = $gstAmount / 2;
        $sgstAmount = $gstAmount / 2;
        
        $itemTotal = $price * $qty;
        $itemGST = $gstAmount * $qty;
        $itemBaseTotal = $basePrice * $qty;
        
        $subtotal += $itemBaseTotal;
        $totalGST += $itemGST;
        
        if (!isset($gstBreakdown[$gstPercent])) {
            $gstBreakdown[$gstPercent] = [
                'gst_percent' => $gstPercent,
                'cgst' => $gstPercent / 2,
                'sgst' => $gstPercent / 2,
                'total_gst' => 0,
                'taxable_value' => 0,
                'items' => []
            ];
        }
        $gstBreakdown[$gstPercent]['total_gst'] += $itemGST;
        $gstBreakdown[$gstPercent]['taxable_value'] += $itemBaseTotal;
        $gstBreakdown[$gstPercent]['items'][] = $productName;
    }
    
    $grandTotal = $subtotal + $totalGST;
    $roundOff = round($grandTotal) - $grandTotal;
    $finalTotal = round($grandTotal);
    
    $statusClass = '';
    $statusText = '';
    switch($order->status) {
        case 0: $statusClass = 'status-pending'; $statusText = 'Pending'; break;
        case 5: $statusClass = 'status-packing'; $statusText = 'Packing In-Progress'; break;
        case 1: $statusClass = 'status-shipped'; $statusText = 'Out for Delivery'; break;
        case 2: $statusClass = 'status-delivered'; $statusText = 'Delivered'; break;
        case 3: $statusClass = 'status-cancelled'; $statusText = 'Cancelled'; break;
        case 4: $statusClass = 'status-returned'; $statusText = 'Returned'; break;
        default: $statusClass = 'status-pending'; $statusText = 'Pending';
    }
@endphp

<div class="container-fluid py-4">

    <!-- Header Buttons -->
    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <div>
            <h4 class="fw-bold">Order Management: #{{ $order->tx_id }}</h4>
            <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
        </div>
        <div class="btn-group gap-2">
            <button onclick="printLabelOnly()" class="btn btn-print-label">
                <i class="bx bx-printer me-1"></i> 🏷️ Print Label Only
            </button>
            <button onclick="printInvoiceOnly()" class="btn btn-print-invoice">
                <i class="bx bx-printer me-1"></i> 📄 Print Invoice Only
            </button>
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">Back</a>
        </div>
    </div>

    <!-- Update Status Card -->
<!-- Update Status Card -->
<div class="card mt-4 no-print border-warning update-status-card">
    <div class="card-header bg-warning text-dark">
        <h5 class="mb-0 fw-bold"><i class="bx bx-refresh me-2"></i>Update Order Progress</h5>
    </div>
    <div class="card-body">
        <form id="statusForm" action="{{ route('admin.productorders.update_status', $order->id) }}" method="POST">
            @csrf
            <div class="row align-items-end">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Set Current Status</label>
                    <select name="status" id="statusSelect" class="form-select shadow-none">
                        <option value="0" {{ $order->status == 0 ? 'selected' : '' }}>📦 Received (Pending)</option>
                        <option value="5" {{ $order->status == 5 ? 'selected' : '' }}>📦 Packing In-Progress</option>
                        <option value="1" {{ $order->status == 1 ? 'selected' : '' }}>🚚 Out for Delivery</option>
                        <option value="2" {{ $order->status == 2 ? 'selected' : '' }}>✅ Delivered (Complete)</option>
                        <option value="3" {{ $order->status == 3 ? 'selected' : '' }}>❌ Cancelled</option>
                        <option value="4" {{ $order->status == 4 ? 'selected' : '' }}>↩️ Returned</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="button" onclick="handleStatusUpdate()" class="btn btn-dark w-100">Update Status</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function handleStatusUpdate() {
    const status = document.getElementById('statusSelect').value;
    
    if (status === '2') {
        // Show modal for delivered status to collect payout_id
        const modal = new bootstrap.Modal(document.getElementById('deliveredModal'));
        modal.show();
    } else {
        // Normal submit for other statuses
        document.getElementById('statusForm').submit();
    }
}

// Auto-fill payout_id if already exists
document.addEventListener('DOMContentLoaded', function() {
    const payoutInput = document.getElementById('payout_id');
    if (payoutInput) {
        payoutInput.value = "{{ $order->payout_id ?? '' }}";
    }
});
</script>

    <hr class="my-5 no-print">

    <!-- PART 1: PACKAGING STICKER -->
    <div id="shipping-label" class="print-label-only">
        <div class="row justify-content-center mb-5">
            <div class="col-md-8">
                <div class="packaging-sticker card p-4 shadow-sm" style="margin-top: 30px;">
                    <div class="text-center border-bottom pb-3 mb-3">
                        <h3 class="fw-bold mb-0">📦 SHIPPING LABEL</h3>
                        <small>Order #{{ $order->tx_id }}</small>
                    </div>
                    
                    <div class="row">
                        <div class="col-12 mb-4">
                            <p class="mb-1 fw-bold text-muted"><i class="bx bx-building"></i> FROM (Sender):</p>
                            <h5 class="mb-0 fw-bold">{{ company_info('bill_company_name') }}</h5>
                            <p class="mb-0">{{ company_info('bill_company_address') }}, {{ company_info('bill_company_state') }}</p>
                            <p class="mb-0">📞 {{ company_info('company_mobile') ?? 'N/A' }}</p>
                            @if(company_info('company_gst'))
                            <p class="mb-0">GSTIN: {{ company_info('company_gst') }}</p>
                            @endif
                        </div>
                        
                        <div class="col-12 mb-4">
                            <p class="mb-1 fw-bold text-muted"><i class="bx bx-user"></i> TO (Recipient):</p>
                            <h5 class="mb-0 fw-bold">{{ $order->user->name ?? 'User' }} (ID: {{ $order->tx_user_id }})</h5>
                            <p class="mb-0">📞 {{ $order->order_details }}</p>
                            <p class="mb-0">📍 {{ $order->order_address }}</p>
                        </div>
                        
                        <div class="col-6">
                            <p class="mb-1 fw-bold text-muted"><i class="bx bx-calendar"></i> Order Date:</p>
                            <p>{{ $order->added_on ? $order->added_on->format('d-m-Y h:i A') : 'N/A' }}</p>
                        </div>
                        
                        <div class="col-6">
                            <p class="mb-1 fw-bold text-muted"><i class="bx bx-package"></i> Items Count:</p>
                            <p>{{ $order->items->sum('qty') }} Products</p>
                        </div>
                    </div>
                    
                    <div class="mt-3 text-center">
                        <div style="display: inline-block; padding: 10px; border-radius: 5px;">
                            <small class="text-muted">Order ID: {{ $order->tx_id }}</small>
                        </div>
                    </div>
                    
                    <div class="mt-3 text-center border-top pt-2">
                        <small class="text-muted">Handle with care | Thank you for your business!</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PART 2: TAX INVOICE -->
    <div id="tax-invoice" class="print-invoice-only">
        <div class="card shadow-sm p-5" id="full-invoice">
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <h2 class="fw-bold text-primary mb-1">TAX INVOICE</h2>
                    <p class="mb-0"><strong>Invoice No:</strong> INV-{{ $order->id }}/{{ $order->added_on ? date('Ymd', strtotime($order->added_on)) : '' }}</p>
                    <p class="mb-0"><strong>Order ID:</strong> #{{ $order->tx_id }}</p>
                    <p><strong>Invoice Date:</strong> {{ $order->added_on ? $order->added_on->format('d M, Y h:i A') : 'N/A' }}</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <h4 class="fw-bold text-success">{{ company_info('bill_company_name') }}</h4>
                    <p class="mb-0">{{ company_info('bill_company_address') }}</p>
                    @if(company_info('company_gst'))
                    <p class="mb-0"><strong>GSTIN:</strong> {{ company_info('company_gst') }}</p>
                    @endif
                    @if(company_info('bill_company_pan'))
                    <p class="mb-0"><strong>PAN:</strong> {{ company_info('bill_company_pan') }}</p>
                    @endif
                    <p class="mb-0"><strong>Email:</strong> {{ company_info('company_email') }}</p>
                    @if(company_info('company_phone'))
                    <p><strong>Phone:</strong> {{ company_info('company_phone') }}</p>
                    @endif
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="bg-light p-3 rounded">
                        <h6 class="fw-bold mb-2"><i class="bx bx-user"></i> Billing Details</h6>
                        <p class="mb-1"><strong>{{ $order->user->name ?? 'User' }}</strong></p>
                        <p class="mb-0">{{ $order->order_address }}</p>
                        <p class="mb-0">{{ $order->order_details }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="bg-light p-3 rounded">
                        <h6 class="fw-bold mb-2"><i class="bx bx-truck"></i> Shipping Details</h6>
                        <p class="mb-1"><strong>{{ $order->user->name ?? 'User' }}</strong></p>
                        <p class="mb-0">{{ $order->order_address }}</p>
                        <p class="mb-0">{{ $order->order_details }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Product Table -->
            <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle invoice-table">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center" style="width: 5%;">#</th>
                            <th style="width: 28%;">Product Details</th>
                            <th class="text-center" style="width: 8%;">HSN Code</th>
                            <th class="text-center" style="width: 8%;">MRP</th>
                            <th class="text-center" style="width: 8%;">Price</th>
                            <th class="text-center" style="width: 6%;">GST%</th>
                            <th class="text-center" style="width: 6%;">Qty</th>
                            <th class="text-end" style="width: 10%;">Taxable</th>
                            <th class="text-end" style="width: 10%;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $index => $item)
                        @php
                            $product = $item->product;
                            $gstPercent = $product?->gst_percent ?? 18;
                            $price = $item->price;
                            $qty = $item->qty;
                            
                            $gstAmount = ($price * $gstPercent) / (100 + $gstPercent);
                            $basePrice = $price - $gstAmount;
                            
                            $itemTotal = $price * $qty;
                            $itemGST = $gstAmount * $qty;
                            $itemBaseTotal = $basePrice * $qty;
                        @endphp
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($product?->imgs)
                                        <img src="{{ asset('storage/'.$product->imgs) }}" class="me-3 rounded no-print" width="50" height="50" style="object-fit: cover;">
                                    @endif
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $product?->name ?? 'Product Removed/Unavailable' }}</h6>
                                        <small class="text-muted">Code: {{ $product?->p_code ?? 'N/A' }}</small>
                                        <br>
                                        <small class="text-muted">BV: {{ $item->bv }} </small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">{{ $product?->hsn_code ?? 'NA' }}</td>
                            <td class="text-center">₹ {{ number_format($product?->mrp ?? $price, 2) }}</td>
                            <td class="text-center">₹ {{ number_format($price, 2) }}</td>
                            <td class="text-center">{{ $gstPercent }}%</td>
                            <td class="text-center">{{ $qty }}</td>
                            <td class="text-end">₹ {{ number_format($itemBaseTotal, 2) }}</td>
                            <td class="text-end fw-bold">₹ {{ number_format($itemTotal, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- GST Summary -->
            @if(count($gstBreakdown) > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="gst-summary">
                        <h6><i class="bx bx-tax"></i> GST Summary (Tax Invoice)</h6>
                        <table class="table table-sm table-bordered" style="width: 100%;">
                            <thead>
                                <tr style="background: #e9ecef;">
                                    <th>HSN/SAC</th>
                                    <th>Description</th>
                                    <th class="text-center">Taxable Value</th>
                                    <th class="text-center">CGST (%)</th>
                                    <th class="text-center">SGST (%)</th>
                                    <th class="text-center">Total GST</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                @php
                                    $product = $item->product;
                                    $gstPercent = $product?->gst_percent ?? 18;
                                    $gstAmount = ($item->price * $gstPercent) / (100 + $gstPercent);
                                    $basePrice = $item->price - $gstAmount;
                                    $itemBaseTotal = $basePrice * $item->qty;
                                    $itemGST = $gstAmount * $item->qty;
                                @endphp
                                <tr>
                                    <td>{{ $product?->hsn_code ?? 'NA' }}</td>
                                    <td>{{ $product?->name ?? 'Product Removed' }}</td>
                                    <td class="text-end">₹ {{ number_format($itemBaseTotal, 2) }}</td>
                                    <td class="text-center">{{ $gstPercent/2 }}%</td>
                                    <td class="text-center">{{ $gstPercent/2 }}%</td>
                                    <td class="text-end">₹ {{ number_format($itemGST, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2" class="text-end">Total:</th>
                                    <th class="text-end">₹ {{ number_format($subtotal, 2) }}</th>
                                    <th colspan="2"></th>
                                    <th class="text-end">₹ {{ number_format($totalGST, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Total Amount Section -->
            <div class="row mt-4">
                <div class="col-md-7">
                    <div class="bg-light p-3 rounded">
                        <h6 class="fw-bold border-bottom pb-2 mb-2"><i class="bx bx-bank"></i> Payment & Bank Details</h6>
                        <p class="mb-1"><strong>Payment Method:</strong> {{ ucfirst($order->payment_method ?? 'Cashfree') }}</p>
                        <p class="mb-1"><strong>Payment Status:</strong> 
                            @if($order->payment_status == 1)
                                <span class="text-success">✅ Paid</span>
                            @else
                                <span class="text-danger">⏳ Pending</span>
                            @endif
                        </p>
                        <p class="mb-1"><strong>Transaction ID:</strong> {{ $order->payout_id ?? '-' }}</p>
                        <hr>
                        <p class="mb-1"><strong>Bank Name:</strong> {{ company_info('bill_company_bank_name') ?? 'N/A' }}</p>
                        <p class="mb-1"><strong>Account No:</strong> {{ company_info('bill_company_bank_account') ?? 'N/A' }}</p>
                        <p class="mb-1"><strong>IFSC Code:</strong> {{ company_info('bill_company_bank_ifsc') ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="p-3 rounded" style="background: #f8fff9; border: 2px solid #28a745;">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal (Taxable Value):</span>
                            <strong>₹ {{ number_format($subtotal, 2) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total GST:</span>
                            <strong>₹ {{ number_format($totalGST, 2) }}</strong>
                        </div>
                        @if($order->order_mrp != $order->order_amount)
                        <div class="d-flex justify-content-between mb-2 text-danger">
                            <span>Discount:</span>
                            <strong>- ₹ {{ number_format($order->order_mrp - $order->order_amount, 2) }}</strong>
                        </div>
                        @endif
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total BV Points:</span>
                            <strong>{{ number_format($order->order_bv, 2) }}</strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <h4 class="fw-bold mb-0">Grand Total:</h4>
                            <h4 class="fw-bold text-success mb-0">₹ {{ number_format($order->order_amount, 2) }}</h4>
                        </div>
                        <div class="mt-2 text-muted small">
                            <strong>Amount in words:</strong> 
                            {{ \App\Helpers\NumberToWords::convert($order->order_amount) }} Rupees Only
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Order Notes -->
            @if($order->order_notes && $order->order_notes != 'Note: ')
            <div class="row mt-4">
                <div class="col-12">
                    <div class="bg-light p-3 rounded">
                        <h6 class="fw-bold mb-2"><i class="bx bx-note"></i> Order Notes</h6>
                        <p class="mb-0">{{ str_replace('Note: ', '', $order->order_notes) }}</p>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Terms & Conditions -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="p-3 rounded">
                        <h6 class="fw-bold mb-2"><i class="bx bx-file"></i> Terms & Conditions</h6>
                        <ul class="mb-0 small">
                            <li>Goods once sold will not be taken back unless damaged/defective.</li>
                            <li>This is a computer generated invoice and does not require physical signature.</li>
                            <li>Subject to local jurisdiction.</li>
                            <li>Please check all items at the time of delivery.</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Signature Section -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <p class="mb-0"><strong>Customer Signature:</strong></p>
                    <br><br>
                    <p class="text-muted small">(Received goods in good condition)</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0"><strong>For, {{ company_info('bill_company_name') }}</strong></p>
                    <br><br>
                    <p class="fw-bold mb-0">(Authorized Signatory)</p>
                </div>
            </div>
            
            <!-- Thank You Message -->
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <hr>
                    <p class="text-muted mb-0">Thank you for shopping with us! For any queries, contact  {{ company_info('company_email') }}</p>
                </div>
            </div>
            
        </div>
    </div>
    
</div>

<!-- Payment Reference Modal -->
<div class="modal fade" id="deliveredModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="bx bx-check-circle me-2"></i>Complete Order - Delivered</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="deliveredForm" method="POST" action="{{ route('admin.productorders.update_status', $order->id) }}">
                @csrf
                <input type="hidden" name="status" value="2">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="bx bx-info-circle me-1"></i>
                        Marking this order as <strong>Delivered</strong> will credit BV to the user's wallet.
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Payment Reference / Transaction ID 
                            <span class="text-muted">(Optional)</span>
                        </label>
                        <input type="text" 
                               name="payout_id" 
                               id="payout_id" 
                               class="form-control" 
                               placeholder="Enter Bank / UPI Transaction ID"
                               maxlength="100">
                        <small class="text-muted">
                            <i class="bx bx-info-circle"></i> 
                            Enter the payment reference if payment was received via Bank/UPI
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bx bx-check me-1"></i> Confirm Delivered
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function printLabelOnly() {
        var originalTitle = document.title;
        document.title = 'Shipping Label - Order {{ $order->tx_id }}';
        
        var printContents = document.getElementById('shipping-label').innerHTML;
        
        var printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Shipping Label - Order {{ $order->tx_id }}</title>
                <style>
                    * { margin: 0; padding: 0; box-sizing: border-box; }
                    body { font-family: Arial, sans-serif; background: #fff; margin: 0; padding: 0; }
                    .label-container { margin-top: 40px !important; padding: 20px; }
                    .packaging-sticker { background: #fff; border: 2px dashed #333; border-radius: 8px; padding: 20px; max-width: 800px; margin: 0 auto; }
                    .text-center { text-align: center; }
                    .border-bottom { border-bottom: 1px solid #ddd; }
                    .border-top { border-top: 1px solid #ddd; }
                    .fw-bold { font-weight: bold; }
                    .mb-0 { margin-bottom: 0; }
                    .mb-3 { margin-bottom: 15px; }
                    .mb-4 { margin-bottom: 20px; }
                    .mt-3 { margin-top: 15px; }
                    .pb-2 { padding-bottom: 10px; }
                    .pb-3 { padding-bottom: 15px; }
                    .pt-2 { padding-top: 10px; }
                    .text-muted { color: #6c757d; }
                    .row { display: flex; flex-wrap: wrap; }
                    .col-6 { width: 50%; }
                    .col-12 { width: 100%; }
                    @media print {
                        body { margin: 0; padding: 0; }
                        .label-container { margin-top: 40px !important; }
                    }
                </style>
            </head>
            <body>
                <div class="label-container">
                    ${printContents}
                </div>
                <script>
                    window.onload = function() {
                        window.print();
                        window.onafterprint = function() {
                            window.close();
                        };
                    };
                <\/script>
            </body>
            </html>
        `);
        printWindow.document.close();
        document.title = originalTitle;
    }
    
    function printInvoiceOnly() {
        var originalTitle = document.title;
        document.title = 'Tax Invoice - Order {{ $order->tx_id }}';
        
        var printContents = document.getElementById('tax-invoice').innerHTML;
        
        var printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Tax Invoice - Order {{ $order->tx_id }}</title>
                <style>
                    * { margin: 0; padding: 0; box-sizing: border-box; }
                    body { font-family: Arial, sans-serif; background: #fff; margin: 0; padding: 20px; }
                    .table { width: 100%; border-collapse: collapse; }
                    .table th, .table td { border: 1px solid #ddd; padding: 8px; }
                    .bg-light { background: #f8f9fa; }
                    .text-center { text-align: center; }
                    .text-end { text-align: right; }
                    .text-start { text-align: left; }
                    .text-primary { color: #0d6efd; }
                    .text-success { color: #28a745; }
                    .text-danger { color: #dc3545; }
                    .fw-bold { font-weight: bold; }
                    .mb-0 { margin-bottom: 0; }
                    .mb-1 { margin-bottom: 5px; }
                    .mb-2 { margin-bottom: 10px; }
                    .mb-4 { margin-bottom: 20px; }
                    .mt-2 { margin-top: 10px; }
                    .mt-4 { margin-top: 20px; }
                    .p-3 { padding: 15px; }
                    .p-5 { padding: 30px; }
                    .rounded { border-radius: 8px; }
                    .border-bottom { border-bottom: 1px solid #ddd; }
                    .small { font-size: 12px; }
                    .text-muted { color: #6c757d; }
                    @media print {
                        body { margin: 0; padding: 0; }
                        .no-print { display: none !important; }
                    }
                </style>
            </head>
            <body>
                <div class="invoice-container">
                    ${printContents}
                </div>
                <script>
                    window.onload = function() {
                        window.print();
                        window.onafterprint = function() {
                            window.close();
                        };
                    };
                <\/script>
            </body>
            </html>
        `);
        printWindow.document.close();
        document.title = originalTitle;
    }
</script>

@endsection