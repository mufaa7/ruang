<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'title'      => ['required', 'string', 'min:1', 'max:255'],
            'content'    => ['nullable', 'string'],
            'folder_id'  => ['nullable', 'exists:note_folders,id'],
            'subject_id' => ['nullable', 'exists:subjects,id'],
            'color'      => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'visibility' => ['nullable', 'in:private,public,subject_only'],
            'tags'       => ['nullable', 'array'],
            'tags.*'     => ['exists:tags,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'    => 'Kasih judul note-nya dulu dong! ✏️',
            'color.regex'       => 'Format warna harus hex ya (contoh: #6366f1)',
            'folder_id.exists'  => 'Folder yang dipilih ga ketemu',
        ];
    }
}
