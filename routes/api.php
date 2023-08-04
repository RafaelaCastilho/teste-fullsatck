<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\TarefaController;



Route::group([

    'middleware' => 'apiJWT',
    'prefix' => 'auth'

], function ($router) {
    Route::apiResource('funcionarios', FuncionarioController::class);
    Route::apiResource('tarefas', TarefaController::class);
    Route::apiResource('departamentos', DepartamentoController::class);
    Route::apiResource('users',UserController::class);
    Route::post('logout', [AuthController::class, "logout"]);

});

Route::post('auth/login', [AuthController::class, "login"]);