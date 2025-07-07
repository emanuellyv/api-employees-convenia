<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportEmployeesRequest extends FormRequest
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
            'file' => 'required|mimes:csv,txt',
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'É obrigatório inserir um arquivo',
            'file.mimes'    => 'O arquivo deve ser um CSV.',
        ];
    }
}
