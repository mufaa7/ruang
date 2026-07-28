<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMakalahRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:255'],
            'sub_judul' => ['nullable', 'string', 'max:255'],
            'nama_penulis' => ['required', 'string', 'max:255'],
            'nim' => ['nullable', 'string', 'max:100'],
            'nama_dosen' => ['nullable', 'string', 'max:255'],
            'mata_kuliah' => ['nullable', 'string', 'max:255'],
            'universitas' => ['nullable', 'string', 'max:255'],
            'fakultas' => ['nullable', 'string', 'max:255'],
            'prodi' => ['nullable', 'string', 'max:255'],
            'kota' => ['nullable', 'string', 'max:100'],
            'tahun' => ['nullable', 'string', 'max:4'],
        ];
    }
    
    public function messages(): array
    {
        return [
            'judul.required' => 'Judul makalahnya jangan sampai kosong bestie.',
            'nama_penulis.required' => 'Nama penulisnya diisi dong.',
        ];
    }
}
