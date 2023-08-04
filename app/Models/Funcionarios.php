<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Funcionarios extends Model 
{
    protected $fillable = [
        'firstName',
        'lastName',
        'email',
        'phone',
        'departament_id'
    ];

    function containsOnlyNumbers($phone){
        return preg_match('/^[0-9]+$/', $phone);
    }
    function isValidEmail($email){
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    function isForeignId($departament_id) {
        $existe = Departamento::where('id', $departament_id)->exists();
        return $existe;
    }

    function existId($id) {
        $existe = Funcionarios::where('id', $id)->exists();
        return $existe;
    }
    
}
