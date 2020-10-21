<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Management\Client\SaveRequest;
use App\Http\Requests\Management\Client\DeleteRequest;
use App\Models\Client;

class ClientController extends Controller
{
    public function index()
    {
        if (!empty(request()->route('id'))) {
            $client = Client::find(request()->route('id'));

            if ($client) {
                return view('management.client.index', ['client' => $client]);
            }
        }

        return view('management.client.index');
    }

    public function list()
    {
        $clients = Client::all();

        return view('management.client.list', ['clients' => $clients]);
    }

    public function save(SaveRequest $request)
    {
        $client = !empty($request->input('id')) ? Client::find($request->input('id')) : new Client();
        $client->name = $request->input('name');
        $client->phone = $request->input('phone');
        $client->cep = $request->input('cep');
        $client->address = $request->input('address');
        $client->number = $request->input('number');
        $client->complement = $request->input('complement');
        $client->district = $request->input('district');
        $client->city = $request->input('city');
        $client->state = $request->input('state');
        $client->save();

        return response()->json(['message' => 'Dados salvo'], 201);
    }

    public function delete(DeleteRequest $request)
    {
        $client = Client::find($request->input('id'));
        $client->delete();

        return response()->json(['message' => 'Cliente excluído'], 201);
    }
}
