<?php

namespace App\Http\Controllers;

use App\Models\RecurringTemplate;
use App\Models\Client;
use App\Models\Invoice;
use Illuminate\Http\Request;

class RecurringTemplateController extends Controller
{
    /**
     * Daftar semua recurring template
     */
    public function index()
    {
        $templates = RecurringTemplate::with(['client', 'invoice'])
            ->orderBy('status')
            ->orderBy('generate_day')
            ->paginate(15);

        return view('recurring-templates.index', compact('templates'));
    }

    /**
     * Pause template (hentikan sementara)
     */
    public function pause(RecurringTemplate $recurringTemplate)
    {
        $recurringTemplate->update(['status' => 'paused']);

        return back()->with('success',
            "Template {$recurringTemplate->template_name} berhasil dijeda.");
    }

    /**
     * Resume template (aktifkan kembali)
     */
    public function resume(RecurringTemplate $recurringTemplate)
    {
        $recurringTemplate->update(['status' => 'active']);

        return back()->with('success',
            "Template {$recurringTemplate->template_name} berhasil diaktifkan kembali.");
    }

    /**
     * Hapus template
     */
    public function destroy(RecurringTemplate $recurringTemplate)
    {
        $name = $recurringTemplate->template_name;
        $recurringTemplate->delete();

        return redirect()->route('recurring-templates.index')
            ->with('success', "Template {$name} berhasil dihapus.");
    }

    // Method berikut diperlukan karena pakai --resource
    // tapi belum diimplementasi di fase ini

    public function create() { return redirect()->route('recurring-templates.index'); }
    public function store(Request $request) { return redirect()->route('recurring-templates.index'); }
    public function show(RecurringTemplate $recurringTemplate) { return redirect()->route('recurring-templates.index'); }
    public function edit(RecurringTemplate $recurringTemplate) { return redirect()->route('recurring-templates.index'); }
    public function update(Request $request, RecurringTemplate $recurringTemplate) { return redirect()->route('recurring-templates.index'); }
}