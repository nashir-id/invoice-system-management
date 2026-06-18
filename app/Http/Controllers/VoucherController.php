<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    /**
     * Daftar semua voucher
     */
    public function index()
    {
        $vouchers = Voucher::withCount('invoices')
            ->latest()
            ->paginate(15);

        return view('vouchers.index', compact('vouchers'));
    }

    /**
     * Form tambah voucher baru
     */
    public function create()
    {
        return view('vouchers.create');
    }

    /**
     * Simpan voucher baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'code'            => ['required', 'string', 'max:50', 'unique:vouchers,code'],
            'discount_amount' => ['required', 'numeric', 'min:0'],
            'description'     => ['nullable', 'string', 'max:255'],
            'valid_until'     => ['nullable', 'date', 'after:today'],
        ], [
            'code.required'            => 'Kode voucher wajib diisi.',
            'code.unique'              => 'Kode voucher sudah digunakan.',
            'discount_amount.required' => 'Nominal diskon wajib diisi.',
            'discount_amount.min'      => 'Nominal diskon tidak boleh minus.',
            'valid_until.after'        => 'Tanggal kadaluarsa harus setelah hari ini.',
        ]);

        Voucher::create([
            'code'            => strtoupper(trim($request->code)),
            'discount_amount' => $request->discount_amount,
            'description'     => $request->description,
            'valid_until'     => $request->valid_until,
            'is_active'       => true,
        ]);

        return redirect()->route('vouchers.index')
            ->with('success', "Voucher {$request->code} berhasil ditambahkan.");
    }

    /**
     * Form edit voucher
     */
    public function edit(Voucher $voucher)
    {
        return view('vouchers.edit', compact('voucher'));
    }

    /**
     * Update voucher
     */
    public function update(Request $request, Voucher $voucher)
    {
        $request->validate([
            'code'            => ['required', 'string', 'max:50', 'unique:vouchers,code,' . $voucher->id],
            'discount_amount' => ['required', 'numeric', 'min:0'],
            'description'     => ['nullable', 'string', 'max:255'],
            'valid_until'     => ['nullable', 'date'],
        ]);

        $voucher->update([
            'code'            => strtoupper(trim($request->code)),
            'discount_amount' => $request->discount_amount,
            'description'     => $request->description,
            'valid_until'     => $request->valid_until,
        ]);

        return redirect()->route('vouchers.index')
            ->with('success', "Voucher {$voucher->code} berhasil diperbarui.");
    }

    /**
     * Aktifkan / nonaktifkan voucher
     */
    public function destroy(Voucher $voucher)
    {
        $voucher->update(['is_active' => ! $voucher->is_active]);

        $status = $voucher->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Voucher {$voucher->code} berhasil {$status}.");
    }

    public function forceDelete(Voucher $voucher)
    {
        $code = $voucher->code;

        $voucher->invoices()->update(['voucher_id' => null]);
        $voucher->delete();

        return redirect()->route('vouchers.index')
            ->with('success', "Voucher {$code} berhasil dihapus.");
    }

    /**
     * AJAX — validasi kode voucher di form invoice
     * Route: POST /vouchers/validate-code
     */
    public function validateCode(Request $request)
    {
        $code = strtoupper(trim($request->code ?? ''));

        if (empty($code)) {
            return response()->json([
                'valid'   => false,
                'message' => 'Kode voucher tidak boleh kosong.',
            ]);
        }

        $voucher = Voucher::where('code', $code)
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('valid_until')
                  ->orWhere('valid_until', '>=', now());
            })
            ->first();

        if ($voucher) {
            return response()->json([
                'valid'           => true,
                'discount_amount' => $voucher->discount_amount,
                'message'         => "Voucher valid — diskon Rp " .
                                     number_format($voucher->discount_amount, 0, ',', '.'),
            ]);
        }

        return response()->json([
            'valid'   => false,
            'message' => 'Kode voucher tidak valid atau sudah kadaluarsa.',
        ]);
    }

    /**
     * AJAX — cek voucher via GET (untuk form invoice lama)
     * Route: GET /vouchers/check?code=xxx
     */
    public function check(Request $request)
    {
        return $this->validateCode($request);
    }

    // show tidak dipakai
    public function show(Voucher $voucher)
    {
        return redirect()->route('vouchers.index');
    }
}
