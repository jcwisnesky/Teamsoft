<?php

namespace App\Http\Controllers;
use App\Models\Endereco;
use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::all();
        return response()->json($clientes);
    }

    public function store(Request $request)
{
    $clienteData = $request->only(['cnpj', 'razao_social', 'nome_contato', 'telefone']);
    $enderecoData = $request->only(['logradouro', 'numero', 'complemento', 'bairro', 'cidade', 'estado', 'cep']);

    $cliente = Cliente::create($clienteData);

    // Associação entre cliente e endereço
    $endereco = new Endereco($enderecoData);
    $cliente->enderecos()->save($endereco);

    // Recupera o endereço associado ao cliente
    $cliente['Endereco'] = $cliente->enderecos;

    return response()->json($cliente, 201);
}

    public function show($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente['Endereco'] = $cliente->enderecos;
        return response()->json($cliente);
    }

    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->update($request->all());
        return response()->json($cliente, 200);
    }

    public function destroy($id)
    {
        Cliente::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
