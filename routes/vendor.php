<?php

use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Vendor\BannerController;
use App\Http\Controllers\Vendor\BusinessSettingsController;
use App\Http\Controllers\Vendor\CategoryController;
use App\Http\Controllers\Vendor\DashboardController;
use App\Http\Controllers\Vendor\ItemController;
use App\Http\Controllers\Vendor\MenuSettingsController;
use App\Http\Controllers\Vendor\ProfileController;
use App\Http\Controllers\Vendor\QrSettingsController;
use App\Http\Controllers\Vendor\RestaurantController;
use App\Http\Controllers\Vendor\CareerJobController;
use App\Http\Controllers\Vendor\CustomerController;
use App\Http\Controllers\Vendor\KitchenStaffController;
use App\Http\Controllers\Vendor\MenuOrderController;
use App\Http\Controllers\Vendor\SubscriptionController;
use App\Http\Controllers\Vendor\TodaySpecialController;
use Illuminate\Support\Facades\Route;


Route::middleware(['web', 'vendor'])->prefix('vendor')->as('vendor.')->group(function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('dashboard', 'dashboard')->name('dashboard');
        Route::get('site_direction', 'site_direction_vendor')->name('site_direction');
        Route::get('store-token', 'updateDeviceToken')->name('store.token');
        Route::get('order-stats', 'order_stats')->name('dashboard.order-stats');
    });

    # Item Management
    Route::prefix('item')->as('item.')->middleware(['module:item', 'subscription:item'])->controller(ItemController::class)->group(function () {
        Route::get('add-new', 'index')->name('add-new');
        Route::post('variant-combination', 'variant_combination')->name('variant-combination');
        Route::post('store', 'store')->name('store');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update/{id}', 'update')->name('update');
        Route::get('list', 'list')->name('list');
        Route::delete('delete/{id}', 'delete')->name('delete');
        Route::get('status/{id}/{status}', 'status')->name('status');
        Route::post('search', 'search')->name('search');
        Route::get('view/{id}', 'view')->name('view');
        Route::get('remove-image', 'remove_image')->name('remove-image');
        Route::get('get-categories', 'get_categories')->name('get-categories');
        Route::get('recommended/{id}/{status}', 'recommended')->name('recommended');
        Route::get('pending/item/list', 'pending_item_list')->name('pending_item_list');
        Route::get('requested/item/view/{id}', 'requested_item_view')->name('requested_item_view');

        Route::get('product-gallery', 'product_gallery')->name('product_gallery');


        //Mainul
        Route::get('get-variations', 'get_variations')->name('get-variations');
        Route::get('stock-limit-list', 'stock_limit_list')->name('stock-limit-list');
        Route::get('get-stock', 'get_stock')->name('get_stock');
        Route::post('stock-update', 'stock_update')->name('stock-update');

        Route::post('food-variation-generate', 'food_variation_generator')->name('food-variation-generate');
        Route::post('variation-generate', 'variation_generator')->name('variation-generate');

        //Import and export
        Route::get('bulk-import', 'bulk_import_index')->name('bulk-import');
        Route::post('bulk-import', 'bulk_import_data');
        Route::get('bulk-export', 'bulk_export_index')->name('bulk-export-index');
        Route::post('bulk-export', 'bulk_export_data')->name('bulk-export');
        // Route::get('flash-sale', 'flash_sale')->name('flash_sale');
        Route::get('get-brand-list', 'getBrandList')->name('getBrandList');
        Route::get('suggest', 'suggest')->name('suggest');
    });

    Route::prefix('item-gallery')->as('gallery.')->controller(\App\Http\Controllers\Admin\FileManagerController::class)->group(function () {
        Route::get('/api/{folder_path?}', 'apiIndex')->name('api');
        Route::post('/image-upload', 'upload')->name('image-upload');
    });

    # Menu Management
    Route::prefix('menu-category')->as('category.')->middleware(['module:category', 'subscription:category'])->controller(CategoryController::class)->group(function () {
        Route::get('', 'index')->name('index');
        Route::post('store', 'store')->name('store');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update/{id}', 'update')->name('update');
        Route::delete('delete/{id}', 'distroy')->name('delete');
        Route::get('status/{id}/{status}', 'updateStatus')->name('status');
        Route::get('sub-menu-list', 'sub_index')->name('sub-index');
        Route::get('sub-menu/edit', 'sub_edit')->name('sub-edit');
        Route::post('sub-menu/update', 'sub_update')->name('sub-update');
        Route::get('get-all', 'get_all')->name('get-all');
        Route::post('update-order', 'updateOrder')->name('update-order');
    });

    # Banners Management
    Route::prefix('banner')->as('banner.')->middleware(['module:banner', 'subscription:banner'])->controller(BannerController::class)->group(function () {
    // Route::group(['prefix' => 'banner', 'as' => 'banner.', 'middleware' => ['module:banner', 'subscription:banner']], function () {
        Route::get('list', 'list')->name('list');
        Route::post('store', 'store')->name('store');
        Route::get('edit/{banner}', 'edit')->name('edit');
        Route::post('update/{banner}', 'update')->name('update');
        Route::get('status/{id}/{status}', 'status_update')->name('status_update');
        Route::delete('delete/{banner}', 'delete')->name('delete');
        Route::get('join_campaign/{id}/{status}', 'status')->name('status');
        Route::get('common-banners', 'commonBanners')->name('common-banners');
        Route::post('store-from-common', 'storeFromCommon')->name('store-from-common');
    });
    Route::prefix('subscription')->as('subscriptionackage.')->middleware(['module:business_plan', 'subscription:business_plan'])->controller(SubscriptionController::class)->group(function () {
        Route::get('/subscriber-detail',  'subscriberDetail')->name('subscriberDetail');
        Route::get('/invoice/{id}',  'invoice')->name('invoice');
        Route::post('/cancel-subscription/{id}',  'cancelSubscription')->name('cancelSubscription');
        Route::post('/switch-to-commission/{id}',  'switchToCommission')->name('switchToCommission');
        Route::get('/package-view/{id}/{store_id}', 'packageView')->name('packageView');
        Route::get('/subscriber-transactions/{id}',  'subscriberTransactions')->name('subscriberTransactions');
        Route::get('/subscriber-transaction-export',  'subscriberTransactionExport')->name('subscriberTransactionExport');
        // Route::get('/subscriber-wallet-transactions',  'subscriberWalletTransactions')->name('subscriberWalletTransactions');
        Route::get('/referral-transactions',  'subscriberWalletTransactions')->name('subscriberWalletTransactions');


        Route::post('/package-buy',  'packageBuy')->name('packageBuy');
        Route::post('/add-to-session',  'addToSession')->name('addToSession');
        Route::post('/qr-payment-request',  'qrPaymentRequest')->name('qrPaymentRequest');
    });

    # Menu Order Management
    Route::prefix('menu-order')->as('menu-order.')->controller(MenuOrderController::class)->group(function () {
        Route::get('list/{status?}', 'list')->name('list');
        Route::get('details/{id}', 'details')->name('details');
        Route::get('quick-view/{id}', 'quickView')->name('quick-view');
        Route::put('status-update/{id}', 'updateStatus')->name('status-update');
        Route::get('check-new', 'checkNewOrders')->name('check-new');
        Route::post('mark-checked/{id}', 'markChecked')->name('mark-checked');
        Route::get('generate-invoice/{id}', 'generate_invoice')->name('generate-invoice');
    });

    # Business Setting Management
    Route::prefix('business-settings')->as('business-settings.')->group(function () {
        # Menu template setting
        Route::middleware(['module:notification_setup', 'subscription:notification_setup'])->controller(MenuSettingsController::class)->group(function () {
            Route::get('menu-template', 'index')->name('menu-template');
            Route::patch('menu-template/{id}', 'changeStatus')->name('menu_change_status');
            Route::post('menu-template/colors', 'saveColors')->name('menu_save_colors');
            Route::post('menu-template/colors/reset', 'resetColors')->name('menu_reset_colors');
        });
        # QR Management
        Route::middleware(['module:notification_setup', 'subscription:notification_setup'])->controller(QrSettingsController::class)->group(function () {
            Route::get('qr-setup', 'index')->name('qr-setup');
            Route::post('qr-setup/generate', 'generateQr')->name('generate-qr');
            Route::post('qr-setup/change-template', 'changeTemplate')->name('change-template');
            Route::patch('qr-setup/{id}', 'changeStatus')->name('change_status');
            Route::get('qr-setup/download-pdf', 'downloadPdf')->name('download-qr-pdf');
            Route::post('qr-setup/update-food-images', 'updateFoodImages')->name('update-food-images');
            Route::post('qr-setup/generate-table-qr', 'generateTableQr')->name('generate-table-qr');
            Route::delete('qr-setup/delete-table-qr/{id}', 'deleteTableQr')->name('delete-table-qr');
        });

        Route::middleware(['module:store_setup', 'subscription:store_setup'])->controller(BusinessSettingsController::class)->group(function () {
            Route::get('banner-popup', 'store_index')->name('banner-popup');
            Route::post('add-schedule', 'add_schedule')->name('add-schedule');
            Route::get('remove-schedule/{store_schedule}', 'remove_schedule')->name('remove-schedule');
            Route::get('update-active-status', 'active_status')->name('update-active-status');
            Route::post('update-setup/{store}', 'store_setup')->name('update-setup');
            Route::post('update-meta-data/{store}', 'updateStoreMetaData')->name('update-meta-data');
            Route::get('toggle-settings-status/{store}/{status}/{menu}', 'store_status')->name('toggle-settings');

            Route::get('/menu-template-csutomize',  'menu_template_cusomize')->name('menu-template-customize');
            Route::post('menu-template-csutomize-update/{store}', 'menu_template_cusomize_update')->name('menu-template-cusomize-update');

        });

        Route::middleware(['module:notification_setup', 'subscription:notification_setup'])->controller(BusinessSettingsController::class)->group(function () {
            Route::get('notification-setup', 'notification_index')->name('notification-setup');
            Route::get('notification-status-change/{key}/{type}', 'notification_status_change')->name('notification_status_change');
        });
    });

    Route::prefix('store')->as('shop.')->middleware(['module:my_shop', 'subscription:my_shop'])->controller(RestaurantController::class)->group(function () {
        Route::get('view', 'view')->name('view');
        Route::get('edit', 'edit')->name('edit');
        Route::post('update', 'update')->name('update');
        Route::post('update-message', 'update_message')->name('update-message');
    });

    # Today Special Management
    Route::prefix('today-special')->as('today-special.')->controller(TodaySpecialController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('store', 'store')->name('store');
        Route::delete('delete/{id}', 'destroy')->name('destroy');
        Route::get('status/{id}/{status}', 'status')->name('status');
    });
    # Customers
    Route::prefix('customers')->as('customers.')->controller(CustomerController::class)->group(function () {
        Route::get('/', 'index')->name('index');
    });

    # Career Jobs Management
    Route::prefix('career-jobs')->as('career-jobs.')->controller(CareerJobController::class)->group(function () {
        Route::get('list', 'list')->name('list');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update/{id}', 'update')->name('update');
        Route::get('status/{id}/{status}', 'status')->name('status');
        Route::delete('delete/{id}', 'delete')->name('delete');
        Route::get('applications/{id}', 'applications')->name('applications');
        Route::post('application-status/{id}', 'applicationStatus')->name('application-status');
        Route::delete('application-delete/{id}', 'applicationDelete')->name('application-delete');
        Route::get('download-resume/{id}', 'downloadResume')->name('download-resume');
    });

    Route::get('lang/{locale}', [LanguageController::class, 'lang'])->name('lang');

    # Kitchen Staff Management
    Route::prefix('kitchen-staff')->as('kitchen-staff.')->controller(KitchenStaffController::class)->group(function () {
        Route::get('/', 'index')->name('list');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::delete('/delete/{id}', 'destroy')->name('destroy');
    });

    Route::prefix('profile')->as('profile.')->middleware(['module:profile', 'subscription:profile'])->controller(ProfileController::class)->group(function () {
        Route::get('view', 'view')->name('view');
        Route::post('update', 'update')->name('update');
        Route::post('settings-password', 'settings_password_update')->name('settings-password');
    });
});
