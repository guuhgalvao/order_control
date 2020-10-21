<?php

namespace App\Http\Requests\Management\PaymentMethod;

use Illuminate\Foundation\Http\FormRequest;

class DeleteRequest extends FormRequest
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
            'id' => 'required|integer|exists:payment_methods,id',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'O ID é obrigatório.',
            'id.integer' => 'O ID esta inválido',
            'id.exists' => 'Esta forma de pagamento não existe',
        ];
    }
}
