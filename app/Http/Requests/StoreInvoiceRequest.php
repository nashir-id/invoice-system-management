<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
{
    public function authorize(): bool
{
    return auth()->check();
}

    protected function prepareForValidation(): void
    {
        if (! $this->filled('type')) {
            return;
        }

        $type = strtolower(str_replace([' ', '-'], '_', $this->input('type')));

        if ($type === 'onetime') {
            $type = 'one_time';
        }

        $this->merge(['type' => $type]);
    }

    public function rules(): array
    {
        return [
            'client_id'        => ['required', 'exists:clients,id'],
            'type'             => ['required', 'in:one_time,recurring'],
            'invoice_date'     => ['required', 'date'],
            'due_date'         => ['nullable', 'date', 'after_or_equal:invoice_date'],
            'use_ppn'          => ['boolean'],
            'terms_conditions' => ['nullable', 'string'],
            'estimation'       => ['nullable', 'string', 'max:100'],
            'notes'            => ['nullable', 'string'],
            'voucher_code'     => ['nullable', 'string'],

            // Items wajib ada minimal 1
            'items'                   => ['required', 'array', 'min:1'],
            'items.*.service_name'    => ['required', 'string', 'max:255'],
            'items.*.description'     => ['nullable', 'string'],
            'items.*.price'           => ['required', 'numeric', 'min:0'],
            'items.*.quantity'        => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'client_id.required'            => 'Klien wajib dipilih.',
            'client_id.exists'              => 'Klien tidak ditemukan.',
            'type.required'                 => 'Tipe invoice wajib dipilih.',
            'type.in'                       => 'Tipe invoice tidak valid.',
            'invoice_date.required'         => 'Tanggal invoice wajib diisi.',
            'due_date.after_or_equal'       => 'Tanggal jatuh tempo harus sama atau setelah tanggal invoice.',
            'items.required'                => 'Minimal harus ada 1 item layanan.',
            'items.*.service_name.required' => 'Nama layanan wajib diisi.',
            'items.*.price.required'        => 'Harga layanan wajib diisi.',
            'items.*.price.min'             => 'Harga tidak boleh minus.',
            'items.*.quantity.required'     => 'Kuantitas wajib diisi.',
            'items.*.quantity.min'          => 'Kuantitas minimal 1.',
        ];
    }
}
