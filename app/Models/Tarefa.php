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
}
