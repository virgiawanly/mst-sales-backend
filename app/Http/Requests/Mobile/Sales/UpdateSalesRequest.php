<?php

namespace App\Http\Requests\Mobile\Sales;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSalesRequest extends FormRequest
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
            'tgl' => 'required|date',
            'cust_id' => 'required|exists:m_customer,id',
            'ongkir' => 'nullable|sometimes|numeric|min:0',
            'diskon' => 'nullable|sometimes|numeric|min:0',
            'details' => 'required|array',
            'details.*.barang_id' => 'required', // Exists validation in service
            'details.*.qty' => 'required|numeric|min:1',
            'details.*.diskon_pct' => 'nullable|sometimes|numeric|min:0',
            'details.*.diskon_nilai' => 'nullable|sometimes|numeric|min:0',
        ];
    }
}
