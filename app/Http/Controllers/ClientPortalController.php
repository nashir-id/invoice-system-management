<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientPortalController extends Controller
{
    public function showLogin()
    {
        if (session()->has('client_portal_id')) {
            return redirect()->route('client-portal.dashboard');
        }

        return view('client_portal.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'max:32'],
        ], [
            'code.required' => 'Kode akses wajib diisi.',
        ]);

        $client = Client::where('client_login_code', strtoupper($request->code))
            ->where('is_active', true)
            ->first();

        if (! $client) {
            return back()
                ->withInput()
                ->withErrors(['code' => 'Kode akses tidak ditemukan atau klien tidak aktif.']);
        }

        session(['client_portal_id' => $client->id]);

        return redirect()->route('client-portal.dashboard');
    }

    public function dashboard()
    {
        $client = Client::find(session('client_portal_id'));

        if (! $client || ! $client->is_active) {
            session()->forget('client_portal_id');
            return redirect()->route('client-portal.login');
        }

        $invoices = $client->invoices()
            ->with('payment')
            ->latest('invoice_date')
            ->paginate(10);

        $stats = [
            'total_invoices' => $client->invoices()->count(),
            'total_paid' => $client->invoices()->paid()->sum('total'),
            'total_outstanding' => $client->invoices()->unpaid()->sum('total'),
        ];

        return view('client_portal.dashboard', compact('client', 'invoices', 'stats'));
    }

    public function logout()
    {
        session()->forget('client_portal_id');

        return redirect()->route('client-portal.login')->with('success', 'Anda sudah keluar dari portal klien.');
    }
}
