<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['owner', 'admin']);
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('client_login_code')) {
            $this->merge([
                'client_login_code' => strtoupper($this->client_login_code),
            ]);
        }
    }

    public function rules(): array
{
    return [
        'company_name'     => ['required', 'string', 'max:255'],
        'pic_name' => ['nullable', 'string', 'max:255'],
        'phone'    => ['nullable', 'string', 'max:20'],
        'email'    => ['nullable', 'email', 'max:255'],
        'website'  => ['nullable', 'string', 'max:255'],
        'address'  => ['nullable', 'string', 'max:500'],
        'notes'    => ['nullable', 'string'],
        'client_login_code' => [
            'required',
            'string',
            'max:32',
            'alpha_dash',
            Rule::unique('clients', 'client_login_code')->ignore($this->route('client')),
        ],
    ];
}

    public function messages(): array
    {
        return [
            'company_name.required' => 'Nama perusahaan / klien wajib diisi.',
            'email.email'           => 'Format email tidak valid.',
            'client_login_code.required' => 'Kode login klien wajib diisi.',
            'client_login_code.alpha_dash' => 'Kode login hanya boleh berisi huruf, angka, strip, atau underscore.',
            'client_login_code.unique' => 'Kode login ini sudah digunakan klien lain.',
        ];
    }
}
