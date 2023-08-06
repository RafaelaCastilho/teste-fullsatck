<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Nette\Utils\DateTime;

class Tarefa extends Model
{
    protected $fillable = [
        'title',
        'description',
        'assignee_id',
        'due_date'
    ];
    function isForeignId($assignee_id) {
        $existe = Funcionarios::where('id', $assignee_id)->exists();
        return $existe;
    }
    function existId($id) {
        $existe = Tarefa::where('id', $id)->exists();
        return $existe;
    }
}
