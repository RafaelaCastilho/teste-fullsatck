<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Funcionarios;
use App\Models\Utils\Validation;
use Illuminate\Http\Request;

class FuncionarioController extends Controller
{
    // Instância do modelo de Funcionários
    protected $funcionario;
    // Instância da classe de validação personalizada
    protected $valid;

    // Cria uma nova instância do controlador, usando como parâmetros o funcionario e o valid
    public function __construct(Funcionarios $funcionario, Validation $valid)
    {
        $this->funcionario = $funcionario;
        $this->valid = $valid;
    }

    // Obtém todos os funcionários
    public function index()
    {
        return Funcionarios::all();
    }

    // Cria um novo funcionário e retorna uma mensagem de erro de acordo com o código
    public function store(Request $request)
    {
        // Obtém alguns dados da requisição
        $email = $request->email;
        $phone = $request->phone;
        $foreign_id = $request->departament_id;

        try {
            // Validações
            if (!$this->valid->isValidEmail($email)) {
                return "Email inválido. O email deve estar correto e sem espaços.";
            }
            if (!$this->valid->containsOnlyNumbers($phone)) {
                return "Telefone inválido. O telefone deve conter apenas números.";
            }
            if (!$this->valid->isForeignId($foreign_id, $this->funcionario)) {
                return "Departamento inválido. O departamento deve existir.";
            }
            // Criação do novo funcionário se tudo for validado
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

    // Obtém um funcionário específico pelo ID
    public function show(string $id)
    {
        // Verifica se o ID não existe
        if (!$this->valid->existId($id, $this->funcionario)) {
            return "Não encontrado.";
        }
        return Funcionarios::findOrFail($id);
    }

    // Atualiza um funcionário existente pleo ID
    public function update(Request $request, string $id)
    {
        // Obtém alguns dados da requisição
        $email = $request->email;
        $phone = $request->phone;
        $foreign_id = $request->departament_id;

        try {
            // Validações
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

            // Atualização do funcionário se tudo for validado
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

    // Remove um funcionário pelo ID
    public function destroy(string $id)
    {
        // Verifica se o ID não existe
        if (!$this->valid->existId($id, $this->funcionario)) {
            return "Funcionário não encontrado.";
        }

        //Remoção do funcionário
        $funcionario = Funcionarios::findOrFail($id);
        $funcionario->delete();
    }
}