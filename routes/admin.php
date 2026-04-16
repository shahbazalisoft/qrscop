<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\BusinessSettingsController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmailSettingsController;
use App\Http\Controllers\Admin\FileManagerController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\MenuSettingController;
use App\Http\Controllers\Admin\SystemController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\Setting\ThirdPartySettingsController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\CommonBannerController;
use App\Http\Controllers\Admin\CareerJobController;
use App\Http\Controllers\Admin\MenuOrderController;
use App\Http\Controllers\Admin\Subscription\SubscriptionController;

Route::middleware(['web', 'admin'])->prefix('admin')->as('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('profile', [SystemController::class, 'settings'])->name('profile');
    
    Route::post('profile', [SystemController::class, 'settings_update']);
    Route::post('settings-password', [SystemController::class, 'settings_password_update'])->name('settings-password');
    Route::get('system-currency', [SystemController::class, 'system_currency'])->name('system_currency');
    Route::get('maintenance-mode', [SystemController::class, 'maintenance_mode'])->name('maintenance-mode');

    Route::prefix('item')->as('item.')->middleware(['module:item'])->controller(ItemController::class)->group(function () {
        Route::get('add-new', 'index')->name('add-new');
        Route::post('store', 'store')->name('store');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update/{id}', 'update')->name('update');
        Route::get('list', 'list')->name('list');
        Route::delete('delete/{id}', 'delete')->name('delete');
        Route::get('status/{id}/{status}', 'status')->name('status');
        Route::post('search', 'search')->name('search');
        Route::post('store/{store_id}/search', 'search_store')->name('store-search');
        // Route::get('remove-image', 'remove_image')->name('remove-image');
        Route::get('view/{id}', 'view')->name('view');
        Route::get('store-item-export', 'store_item_export')->name('store-item-export');
        Route::get('reviews-export', 'reviews_export')->name('reviews_export');
        Route::get('item-wise-reviews-export', 'item_wise_reviews_export')->name('item_wise_reviews_export');
        Route::post('variant-combination', 'variant_combination')->name('variant-combination');
        // Route::get('product-gallery', 'product_gallery')->name('product_gallery');

        //Import and export
        Route::get('bulk-import', 'bulk_import_index')->name('bulk-import');
        Route::post('bulk-import', 'bulk_import_data');
        Route::get('bulk-export', 'bulk_export_index')->name('bulk-export-index');
        Route::post('bulk-export', 'bulk_export_data')->name('bulk-export');

        //ajax request
        Route::get('get-categories', 'get_categories')->name('get-categories');
        Route::get('get-items', 'get_items')->name('getitems');
        Route::get('get-items-flashsale', 'get_items_flashsale')->name('getitems-flashsale');
        Route::post('food-variation-generate', 'food_variation_generator')->name('food-variation-generate');
        Route::post('variation-generate', 'variation_generator')->name('variation-generate');


        Route::get('export', 'export')->name('export');

        //Mainul
        Route::get('get-variations', 'get_variations')->name('get-variations');
        Route::post('stock-update', 'stock_update')->name('stock-update');

        //Import and export
        Route::get('bulk-import', 'bulk_import_index')->name('bulk-import');
        Route::post('bulk-import', 'bulk_import_data');
        Route::get('bulk-export', 'bulk_export_index')->name('bulk-export-index');
        Route::post('bulk-export', 'bulk_export_data')->name('bulk-export');
    });
    Route::prefix('item-gallery')->as('gallery.')->controller(FileManagerController::class)->group(function () {
        Route::get('/index/{folder_path?}', 'index')->name('index');
        Route::get('/api/{folder_path?}', 'apiIndex')->name('api');
        Route::get('/download/{file_name}', 'download')->name('download');
        Route::post('/image-upload', 'upload')->name('image-upload');
        Route::delete('/delete/{file_path}', 'destroy')->name('destroy');
    });

    # Menu Management
    Route::group(['prefix' => 'menu', 'as' => 'category.'], function () {
        Route::get('list', [CategoryController::class, 'index'])->name('list');
        Route::post('store', [CategoryController::class, 'store'])->name('store');
        Route::get('edit/{id}', [CategoryController::class, 'edit'])->name('edit');
        Route::post('update/{id}', [CategoryController::class, 'update'])->name('update');
        Route::get('status/{id}/{status}', [CategoryController::class, 'status'])->name('status');
        Route::get('get-all', [CategoryController::class, 'get_all'])->name('get-all');
        Route::get('priority', [CategoryController::class, 'priority'])->name('priority');
        Route::delete('delete/{id}', [CategoryController::class, 'delete'])->name('delete');
    });
    # Restuarant Management
    Route::prefix('store')->as('store.')->group(function () {

        Route::middleware(['module:store'])->controller(VendorController::class)->group(function () {
            Route::get('update-application/{id}/{status}', 'update_application')->name('application');
            Route::get('add', 'index')->name('add');
            Route::post('store', 'store')->name('store');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::post('update/{store:id}', 'update')->name('update');
            Route::post('update-settings/{store}', 'pdateStoreSettings')->name('update-settings');
            Route::post('update-meta-data/{store}', 'updateStoreMetaData')->name('update-meta-data');
            Route::delete('delete/{store:id}', 'destroy')->name('delete');
            Route::get('view/{store}/{tab?}/{sub_tab?}', 'view')->name('view');
            Route::get('list', 'list')->name('list');
            Route::get('status/{store:id}/{status}', 'status')->name('status');
            Route::get('get-stores', 'get_stores')->name('get-stores');
        });
    });
    Route::prefix('menu-order')->as('menu-order.')->controller(MenuOrderController::class)->group(function () {
        Route::get('list/{status?}', 'list')->name('list');
        Route::get('details/{id}', 'details')->name('details');
        Route::get('quick-view/{id}', 'quickView')->name('quick-view');
        Route::put('status-update/{id}', 'updateStatus')->name('status-update');
    });

    Route::prefix('menu-templates')->as('menu-templates.')->group(function () {
        Route::get('', [MenuSettingController::class, 'index'])->name('list');
        Route::post('store', [MenuSettingController::class, 'store'])->name('store');
        Route::get('edit/{id}', [MenuSettingController::class, 'edit'])->name('edit');
        Route::post('update/{id}', [MenuSettingController::class, 'update'])->name('update');
        Route::delete('delete/{id}', [MenuSettingController::class, 'distroy'])->name('delete');
        Route::get('status/{id}/{status}', [MenuSettingController::class, 'updateStatus'])->name('status');
        Route::get('update-priority/{template}', [MenuSettingController::class, 'update_priority'])->name('priority');
    });

    # Setting
    
    Route::prefix('settings')->as('settings.')->group(function () {
        Route::controller(SettingController::class)->group(function () {
            Route::get('', 'index')->name('index');
        });
        Route::prefix('general')->as('general.')->controller(BusinessSettingsController::class)->group(function () {
            Route::get('', 'business_index')->name('edit');
            Route::post('update-setup', 'business_setup')->name('update');
            Route::get('website-appearance', 'website_appearance')->name('website-appearance');
            Route::post('website-appearance-update', 'website_appearance_update')->name('website-appearance-update');

            Route::get('clear-cache', 'clear_cache')->name('clear_cache');
        });
        Route::prefix('email')->as('email.')->controller(EmailSettingsController::class)->group(function () {
            Route::get('config', 'index')->name('index');
            Route::post('config', 'mail_config')->name('mail_config');
            Route::post('mail-config-status', 'config_status')->name('config_status');
            Route::get('test', 'test_mail')->name('test');
            Route::get('send-mail', 'send_mail')->name('send');
            #Admin Template
            Route::get('admin-template/{type}', 'admin_email_index')->name('admin_template');
            Route::post('admin-template/{type}', 'admin_email_update')->name('admin_template');
            Route::get('email-status/{type}/{tab}/{status}', 'update_email_status')->name('email-status');

            #Vendor Template
            Route::get('vendor-template/{type}', 'vendor_email_index')->name('vendor_template');
            Route::post('vendor-template/{type}', 'vendor_email_update')->name('vendor_template');
        });
        #Third Party Management
        Route::prefix('third-party')->as('third-party.')->controller(ThirdPartySettingsController::class)->group(function () {
            Route::get('recaptcha', 'recaptcha_index')->name('recaptcha_index');
            Route::post('recaptcha-update', 'recaptcha_update')->name('recaptcha_update');

            //Google Analytics
            Route::get('analytics', 'analytics_index')->name('analytics_index');
            Route::post('analytics-update', 'analytics_update')->name('analytics_update');

       
            Route::get('sms-module', 'smsModule')->name('sms_module');
            Route::post('sms-module-update/{sms_module}', 'sms_update')->name('sms-module-update');

            Route::get('social-login', 'viewSocialLogin')->name('social_login_index');
            Route::post('social-login/update/{service}', 'updateSocialLogin')->name('social_login_update');
            Route::post('apple-login/update/{service}', 'updateAppleLogin')->name('apple_login_update');

            Route::get('payment-method', 'payment_index')->name('payment-method');
            Route::post('payment-method-update', 'payment_config_update')->name('payment-method-update');
        });
        
        # Subscription Mamagement
        Route::prefix('subscription')->as('subscription.')->controller(SubscriptionController::class)->group(function () {
            Route::get('subscriptionackage', 'index')->name('subscriptionackage.index');
            Route::get('subscriptionackage/create', 'create')->name('subscriptionackage.create');
            Route::post('subscriptionackage/create', 'store')->name('subscriptionackage.store');
            Route::get('subscriptionackage/status/{subscriptionackage}', 'statusChange')->name('subscriptionackage.status');
            Route::get('subscriptionackage/edit/{subscriptionackage}', 'edit')->name('subscriptionackage.edit');
            Route::put('subscriptionackage/update/{subscriptionackage}', 'update')->name('subscriptionackage.update');
            Route::get('subscriptionackage/{subscriptionackage}', 'show')->name('subscriptionackage.show');
            Route::get('subscriptionackage/switchplan', 'switchPlan')->name('subscriptionackage.switchPlan');
            Route::get('subscriptionackage/transaction/{subscriptionackage}', 'transaction')->name('subscriptionackage.transaction');
            Route::get('subscriptionackage/overview/{subscriptionackage}', 'overView')->name('subscriptionackage.overView');
            Route::get('subscriptionackage/invoice/{subscriptionackage}', 'invoice')->name('subscriptionackage.invoice');

            # Subscriber Management
            Route::get('/subscriber-list',  'subscriberList')->name('subscriptionackage.subscriberList');
            Route::get('/subscriber-list-export',  'subscriberListExport')->name('subscriptionackage.subscriberListExport');
            Route::get('/subscriber-transaction-export',  'subscriberTransactionExport')->name('subscriptionackage.subscriberTransactionExport');
            Route::post('/cancel-subscription/{id}',  'cancelSubscription')->name('subscriptionackage.cancelSubscription');
            Route::post('/switch-to-commission/{id}', 'switchToCommission')->name('subscriptionackage.switchToCommission');
            Route::get('/subscriber-detail/{id}', 'subscriberDetail')->name('subscriptionackage.subscriberDetail');
            Route::get('/package-view/{id}/{store_id}', 'packageView')->name('subscriptionackage.packageView');
            Route::get('/subscriber-transactions/{id}', 'subscriberTransactions')->name('subscriptionackage.subscriberTransactions');
            Route::get('/subscriber-wallet-transactions/{id}', 'subscriberWalletTransactions')->name('subscriptionackage.subscriberWalletTransactions');
            # Setting
            Route::get('settings', 'settings')->name('subscriptionackage.settings');
            Route::get('/trial-status', 'trialStatus')->name('subscriptionackage.trialStatus');
            Route::post('/setting-update', 'settingUpdate')->name('subscriptionackage.settingUpdate');
            # QR Payment Requests
            Route::get('/qr-payment-requests', 'qrPaymentRequests')->name('subscriptionackage.qrPaymentRequests');
            Route::post('/qr-payment-request-action/{id}', 'qrPaymentRequestAction')->name('subscriptionackage.qrPaymentRequestAction');
            Route::post('/qr-payment-settings', 'qrPaymentSettingsUpdate')->name('subscriptionackage.qrPaymentSettingsUpdate');
        });
    });
    Route::prefix('common-banner')->as('common-banner.')->controller(CommonBannerController::class)->group(function () {
        Route::get('list', 'list')->name('list');
        Route::post('store', 'store')->name('store');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update/{common_banner}', 'update')->name('update');
        Route::get('status-update/{id}/{status}', 'status_update')->name('status_update');
        Route::delete('delete/{common_banner}', 'delete')->name('delete');
    });

    Route::prefix('notifications')->as('notifications.')->controller(AdminNotificationController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/get', 'getNotifications')->name('get');
        Route::post('/mark-read/{id}', 'markAsRead')->name('mark-read');
        Route::post('/mark-all-read', 'markAllAsRead')->name('mark-all-read');
    });

    Route::prefix('users/contact')->as('users.contact.')->controller(ContactController::class)->group(function () {
        Route::get('list', 'list')->name('list');
        Route::get('view/{id}', 'view')->name('contact-view');
        Route::post('update/{id}', 'update')->name('contact-update');
        Route::post('send-mail/{id}', 'sendMail')->name('contact-send-mail');
        Route::delete('delete/{id}', 'delete')->name('contact-delete');
        Route::get('export', 'exportList')->name('exportList');
    });

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

    Route::prefix('business-settings')->as('business-settings.')->middleware(['module:settings'])->controller(BusinessSettingsController::class)->group(function () {
        

        Route::prefix('language')->as('language.')->controller(LanguageController::class)->group(function () {
            Route::get('', 'index')->name('index');
            Route::post('add-new', 'store')->name('add-new');
            Route::get('update-status', 'update_status')->name('update-status');
            Route::get('update-default-status', 'update_default_status')->name('update-default-status');
            Route::post('update', 'update')->name('update');
            Route::get('translate/{lang}', 'translate')->name('translate');
            Route::post('translate-submit/{lang}', 'translate_submit')->name('translate-submit');
            Route::post('remove-key/{lang}', 'translate_key_remove')->name('remove-key');
            Route::get('delete/{lang}', 'delete')->name('delete');
            Route::any('auto-translate/{lang}', 'auto_translate')->name('auto-translate');
            Route::get('auto-translate-all/{lang}', 'auto_translate_all')->name('auto_translate_all');
        });
    });
});
