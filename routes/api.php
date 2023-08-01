<?php

use App\Http\Controllers\DepartamentoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\TarefaController;
use App\Http\Controllers\AuthController;

Route::apiResource('funcionarios', FuncionarioController::class);
Route::apiResource('tarefas', TarefaController::class);
Route::apiResource('departamentos', DepartamentoController::class);
Route::post('auth/login', 'AuthController@login');