<?php

namespace App\Models\Utils;

use Illuminate\Database\Eloquent\Model;

class Validation extends Model 
{
    // Verifica se uma string contém apenas números
    function containsOnlyNumbers($phone){
        return preg_match('/^[0-9]+$/', $phone);
    }
    // Verifica se um email é válido
    function isValidEmail($email){
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    // Verifica se um ID existe em uma tabela específica
    function existId($id, $tabela){
        $existe = $tabela::where('id', $id)->exists();
        return $existe;
    }
    // Verifica se um ID estrangeiro existe em uma tabela específica
    function isForeignId($foreign_id, $table) {
        $existe = $table::where('id', $foreign_id)->exists();
        return $existe;
    }
}