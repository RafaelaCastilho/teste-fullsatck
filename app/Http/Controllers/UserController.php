<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return User::all();
    }

    public function store(Request $request)
    {
        $user = new User();
        $email = $request->email;

        if ($user->isValidEmail($email)) {
            User::create($request->all());
        } else {
            echo "Email inválidos." ;
        }
    }

    public function show(string $id)
    {
        return User::findOrFail($id);
    }

    public function update(Request $request, string $id)
    {
        $user = new User();
        $email = $request->email;

        if ($$user->isValidEmail($email)) {
            $user->update($request->all());
        } else {
            echo "Email inválidos." ;
        }
    }

    public function destroy(string $id)
    {
        $user = new User();
        $user::findOrFail($id);

        if($user->existId($id)){
            $user->delete();
        }

    }
}
