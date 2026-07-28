<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSubjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['sometimes', 'required', 'string', 'min:3', 'max:100'],
            'code'        => ['nullable', 'string', 'max:20'],
            'lecturer'    => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'icon'        => ['nullable', 'string', 'max:10'],
            'color'       => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'semester'    => ['nullable', 'string', 'max:50'],
        ];
    }
    
    public function messages(): array
    {
        return [
            'name.required' => 'Nama subject wajib diisi ya! 📚',
            'name.min'      => 'Nama subject minimal 3 karakter dong',
            'color.regex'   => 'Format warna harus hex (contoh: #6366f1)',
        ];
    }
}
