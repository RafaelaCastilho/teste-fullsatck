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
        $due_date = $request->due_date;
        $assignee_id = $request->assignee_id;

        if ($tarefa->isValidDate($due_date) && $tarefa->isForeignId($assignee_id)) {
            Tarefa::Create($request->all());
        } else {
            echo "Dados inválidos. \nO departamento deve existir. \nA data pode estar inválida ou não está no formato correto.";
        }

    }

    public function show(string $id)
    {
        return Tarefa::findOrFail($id);
    }
    public function update(Request $request, string $id)
    {
        $tarefa = new Tarefa();
        $tarefa::findOrFail($id);
        $due_date = $request->due_date;

        if ($tarefa->isValidDate($due_date)) {
            $tarefa->update($request->all());
        } else {
            echo "Dados inválidos. \nO departamento deve existir. \nA data pode estar inválida ou não está no formato correto.";
        }

    }
    public function destroy(string $id)
    {
        $tarefa = Tarefa::findOrFail($id);
        $tarefa->delete();
    }
}