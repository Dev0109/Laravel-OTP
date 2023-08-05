<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::get('/home', 'Admin\HomeController@home');


Route::get('/otp/verify', 'OTPVerificationController@showVerificationForm')->name('otp.verify');
Route::post('/otp/sendverification', 'OTPVerificationController@sendVerification')->name('otp.sendverification');
Route::post('/otp/verifycode', 'OTPVerificationController@verifyCode')->name('otp.verifycode');
Route::get('/captcha', 'CaptchaController@index')->name('captcha');
Route::post('/captcha/verify', 'CaptchaController@captchaVerify')->name('captcha.verify');

Auth::routes(['register' => true]);
// Admin

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth', 'check.session']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('dashboard-test/{uid?}', 'HomeController@indexTest')->name('dashboard-test');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');
    Route::get('permissions-test/{uid?}', 'PermissionsController@indexTest')->name('permissions-test');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');
    Route::get('roles-test/{uid?}', 'RolesController@indexTest')->name('roles-test');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');
    Route::post('users/add/deliveryaddress', 'UsersController@addDeliveryAddress')->name('users.add.DeliveryAddress');
    Route::post('users/add/deliverycondition', 'UsersController@addDeliveryCondition')->name('users.add.DeliveryCondition');
    Route::get('test-users', 'UsersController@userTest')->name('users.test');
    Route::post('test-users', 'UsersController@userTest')->name('users.test');
    Route::get('users-test/{uid?}', 'UsersController@indexTest')->name('users-test');
    Route::get('users/verify-test/{uid?}', 'UsersController@verifyTest')->name('users.verify-test');

    // Scooters
    Route::delete('scooters/destroy', 'ScootersController@massDestroy')->name('scooters.massDestroy');
    Route::resource('scooters', 'ScootersController');
    Route::resource('pricings', 'PricingsController');
    Route::resource('pricemanage', 'PricemanageController');
    Route::resource('pricetype', 'PricetypeController');
    Route::resource('pricecompetitor', 'PricecompetitorController');
    Route::resource('language', 'LanguageController');
    Route::get('pricetype-test/{uid?}', 'PricetypeController@indexTest')->name('pricetype-test');
    Route::get('pricings-test/{uid?}', 'PricingsController@indexTest')->name('pricings-test');
    Route::get('pricemanage-test/{uid?}', 'PricemanageController@indexTest')->name('pricemanage-test');
    Route::get('pricecompetitor-test/{uid?}', 'PricecompetitorController@indexTest')->name('pricecompetitor-test');
    Route::get('pricecompares/allcom-test/{uid?}', 'PricecompareController@allcompetitorsTest')->name('pricecompares.allcom-test');
    Route::get('language-test/{uid?}', 'LanguageController@indexTest')->name('language-test');
    
    Route::get('languages/{id}', 'LanguageController@langedit')->name('language.langedit');
    Route::post('languages/store/key/{id}', 'LanguageController@storeLanguageJson')->name('languages.store.key');
    Route::post('languages/delete/{id}', 'LanguageController@deleteLanguageJson')->name('languages.key.delete');
    Route::post('languages/edit/{id}', 'LanguageController@updateLanguageJson')->name('languages.key.edit');
    Route::post('languages/import/{id}', 'LanguageController@importLanguageJson')->name('languages.key.import');

    Route::get('languages/change/{lang?}', 'LanguageController@changeLanguage')->name('lang');

    Route::get('pricecompare/{id}', 'PricecompareController@index')->name('pricecompare-filter');
    Route::get('pricecompare/{pid}/create/{id}', 'PricecompareController@create')->name('pricecompare.create');
    Route::post('pricecompare/store', 'PricecompareController@store')->name('pricecompare.store');
    Route::get('pricecompare/{pid}/edit/{id}', 'PricecompareController@edit')->name('pricecompare.edit');
    Route::post('pricecompare/update', 'PricecompareController@update')->name('pricecompare.update');
    Route::get('pricecompare/{pid}/show/{id}', 'PricecompareController@show')->name('pricecompare.show');
    Route::get('pricecompare/destroy/{id}', 'PricecompareController@destroy')->name('pricecompare.destroy');
    Route::get('pricecompares/allcom', 'PricecompareController@allcompetitors')->name('pricecompares.allcom');
    Route::post('pricecompares/exportpdf', 'PricecompareController@generatePDF')->name('pricecompares.exportpdf');
    Route::get('pricecompares/viewforpdf', 'PricecompareController@viewForPDF')->name('pricecompares.viewforpdf');
    Route::post('pricecompares/onestore', 'PricecompareController@onestore')->name('pricecompares.onestore');

    Route::post('scooters-import', 'ScootersController@import')->name('scooters-import');
    Route::get('scooter-excel', 'ScootersController@excelimport')->name('scooters-excel');
    Route::get('scooter-pdf/{id}', 'ScootersController@generatePDF')->name('scooter-pdf');
    // Route::post('scooters/{id}', 'ScootersController@uploadSIGN')->name('scooter-sign');
    
    Route::get('scooter/{id}', 'ScootersController@filterList')->name('scooters-filter');
    Route::post('scooters/deleteAll', 'ScootersController@deleteAll')->name('scooters.deleteAll');
    Route::get('scooter-test/{uid?}/{id?}', 'ScootersController@filterListTest')->name('scooters-filter-test');
    Route::get('scooters-test/{uid?}', 'ScootersController@indexTest')->name('scooters-test');

    Route::get('history', 'HistoryController@index')->name('history');

    // Projects management routes
    Route::get('projects', 'ProjectsController@index')->name('projects');
    Route::get('projects/profile/{pid?}/{cid?}/{uid?}', 'ProjectsController@profile')->name('projects.profile');
    Route::get('projects/detail/{pid?}/{cid?}/{uid?}', 'ProjectsController@detail')->name('projects.detail');
    Route::get('projects/get/models', 'ProjectsController@get_models')->name('projects.get.models');
    Route::get('projects/get/completedata', 'ProjectsController@get_completedata')->name('projects.get.completedata');
    Route::get('projects/get/contactlist', 'ProjectsController@get_contact_list')->name('projects.get.contactlist');
    Route::get('projects/get/modelprice', 'ProjectsController@get_model_price')->name('projects.get.modelprice');
    Route::post('projects/store/contact', 'ProjectsController@store_contact')->name('projects.store.contact');
    Route::post('projects/delete/contact/{id?}', 'ProjectsController@delete_contact')->name('projects.delete.contact');
    Route::post('projects/store/project', 'ProjectsController@save_project')->name('projects.store.project');
    Route::post('projects/delete/project', 'ProjectsController@delete_project')->name('projects.delete.project');
    Route::post('projects/duplicate/project', 'ProjectsController@duplicate_project')->name('projects.duplicate.project');
    Route::post('projects/status/change', 'ProjectsController@status_change')->name('projects.status.change');
    Route::post('projects/save/company', 'ProjectsController@save_company')->name('projects.save.company');
    Route::post('projects/delete/company', 'ProjectsController@delete_company')->name('projects.delete.company');
    Route::get('projects/job', 'ProjectsController@job')->name('projects.job');
    Route::post('projects/job/save', 'ProjectsController@store_job')->name('projects.store.job');
    Route::post('projects/job/delete', 'ProjectsController@delete_job')->name('projects.delete.job');
    Route::get('projects/job-test/{uid?}', 'ProjectsController@jobTest')->name('projects.job-test');

    // Customer Manager routes
    Route::get('customer', 'CustomerController@index')->name('customer');
    Route::get('customer/get/contactlist', 'CustomerController@get_contact_list')->name('customer.get.contactlist');
    Route::post('customer/save/company', 'CustomerController@save_company')->name('customer.save.company');
    Route::post('customer/delete/company', 'CustomerController@delete_company')->name('customer.delete.company');
    Route::post('customer/store/contact', 'CustomerController@store_contact')->name('customer.store.contact');
    Route::post('customer/delete/contact/{id?}', 'CustomerController@delete_contact')->name('customer.delete.contact');


    // Settings management routes
    Route::get('settings', 'SettingsController@index')->name('settings');
    Route::post('settings/logo', 'SettingsController@logo_update')->name('settings.logo.update');
    Route::post('settings/save/seller', 'SettingsController@save_seller')->name('settings.save.seller');
    Route::post('settings/save/version', 'SettingsController@save_version')->name('settings.save.version');
    Route::get('settings-test/{uid?}', 'SettingsController@indexTest')->name('settings-test');

    // Scooterstatuses
    Route::delete('scooter-statuses/destroy', 'ScooterStatusController@massDestroy')->name('scooter-statuses.massDestroy');
    Route::resource('scooter-statuses', 'ScooterStatusController');
});

Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
// Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::get('password-test/{uid?}', 'ChangePasswordController@editTest')->name('password.edit-test');
    }
});

