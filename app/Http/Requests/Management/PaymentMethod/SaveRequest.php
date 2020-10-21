<?php

namespace App\Http\Requests\Management\PaymentMethod;

use Illuminate\Foundation\Http\FormRequest;

class SaveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'nullable|integer|exists:payment_methods,id',
            'name' => 'required|string|min:3',
            'is_credit' => 'nullable|integer|size:1',
        ];
    }

    public function messages()
    {
        return [
            'id.integer' => 'O ID esta inválido',
            'id.exists' => 'Este cliente não existe',
            'name.required' => 'O nome é obrigatório.',
            'name.string' => 'O nome esta inválido',
            'name.min' => 'O nome deve conter :min caracteres',
            'is_credit.integer' => 'A opção de crédito esta inválida',
            'is_credit.min' => 'A opção de crédito deve ser :size',
        ];
    }
}
