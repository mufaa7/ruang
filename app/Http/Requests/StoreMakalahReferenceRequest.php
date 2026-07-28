<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreMakalahReferenceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => 'required|string',
            'penulis' => 'required|string',
            'judul' => 'required|string',
            'tahun' => 'nullable|string',
            'kota_terbit' => 'nullable|string',
            'penerbit' => 'nullable|string',
            'nama_jurnal' => 'nullable|string',
            'volume' => 'nullable|string',
            'nomor' => 'nullable|string',
            'halaman' => 'nullable|string',
            'website_name' => 'nullable|string',
            'url' => 'nullable|string',
            'tanggal_akses' => 'nullable|string',
        ];
    }
}
