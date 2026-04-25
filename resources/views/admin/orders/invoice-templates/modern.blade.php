<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->id }} - {{ config('app.name') }}</title>
    <style>
        * { box-sizing: border-box; }

        html, body {
            margin: 0;
            padding: 0;
            background: #f6f3ec;
            color: #111111;
            font-family: Arial, Helvetica, sans-serif;
        }

        @media print {
            html, body {
                background: #f6f3ec !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .no-print { display: none !important; }
            .sheet { box-shadow: none !important; margin: 0 !important; border-radius: 0 !important; }
        }

        .wrap {
            max-width: 860px;
            margin: 0 auto;
            padding: 22px 18px 26px;
        }

        .sheet {
            background: transparent;
            padding: 0 6px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 24px;
            min-height: 88px;
            margin-bottom: 26px;
        }

        .logo {
            width: 74px;
            height: 74px;
            object-fit: contain;
            object-position: left top;
            display: block;
        }

        .invoice-word {
            font-family: Georgia, 'Times New Roman', serif;
            font-size: 44px;
            line-height: 0.92;
            letter-spacing: -0.06em;
            font-weight: 400;
            text-transform: uppercase;
            margin: 0;
            padding-top: 4px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            gap: 24px;
            align-items: flex-start;
            margin-bottom: 34px;
        }

        .bill-block {
            max-width: 460px;
        }

        .label {
            margin: 0 0 8px;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0;
            text-transform: uppercase;
        }

        .customer-name {
            margin: 0;
            font-size: 16px;
            line-height: 1.3;
            font-weight: 400;
        }

        .customer-details {
            margin-top: 4px;
            font-size: 14px;
            line-height: 1.4;
            font-weight: 400;
        }

        .right-block {
            text-align: right;
            min-width: 220px;
            padding-top: 3px;
        }

        .right-block .line {
            font-size: 14px;
            line-height: 1.35;
            font-weight: 400;
        }

        .rule {
            border: 0;
            border-top: 1px solid #1a1a1a;
            margin: 0 0 0 0;
        }

        .table-section {
            margin-top: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        thead th {
            text-align: left;
            padding: 10px 10px 10px 0;
            border-bottom: 1px solid #222;
            font-size: 13px;
            font-weight: 700;
        }

        thead th:first-child,
        tbody td:first-child {
            padding-left: 0;
        }

        tbody td {
            padding: 11px 10px 11px 0;
            border-bottom: 1px solid #1f1f1f;
            vertical-align: top;
            font-size: 13px;
            font-weight: 400;
        }

        .right {
            text-align: right;
        }

        .totals {
            width: 250px;
            margin-left: auto;
            margin-top: 10px;
        }

        .totals-row {
            display: flex;
            justify-content: space-between;
            gap: 18px;
            padding: 8px 0;
            font-size: 13px;
            font-weight: 700;
        }

        .totals-row.subtle {
            font-weight: 400;
        }

        .totals-divider {
            border-top: 1px solid #1a1a1a;
            width: 180px;
            margin-left: auto;
        }

        .grand-total {
            display: flex;
            justify-content: space-between;
            gap: 18px;
            align-items: baseline;
            padding-top: 10px;
            font-size: 19px;
            font-weight: 700;
        }

        .thankyou {
            margin: 52px 0 48px;
            font-family: Georgia, 'Times New Roman', serif;
            font-size: 22px;
            font-weight: 400;
            letter-spacing: -0.03em;
        }

        .footer-grid {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 28px;
            margin-top: 18px;
        }

        .payment-title {
            margin: 0 0 8px;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .payment-copy {
            font-size: 12px;
            line-height: 1.4;
            font-weight: 400;
        }

        .sign-block {
            text-align: right;
            min-width: 280px;
        }

        .sign-name {
            font-family: Georgia, 'Times New Roman', serif;
            font-size: 18px;
            font-weight: 400;
            margin: 0 0 6px;
        }

        .sign-address {
            margin: 0;
            font-size: 12px;
            line-height: 1.4;
        }

        .print-btn {
            margin-top: 28px;
            border: 1px solid #111;
            background: transparent;
            color: #111;
            padding: 8px 12px;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            cursor: pointer;
        }

        .print-btn:hover {
            background: #111;
            color: #f6f3ec;
        }

        @media (max-width: 800px) {
            .wrap {
                padding: 18px;
            }

            .topbar,
            .info-row,
            .footer-grid {
                flex-direction: column;
            }

            .right-block,
            .sign-block {
                text-align: left;
            }

            .totals {
                width: 100%;
            }

            .invoice-word {
                font-size: 40px;
            }

            table,
            thead th,
            tbody td {
                font-size: 14px;
            }

            .thankyou {
                font-size: 28px;
                margin: 72px 0 64px;
            }
        }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="sheet">
            <div class="topbar">
                <img class="logo" src="{{ asset('images/cavarilogo.png') }}" alt="{{ config('app.name') }}">
                <h1 class="invoice-word">Invoice</h1>
            </div>

            <div class="info-row">
                <div class="bill-block">
                    <p class="label">Billed To:</p>
                    <p class="customer-name">{{ $order->user ? $order->user->name : 'Guest' }}</p>
                    <div class="customer-details">
                        {{ $order->user->phone ?? '' }}
                        @if(!empty($order->user->phone))<br>@endif
                        {{ $order->shipping_address }}
                    </div>
                </div>

                <div class="right-block">
                    <div class="line">Invoice No. {{ $order->id }}</div>
                    <div class="line">{{ $order->created_at->format('d F Y') }}</div>
                </div>
            </div>

            <hr class="rule">

            <div class="table-section">
                <table>
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th class="right">Quantity</th>
                            <th class="right">Unit Price</th>
                            <th class="right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>
                                    {{ $item->product ? $item->product->name : ($item->custom_name ?? 'Unknown Product') }}
                                </td>
                                <td class="right">{{ $item->quantity }}</td>
                                <td class="right">${{ number_format($item->price, 0) }}</td>
                                <td class="right">${{ number_format($item->price * $item->quantity, 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="totals">
                    <div class="totals-row subtle">
                        <span>Subtotal</span>
                        <span>${{ number_format($order->total_price, 0) }}</span>
                    </div>
                    <div class="totals-row subtle">
                        <span>Tax (0%)</span>
                        <span>$0</span>
                    </div>
                    <div class="totals-divider"></div>
                    <div class="grand-total">
                        <span>Total</span>
                        <span>${{ number_format($order->total_price, 0) }}</span>
                    </div>
                </div>
            </div>

            <div class="thankyou">Thank you!</div>

            <div class="footer-grid">
                <div>
                    <div class="payment-title">Payment Information</div>
                    <div class="payment-copy">
                        Briard Bank<br>
                        Account Name: Samira Hadid<br>
                        Account No.: 123-456-7890<br>
                        Pay by: {{ $order->created_at->addDays(14)->format('j F Y') }}
                    </div>
                </div>

                <div class="sign-block">
                    <p class="sign-name">{{ config('app.name') }}</p>
                    <p class="sign-address">123 Gemstone Ave, Jewelry City</p>
                </div>
            </div>

            <button onclick="window.print()" class="print-btn no-print">Print / Save PDF</button>
        </div>
    </div>
</body>
</html>
