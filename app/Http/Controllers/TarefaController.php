<?php

namespace App\Http\Controllers;

use App\Models\Utils\Validation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tarefa;


class TarefaController extends Controller
{
    protected $tarefa;
    protected $valid;

    public function __construct(Tarefa $tarefa, Validation $valid)
    {
        $this->tarefa = $tarefa;
        $this->valid = $valid;
    }
    public function index()
    {
        return Tarefa::all();
    }

    public function store(Request $request)
    {
        $foreign_id = $request->assignee_id;

        try {
            if (!$this->valid->isForeignId($foreign_id, $this->tarefa)) {
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
        if (!$this->valid->existId($id, $this->tarefa)) {
            return "Não encontrado.";
        } 
        return Tarefa::findOrFail($id);
    }
    public function update(Request $request, string $id)
    {
        $foreign_id = $request->assignee_id;

        try {
            if (!$this->valid->existId($id, $this->tarefa)) {
                return "Tarefa não encontrada";
            }
            if ($this->valid->isForeignId($foreign_id, $this->tarefa)) {
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
        if (!$this->valid->existId($id, $this->tarefa)) {
            return "Tarefa não encontrada.";
        } 
        $tarefa = Tarefa::findOrFail($id);
        $tarefa->delete();
    }
}