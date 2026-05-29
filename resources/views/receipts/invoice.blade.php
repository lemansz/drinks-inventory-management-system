<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; margin: 0; padding: 0; }
        .container { width: 100%; padding: 10px; }
        .text-center { text-align: center; }
        .dashed-line { border-top: 1px dashed #000; margin: 5px 0; }
        .item-table { width: 100%; }
        .item-name { font-weight: bold; display: block; }
        .item-details { font-size: 10px; color: #555; }
    </style>
</head>
<body>
    <div class="container">
        <div class="text-center">
            <h2 style="margin: 0;">mcheck</h2>
            <p>Date: {{ $sale->created_at->format('d/m/Y g:i A') }}<br>
            Receipt #: {{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</p>
        </div>

        <div class="dashed-line"></div>

        <table class="item-table">
            @foreach($items as $item)
                <tr>
                    <td colspan="2">
                        <span class="item-name">{{ $item['name'] }}</span>
                        <span class="item-details">
                            {{ $item['qty'] }} Units 
                            @if($item['has_crates'])
                                ({{ $item['full_crates'] }} Crates @if($item['extra_units'] > 0) + {{ $item['extra_units'] }} units @endif)
                            @endif
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>{{ $item['qty'] }} x {{ number_format($item['price'], 2) }}</td>
                    <td style="text-align: right;">{{ $currency }}{{ number_format($item['subtotal'], 2) }}</td>
                </tr>
            @endforeach
        </table>

        <div class="dashed-line"></div>

        <table class="item-table" style="font-weight: bold; font-size: 14px;">
            <tr>
                <td>TOTAL</td>
                <td style="text-align: right;">{{ $currency }}{{ number_format($sale->total_amount, 2) }}</td>
            </tr>
        </table>

        <div class="dashed-line"></div>
        <p class="text-center">THANK YOU FOR YOUR PATRONAGE!</p>
    </div>
</body>
</html>
