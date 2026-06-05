<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $year  = $request->input('year',  now()->year);
        $month = $request->input('month', null);

        // ── Statistik utama ──────────────────────────────────────
        $totalInvoices = Invoice::whereYear('invoice_date', $year)->count();
        $totalRevenue  = Invoice::whereYear('invoice_date', $year)->paid()->sum('total');
        $totalUnpaid   = Invoice::whereYear('invoice_date', $year)->unpaid()->sum('total');
        $totalOverdue  = Invoice::whereYear('invoice_date', $year)->overdue()->count();

        // ── Pendapatan per bulan (untuk chart) ───────────────────
        $monthlyRevenue = Invoice::paid()
            ->whereYear('invoice_date', $year)
            ->select(
                DB::raw('MONTH(invoice_date) as month'),
                DB::raw('SUM(total) as total')
            )
            ->groupBy(DB::raw('MONTH(invoice_date)'))
            ->orderBy('month')
            ->pluck('total', 'month');

        // Pastikan semua 12 bulan ada (isi 0 jika tidak ada data)
        $chartData = collect(range(1, 12))->mapWithKeys(
            fn($m) => [$m => $monthlyRevenue->get($m, 0)]
        );

        // ── Top 5 klien berdasarkan total invoice ────────────────
        $topClients = Invoice::paid()
            ->whereYear('invoice_date', $year)
            ->select('client_id', DB::raw('SUM(total) as total_revenue'), DB::raw('COUNT(*) as invoice_count'))
            ->with('client:id,company_name')
            ->groupBy('client_id')
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get();

        // ── Invoice terbaru ──────────────────────────────────────
        $recentInvoices = Invoice::with('client')
            ->latest('invoice_date')
            ->limit(8)
            ->get();

        // ── Daftar tahun untuk filter ────────────────────────────
        $years = Invoice::selectRaw('YEAR(invoice_date) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        if ($years->isEmpty()) {
            $years = collect([now()->year]);
        }

        return view('reports.index', compact(
            'year', 'month',
            'totalInvoices', 'totalRevenue', 'totalUnpaid', 'totalOverdue',
            'chartData', 'topClients', 'recentInvoices', 'years'
        ));
    }

    public function exportExcel(Request $request)
    {
        [$startDate, $endDate] = $this->weeklyReportRange(
            $request->filled('date') ? Carbon::parse($request->input('date')) : null
        );

        $invoices = Invoice::with(['client:id,company_name', 'payment'])
            ->paid()
            ->whereBetween('invoice_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->orderBy('invoice_date')
            ->orderBy('invoice_number')
            ->get();

        $summary = [
            'subtotal' => $invoices->sum(fn($invoice) => (float) $invoice->subtotal),
            'discount' => $invoices->sum(fn($invoice) => (float) $invoice->discount),
            'ppn' => $invoices->sum(fn($invoice) => (float) $invoice->ppn_amount),
            'total' => $invoices->sum(fn($invoice) => (float) $invoice->total),
        ];

        $filename = 'laporan-keuangan-' .
            $startDate->format('Ymd') . '-' .
            $endDate->format('Ymd') . '.xls';

        return response()
            ->view('reports.excel', compact('startDate', 'endDate', 'invoices', 'summary'))
            ->header('Content-Type', 'application/vnd.ms-excel; charset=UTF-8')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    private function weeklyReportRange(?Carbon $date = null): array
    {
        $date = ($date ?? now())->copy();
        $startDay = intdiv($date->day - 1, 7) * 7 + 1;
        $endDay = min($startDay + 6, $date->copy()->endOfMonth()->day);

        return [
            $date->copy()->day($startDay)->startOfDay(),
            $date->copy()->day($endDay)->endOfDay(),
        ];
    }
}
