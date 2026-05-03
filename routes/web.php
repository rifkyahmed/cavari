<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Temporary route to fix storage link on hosting server
Route::get('/fix-storage', function () {
    try {
        $target = storage_path('app/public');
        $shortcut = public_path('storage');
        
        if (file_exists($shortcut)) {
            if (is_link($shortcut)) {
                return "Storage link already exists and is a symlink. If images still don't show, check your .env APP_URL.";
            } else {
                // It's a directory, move it
                rename($shortcut, $shortcut . '_backup_' . time());
            }
        }
        
        app('files')->link($target, $shortcut);
        
        // Clear caches
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        \Illuminate\Support\Facades\Artisan::call('route:clear');

        return "Storage link created and caches cleared! Please update your .env APP_URL to match your domain (e.g. https://yourdomain.com).";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

// Live exchange rates (cached 1 h, no auth required)
Route::get('/api/exchange-rates', [\App\Http\Controllers\ExchangeRateController::class, 'rates'])->name('api.exchange-rates');


Route::redirect('/products', '/shop');
Route::get('/shop', [ProductController::class, 'index'])->name('products.index');
Route::get('/shop/gems', [ProductController::class, 'gems'])->name('shop.gems');
Route::get('/shop/jewelry', [ProductController::class, 'jewelry'])->name('shop.jewelry');
Route::get('/products/{id}/quick-view', [ProductController::class, 'quickView'])->name('products.quick-view');
Route::get('/shop/custom-design', function () {
    return view('shop.custom-design');
})->name('shop.custom-design');
Route::get('/about', function () {
    return view('about');
})->name('about');
Route::get('/contact', function () {
    return view('contact');
})->name('contact');
Route::post('/contact', [\App\Http\Controllers\PublicFormController::class, 'submitContact'])->name('contact.submit')->middleware('throttle:5,1');

Route::get('/shipping-returns', function () {
    return view('pages.shipping-returns');
})->name('shipping-returns');

Route::get('/legal-privacy', function () {
    return view('pages.legal-privacy');
})->name('legal-privacy');

Route::get('/share-experience', [\App\Http\Controllers\PublicReviewController::class, 'index'])->name('reviews.public');
Route::post('/share-experience', [\App\Http\Controllers\PublicReviewController::class, 'store'])->name('reviews.public.store')->middleware('throttle:5,1');

Route::post('/custom-design', [\App\Http\Controllers\PublicFormController::class, 'submitCustomization'])->name('shop.custom-design.submit')->middleware('throttle:5,1');

Route::get('/product/{slug}', [ProductController::class, 'show'])->name('products.show');

Route::get('/journal', [JournalController::class, 'index'])->name('journal.index');
Route::get('/journal/{slug}', [JournalController::class, 'show'])->name('journal.show');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'store'])->name('cart.store');
Route::patch('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/coupon/apply', [CartController::class, 'applyCoupon'])->name('cart.coupon.apply');
Route::post('/cart/coupon/remove', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');
Route::post('/cart/gift-card/apply', [CartController::class, 'applyGiftCard'])->name('cart.gift-card.apply');
Route::post('/cart/gift-card/remove', [CartController::class, 'removeGiftCard'])->name('cart.gift-card.remove');

Route::get('/gift-cards', [\App\Http\Controllers\GiftCardController::class, 'index'])->name('gift-cards.index');
Route::post('/gift-cards/initialize', [\App\Http\Controllers\GiftCardController::class, 'initialize'])->name('gift-cards.initialize');

Route::middleware(['auth'])->group(function () {
    Route::get('/gift-cards/checkout', [\App\Http\Controllers\GiftCardController::class, 'checkout'])->name('gift-cards.checkout');
    Route::post('/gift-cards/purchase', [\App\Http\Controllers\GiftCardController::class, 'purchase'])->name('gift-cards.purchase');
    
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success/{id}', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/order-confirmed/{id}', [CheckoutController::class, 'orderConfirmed'])->name('order.confirmed');
    
        // Coinbase Crypto Checkout
    Route::post('/checkout/coinbase', [PaymentController::class, 'createCharge'])
        ->name('checkout.coinbase')
        ->middleware('auth');

    // Coinbase Webhook (public endpoint, protected by HMAC inside controller)
    Route::post('/webhook/coinbase', [PaymentController::class, 'webhook'])
        ->name('webhook.coinbase')
        ->middleware('throttle:60,1');

    // Wishlist
    Route::get('/wishlist', [\App\Http\Controllers\WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist', [\App\Http\Controllers\WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{id}', [\App\Http\Controllers\WishlistController::class, 'destroy'])->name('wishlist.destroy');

    // Orders
    Route::get('/orders', [\App\Http\Controllers\UserOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [\App\Http\Controllers\UserOrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{id}/invoice', [\App\Http\Controllers\UserOrderController::class, 'invoice'])->name('user.orders.invoice');
    // Reviews
    Route::post('/products/{product}/reviews', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
});

Route::get('/checkout/success-public/{uuid}', [CheckoutController::class, 'publicSuccess'])->name('checkout.public-success');

// Custom Orders (Outside auth middleware to allow guest capture of intended URL)
Route::get('/custom-orders/{uuid}/pay', [\App\Http\Controllers\CustomOrderController::class, 'pay'])->name('custom_orders.pay');
Route::post('/custom-orders/{uuid}/process', [\App\Http\Controllers\CustomOrderController::class, 'process'])->name('custom_orders.process');

// Public order access links
Route::get('/order-access/{uuid}', [\App\Http\Controllers\UserOrderController::class, 'publicShow'])->name('orders.public.show');
Route::get('/order-access/{uuid}/invoice', [\App\Http\Controllers\UserOrderController::class, 'publicInvoice'])->name('orders.public.invoice');

// Profile Routes (Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Profile Addresses
    Route::post('/profile/address', [\App\Http\Controllers\UserAddressController::class, 'store'])->name('profile.address.store');
    Route::delete('/profile/address/{address}', [\App\Http\Controllers\UserAddressController::class, 'destroy'])->name('profile.address.destroy');
    
    // Birthday Popup seen
    Route::post('/coupon/{id}/seen', function ($id) {
        session(['birthday_popup_seen_' . $id => true]);
        return response()->json(['success' => true]);
    })->name('api.coupon.seen');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/live-data', [AdminDashboardController::class, 'liveData'])->name('dashboard.live-data');
    Route::post('/dashboard/update-gold', [AdminDashboardController::class, 'updateGoldPrice'])->name('dashboard.update-gold');
    Route::post('/gemlightbox-import', [\App\Http\Controllers\Admin\GemLightboxImportController::class, 'fetch'])->name('gemlightbox.import');
    Route::patch('products/{product}/toggle-visibility', [AdminProductController::class, 'toggleVisibility'])->name('products.toggle-visibility');
    Route::post('products/bulk-delete', [AdminProductController::class, 'bulkDelete'])->name('products.bulkDelete');
    Route::delete('products/delete-all', [AdminProductController::class, 'destroyAll'])->name('products.destroyAll');
    Route::resource('products', AdminProductController::class);
    Route::delete('categories/delete-all', [\App\Http\Controllers\Admin\CategoryController::class, 'destroyAll'])->name('categories.destroyAll');
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('journals', \App\Http\Controllers\Admin\JournalController::class);
    Route::post('customers/{customer}/send-birthday-offer', [\App\Http\Controllers\Admin\CustomerController::class, 'sendBirthdayOffer'])->name('customers.send-birthday-offer');
    Route::resource('customers', \App\Http\Controllers\Admin\CustomerController::class);
    Route::get('orders/create-custom', [\App\Http\Controllers\Admin\OrderController::class, 'createCustom'])->name('orders.create-custom');
    Route::post('orders/store-custom', [\App\Http\Controllers\Admin\OrderController::class, 'storeCustom'])->name('orders.store-custom');
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class);
    Route::get('orders/{order}/invoice', [\App\Http\Controllers\Admin\OrderController::class, 'invoice'])->name('orders.invoice');
    
    // Marketing
    Route::resource('coupons', \App\Http\Controllers\Admin\CouponController::class);
    Route::patch('promotions/{promotion}/toggle', [\App\Http\Controllers\Admin\PromotionController::class, 'toggleActive'])->name('promotions.toggle');
    Route::resource('promotions', \App\Http\Controllers\Admin\PromotionController::class);

    // Messages
    Route::resource('messages', \App\Http\Controllers\Admin\MessageController::class);
    Route::resource('customization-requests', \App\Http\Controllers\Admin\CustomizationRequestController::class);
    Route::resource('source-requests', \App\Http\Controllers\Admin\SourceRequestController::class);

    // Reviews
    Route::get('/reviews', [\App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/create', [\App\Http\Controllers\Admin\ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/reviews', [\App\Http\Controllers\Admin\ReviewController::class, 'store'])->name('reviews.store');
    Route::patch('/reviews/{review}/toggle', [\App\Http\Controllers\Admin\ReviewController::class, 'toggleApproval'])->name('reviews.toggle');
    Route::delete('/reviews/{review}', [\App\Http\Controllers\Admin\ReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::resource('website-reviews', \App\Http\Controllers\Admin\WebsiteReviewController::class);

    // Analytics
    Route::get('/analytics/export', [\App\Http\Controllers\Admin\AnalyticsController::class, 'export'])->name('analytics.export');
    Route::get('/analytics', [\App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics.index');

    // Abandoned Checkouts
    Route::get('/abandoned-checkouts', [\App\Http\Controllers\Admin\AbandonedCheckoutController::class, 'index'])->name('abandoned-checkouts.index');
    Route::get('/abandoned-checkouts/{abandonedCheckout}', [\App\Http\Controllers\Admin\AbandonedCheckoutController::class, 'show'])->name('abandoned-checkouts.show');
    Route::post('/abandoned-checkouts/{abandonedCheckout}/send-reminder', [\App\Http\Controllers\Admin\AbandonedCheckoutController::class, 'sendReminder'])->name('abandoned-checkouts.send-reminder');
    Route::delete('/abandoned-checkouts/{abandonedCheckout}', [\App\Http\Controllers\Admin\AbandonedCheckoutController::class, 'destroy'])->name('abandoned-checkouts.destroy');

    // Settings
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'edit'])->name('settings.edit');
    Route::put('/settings/general', [\App\Http\Controllers\Admin\SettingsController::class, 'updateGeneral'])->name('settings.general');
    Route::put('/settings/profile', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
    Route::put('/settings/password', [\App\Http\Controllers\Admin\SettingsController::class, 'updatePassword'])->name('settings.password');

    // Gift Cards
    Route::resource('gift-cards', \App\Http\Controllers\Admin\AdminGiftCardController::class);
});

// Dashboard redirection
Route::get('/dashboard', function () {
    if (auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
