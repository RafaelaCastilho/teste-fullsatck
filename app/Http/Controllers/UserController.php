<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Utils\Validation;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $user;
    protected $valid;

    public function __construct(User $user, Validation $valid){
        $this->user = $user;
        $this->valid = $valid;
    }
    public function index()
    {
        return User::all();
    }
    public function store(Request $request)
    {
        $email = $request->email;

        try {
            if (!$this->valid->isValidEmail($email)) {
                return "Email inválido. O email deve estar correto e sem espaços.";
            }
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

    public function show(string $id)
    {
        if (!$this->valid->existId($id, $this->user)) {
            return "Não encontrado.";
        } 
        return User::findOrFail($id);
    }

    public function update(Request $request, string $id)
    {
        $email = $request->email;

        try {
            if (!$this->valid->existId($id, $this->user)) {
                return "Usuário não encontrado.";
            }
            if (!$this->valid->isValidEmail($email)) {
                return "Email inválido. O email deve estar correto e sem espaços.";
            }

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

    public function destroy(string $id)
    {
        if (!$this->valid->existId($id, $this->user)) {
            return "Usuário não encontrado.";
        }
        $user = User::findOrFail($id);
        $user->delete();
    }
}