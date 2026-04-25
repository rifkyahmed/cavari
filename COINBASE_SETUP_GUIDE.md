# Coinbase Commerce Integration Guide

I have implemented the full development work for Coinbase cryptocurrency payments. To make it work, you need to obtain your API keys and configure your webhook.

## Step 1: Get your API Key
1. Go to [Coinbase Commerce Dashboard](https://commerce.coinbase.com/).
2. Sign in or create an account.
3. Go to **Settings** > **Security**.
4. Click on **New API Key** to generate a key.
5. Copy this key and paste it into your `.env` file:
   ```env
   COINBASE_COMMERCE_API_KEY=your_api_key_here
   ```

## Step 2: Set up the Webhook
1. In the Coinbase Commerce Dashboard, go to **Settings** > **Notifications**.
2. Click **Add an endpoint**.
3. Enter your webhook URL. It should look like this:
   `https://yourdomain.com/webhook/coinbase`
   *(For local testing, you might need a tool like Ngrok to expose your local server)*.
4. Select the events you want to receive. At minimum, select `charge:confirmed`.
5. After adding, click **Show Shared Secret**.
6. Copy the secret and paste it into your `.env` file:
   ```env
   COINBASE_COMMERCE_WEBHOOK_SECRET=your_webhook_secret_here
   ```

## Step 3: Configure Success/Cancel URLs
Update these in your `.env` file as well:
```env
COINBASE_COMMERCE_SUCCESS_URL=https://yourdomain.com/checkout/success
COINBASE_COMMERCE_CANCEL_URL=https://yourdomain.com/checkout
```

## How it works:
- **Checkout**: Users can now choose "Cryptocurrency" as a payment method.
- **Redirection**: When they confirm the order, they are redirected to a secure Coinbase page.
- **Verification**: Once the payment is confirmed on the blockchain, Coinbase sends a notification (webhook) to your site, and the order is automatically marked as "paid".
- **Security**: The API calls use HMAC signature verification to ensure that only legitimate requests from Coinbase can update your order status.
