<?php

namespace App\Http\Requests\Management\Client;

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
            'id' => 'required|integer|exists:clients,id',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'O ID é obrigatório.',
            'id.integer' => 'O ID esta inválido',
            'id.exists' => 'Este cliente não existe',
        ];
    }
}
