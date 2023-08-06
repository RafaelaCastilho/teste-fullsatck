<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Funcionarios;
use App\Models\Validation;
use Illuminate\Http\Request;

class FuncionarioController extends Controller
{
    public function index()
    {
        return Funcionarios::all();
    }

    public function store(Request $request)
    {
        $funcionario = new Funcionarios();
        $valid = new Validation();

        $email = $request->email;
        $phone = $request->phone;
        $departament_id = $request->departament_id;

        try {
            if (!$valid->isValidEmail($email)) {
                return "Email inválido. O email deve estar correto e sem espaços.";
            }
            if (!$valid->containsOnlyNumbers($phone)) {
                return "Telefone inválido. O telefone deve conter apenas números.";
            }
            if (!$funcionario->isForeignId($departament_id)) {
                return "Departamento inválido. O departamento deve existir.";
            }
            return Funcionarios::create($request->all());

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '22001') {
                return response()->json(['status' => 'Limite de caracteres atingido']);
            }
            if ($e->getCode() === 'HY000') {
                return response()->json(['status' => 'Campo faltando']);
            }
        }
    }

    public function show(string $id)
    {
        $funcionario = new Funcionarios();

        if ($funcionario->existId($id)) {
            return Funcionarios::findOrFail($id);
        } else {
            return "Não encontrado.";
        }
    }

    public function update(Request $request, string $id)
    {
        $funcionario = new Funcionarios();
        $valid = new Validation();

        $email = $request->email;
        $phone = $request->phone;
        $departament_id = $request->departament_id;

        try {
            if (!$funcionario->existId($id)) {
                return "Funcionário não encontrado";
            }
            if (!$valid->isValidEmail($email)) {
                return "Email inválido. O email deve estar correto e sem espaços.";
            }
            if (!$valid->containsOnlyNumbers($phone)) {
                return "Telefone inválido. O telefone deve conter apenas números.";
            }
            if (!$funcionario->isForeignId($departament_id)) {
                return "Departamento inválido. O departamento deve existir.";
            }

            $funcionario = Funcionarios::findOrFail($id);
            return $funcionario->update($request->all());

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '22001') {
                return response()->json(['status' => 'Limite de caracteres atingido']);
            }
        }
    }

    public function destroy(string $id)
    {
        $funcionario = new Funcionarios();

        if ($funcionario->existId($id)) {
            $funcionario = Funcionarios::findOrFail($id);
            $funcionario->delete();
        } else {
            return "Funcionário não encontrado.";
        }
    }
}