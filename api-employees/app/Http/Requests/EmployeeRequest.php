<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class EmployeeRequest extends FormRequest
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
            'errors' => $validator->errors(),
        ], 422));
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'cpf' => preg_replace('/[^0-9]/', '', $this->cpf),
        ]);
    }

    public function rules(): array
    {
        $employee = $this->route('employee');

        return [
            'name'  => 'required|max:80|regex:/^[\pL\s\-]+$/u',
            'email' => 'required|email|unique:employees,email,' . ($employee ? $employee->id : null),
            'cpf'   => 'required|digits:11|unique:employees,cpf,' . ($employee ? $employee->id : null),
            'city'  => 'required',
            'state' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'  => 'É obrigatório informar o nome do colaborador',
            'name.max'       => 'O nome deve conter no máximo 80 caracteres',
            'name.regex'     => 'O nome deve conter apenas letras.',
            'email.required' => 'É obrigatório informar o email do colaborador',
            'email.email'    => 'É necessário informar um email válido.',
            'email.unique'   => 'O email informado já está cadastrado.',
            'cpf.unique'     => 'O CPF informado já está cadastrado.',
            'cpf.required'   => 'É obrigatório informar o CPF do colaborador',
            'cpf.digits'     => 'O CPF deve ter 11 números.',
            'city.required'  => 'É obrigatório informar a cidade do colaborador',
            'state.required' => 'É obrigatório informar o estado do colaborador',
        ];
    }
}
