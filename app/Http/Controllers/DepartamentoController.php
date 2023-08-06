<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use App\Models\Utils\Validation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DepartamentoController extends Controller
{
    protected $departamento;
    protected $valid;
     public function __construct(Departamento $departamento, Validation $valid){
        $this->departamento = $departamento;
        $this->valid = $valid;
     }

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
        if (!$this->valid->existId($id, $this->departamento)) {
            return "Não encontrado.";
        } 
        return Departamento::findOrFail($id);
    }
    public function update(Request $request, string $id)
    {
        try {
            if (!$this->valid->existId($id, $this->departamento)) {
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
        if (!$this->valid->existId($id, $this->departamento)) {
            return "Departamento não encontrado.";
        } 
        $departamento = Departamento::findOrFail($id);
        $departamento->delete();
    }
}