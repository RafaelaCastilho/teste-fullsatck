<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Utils\Validation;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Instância do modelo de Usuários
    protected $user;

    // Instância da classe de validação personalizada
    protected $valid;

    // Cria uma nova instância do controlador, usando como parâmetros o user e o valid
    public function __construct(User $user, Validation $valid)
    {
        $this->user = $user;
        $this->valid = $valid;
    }
    // Obtém todos os usuários
    public function index()
    {
        return User::all();
    }

    // Cria um novo usuário e retorna uma mensagem de erro de acordo com o código
    public function store(Request $request)
    {
        // Obtém o email 
        $email = $request->email;

        try {
            // Verifica se o email não é válido
            if (!$this->valid->isValidEmail($email)) {
                return "Email inválido. O email deve estar correto e sem espaços.";
            }

            //Criação do no usuário
            return User::create($request->all());

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
            if ($e->getCode() === '23000') {
                return response()->json(['status' => 'Email já cadastrado.']);
            }
        }

    }

    // Obtém um usuário específico pelo ID
    public function show(string $id)
    {
        // Verifica se o ID não existe
        if (!$this->valid->existId($id, $this->user)) {
            return "Não encontrado.";
        }
        return User::findOrFail($id);
    }

    // Atualiza um usuário existente pleo ID
    public function update(Request $request, string $id)
    {
        // Obtém o email
        $email = $request->email;

        try {
            //Validações
            if (!$this->valid->existId($id, $this->user)) {
                return "Usuário não encontrado.";
            }
            if (!$this->valid->isValidEmail($email)) {
                return "Email inválido. O email deve estar correto e sem espaços.";
            }

            // Atualização do usuário se tudo for validado
            $user = User::findOrFails($id);
            return $user->update($request->all());

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '22001') {
                return response()->json(['status' => 'Limite de caracteres atingido']);
            }
            if ($e->getCode() === '22007') {
                return response()->json(['status' => 'Formato da data inconrreto. Tente: yyyy-mm-dd hh:mm:ss']);
            }
            if ($e->getCode() === '23000') {
                return response()->json(['status' => 'Email já cadastrado.']);
            }
        }
    }

    // Remove um usuário pelo ID
    public function destroy(string $id)
    {
        // Verifica se o ID não existe
        if (!$this->valid->existId($id, $this->user)) {
            return "Usuário não encontrado.";
        }

        // Remoção do usuário
        $user = User::findOrFail($id);
        $user->delete();
    }
}