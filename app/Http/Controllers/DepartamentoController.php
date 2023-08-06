<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use App\Models\Utils\Validation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DepartamentoController extends Controller
{
    // Instância do modelo de departamento
    protected $departamento;
    // Instância da classe de validação personalizada
    protected $valid;
    // Cria uma nova instânica do controlador, usando como parâmetros o departamento e o valid
    public function __construct(Departamento $departamento, Validation $valid)
    {
        $this->departamento = $departamento;
        $this->valid = $valid;
    }

    // Obtém todos os departamentos
    public function index()
    {
        return Departamento::all();
    }

    // Cria um novo departamento e retorna uma mensagem de erro de acordo com o código retornado
    public function store(Request $request)
    {
        try {
            Departamento::create($request->all());
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '22001') {
                return response()->json(['status' => 'Limite de caracteres atingido']);
            }
            if ($e->getCode() === 'HY000') {
                return response()->json(['status' => 'Campo faltando']);
            }
        }
    }

    // Obtém um departamento específico pelo ID
    public function show(string $id)
    {
        // Verifica se o ID informado não existe
        if (!$this->valid->existId($id, $this->departamento)) {
            return "Não encontrado.";
        }
        return Departamento::findOrFail($id);
    }

    // Atualiza um departamento existente pelo ID
    public function update(Request $request, string $id)
    {
        try {
            // Verifica se o ID informado não existe
            if (!$this->valid->existId($id, $this->departamento)) {
                return "Departamento não encontrado";
            }

            // Atualização do departamento
            $departamento = Departamento::findOrFail($id);
            return $departamento->update($request->all());

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '22001') {
                return response()->json(['status' => 'Limite de caracteres atingido']);
            }
        }
    }

    // Remove um departamento pelo ID
    public function destroy(string $id)
    {
        // Verifica se o ID informado não existe
        if (!$this->valid->existId($id, $this->departamento)) {
            return "Departamento não encontrado.";
        }

        // Remoção do departamento
        $departamento = Departamento::findOrFail($id);
        $departamento->delete();
    }
}