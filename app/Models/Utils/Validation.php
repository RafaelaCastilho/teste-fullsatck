<?php

namespace App\Models\Utils;

use Illuminate\Database\Eloquent\Model;

class Validation extends Model 
{
    function containsOnlyNumbers($phone){
        return preg_match('/^[0-9]+$/', $phone);
    }
    function isValidEmail($email){
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    function existId($id, $tabela){
        $existe = $tabela::where('id', $id)->exists();
        return $existe;
    }
    function isForeignId($foreign_id, $table) {
        $existe = $table::where('id', $foreign_id)->exists();
        return $existe;
    }
}