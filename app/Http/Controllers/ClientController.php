<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Requests\AddClientRequest;
use App\Services\ClientService;
use App\Http\Requests\UpdateClientRequest;

class ClientController extends Controller
{

    public function index()
    {
        $clients = Client::all();
        return response()->json($clients);
    }

    public function store(AddClientRequest $request, ClientService $service)
    {
        $validated = $request->validated();
        $client = $service->store($validated);
        return response()->json([
            'message' => 'Client created successfully',
            'client' => $client
        ], 201);
    }



    public function show(Client $client)
    {
        return response()->json($client);
    }


    public function update(UpdateClientRequest $request, ClientService $clientService, Client $client)
    {
        $validated = $request->updateClient();
        $updatedClient = $clientService->update($client, $validated);
        return response()->json([
            'message' => 'Client updated successfully',
            'client' => $updatedClient
        ]);
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return response()->json([
            'message' => 'Client deleted successfully'
        ]);
    }
}
