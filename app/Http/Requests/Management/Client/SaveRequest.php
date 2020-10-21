<?php

namespace App\Http\Requests\Management\Client;

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
            'id' => 'nullable|integer|exists:clients,id',
            'name' => 'required|string|min:3',
            'phone' => 'required|string|min:14|max:15',
            'cep' => 'required|string|size:9',
            'address' => 'required|string|max:191',
            'number' => 'required|integer|digits_between:1,10',
            'complement' => 'nullable|string|max:191',
            'district' => 'required|string|max:191',
            'city' => 'required|string|max:191',
            'state' => 'required|string|size:2',
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
            'phone.required' => 'O telefone é obrigatório.',
            'phone.string' => 'O telefone esta inválido',
            'phone.min' => 'O telefone deve conter :min caracteres',
            'phone.max' => 'O telefone não pode ultrapassar :max caracteres',
            'cep.required' => 'O CEP é obrigatório.',
            'cep.string' => 'O CEP esta inválido',
            'cep.size' => 'O CEP deve conter 9 caracteres',
            'address.required' => 'O logradouro é obrigatório.',
            'address.string' => 'O logradouro esta inválido',
            'address.max' => 'O logradouro não pode ultrapassar :max caracteres',
            'number.required' => 'O número é obrigatório.',
            'number.integer' => 'O número esta inválido',
            'number.digits_between' => 'O número deve conter entre :min a :max caracteres',
            'complement.string' => 'O complemento esta inválido',
            'complement.max' => 'O complemento não pode ultrapassar :max caracteres',
            'district.required' => 'O bairro é obrigatório.',
            'district.string' => 'O bairro esta inválido',
            'district.max' => 'O bairro não pode ultrapassar :max caracteres',
            'city.required' => 'A cidade é obrigatória.',
            'city.string' => 'A cidade esta inválida',
            'city.max' => 'A cidade não pode ultrapassar :max caracteres',
            'state.required' => 'O estado é obrigatório.',
            'state.string' => 'O estado esta inválido',
            'state.size' => 'O estado deve conter :size caracteres',
        ];
    }
}
