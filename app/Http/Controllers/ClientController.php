<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClientResource;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Requests\AddClientRequest;
use App\Services\ClientService;
use App\Http\Requests\UpdateClientRequest;
use Log;

class ClientController extends Controller
{

public function index(Request $request)
{
   // $search = request()->input('search');

   $query = Client::with(['family', 'personalInfo.passport', 'muhram', 'branch']);

    if ($request->filled('q')) {
        $search = $request->query('q');

        $query->where(function($q) use ($search) {
            $q->whereHas('personalInfo', function($q2) use ($search) {
                $q2->where('first_name_ar', 'like', "%{$search}%")
                   ->orWhere('last_name_ar', 'like', "%{$search}%");
            })
            ->orWhereHas('personalInfo.passport', function($q3) use ($search) {
                // بحث داخل رقم الجواز
                $q3->where('passport_number', 'like', "%{$search}%");
            })
            ->orWhere('id', $search);
        });
    }

    //$clients = $query->get();
    $clients = $query->paginate(6);
    return ClientResource::collection($clients);
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



    public function show( $id)
    {
     $client = Client::with(['family', 'personalInfo.passport', 'muhram', 'branch'])->find($id);
    return new ClientResource($client);
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
