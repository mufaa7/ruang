<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaperRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'      => ['sometimes', 'string', 'min:5', 'max:255'],
            'abstract'   => ['nullable', 'string', 'max:1000'],
            'subject_id' => ['nullable', 'exists:subjects,id'],
            'visibility' => ['sometimes', 'in:private,public,subject_only'],
            'status'     => ['sometimes', 'in:draft,in_review,published,archived'],
            'tags'       => ['nullable', 'array'],
            'tags.*'     => ['string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.min'    => 'Judul paper minimal 5 karakter ya bestie',
            'status.in'    => 'Status yang dipilih ga valid nih',
            'tags.*.max'   => 'Nama tag kepanjangan, maksimal 50 karakter',
        ];
    }
}
