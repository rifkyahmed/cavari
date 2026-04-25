<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Helvetica', sans-serif; background-color: #ffffff; margin: 0; padding: 0; }
        .container { width: 100%; max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .header { padding: 40px 0; text-align: center; }
        .content { padding: 40px; text-align: center; }
        .gift-card { 
            background: #1a1a1a; 
            border-radius: 20px; 
            padding: 40px; 
            color: #ffffff; 
            margin: 20px 0;
            text-align: left;
            position: relative;
            overflow: hidden;
        }
        .title { font-size: 24px; font-weight: bold; margin-bottom: 10px; font-family: 'serif'; }
        .amount { font-size: 48px; font-weight: bold; margin: 20px 0; font-family: 'monospace'; }
        .code-box { 
            background: rgba(255,255,255,0.1); 
            padding: 15px; 
            border-radius: 10px; 
            display: inline-block; 
            font-family: 'monospace'; 
            font-size: 20px; 
            letter-spacing: 5px;
            color: #ffffff;
            border: 1px solid rgba(255,255,255,0.2);
        }
        .footer { padding: 40px; text-align: center; font-size: 12px; color: #999999; border-top: 1px solid #f0f0f0; }
        .btn { 
            background: #000000; 
            color: #ffffff; 
            padding: 15px 30px; 
            text-decoration: none; 
            border-radius: 5px; 
            display: inline-block; 
            margin-top: 30px; 
            font-weight: bold;
            font-size: 14px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ $message->embed(public_path('images/cavarilogo.png')) }}" alt="Cavari" style="height: 60px; width: auto;">
        </div>
        <div class="content">
            <p style="font-size: 18px; line-height: 1.6; color: #333333;">Greetings from the House of Cavari,</p>
            <p style="font-size: 16px; line-height: 1.6; color: #666666;">
                <strong>{{ $giftCard->sender_name }}</strong> has shared a piece of the Cavari legacy with you. 
                They’ve sent you a Digital Credit to be used across our exquisite gemstone and jewelry collections.
            </p>

            @if($giftCard->message)
            <div style="font-style: italic; color: #000; margin: 30px 0; border-left: 2px solid #000; padding-left: 20px; text-align: left;">
                "{{ $giftCard->message }}"
            </div>
            @endif

            <div class="gift-card">
                <div class="title">The Cavari Credit</div>
                <div style="font-size: 10px; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 2px;">Available Valuation</div>
                <div class="amount">${{ number_format($giftCard->balance, 2) }}</div>
                
                <p style="font-size: 12px; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 5px;">Your Authentication Code</p>
                <div class="code-box">{{ $giftCard->code }}</div>
            </div>

            <p style="margin-top: 40px; color: #666666;">Apply this code at checkout to redeem your gift.</p>
            
            <a href="{{ url('/shop') }}" class="btn">Explore The Collection</a>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Cavari Gemstones. All rights reserved.
            <br>
            Exquisite gems and luxury jewelry perfect for every occasion.
        </div>
    </div>
</body>
</html>
