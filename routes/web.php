<?php

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
use Illuminate\Support\Facades\Route;


Route::get('/', fn() => redirect()->route('login'));
Route::get('invoice/public/{token}', [InvoiceController::class, 'publicView'])
    ->name('invoice.public');


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Profile (bawaan Breeze — semua role)
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    
    // OWNER + ADMIN — buat & kelola invoice, klien, voucher, laporan
    Route::middleware(['role:owner,admin'])->group(function () {

        // Klien
        Route::resource('clients', ClientController::class);
        Route::patch('clients/{client}/activate', [ClientController::class, 'activate'])
            ->name('clients.activate');
        Route::get('clients/{client}/data', [ClientController::class, 'getData'])
            ->name('clients.getData'); // AJAX untuk auto-fill form invoice

        // Invoice
        Route::resource('invoices', InvoiceController::class);
        Route::get('invoices/{invoice}/download',     [InvoiceController::class, 'download'])
            ->name('invoices.download'); 
        Route::post('invoices/{invoice}/send-email',  [InvoiceController::class, 'sendEmail'])
            ->name('invoices.send-email'); 
        Route::get('invoices/{invoice}/whatsapp',     [InvoiceController::class, 'whatsappLink'])
            ->name('invoices.whatsapp');
        Route::post('invoices/{invoice}/duplicate',   [InvoiceController::class, 'duplicate'])
            ->name('invoices.duplicate');

        //Pembayaran (tandai lunas)
        Route::post('invoices/{invoice}/pay', [PaymentController::class, 'store'])
            ->name('invoices.pay');

        // Voucher
        Route::resource('vouchers', VoucherController::class);          
        Route::post('vouchers/validate-code', [VoucherController::class, 'validateCode'])
            ->name('vouchers.validate');

        // Recurring Template 
        Route::resource('recurring-templates', RecurringTemplateController::class); 
        Route::post('recurring-templates/{template}/pause',  [RecurringTemplateController::class, 'pause'])
            ->name('recurring-templates.pause');
        Route::post('recurring-templates/{template}/resume', [RecurringTemplateController::class, 'resume'])
            ->name('recurring-templates.resume');

        // Laporan
        Route::get('/reports',               [ReportController::class, 'index'])
            ->name('reports.index');         
        Route::get('/reports/export-excel',  [ReportController::class, 'exportExcel'])
            ->name('reports.export-excel');
        Route::get('/reports/export-csv',    [ReportController::class, 'exportCsv'])
            ->name('reports.export-csv');
    });

    
    // OWNER ONLY — pengaturan sistem & manajemen user
    Route::middleware(['role:owner'])->group(function () {

        // Manajemen User 
        Route::resource('users', UserController::class)->except(['show']); 
        Route::post('users/{user}/toggle-active', [UserController::class, 'toggleActive'])
            ->name('users.toggle-active');

        // Pengaturan Sistem 
        Route::get('/settings',  [SettingController::class, 'index'])
            ->name('settings.index');        
        Route::put('/settings',  [SettingController::class, 'update'])
            ->name('settings.update');
        Route::get('/settings/audit-log', [SettingController::class, 'auditLog'])
            ->name('settings.audit-log');
    });
    
    Route::middleware(['role:owner'])->group(function () {
    Route::delete('invoices/{invoice}/pay', [PaymentController::class, 'destroy'])
        ->name('invoices.pay.cancel');
});
    
});

require __DIR__.'/auth.php';