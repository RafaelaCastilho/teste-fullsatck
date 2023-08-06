<?php

namespace App\Http\Controllers;

use App\Models\Utils\Validation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tarefa;


class TarefaController extends Controller
{
    // Instância do modelo de Tarefas    
    protected $tarefa;
    // Instância da classe de validação personalizada
    protected $valid;

    // Cria uma nova instância do controlador, usando como parâmetros a tarefa e o valid
    public function __construct(Tarefa $tarefa, Validation $valid)
    {
        $this->tarefa = $tarefa;
        $this->valid = $valid;
    }

    // Obtém todas as tarefas
    public function index()
    {
        return Tarefa::all();
    }

    // Cria uma nova tarefas e retorna uma mensagem de erro de acordo com o código
    public function store(Request $request)
    {
        // Obtém a chave estrangeira
        $foreign_id = $request->assignee_id;

        try {
            // Vreifica se a chave estrangeira não existe
            if (!$this->valid->isForeignId($foreign_id, $this->tarefa)) {
                return "Funcionário inválido";
            }
            // Cria a nova tarefa caso exista a chave estrangeira
            return Tarefa::create($request->all());

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '22001') {
                return response()->json(['status' => 'Limite de caracteres atingido']);
            }
            if ($e->getCode() === 'HY000') {
                return response()->json(['status' => 'Campo faltando']);
            }
            if ($e->getCode() === '22007') {
                return response()->json(['status' => 'Formato da data inconrreto. Tente: yyyy-mm-dd hh:mm:ss']);
            }
        }
    }

    // Obtém uma tarefa específica pelo ID
    public function show(string $id)
    {
        // Verifica se o ID não existe
        if (!$this->valid->existId($id, $this->tarefa)) {
            return "Não encontrado.";
        }
        return Tarefa::findOrFail($id);
    }

    // Atualiza uma tarefa existente pelo ID
    public function update(Request $request, string $id)
    {
        // Obtém a chave estrangeira
        $foreign_id = $request->assignee_id;

        try {
            // Validações
            if (!$this->valid->existId($id, $this->tarefa)) {
                return "Tarefa não encontrada";
            }
            if ($this->valid->isForeignId($foreign_id, $this->tarefa)) {
                return "Funcionário inválido";
            }

            // Atualização da tarefa se tudo for validado
            $tarefa = Tarefa::findOrFail($id);
            return $tarefa->update($request->all());

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '22001') {
                return response()->json(['status' => 'Limite de caracteres atingido']);
            }
            if ($e->getCode() === '22007') {
                return response()->json(['status' => 'Formato da data inconrreto. Tente: yyyy-mm-dd hh:mm:ss']);
            }
        }

    }

    // Remove uma tarefa pelo ID
    public function destroy(string $id)
    {
        if (!$this->valid->existId($id, $this->tarefa)) {
            return "Tarefa não encontrada.";
        }
        $tarefa = Tarefa::findOrFail($id);
        $tarefa->delete();
    }
}