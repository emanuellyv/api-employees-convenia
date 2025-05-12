<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employee = Employee::all();

        return view('employees.index', ['employees' => $employee]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt',
        ], [
            'file.required' => 'É obrigatório selecionar um arquivo.',
            'file.mimes' => 'O arquivo deve ser um CSV.',
        ]);

        $headers = ['name', 'email', 'cpf', 'city', 'state'];

        $dataFile = array_map('str_getcsv', file($request->file('file')));

        $cpfAlreadyExist = false;

        foreach ($dataFile as $keyData => $row) {
            foreach ($headers as $key => $header) {

                if ($header === 'cpf' ) {
                    if (Employee::where('cpf', $row[$key])->first()) {
                        $cpfAlreadyExist .= $row[$key] . ', ';
                    }
                }

                $arrayValues[$keyData][$header] = $row[$key];
            }
        }

        if ($cpfAlreadyExist) {
            return redirect()->back()->with('error', 'Arquivo não importado. Existem CPFs já cadastrados: ' . $cpfAlreadyExist);
        }

        Employee::insert($arrayValues);

        return redirect()->back()->with('success', 'Arquivo importado com sucesso.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        //
    }
}
