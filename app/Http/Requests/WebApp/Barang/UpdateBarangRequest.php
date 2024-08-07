<?php

namespace App\Http\Requests\WebApp\Barang;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBarangRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'kode' => 'required|max:10|unique:m_barang,kode,' . $this->barang . ',id,deleted_at,NULL',
            'nama' => 'required|max:100',
            'harga' => 'required|numeric|min:0'
        ];
    }
}
