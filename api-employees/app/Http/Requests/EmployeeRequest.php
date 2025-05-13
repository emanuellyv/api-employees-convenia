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
            'errors' => $validator->errors()
        ], 422));
    }

    public function rules(): array
    {
        $employeeId = $this->route('employee');

        return [
            'name' => 'required',
            'email' => 'required|email|unique:employees,email,' . ($employeeId ? $employeeId->id : null),
            'cpf' => 'required',
            'city' => 'required',
            'state' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'É obrigatório informar o nome do colaborador',
            'email.required' => 'É obrigatório informar o email do colaborador',
            'email.email' => 'É necessário informar um email válido.',
            'email.unique' => 'O email informado já está cadastrado.',
            'cpf.required' => 'É obrigatório informar o CPF do colaborador',
            'city.required' => 'É obrigatório informar a cidade do colaborador',
            'state.required' => 'É obrigatório informar o estado do colaborador',
        ];
    }
}
