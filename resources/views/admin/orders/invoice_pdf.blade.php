<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $order->tx_id }}</title>
    <style>
        @page {
            margin: 12mm 12mm 12mm 12mm;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            line-height: 1.35;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .invoice-box {
            width: 100%;
        }
        .invoice-header {
            text-align: center;
            margin-bottom: 8px;
            padding-bottom: 6px;
            border-bottom: 2px solid #333;
        }
        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 2px;
        }
        .invoice-header div {
            margin: 0;
            padding: 0;
        }
        .invoice-title {
            font-size: 16px;
            font-weight: bold;
            margin: 8px 0 6px 0;
            text-align: center;
        }
        .order-info {
            margin-bottom: 6px;
        }
        .order-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .order-info td {
            padding: 2px 4px;
            vertical-align: top;
            font-size: 11px;
        }
        .shipping-info {
            margin-bottom: 8px;
            font-size: 11px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
            margin-bottom: 6px;
            page-break-inside: avoid;
        }
        .items-table th,
        .items-table td {
            border: 1px solid #ddd;
            padding: 3px 4px;
            text-align: left;
            font-size: 10px;
        }
        .items-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .text-right {
            text-align: right !important;
        }
        .gst-title {
            font-size: 12px;
            font-weight: bold;
            margin-top: 4px;
            margin-bottom: 3px;
        }
        .totals-wrapper-table {
            width: 100%;
            margin-top: 4px;
            page-break-inside: avoid;
        }
        .totals-box {
            width: 280px;
        }
        .totals-box table {
            width: 100%;
            border-collapse: collapse;
        }
        .totals-box td {
            padding: 3px 4px;
            font-size: 11px;
        }
        .totals-box .grand-total {
            font-size: 13px;
            font-weight: bold;
            background-color: #f5f5f5;
            border-top: 1px solid #333;
            border-bottom: 1px solid #333;
        }
        .status-badge {
            display: inline-block;
            margin-top: 2px;
            padding: 1px 5px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .status-delivered { background: #28a745; color: white; }
        .status-pending { background: #ffc107; color: black; }
        .status-shipped { background: #17a2b8; color: white; }
        .status-cancelled { background: #dc3545; color: white; }
        .status-returned { background: #6c757d; color: white; }
        .status-accepted { background: #0d6efd; color: white; }
    </style>
</head>
<body>
    @php
        // GST and Tax Calculations (Assuming price is inclusive of GST)
        $calculatedSubtotal = 0;
        $calculatedTotalGst = 0;
        $gstBreakdown = [];

        foreach($order->items as $item) {
            $gstPercent = $item->product->gst_percent ?? 18;
            $itemPriceInclusive = $item->price;
            $qty = $item->qty;

            $gstAmountPerUnit = ($itemPriceInclusive * $gstPercent) / (100 + $gstPercent);
            $taxablePricePerUnit = $itemPriceInclusive - $gstAmountPerUnit;

            $itemTaxableTotal = $taxablePricePerUnit * $qty;
            $itemGstTotal = $gstAmountPerUnit * $qty;

            $calculatedSubtotal += $itemTaxableTotal;
            $calculatedTotalGst += $itemGstTotal;

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
                            $statusText = match((int) $order->status) {
                                0 => 'Pending',
                                1 => 'Shipped',
                                2 => 'Delivered',
                                3 => 'Cancelled',
                                4 => 'Returned',
                                5 => 'Accepted',
                                default => 'Unknown'
                            };
                        @endphp
                        <span class="status-badge status-{{ strtolower($statusText) }}">{{ $statusText }}</span>
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

        <div class="shipping-info">
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
                @forelse($order->items as $key => $item)
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
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;">No products found for this order</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="gst-title">GST Summary Breakdown</div>
        <table class="items-table">
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
                @forelse($gstBreakdown as $gst)
                <tr>
                    <td><strong>{{ $gst['rate'] }}%</strong></td>
                    <td class="text-right">₹{{ number_format($gst['taxable'], 2) }}</td>
                    <td class="text-right">₹{{ number_format($gst['gst_amount'] / 2, 2) }} ({{ $gst['rate'] / 2 }}%)</td>
                    <td class="text-right">₹{{ number_format($gst['gst_amount'] / 2, 2) }} ({{ $gst['rate'] / 2 }}%)</td>
                    <td class="text-right"><strong>₹{{ number_format($gst['gst_amount'], 2) }}</strong></td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center;">No GST data</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <table class="totals-wrapper-table">
            <tr>
                <td width="55%" style="vertical-align: top;">
                    @if($order->order_notes && $order->order_notes != 'Note: ')
                        <div style="font-size: 10px; color: #555;">
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
