<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ManagerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'errors' => $validator->errors()
        ], 422));
    }

    public function rules(): array
    {
        $managerId = $this->route('manager');

        return [
            'name' => 'required',
            'email' => 'required|email|unique:managers,email,' . ($managerId ? $managerId->id : null)
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'É obrigatório informar o nome do gerente.',
            'email.required' => 'É obrigatório informar o email do gerente.',
            'email.email' => 'É necessário informar um email válido.',
            'email.unique' => 'O email informado já está cadastrado.'
        ];
    }
}
