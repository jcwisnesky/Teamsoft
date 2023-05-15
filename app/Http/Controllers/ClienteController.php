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
        $request->validate([
            'cnpj' => 'required',
            'razao_social' => 'required',
            'nome_contato' => 'required',
            'telefone' => 'required',
            'endereco' => 'required|array',
            'endereco.*.logradouro' => 'required',
            'endereco.*.numero' => 'required',
            'endereco.*.complemento' => 'nullable',
            'endereco.*.bairro' => 'required',
            'endereco.*.cidade' => 'required',
            'endereco.*.estado' => 'required',
            'endereco.*.cep' => 'required',
        ]);

        $clienteData = $request->only(['cnpj', 'razao_social', 'nome_contato', 'telefone']);
        $enderecosData = $request->input('endereco');

        $cliente = Cliente::create($clienteData);

        // Salvar os endereços
        $enderecos = [];
        foreach ($enderecosData as $enderecoData) {
            // Obter latitude e longitude usando a API do Google Maps Geocoding
            $enderecoCompleto = "{$enderecoData['logradouro']}, {$enderecoData['numero']}, {$enderecoData['bairro']}, {$enderecoData['cidade']}, {$enderecoData['estado']}, {$enderecoData['cep']}";
            $enderecoFormatado = urlencode($enderecoCompleto);
            $apiKey = "SUA_CHAVE_DE_API_DO_GOOGLE_MAPS";
            $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$enderecoFormatado}&key={$apiKey}";

            $response = file_get_contents($url);
            $data = json_decode($response);

            if ($data->status === "OK") {
                $latitude = $data->results[0]->geometry->location->lat;
                $longitude = $data->results[0]->geometry->location->lng;

                // Adicionar latitude e longitude ao objeto de endereço
                $enderecoData['latitude'] = $latitude;
                $enderecoData['longitude'] = $longitude;
            } else {
                // Define latitude e longitude como nulas
                $enderecoData['latitude'] = null;
                $enderecoData['longitude'] = null;
            }

            // Salvar o endereço
            $endereco = new Endereco($enderecoData);
            $cliente->enderecos()->save($endereco);
            $enderecos[] = $endereco;
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

    $request->validate([
        'cnpj' => 'required',
        'razao_social' => 'required',
        'nome_contato' => 'required',
        'telefone' => 'required',
        'endereco.*.id' => 'nullable',
        'endereco.*.logradouro' => 'required',
        'endereco.*.numero' => 'required',
        'endereco.*.complemento' => 'nullable',
        'endereco.*.bairro' => 'required',
        'endereco.*.cidade' => 'required',
        'endereco.*.estado' => 'required',
        'endereco.*.cep' => 'required',
    ]);

    $cliente->update($request->only(['cnpj', 'razao_social', 'nome_contato', 'telefone']));

    foreach ($request->input('endereco', []) as $enderecoData) {
        $enderecoId = $enderecoData['id'] ?? null;

        if ($enderecoId) {
            $endereco = Endereco::where('cliente_id', $id)->where('id', $enderecoId)->first();

            if ($endereco) {
                $endereco->update($enderecoData);
            }
        } else {
            $cliente->enderecos()->create($enderecoData);
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
