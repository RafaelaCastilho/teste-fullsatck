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
}
