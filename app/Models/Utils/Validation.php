<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Validation extends Model 
{
    function containsOnlyNumbers($phone){
        return preg_match('/^[0-9]+$/', $phone);
    }
    function isValidEmail($email){
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}