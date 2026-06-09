<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Setting;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::firstOrCreate([]);

        return view('settings.index', [

            'setting' => $setting,

            'totalUsers' =>
                User::count(),

            'owners' =>
                User::where('role', 'owner')->count(),

            'admins' =>
                User::where('role', 'admin')->count(),

            'staffs' =>
                User::where('role', 'staff')->count(),

            'activeVouchers' =>
                Voucher::where('is_active', 1)->count(),

            'logs' =>
                AuditLog::latest()
                    ->take(15)
                    ->get(),
        ]);
    }

    public function update(Request $request)
    {
        $setting = Setting::firstOrCreate([]);

        $data = $request->validate([

            // Profil Bisnis
            'business_name' => 'required|max:255',
            'tagline' => 'nullable|max:255',
            'business_email' => 'nullable|email|max:255',
            'website' => 'nullable|max:255',

            // Logo
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            // Mandiri
            'mandiri_name' => 'nullable|max:255',
            'mandiri_number' => 'nullable|max:255',
            'mandiri_holder' => 'nullable|max:255',

            // BCA
            'bca_name' => 'nullable|max:255',
            'bca_number' => 'nullable|max:255',
            'bca_holder' => 'nullable|max:255',

            // SeaBank
            'seabank_name' => 'nullable|max:255',
            'seabank_number' => 'nullable|max:255',
            'seabank_holder' => 'nullable|max:255',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Upload Logo
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('logo')) {

            // Hapus logo lama
            if (
                $setting->logo &&
                Storage::disk('public')->exists($setting->logo)
            ) {
                Storage::disk('public')->delete(
                    $setting->logo
                );
            }

            // Simpan logo baru
            $data['logo'] = $request
                ->file('logo')
                ->store('settings', 'public');
        }

        $setting->update($data);

        /*
        |--------------------------------------------------------------------------
        | Audit Log
        |--------------------------------------------------------------------------
        */

        AuditLog::create([
            'user_id' => auth()->id(),
            'module' => 'Settings',
            'action' => 'Update',
            'description' => 'Mengubah Pengaturan Sistem',
        ]);

        return back()->with(
            'success',
            'Pengaturan berhasil diperbarui.'
        );
    }
}