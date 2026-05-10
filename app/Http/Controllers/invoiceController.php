<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Client;
use App\Models\Voucher;
use App\Models\InvoiceItem;
use App\Models\InvoiceLog;
use App\Http\Requests\StoreInvoiceRequest;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Daftar semua invoice dengan filter & search
     */
    public function index(Request $request)
    {
        $query = Invoice::with(['client', 'payment', 'creator'])
            ->latest('invoice_date');

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter klien
        if ($request->filled('client_id')) {
            $query->forClient($request->client_id);
        }

        // Filter tipe
        if ($request->filled('type')) {
            $query->ofType($request->type);
        }

        // Filter rentang tanggal
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->inDateRange($request->date_from, $request->date_to);
        }

        // Search nomor invoice
        if ($request->filled('search')) {
            $query->where('invoice_number', 'like', '%' . $request->search . '%');
        }

        $invoices = $query->paginate(15)->withQueryString();

        // Statistik header
        $stats = [
            'all'     => Invoice::count(),
            'unpaid'  => Invoice::unpaid()->count(),
            'paid'    => Invoice::paid()->count(),
            'overdue' => Invoice::overdue()->count(),
        ];

        $clients = Client::active()->orderBy('company_name')->get();

        return view('invoices.index', compact('invoices', 'stats', 'clients'));
    }

    /**
     * Form buat invoice baru
     */
    public function create()
    {
        $clients     = Client::active()->orderBy('company_name')->get();
        $defaultTnC  = config('invoice.default_terms', 'Pembayaran dilakukan melalui transfer bank.');
        return view('invoices.create', compact('clients', 'defaultTnC'));
    }

    /**
     * Simpan invoice baru
     */
    public function store(StoreInvoiceRequest $request)
    {
        $data = $request->validated();

        // 1. Ambil data klien
        $client = Client::findOrFail($data['client_id']);

        // 2. Generate nomor invoice otomatis
        $invoiceNumber = Invoice::generateNumber($client);

        // 3. Hitung subtotal dari semua item
        $subtotal = collect($data['items'])->sum(fn($item) => $item['price'] * $item['quantity']);

        // 4. Hitung diskon dari voucher (jika ada)
        $discount  = 0;
        $voucherId = null;
        if (!empty($data['voucher_code'])) {
            $voucher = Voucher::findByCode($data['voucher_code']);
            if ($voucher) {
                $discount  = $voucher->discount_amount;
                $voucherId = $voucher->id;
            }
        }

        // 5. Hitung PPN 11%
        $usePN     = $request->boolean('use_ppn');
        $afterDisc = $subtotal - $discount;
        $ppnAmount = $usePN ? round($afterDisc * 0.11) : 0;
        $total     = $afterDisc + $ppnAmount;

        // 6. Buat invoice
        $invoice = Invoice::create([
            'client_id'        => $client->id,
            'created_by'       => auth()->id(),
            'voucher_id'       => $voucherId,
            'invoice_number'   => $invoiceNumber,
            'type'             => $data['type'],
            'invoice_date'     => $data['invoice_date'],
            'due_date'         => $data['due_date'] ?? null,
            'use_ppn'          => $usePN,
            'subtotal'         => $subtotal,
            'discount'         => $discount,
            'ppn_amount'       => $ppnAmount,
            'total'            => $total,
            'status'           => 'unpaid',
            'terms_conditions' => $data['terms_conditions'] ?? null,
            'estimation'       => $data['estimation'] ?? null,
            'notes'            => $data['notes'] ?? null,
            'public_token'     => \Str::random(32),
        ]);

        // 7. Simpan semua item
        foreach ($data['items'] as $item) {
            InvoiceItem::create([
                'invoice_id'   => $invoice->id,
                'service_name' => $item['service_name'],
                'description'  => $item['description'] ?? null,
                'price'        => $item['price'],
                'quantity'     => $item['quantity'],
                'subtotal'     => $item['price'] * $item['quantity'],
            ]);
        }

        // 8. Catat log
        InvoiceLog::record($invoice, 'created', "Invoice {$invoiceNumber} dibuat oleh " . auth()->user()->name);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', "Invoice {$invoiceNumber} berhasil dibuat.");
    }

    /**
     * Halaman detail invoice
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['client', 'items', 'voucher', 'payment', 'creator', 'logs.user']);
        return view('invoices.show', compact('invoice'));
    }

    /**
     * Form edit invoice (hanya status UNPAID)
     */
    public function edit(Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return back()->with('error', 'Invoice yang sudah lunas tidak dapat diedit.');
        }

        $clients    = Client::active()->orderBy('company_name')->get();
        $defaultTnC = config('invoice.default_terms', '');
        $invoice->load('items');

        return view('invoices.edit', compact('invoice', 'clients', 'defaultTnC'));
    }

    /**
     * Update invoice
     */
    public function update(StoreInvoiceRequest $request, Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return back()->with('error', 'Invoice yang sudah lunas tidak dapat diedit.');
        }

        $data   = $request->validated();
        $client = Client::findOrFail($data['client_id']);

        // Hitung ulang
        $subtotal  = collect($data['items'])->sum(fn($i) => $i['price'] * $i['quantity']);
        $discount  = 0;
        $voucherId = $invoice->voucher_id;

        if (!empty($data['voucher_code'])) {
            $voucher = Voucher::findByCode($data['voucher_code']);
            if ($voucher) {
                $discount  = $voucher->discount_amount;
                $voucherId = $voucher->id;
            }
        }

        $usePN     = $request->boolean('use_ppn');
        $afterDisc = $subtotal - $discount;
        $ppnAmount = $usePN ? round($afterDisc * 0.11) : 0;
        $total     = $afterDisc + $ppnAmount;

        // Update invoice
        $invoice->update([
            'client_id'        => $client->id,
            'voucher_id'       => $voucherId,
            'type'             => $data['type'],
            'invoice_date'     => $data['invoice_date'],
            'due_date'         => $data['due_date'] ?? null,
            'use_ppn'          => $usePN,
            'subtotal'         => $subtotal,
            'discount'         => $discount,
            'ppn_amount'       => $ppnAmount,
            'total'            => $total,
            'terms_conditions' => $data['terms_conditions'] ?? null,
            'estimation'       => $data['estimation'] ?? null,
            'notes'            => $data['notes'] ?? null,
        ]);

        // Hapus item lama, simpan item baru
        $invoice->items()->delete();
        foreach ($data['items'] as $item) {
            InvoiceItem::create([
                'invoice_id'   => $invoice->id,
                'service_name' => $item['service_name'],
                'description'  => $item['description'] ?? null,
                'price'        => $item['price'],
                'quantity'     => $item['quantity'],
                'subtotal'     => $item['price'] * $item['quantity'],
            ]);
        }

        InvoiceLog::record($invoice, 'updated', "Invoice diupdate oleh " . auth()->user()->name);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', "Invoice {$invoice->invoice_number} berhasil diperbarui.");
    }

    /**
     * Hapus invoice (hanya UNPAID / DRAFT)
     */
    public function destroy(Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return back()->with('error', 'Invoice yang sudah lunas tidak dapat dihapus.');
        }

        $number = $invoice->invoice_number;
        $invoice->items()->delete();
        $invoice->logs()->delete();
        $invoice->delete();

        return redirect()->route('invoices.index')
            ->with('success', "Invoice {$number} berhasil dihapus.");
    }

    /**
     * Duplikat invoice untuk klien yang sama
     */
    public function duplicate(Invoice $invoice)
    {
        $newInvoice = $invoice->replicate(['invoice_number', 'status', 'public_token']);
        $newInvoice->invoice_number = Invoice::generateNumber($invoice->client);
        $newInvoice->invoice_date   = now()->toDateString();
        $newInvoice->status         = 'unpaid';
        $newInvoice->public_token   = \Str::random(32);
        $newInvoice->created_by     = auth()->id();
        $newInvoice->save();

        foreach ($invoice->items as $item) {
            $newItem = $item->replicate(['invoice_id']);
            $newItem->invoice_id = $newInvoice->id;
            $newItem->save();
        }

        InvoiceLog::record($newInvoice, 'duplicated', "Invoice diduplikat dari {$invoice->invoice_number}");

        return redirect()->route('invoices.show', $newInvoice)
            ->with('success', "Invoice berhasil diduplikat menjadi {$newInvoice->invoice_number}.");
    }

    /**
     * Halaman invoice publik (via link WhatsApp)
     */
    public function publicView(string $token)
    {
        $invoice = Invoice::where('public_token', $token)
            ->with(['client', 'items', 'voucher'])
            ->firstOrFail();

        return view('invoices.public', compact('invoice'));
    }

    /**
     * AJAX — generate link WhatsApp
     */
    public function whatsappLink(Invoice $invoice)
    {
        $invoice->load('client');
        $phone   = preg_replace('/[^0-9]/', '', $invoice->client->phone ?? '');
        $phone   = ltrim($phone, '0');
        $phone   = '62' . $phone;
        $url     = route('invoice.public', $invoice->public_token);
        $message = urlencode("Halo {$invoice->client->pic_name},\n\nBerikut invoice {$invoice->invoice_number} dari NASHIR.ID:\n{$url}\n\nTotal: Rp " . number_format($invoice->total, 0, ',', '.') . "\n\nTerima kasih.");

        return response()->json(['url' => "https://wa.me/{$phone}?text={$message}"]);
    }
}