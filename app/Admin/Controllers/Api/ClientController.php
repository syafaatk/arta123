<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::all();
        return response()->json($clients);
    }

    public function show($id)
    {
        $client = Client::find($id);
        return response()->json($client);
    }

    public function store(Request $request)
    {
        $client = Client::create($request->all());
        return response()->json($client, 201);
    }

    public function update(Request $request, $id)
    {
        $client = Client::find($id);
        $client->update($request->all());
        return response()->json($client, 200);
    }

    public function destroy($id)
    {
        Client::destroy($id);
        return response()->json(null, 204);
    }
}