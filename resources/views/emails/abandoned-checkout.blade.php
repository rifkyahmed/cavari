<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Your Order — Cavari</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Georgia', serif;
            background-color: #f5f4f1;
            color: #2d2d2d;
        }
        .wrapper {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 4px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
        }
        .header {
            background: #1a1a1a;
            padding: 40px 48px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            font-size: 22px;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            font-family: 'Arial', sans-serif;
            font-weight: 700;
        }
        .header p {
            color: #aaa;
            font-size: 12px;
            letter-spacing: 0.15em;
            margin-top: 6px;
        }
        .body {
            padding: 48px;
        }
        .greeting {
            font-size: 24px;
            font-weight: bold;
            color: #1a1a1a;
            margin-bottom: 24px;
        }
        .message-box {
            background: #faf9f7;
            border-left: 3px solid #c9a96e;
            padding: 20px 24px;
            border-radius: 0 4px 4px 0;
            font-size: 15px;
            line-height: 1.8;
            color: #444;
            white-space: pre-line;
            margin-bottom: 36px;
        }
        .divider {
            border: none;
            border-top: 1px solid #eee;
            margin: 32px 0;
        }
        .cart-title {
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: #888;
            font-family: 'Arial', sans-serif;
            margin-bottom: 20px;
        }
        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 0;
            border-bottom: 1px solid #f0eeeb;
        }
        .cart-item:last-child {
            border-bottom: none;
        }
        .item-name {
            font-size: 15px;
            font-weight: 600;
            color: #1a1a1a;
        }
        .item-qty {
            font-size: 12px;
            color: #999;
            margin-top: 2px;
        }
        .item-price {
            font-size: 15px;
            font-weight: bold;
            font-family: 'Courier New', monospace;
            color: #1a1a1a;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0 0;
            margin-top: 12px;
            border-top: 2px solid #1a1a1a;
        }
        .total-label {
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: #1a1a1a;
            font-family: 'Arial', sans-serif;
        }
        .total-amount {
            font-size: 22px;
            font-weight: bold;
            font-family: 'Courier New', monospace;
            color: #1a1a1a;
        }
        .cta-section {
            text-align: center;
            margin-top: 40px;
        }
        .cta-button {
            display: inline-block;
            background: #1a1a1a;
            color: #ffffff;
            text-decoration: none;
            padding: 16px 48px;
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            font-family: 'Arial', sans-serif;
            border-radius: 2px;
        }
        .cta-sub {
            margin-top: 12px;
            font-size: 12px;
            color: #aaa;
        }
        .footer {
            background: #f5f4f1;
            padding: 32px 48px;
            text-align: center;
            border-top: 1px solid #eee;
        }
        .footer p {
            font-size: 12px;
            color: #aaa;
            line-height: 1.7;
        }
        .footer strong {
            color: #888;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Header -->
        <div class="header">
            <h1>Cavari</h1>
            <p>Fine Gems & Jewellery</p>
        </div>

        <!-- Body -->
        <div class="body">
            <p class="greeting">Hello, {{ $checkout->user_name }} 👋</p>

            <!-- Admin's Custom Message -->
            <div class="message-box">{{ $customMessage }}</div>

            <hr class="divider">

            <!-- Cart Items -->
            <p class="cart-title">Your Selected Items</p>

            @foreach($checkout->cart_data as $productId => $item)
            <div class="cart-item">
                <div>
                    <div class="item-name">{{ $item['name'] ?? 'Product #' . $productId }}</div>
                    <div class="item-qty">Qty: {{ $item['quantity'] ?? 1 }}</div>
                </div>
                <div class="item-price">{{ \App\Helpers\CurrencyHelper::format(($item['price'] ?? 0) * ($item['quantity'] ?? 1)) }}</div>
            </div>
            @endforeach

            <!-- Total -->
            <div class="total-row">
                <span class="total-label">Cart Total</span>
                <span class="total-amount">{{ \App\Helpers\CurrencyHelper::format($checkout->cart_total) }}</span>
            </div>

            <!-- CTA -->
            <div class="cta-section">
                <a href="{{ url('/checkout') }}" class="cta-button">Complete My Order</a>
                <p class="cta-sub">Your items are still available — don't miss out.</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>
                <strong>Cavari Fine Gems & Jewellery</strong><br>
                If you have any questions, simply reply to this email.<br>
                <br>
                You received this email because you have an account with us.<br>
                © {{ date('Y') }} Cavari. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
