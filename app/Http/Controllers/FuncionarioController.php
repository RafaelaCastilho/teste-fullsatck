<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Funcionarios;

class FuncionarioController extends Controller
{
    public function index()
    {
        return Funcionarios::all();
    }

    public function store(Request $request)
    {
        $funcionario = new Funcionarios();

        $email = $request->email;
        $phone = $request->phone;
        $departament_id = $request->departament_id;

        if ($funcionario->isValidEmail($email) && $funcionario->containsOnlyNumbers($phone) && $funcionario->isForeignId($departament_id)) {

            Funcionarios::create($request->all());
        } else {
            echo "Dados inválidos. \nO email deve estar correto e sem espaços. \nO telefone deve conter apenas números. \nO departamento deve existir.";
        }

    }

    public function show(string $id)
    {
        return Funcionarios::findOrFail($id);
    }

    public function update(Request $request, string $id)
    {
        $funcionario = new Funcionarios();
        $funcionario::findOrFail($id);

        $email = $request->email;
        $phone = $request->phone;
        $departament_id = $request->departament_id;

        if ($funcionario->isValidEmail($email) && $funcionario->containsOnlyNumbers($phone) && $funcionario->isForeignId($departament_id)) {
            $funcionario->update($request->all());
        } else {
            echo "Dados inválidos. \nO email deve estar correto e sem espaços. \nO telefone deve cconter apenas números. \nO departamento deve existir.";
        }
    }

    public function destroy(string $id)
    {
        $funcionario = Funcionarios::findOrFail($id);
        $funcionario->delete();
    }
}