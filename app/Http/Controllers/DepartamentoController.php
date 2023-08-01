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
        Departamento::Create($request->all());
    }

    public function show(string $id)
    {
        return Departamento::findOrFail($id);
    }
    public function update(Request $request, string $id)
    {
        $departamento = Departamento::findOrFail($id);
        $departamento->update($request->all());
    }
    public function destroy(string $id)
    {
        $departamento = Departamento::findOrFail($id);
        $departamento->delete();
    }
}
