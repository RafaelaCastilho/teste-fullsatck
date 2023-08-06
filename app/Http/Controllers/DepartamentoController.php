<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DepartamentoController extends Controller
{
    public function index()
    {
        return Departamento::all();
    }

    public function store(Request $request)
    {
        try {
            Departamento::create($request->all());
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '22001') {
                return response()->json(['status' => 'Limite de caracteres atingido']);
            }
            if ($e->getCode() === 'HY000') {
                return response()->json(['status' => 'Campo faltando']);
            }
        }
    }

    public function show(string $id)
    {
        $departamento = new Departamento();

        if ($departamento->existId($id)) {
            return Departamento::findOrFail($id);
        } else {
            return "Não encontrado.";
        }
    }
    public function update(Request $request, string $id)
    {
        $departamento = new Departamento();
        
        try {
            if (!$departamento->existId($id)) {
                return "Departamento não encontrado";
            } 
            
            $departamento = Departamento::findOrFail($id);
            return $departamento->update($request->all());

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '22001') {
                return response()->json(['status' => 'Limite de caracteres atingido']);
            }
        }
    }
    public function destroy(string $id)
    {
        $departamento = new Departamento();

        if ($departamento->existId($id)) {
            $departamento = Departamento::findOrFail($id);
            $departamento->delete();
        } else {
            return "Departamento não encontrado.";
        }
    }
}