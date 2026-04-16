<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\MenuCartController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\PhonePePaymentController;
use App\Http\Controllers\Kitchen\KitchenLoginController;
use App\Http\Controllers\Kitchen\KitchenDashboardController;

// Home Routes
Route::controller(HomeController::class)->group(function () {
    Route::get('', 'index')->name('home');
    Route::get('about-us', 'about_us')->name('about-us');
    Route::get('privacy-policy', 'privacy_policy')->name('privacy-policy');
    Route::get('terms-and-conditions', 'terms_and_conditions')->name('terms-and-conditions');
    Route::get('restaurants', 'restaurants')->name('restaurants');
    Route::get('contact-us', 'contact_us')->name('contact-us');
    Route::post('contact-us', 'store_contactus')->name('store_contactus');
    Route::post('quick-connect', 'quick_connect')->name('quick_connect');
    Route::get('lang/{locale}', 'lang')->name('lang');
    Route::get('newsletter/subscribe', 'newsLetterSubscribe')->name('newsletter.subscribe');
    Route::get('pricing', 'pricing')->name('pricing');
    Route::get('careers', 'careers')->name('careers');
    Route::get('careers/{id}', 'careerDetail')->name('career.detail');
    Route::post('careers/{id}/apply', 'careerApply')->name('career.apply');
    Route::get('blogs', 'blogs')->name('blogs');
    Route::get('refund', 'refund_policy')->name('refund');

});

// Menu Routes
Route::get('menu', [MenuController::class, 'index'])->name('home-menu');
Route::get('{slug}/menu', [MenuController::class, 'store_menu_by_slug'])->name('store.menu');
Route::get('menu-preview/{template}', [MenuController::class, 'previewTemplate'])->name('menu.preview');
Route::get('{slug}/menu/style/{styleId}', [MenuController::class, 'storeMenuWithStyle'])->name('store.menu.style');

// Menu Cart AJAX Routes
Route::prefix('menu-cart')->as('menu.cart.')->group(function () {
    Route::post('add', [MenuCartController::class, 'add'])->name('add');
    Route::get('get', [MenuCartController::class, 'get'])->name('get');
    Route::post('update-qty', [MenuCartController::class, 'updateQty'])->name('update-qty');
    Route::post('remove', [MenuCartController::class, 'remove'])->name('remove');
    Route::post('clear', [MenuCartController::class, 'clear'])->name('clear');
    Route::post('place-order', [MenuCartController::class, 'placeOrder'])->name('place-order');
    Route::get('orders', [MenuCartController::class, 'getOrders'])->name('orders');
    Route::get('ordered-items', [MenuCartController::class, 'getOrderedItems'])->name('ordered-items');
});

// Menu Item Detail AJAX
Route::get('menu-item/detail', [MenuCartController::class, 'itemDetail'])->name('menu.item.detail');

// Stripe Payment Routes
Route::group(['prefix' => 'payment/stripe', 'as' => 'stripe.'], function () {
    Route::get('pay', [StripePaymentController::class, 'index'])->name('pay');
    Route::get('token', [StripePaymentController::class, 'payment_process_3d'])->name('token');
    Route::get('success', [StripePaymentController::class, 'success'])->name('success');
    Route::get('canceled', [StripePaymentController::class, 'canceled'])
        ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
});

// PhonePe Payment Routes
Route::group(['prefix' => 'payment/phonepe', 'as' => 'phonepe.'], function () {
    Route::get('pay', [PhonePePaymentController::class, 'index'])->name('pay');
    Route::post('initiate', [PhonePePaymentController::class, 'initiate'])->name('initiate');
    Route::any('callback', [PhonePePaymentController::class, 'callback'])->name('callback')
        ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
    Route::get('canceled', [PhonePePaymentController::class, 'canceled'])->name('canceled');
    Route::get('thank-you', [PhonePePaymentController::class, 'thankYou'])->name('thank-you');
});

// Restaurant Registration Routes
Route::prefix('restaurant')->as('restaurant.')->group(function () {
    Route::controller(VendorController::class)->group(function () {
        Route::get('apply', 'create')->name('create');
        Route::post('apply', 'store')->name('store');
        Route::get('business-plan', 'secondStep')->name('secondStep');
        Route::post('business-plan', 'business_plan')->name('business_plan');
        Route::get('back', 'back')->name('back');
        Route::post('payment', 'payment')->name('payment');
        Route::get('final-step', 'final_step')->name('final_step');
        Route::post('check-email-unique', 'checkEmailUnique')->name('checkEmailUnique');
        Route::post('check-phone-unique', 'checkPhoneUnique')->name('checkPhoneUnique');
    });
});

// Login Routes
Route::controller(LoginController::class)->group(function () {
    Route::get('login/{tab}', 'login')->name('login');
    Route::post('login_submit', 'submit')->name('login_post');
    Route::get('logout', 'logout')->name('logout');
    Route::get('/reload-captcha', 'reloadCaptcha')->name('reload-captcha');
    Route::get('reset-password', 'reset_password_request')->name('reset-password');
    Route::post('/vendor-reset-password', 'vendor_reset_password_request')->name('vendor-reset-password');
    Route::get('/password-reset', 'reset_password')->name('change-password');
    Route::post('reset-password-submit', 'reset_password_submit')->name('reset-password-submit');

});

// Kitchen Login Routes
Route::get('kitchen/login', [KitchenLoginController::class, 'showLogin'])->name('kitchen.login');
Route::post('kitchen/login', [KitchenLoginController::class, 'login'])->name('kitchen.login.submit');

// Kitchen Dashboard Routes (protected)
Route::middleware(['web', 'kitchen'])->prefix('kitchen')->as('kitchen.')->group(function () {
    Route::get('/dashboard', [KitchenDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stats', [KitchenDashboardController::class, 'stats'])->name('dashboard.stats');
    Route::get('/orders', [KitchenDashboardController::class, 'orders'])->name('orders');
    Route::post('/orders/{id}/status', [KitchenDashboardController::class, 'updateOrderStatus'])->name('orders.status');
    Route::get('/items', [KitchenDashboardController::class, 'items'])->name('items');
    Route::post('/items/{id}/status', [KitchenDashboardController::class, 'updateItemStatus'])->name('items.status');
    Route::get('/check-new-orders', [KitchenDashboardController::class, 'checkNewOrders'])->name('check-new-orders');
    Route::get('/logout', [KitchenDashboardController::class, 'logout'])->name('logout');
});
