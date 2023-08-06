<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Funcionarios;
use App\Models\Utils\Validation;
use Illuminate\Http\Request;

class FuncionarioController extends Controller
{
    protected $funcionario;
    protected $valid;

    public function __construct(Funcionarios $funcionario, Validation $valid)
    {
        $this->funcionario = $funcionario;
        $this->valid = $valid;
    }
    public function index()
    {
        return Funcionarios::all();
    }

    public function store(Request $request)
    {
        $email = $request->email;
        $phone = $request->phone;
        $foreign_id = $request->departament_id;

        try {
            if (!$this->valid->isValidEmail($email)) {
                return "Email inválido. O email deve estar correto e sem espaços.";
            }
            if (!$this->valid->containsOnlyNumbers($phone)) {
                return "Telefone inválido. O telefone deve conter apenas números.";
            }
            if (!$this->valid->isForeignId($foreign_id, $this->funcionario)) {
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
            if ($e->getCode() === '23000') {
                return response()->json(['status' => 'Email já cadastrado.']);
            }
        }
    }

    public function show(string $id)
    {
        if (!$this->valid->existId($id, $this->funcionario)) {
            return "Não encontrado.";
        } 
        return Funcionarios::findOrFail($id);
    }

    public function update(Request $request, string $id)
    {
        $email = $request->email;
        $phone = $request->phone;
        $foreign_id = $request->departament_id;

        try {
            if (!$this->valid->existId($id, $this->funcionario)) {
                return "Funcionário não encontrado";
            }
            if (!$this->valid->isValidEmail($email)) {
                return "Email inválido. O email deve estar correto e sem espaços.";
            }
            if (!$this->valid->containsOnlyNumbers($phone)) {
                return "Telefone inválido. O telefone deve conter apenas números.";
            }
            if (!$this->valid->isForeignId($foreign_id, $this->funcionario)) {
                return "Departamento inválido. O departamento deve existir.";
            }

            $funcionario = Funcionarios::findOrFail($id);
            return $funcionario->update($request->all());

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '22001') {
                return response()->json(['status' => 'Limite de caracteres atingido']);
            }
            if ($e->getCode() === '23000') {
                return response()->json(['status' => 'Email já cadastrado.']);
            }
        }
    }

    public function destroy(string $id)
    {
        if (!$this->valid->existId($id, $this->funcionario)) {
            return "Funcionário não encontrado.";
        }
        $funcionario = Funcionarios::findOrFail($id);
        $funcionario->delete();
    }
}