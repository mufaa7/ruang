<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateMakalahRequest extends FormRequest
{
    public function authorize(): bool
    {
        $makalah = $this->route('makalah');
        return $makalah && $this->user()->id === $makalah->user_id;
    }

    public function rules(): array
    {
        return [
            'judul' => ['sometimes', 'required', 'string', 'max:255'],
            'sub_judul' => ['nullable', 'string', 'max:255'],
            'nama_penulis' => ['sometimes', 'required', 'string', 'max:255'],
            'nim' => ['nullable', 'string', 'max:100'],
            'nama_dosen' => ['nullable', 'string', 'max:255'],
            'mata_kuliah' => ['nullable', 'string', 'max:255'],
            'universitas' => ['nullable', 'string', 'max:255'],
            'fakultas' => ['nullable', 'string', 'max:255'],
            'prodi' => ['nullable', 'string', 'max:255'],
            'kota' => ['nullable', 'string', 'max:100'],
            'tahun' => ['nullable', 'string', 'max:4'],
            'settings' => ['nullable', 'array'],
        ];
    }
    
    public function messages(): array
    {
        return [
            'judul.required' => 'Judul makalah tidak boleh kosong.',
            'nama_penulis.required' => 'Nama penulis wajib diisi.',
        ];
    }
}
