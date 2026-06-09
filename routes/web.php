<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\RecurringTemplateController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;

/*
|--------------------------------------------------------------------------
| Public Route
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => view('welcome'))
    ->name('home');

Route::get('invoice/public/{token}', [
    InvoiceController::class,
    'publicView'
])->name('invoice.public');

/*
|--------------------------------------------------------------------------
| Authenticated Route
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [
        DashboardController::class,
        'index'
    ])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [
        ProfileController::class,
        'edit'
    ])->name('profile.edit');

    Route::patch('/profile', [
        ProfileController::class,
        'update'
    ])->name('profile.update');

    Route::delete('/profile', [
        ProfileController::class,
        'destroy'
    ])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | OWNER + ADMIN
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:owner,admin'])->group(function () {

    

        /*
        |--------------------------------------------------------------------------
        | Client
        |--------------------------------------------------------------------------
        */

        Route::resource('clients', ClientController::class);

        Route::patch(
            'clients/{client}/activate',
            [ClientController::class, 'activate']
        )->name('clients.activate');

        Route::get(
            'clients/{client}/data',
            [ClientController::class, 'getData']
        )->name('clients.getData');

        /*
        |--------------------------------------------------------------------------
        | Invoice
        |--------------------------------------------------------------------------
        */

        Route::resource('invoices', InvoiceController::class);

        Route::get(
            'invoices/{invoice}/download',
            [InvoiceController::class, 'download']
        )->name('invoices.download');

        Route::post(
            'invoices/{invoice}/send-email',
            [InvoiceController::class, 'sendEmail']
        )->name('invoices.send-email');

        Route::get(
            'invoices/{invoice}/whatsapp',
            [InvoiceController::class, 'whatsappLink']
        )->name('invoices.whatsapp');

        Route::post(
            'invoices/{invoice}/duplicate',
            [InvoiceController::class, 'duplicate']
        )->name('invoices.duplicate');

        /*
        |--------------------------------------------------------------------------
        | Payment
        |--------------------------------------------------------------------------
        */

        // Route::post(
        //     'invoices/{invoice}/pay',
        //     [PaymentController::class, 'store']
        // )->name('invoices.pay');


       Route::get(
'/invoices/{invoice}/payment/{bank}',
[InvoiceController::class, 'paymentPage']
)->name('invoices.payment.page');

    Route::post(
'/invoices/{invoice}/payment/{bank}',
[PaymentController::class, 'store']
)->name('invoices.submit.transfer');

    Route::post(
'/payments/{payment}/approve',
[PaymentController::class, 'approve']
)->name('payments.approve');

   Route::post(
'/payments/{payment}/reject',
[PaymentController::class, 'reject']
)->name('payments.reject');

Route::delete(
'invoices/{invoice}/pay',
[PaymentController::class, 'destroy']
)->name('invoices.pay.cancel');


Route::middleware(['role:owner,admin'])->group(function () {

    Route::get(
        '/payments/verifications',
        [PaymentController::class,'verificationIndex']
    )->name('payments.verifications');

    Route::get(
        '/payments/{payment}',
        [PaymentController::class,'show']
    )->name('payments.show');

});
        /*
        |--------------------------------------------------------------------------
        | Voucher
        |--------------------------------------------------------------------------
        */

        Route::get(
            'vouchers/check',
            [VoucherController::class, 'check']
        )->name('vouchers.check');

        Route::post(
            'vouchers/validate-code',
            [VoucherController::class, 'validateCode']
        )->name('vouchers.validate');

        Route::resource('vouchers', VoucherController::class);

        /*
        |--------------------------------------------------------------------------
        | Recurring Template
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'recurring-templates',
            RecurringTemplateController::class
        );

        Route::post(
            'recurring-templates/{template}/pause',
            [RecurringTemplateController::class, 'pause']
        )->name('recurring-templates.pause');

        Route::post(
            'recurring-templates/{template}/resume',
            [RecurringTemplateController::class, 'resume']
        )->name('recurring-templates.resume');

        /*
        |--------------------------------------------------------------------------
        | Report
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/reports',
            [ReportController::class, 'index']
        )->name('reports.index');

        Route::get(
            '/reports/export-excel',
            [ReportController::class, 'exportExcel']
        )->name('reports.export-excel');

        Route::get(
            '/reports/export-csv',
            [ReportController::class, 'exportCsv']
        )->name('reports.export-csv');
    });

    /*
    |--------------------------------------------------------------------------
    | OWNER ONLY
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:owner'])->group(function () {

        /*
        |--------------------------------------------------------------------------
        | User Management
        |--------------------------------------------------------------------------
        */

        Route::resource('users', UserController::class)
            ->except(['show']);

        Route::post(
            'users/{user}/toggle-active',
            [UserController::class, 'toggleActive']
        )->name('users.toggle-active');

        /*
        |--------------------------------------------------------------------------
        | System Settings
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/settings',
            [SettingController::class, 'index']
        )->name('settings.index');

        Route::put(
            '/settings',
            [SettingController::class, 'update']
        )->name('settings.update');

        /*
        |--------------------------------------------------------------------------
        | Audit Log
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/settings/audit-log',
            [SettingController::class, 'auditLog']
        )->name('settings.audit-log');

        /*
        |--------------------------------------------------------------------------
        | Cancel Payment
        |--------------------------------------------------------------------------
        */

        Route::delete(
            'invoices/{invoice}/pay',
            [PaymentController::class, 'destroy']
        )->name('invoices.pay.cancel');
    });
});

/*
|--------------------------------------------------------------------------
| Authentication Route
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';
