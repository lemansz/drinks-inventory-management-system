<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        /* DejaVu Sans supports Unicode characters like the Naira (₦) symbol */
        body { 
            font-family: 'DejaVu Sans', sans-serif; 
            font-size: 11px; 
            color: #333; 
            line-height: 1.4;
        }
        
        .header { text-align: center; border-bottom: 2px solid #444; padding-bottom: 10px; margin-bottom: 20px; }
        
        /* Summary Box at Top */
        .summary-box { background: #f8f9fa; padding: 15px; border: 1px solid #ddd; margin-bottom: 20px; width: 100%; }
        .summary-title { font-size: 12px; color: #666; margin-bottom: 5px; text-transform: uppercase; }
        .summary-value { font-size: 20px; font-weight: bold; color: #2c3e50; }

        table { width: 100%; border-collapse: collapse; }
        th { background-color: #2c3e50; color: white; padding: 8px; text-align: left; text-transform: uppercase; font-size: 10px; }
        td { border-bottom: 1px solid #eee; padding: 8px; vertical-align: top; }
        
        /* Subtle Spacer Row for new Sales */
        .sale-spacer { height: 10px; background-color: #ffffff; border: none; }
        .sale-label { 
            font-size: 10px; 
            color: #555; 
            background-color: #f1f1f1; 
            font-weight: bold; 
            padding: 5px 8px; 
            border-left: 3px solid #2c3e50;
        }

        .grand-total-row { background-color: #e9ecef; font-weight: bold; border-top: 2px solid #444; }
        .text-success { color: #27ae60; font-weight: bold; }
        .text-right { text-align: right; }
        .stock-info { font-size: 9px; color: #666; }
    </style>
</head>
<body>

    <div class="header">
        <h1>DAILY SALES REPORT</h1>
        <p>{{ $date }}</p>
    </div>

    <!-- Summary Section at the Top -->
    <table class="summary-box">
        <tr>
            <td style="border:none;">
                <div class="summary-title">Today's Revenue</div>
                <div class="summary-value">{{ $currency }}{{ number_format($totalSales, 2) }}</div>
            </td>
            <td style="border:none; text-align: right;">
                <div class="summary-title">Today's Total Profit</div>
                <div class="summary-value" style="color: #27ae60;">{{ $currency }}{{ number_format($totalProfit, 2) }}</div>
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Subtotal</th>
                <th>Profit (on Sale)</th>
                <th>Stock Rem.</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $sale)
                {{-- Sale Header / Spacer --}}
                <tr>
                    <td colspan="6" class="sale-label">
                        SALE #{{ $sale->id }} — Recorded at {{ $sale->created_at->format('h:i A') }}
                    </td>
                </tr>

                @foreach($sale->products as $product)
                    <tr>
                        <td><strong>{{ $product->name }}</strong></td>
                        <td>{{ $product->pivot->quantity_sold }}</td>
                        <td>{{ number_format($product->pivot->price_per_unit, 2) }}</td>
                        <td>{{ number_format($product->pivot->subtotal, 2) }}</td>
                        
                        {{-- Show Profit at the Sale level only to avoid confusion --}}
                        <td style="color: #999;">
                            @if($loop->first) 
                                <span class="text-success">{{ $currency }}{{ number_format($sale->total_profit, 2) }}</span>
                            @else
                                -
                            @endif
                        </td>

                        <td>
                            {{ $product->stock }} <br>
                            <span class="stock-info">
                                ({{ floor($product->stock / $product->pieces_per_crate) }} Crts, {{ $product->stock % $product->pieces_per_crate }} pcs)
                            </span>
                        </td>
                    </tr>
                @endforeach
                
                {{-- Blank gap between sales --}}
                <tr class="sale-spacer"><td colspan="6"></td></tr>
            @endforeach

            <!-- Grand Total Footer -->
            <tr class="grand-total-row">
                <td colspan="3" class="text-right">GRAND TOTALS:</td>
                <td>{{ $currency }}{{ number_format($totalSales, 2) }}</td>
                <td class="text-success">{{ $currency }}{{ number_format($totalProfit, 2) }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>

</body>
</html>
