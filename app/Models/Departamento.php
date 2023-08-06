<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $fillable = [
        'name'
    ];
    function existId($id) {
        $existe = Departamento::where('id', $id)->exists();
        return $existe;
    }
}