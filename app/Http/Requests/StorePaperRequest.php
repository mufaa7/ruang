<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaperRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'title'      => ['required', 'string', 'min:5', 'max:255'],
            'abstract'   => ['nullable', 'string', 'max:1000'],
            'subject_id' => ['nullable', 'exists:subjects,id'],
            'visibility' => ['required', 'in:private,public,subject_only'],
            'tags'       => ['nullable', 'array'],
            'tags.*'     => ['string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'      => 'Judul paper wajib diisi bestie! 📝',
            'title.min'           => 'Judul paper minimal 5 karakter ya',
            'visibility.required' => 'Pilih dulu siapa yang bisa lihat paper-mu',
            'subject_id.exists'   => 'Subject yang dipilih ga valid nih',
            'tags.*.max'          => 'Nama tag kepanjangan, maksimal 50 karakter',
        ];
    }
}
