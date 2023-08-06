<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Validation;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return User::all();
    }

    public function store(Request $request)
    {
        $valid = new Validation();
        $email = $request->email;

        try{
            if (!$valid->isValidEmail($email)) {
                return "Email inválido. O email deve estar correto e sem espaços.";
            }
    
            return User::create($request->all());
        }catch (\Illuminate\Database\QueryException $e) {
            if($e->getCode() ==='22001'){
                return response()->json(['status' => 'Limite de caracteres atingido']);
            }
            if($e->getCode() ==='HY000'){
                return response()->json(['status' => 'Campo faltando']);
            }
            if($e->getCode() === '22007'){
                return response()->json(['status' => 'Formato da data inconrreto. Tente: yyyy-mm-dd hh:mm:ss']);
            }
        }
        
    }

    public function show(string $id)
    {
        $user = new User();
        if ($user->existId($id)) {
            return User::findOrFail($id);
        } else {
            return "Não encontrado.";
        }
    }

    public function update(Request $request, string $id)
    {
        $user = new User();
        $valid = new Validation();
        $email = $request->email;

        try{
            if (!$user->existId($id)) {
                return "Usuário não encontrado.";
            }
            if (!$valid->isValidEmail($email)) {
                return "Email inválido. O email deve estar correto e sem espaços.";
            }
    
            $user = User::findOrFails($id);
            $user->update($request->all());

        }catch (\Illuminate\Database\QueryException $e) {
            if($e->getCode() ==='22001'){
                return response()->json(['status' => 'Limite de caracteres atingido']);
            }
            if($e->getCode() === '22007'){
                return response()->json(['status' => 'Formato da data inconrreto. Tente: yyyy-mm-dd hh:mm:ss']);
            }
        }
    }

    public function destroy(string $id)
    {
        $user = new User();

        if ($user->existId($id)) {
            $user = User::findOrFail($id);
            $user->delete();
        } else {
            return "Usuário não encontrado.";
        }
    }
}