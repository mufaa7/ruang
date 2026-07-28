<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'         => ['sometimes', 'string', 'min:1', 'max:255'],
            'content'       => ['nullable', 'string'],
            'folder_id'     => ['nullable', 'exists:note_folders,id'],
            'color'         => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'visibility'    => ['sometimes', 'in:private,public,subject_only'],
            'is_pinned'     => ['sometimes', 'boolean'],
            'tagline'       => ['nullable', 'string', 'max:255'],
            'hashtags_json' => ['nullable', 'string'],
            'checklist_json'=> ['nullable', 'string'],
            'tags'          => ['nullable', 'array'],
            'tags.*'        => ['exists:tags,id'],
        ];
    }
}
