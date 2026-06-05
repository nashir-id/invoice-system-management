<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Update status invoice yang sudah overdue
        Invoice::unpaid()
            ->whereNotNull('due_date')
            ->where('due_date', '<', now())
            ->update(['status' => 'overdue']);

        // Statistik bulan ini
        $stats = [
            'total_invoices'    => Invoice::whereMonth('invoice_date', now()->month)
                                         ->whereYear('invoice_date', now()->year)
                                         ->count(),
            'total_paid'        => Invoice::paid()
                                         ->whereMonth('invoice_date', now()->month)
                                         ->whereYear('invoice_date', now()->year)
                                         ->sum('total'),
            'total_outstanding' => Invoice::unpaid()->sum('total'),
            'total_overdue'     => Invoice::overdue()->count(),
        ];

        // 10 invoice terbaru
        $recentInvoices = Invoice::with(['client'])
            ->latest('invoice_date')
            ->limit(10)
            ->get();

        return view('dashboard.index', compact('stats', 'recentInvoices'));
    }
}