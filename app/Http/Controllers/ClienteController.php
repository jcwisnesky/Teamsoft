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
    $enderecosData = $request->input('endereco');

    $cliente = Cliente::create($clienteData);

    // Salvar os endereços
    $enderecos = [];
    if (!is_null($enderecosData)) {
        foreach ($enderecosData as $enderecoData) {
            // Define latitude e longitude predefinidas
            $enderecoData['latitude'] = 12345; // Substitua 12345 pela latitude desejada
            $enderecoData['longitude'] = 67890; // Substitua 67890 pela longitude desejada

            // Salvar o endereço
            $endereco = new Endereco($enderecoData);

            // Adicionar a URL ao objeto de endereço
            $endereco->url = "https://maps.google.com/?q={$enderecoData['latitude']},{$enderecoData['longitude']}";

            $cliente->enderecos()->save($endereco);
            $enderecos[] = $endereco;
        }
    }

    // Associar os endereços ao cliente
    $cliente->enderecos = $enderecos;

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

    $cliente->update($request->only(['cnpj', 'razao_social', 'nome_contato', 'telefone']));

    foreach ($request->input('endereco', []) as $enderecoData) {
        if (isset($enderecoData['id'])) {
            $endereco = Endereco::findOrFail($enderecoData['id']);

            $endereco->update([
                'logradouro' => $enderecoData['logradouro'],
                'numero' => $enderecoData['numero'],
                'complemento' => $enderecoData['complemento'],
                'bairro' => $enderecoData['bairro'],
                'cidade' => $enderecoData['cidade'],
                'estado' => $enderecoData['estado'],
                'cep' => $enderecoData['cep'],
            ]);
        }
    }

    return response()->json($cliente, 200);
}


    public function destroy($id)
    {
        Cliente::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
