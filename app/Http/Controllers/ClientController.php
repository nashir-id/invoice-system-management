<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Http\Requests\StoreClientRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    /**
     * Daftar semua klien dengan search & filter
     */
    public function index(Request $request)
    {
        $query = Client::withCount('invoices')
            ->withSum('invoices', 'total');

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter status
        if ($request->status === 'active') {
            $query->active();
        } elseif ($request->status === 'inactive') {
            $query->where('is_active', false);
        }

        $clients = $query->orderBy('company_name')->paginate(15)->withQueryString();

        return view('clients.index', compact('clients'));
    }

    /**
     * Form tambah klien baru
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Simpan klien baru
     */
    public function store(StoreClientRequest $request)
{
    $data = $request->validated();
    $data['client_login_code'] = strtoupper($data['client_login_code']);

    $client = Client::create(array_merge($data, ['is_active' => true]));

    // redirect ke INDEX agar tombol Tambah Klien tetap terlihat
    return redirect()->route('clients.index')
        ->with('success', "Klien {$client->company_name} berhasil ditambahkan.");
}

    /**
     * Halaman profil klien — semua invoice terkait
     */
    public function show(Client $client)
    {
        $invoices = $client->invoices()
            ->with('payment')
            ->latest('invoice_date')
            ->paginate(10);

        $stats = [
            'total_invoices'    => $client->invoices()->count(),
            'total_paid'        => $client->invoices()->paid()->sum('total'),
            'total_outstanding' => $client->invoices()->unpaid()->sum('total'),
            'total_overdue'     => $client->invoices()->overdue()->count(),
        ];

        return view('clients.show', compact('client', 'invoices', 'stats'));
    }

    /**
     * Form edit klien
     */
    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    /**
     * Update data klien
     */
    public function update(StoreClientRequest $request, Client $client)
    {
        $data = $request->validated();
        $data['client_login_code'] = strtoupper($data['client_login_code']);

        $client->update($data);

        return redirect()->route('clients.show', $client)
            ->with('success', "Data {$client->company_name} berhasil diperbarui.");
    }

    /**
     * Nonaktifkan klien (soft delete — tidak hapus data)
     */
    public function destroy(Client $client)
    {
        // Cek apakah ada invoice aktif (unpaid)
        if ($client->invoices()->unpaid()->exists()) {
            return back()->with('error',
                "Klien {$client->company_name} masih memiliki invoice yang belum dibayar. Selesaikan dulu sebelum menonaktifkan."
            );
        }

        $client->update(['is_active' => false]);

        return redirect()->route('clients.index')
            ->with('success', "Klien {$client->company_name} berhasil dinonaktifkan.");
    }

    /**
     * Hapus permanen klien beserta data terkait.
     */
    public function forceDelete(Client $client)
    {
        $name = $client->company_name;

        DB::transaction(function () use ($client) {
            $client->delete();
        });

        return redirect()->route('clients.index')
            ->with('success', "Klien {$name} berhasil dihapus.");
    }

    /**
     * Aktifkan kembali klien
     */
    public function activate(Client $client)
    {
        $client->update(['is_active' => true]);

        return back()->with('success', "Klien {$client->company_name} berhasil diaktifkan kembali.");
    }

    /**
     * AJAX — data klien untuk auto-fill di form invoice
     */
    public function getData(Client $client)
    {
        return response()->json([
            'id'           => $client->id,
            'company_name' => $client->company_name,
            'pic_name'     => $client->pic_name,
            'phone'        => $client->phone,
            'email'        => $client->email,
        ]);
    }

}
