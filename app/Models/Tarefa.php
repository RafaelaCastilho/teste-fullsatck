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
    function isValidDate($due_date, $format = 'Y-m-d')
    {
        $dateTimeObj = DateTime::createFromFormat($format, $due_date);
        return $dateTimeObj && $dateTimeObj->format($format) === $due_date;
    }
    function isForeignId($assignee_id) {
        $existe = Funcionarios::where('id', $assignee_id)->exists();
        return $existe;
    }
}
