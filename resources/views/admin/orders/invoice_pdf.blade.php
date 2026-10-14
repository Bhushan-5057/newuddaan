<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $order->tx_id }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .invoice-box {
            max-width: 100%;
            margin: auto;
   
        }
        .invoice-header {
            text-align: center;
            margin-bottom: 50px;
            padding-bottom: 5px;
            border-bottom: 2px solid #333;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .invoice-title {
            font-size: 20px;
            font-weight: bold;
            margin-top: 5px;
            margin-bottom: 5px;
            text-align: center;
        }
        .order-info {
            margin-bottom: 10px;
        }
        .order-info table {
            width: 100%;
        }
        .order-info td {
            padding: 5px;
            vertical-align: top;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            margin-bottom: 5px;
        }
        .items-table th, .items-table td {
            border: 1px solid #ddd;
            padding: 4px 5px;
            text-align: left;
            font-size: 11px;
        }
        .items-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .text-right {
            text-align: right !important;
        }
        
        /* GST Breakdown Section */
        .gst-title {
            font-size: 13px;
            font-weight: bold;
            margin-top: 5px;
            margin-bottom: 4px;
        }

        /* DomPDF Friendly Totals Table Alignment */
        .totals-wrapper-table {
            width: 100%;
            margin-top: 5px;
        }
        .totals-box {
            width: 300px;
        }
        .totals-box table {
            width: 100%;
            border-collapse: collapse;
        }
        .totals-box td {
            padding: 5px;
            font-size: 12px;
        }
        .totals-box .grand-total {
            font-size: 15px;
            font-weight: bold;
            background-color: #f5f5f5;
            border-top: 1px solid #333;
            border-bottom: 1px solid #333;
        }
   
        .status-badge {
            display: inline-block;
             margin-top: 10px;
            padding: 2px 4px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
        }
        .status-delivered { background: #28a745; color: white; }
        .status-pending { background: #ffc107; color: black; }
        .status-shipped { background: #17a2b8; color: white; }
        .status-cancelled { background: #dc3545; color: white; }
    </style>
</head>
<body>
    @php
        // GST and Tax Calculations (Assuming price is inclusive of GST)
        $calculatedSubtotal = 0;
        $calculatedTotalGst = 0;
        $gstBreakdown = [];
        
        foreach($order->items as $item) {
            $gstPercent = $item->product->gst_percent ?? 18; // Default 18% if not defined
            $itemPriceInclusive = $item->price;
            $qty = $item->qty;
            
            // Formula to reverse extract GST from inclusive price
            $gstAmountPerUnit = ($itemPriceInclusive * $gstPercent) / (100 + $gstPercent);
            $taxablePricePerUnit = $itemPriceInclusive - $gstAmountPerUnit;
            
            $itemTaxableTotal = $taxablePricePerUnit * $qty;
            $itemGstTotal = $gstAmountPerUnit * $qty;
            
            $calculatedSubtotal += $itemTaxableTotal;
            $calculatedTotalGst += $itemGstTotal;
            
            // Group by GST percentages
            if (!isset($gstBreakdown[$gstPercent])) {
                $gstBreakdown[$gstPercent] = [
                    'rate' => $gstPercent,
                    'taxable' => 0,
                    'gst_amount' => 0
                ];
            }
            $gstBreakdown[$gstPercent]['taxable'] += $itemTaxableTotal;
            $gstBreakdown[$gstPercent]['gst_amount'] += $itemGstTotal;
        }
    @endphp

    <div class="invoice-box">
        <div class="invoice-header">
            <div class="company-name">{{ company_info('company_name') }}</div>
            <div>{{ company_info('company_address') }}</div>
            <div>GST: {{ company_info('company_gst') }} | Phone: {{ company_info('company_mobile') }}</div>
            <div>Email: {{ company_info('company_email') }}</div>
        </div>

        <div class="invoice-title">TAX INVOICE</div>

        <div class="order-info">
            <table>
                <tr>
                    <td width="50%">
                        <strong>Order Details:</strong><br>
                        Order ID: {{ $order->tx_id }}<br>
                        Order Date: {{ \Carbon\Carbon::parse($order->added_on)->format('d-m-Y H:i') }}<br>
                        Order Status: 
                        @php
                            $statusText = match($order->status) {
                                0 => 'Pending',
                                1 => 'Shipped',
                                2 => 'Delivered',
                                3 => 'Cancelled',
                                4 => 'Returned',
                                5 => 'Accepted',
                                default => 'Unknown'
                            };
                        @endphp
                        <span class="mt-3 status-badge status-{{ strtolower($statusText) }}">{{ $statusText }}</span>
                    </td>
                    <td width="50%">
                        <strong>Customer Details:</strong><br>
                        Name: {{ $order->user->name ?? 'N/A' }}<br>
                        Username: {{ $order->user->username ?? 'N/A' }}<br>
                        Phone: {{ explode('|', $order->order_details ?? '')[0] ?? 'N/A' }}<br>
                        Email: {{ $order->user->email ?? 'N/A' }}
                    </td>
                </tr>
            </table>
        </div>

        <div class="shipping-info" style="margin-bottom: 20px;">
            <strong>Shipping Address:</strong><br>
            {{ $order->order_address ?? 'N/A' }}
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="35%">Product</th>
                    <th width="12%">SKU</th>
                    <th width="10%">Qty</th>
                    <th width="12%" class="text-right">Price (₹)</th>
                    <th width="10%" class="text-right">GST %</th>
                    <th width="16%" class="text-right">Taxable Total (₹)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $key => $item)
                @php
                    $gstPercent = $item->product->gst_percent ?? 18;
                    $gstAmountPerUnit = ($item->price * $gstPercent) / (100 + $gstPercent);
                    $taxablePricePerUnit = $item->price - $gstAmountPerUnit;
                    $itemTaxableTotal = $taxablePricePerUnit * $item->qty;
                @endphp
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->product->name ?? 'Product' }}</td>
                    <td>{{ $item->product->p_code ?? 'N/A' }}</td>
                    <td>{{ $item->qty }}</td>
                    <td class="text-right">₹{{ number_format($item->price, 2) }}</td>
                    <td class="text-right">{{ $gstPercent }}%</td>
                    <td class="text-right">₹{{ number_format($itemTaxableTotal, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="gst-title">GST Summary Breakdown</div>
        <table class="items-table" style="margin-top: 5px;">
            <thead>
                <tr>
                    <th>GST Rate</th>
                    <th class="text-right">Taxable Value (₹)</th>
                    <th class="text-right">CGST (₹)</th>
                    <th class="text-right">SGST (₹)</th>
                    <th class="text-right">Total Tax Amount (₹)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($gstBreakdown as $gst)
                <tr>
                    <td><strong>{{ $gst['rate'] }}%</strong></td>
                    <td class="text-right">₹{{ number_format($gst['taxable'], 2) }}</td>
                    <td class="text-right">₹{{ number_format($gst['gst_amount'] / 2, 2) }} ({{ $gst['rate'] / 2 }}%)</td>
                    <td class="text-right">₹{{ number_format($gst['gst_amount'] / 2, 2) }} ({{ $gst['rate'] / 2 }}%)</td>
                    <td class="text-right"><strong>₹{{ number_format($gst['gst_amount'], 2) }}</strong></td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <table class="totals-wrapper-table">
            <tr>
                <td width="55%" style="vertical-align: top;">
                    @if($order->order_notes && $order->order_notes != 'Note: ')
                        <div style="font-size: 11px; color: #555;">
                            <strong>Order Note:</strong><br>
                            {{ str_replace('Note: ', '', $order->order_notes) }}
                        </div>
                    @endif
                </td>
                <td width="45%" align="right">
                    <div class="totals-box">
                        <table>
                            <tr>
                                <td width="60%">Taxable Value:</td>
                                <td width="40%" class="text-right">₹{{ number_format($calculatedSubtotal, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Total GST Tax:</td>
                                <td class="text-right">₹{{ number_format($calculatedTotalGst, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Shipping Charge:</td>
                                <td class="text-right">₹{{ number_format($order->shipping_charge ?? 0, 2) }}</td>
                            </tr>
                            <tr class="grand-total">
                                <td><strong>Grand Total:</strong></td>
                                <td class="text-right"><strong>₹{{ number_format($order->order_amount, 2) }}</strong></td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>

    
    </div>
</body>
</html>