<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tarefa;


class TarefaController extends Controller
{
    public function index()
    {
        return Tarefa::all();
    }

    public function store(Request $request)
    {
        $tarefa = new Tarefa();
        $assignee_id = $request->assignee_id;

        try {
            if (!$tarefa->isForeignId($assignee_id)) {
                return "Funcionário inválido";
            }
            return Tarefa::create($request->all());

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '22001') {
                return response()->json(['status' => 'Limite de caracteres atingido']);
            }
            if ($e->getCode() === 'HY000') {
                return response()->json(['status' => 'Campo faltando']);
            }
            if($e->getCode() === '22007'){
                return response()->json(['status' => 'Formato da data inconrreto. Tente: yyyy-mm-dd hh:mm:ss']);
            }
        }
    }

    public function show(string $id)
    {
        $tarefa = new Tarefa();

        if ($tarefa->existId($id)) {
            return Tarefa::findOrFail($id);
        } else {
            return "Não encontrado.";
        }
    }
    public function update(Request $request, string $id)
    {
        $tarefa = new Tarefa();
        $assignee_id = $request->assignee_id;

        try {
            if (!$tarefa->existId($id)) {
                return "Tarefa não encontrada";
            }
            if ($tarefa->isForeignId($assignee_id)) {
                return "Funcionário inválido";
            }

            $tarefa = Tarefa::findOrFail($id);
            return $tarefa->update($request->all());

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '22001') {
                return response()->json(['status' => 'Limite de caracteres atingido']);
            }
            if($e->getCode() === '22007'){
                return response()->json(['status' => 'Formato da data inconrreto. Tente: yyyy-mm-dd hh:mm:ss']);
            }
        }

    }
    public function destroy(string $id)
    {
        $tarefa = new Tarefa();

        if ($tarefa->existId($id)) {
            $tarefa = Tarefa::findOrFail($id);
            $tarefa->delete();
        } else {
            return "Tarefa não encontrada.";
        }

    }
}