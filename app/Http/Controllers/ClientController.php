<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClientResource;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\AddClientRequest;
use App\Services\ClientService;
use App\Http\Requests\UpdateClientRequest;
use Illuminate\Support\Facades\Log;

class ClientController extends Controller
{

    public function index(Request $request)
    {
        $query = Client::with(['family', 'personalInfo.passport', 'muhram', 'branch', 'createdBy', 'tenant']);

        if ($request->filled('q')) {
            $search = $request->query('q');

            $query->where(function ($q) use ($search) {
                $q->whereHas('personalInfo', function ($q2) use ($search) {
                    $q2->where('first_name_ar', 'like', "%{$search}%")
                        ->orWhere('last_name_ar', 'like', "%{$search}%");
                })
                    ->orWhereHas('personalInfo.passport', function ($q3) use ($search) {
                        // بحث داخل رقم الجواز
                        $q3->where('passport_number', 'like', "%{$search}%");
                    })
                    ->orWhere('id', $search)
                    ->orWhere('register_date', 'like', "%{$search}%");
            });
        }

        if ($request->filled('family_id')) {
            $query->where('family_id', $request->query('family_id'));
        }

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->query('branch_id'));
        }

        if ($request->filled('tenant_id')) {
            $query->where('tenant_id', $request->query('tenant_id'));
        }

        if ($request->filled('created_by')) {
            $query->where('created_by', $request->query('created_by'));
        }
        return ClientResource::collection($query->paginate(6));
    }

    public function store(AddClientRequest $request, ClientService $service)
    {
        $validated = $request->validated();
        $tenant = tenant();
        if ($tenant->balance === 0) {
            Log::error('Tenant balance is zero, cannot create client', ['tenant_id' => $tenant->id]);
            return response()->json(['message' => 'Tenant balance is zero, cannot create client'], 403);
        }
        $client = $service->store($validated);
        return response()->json([
            'message' => 'Client created successfully',
            'client' => $client
        ], 201);
    }



    public function show(Client $client)
    {
        $client->load(['family', 'personalInfo.passport', 'muhram', 'branch']);
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



    public function uploadPassportImage(Request $request, Client $client)
    {
        $client->clearMediaCollection('passport_images'); // auto-delete old

        $media = $client
            ->addMedia($request->file('file'))
            ->toMediaCollection('passport_images');

        return response()->json([
            'url' => $media->getUrl(),
            'thumb' => $media->getUrl('thumb'),
            'path' => $media->getPath(),
            'mime' => $media->mime_type,
            'size' => $media->size,
        ]);
    }

    public function uploadPersonalImage(Request $request, Client $client)
    {
        $client->clearMediaCollection('personal_images');

        $media = $client
            ->addMedia($request->file('file'))
            ->toMediaCollection('personal_images');

        return response()->json([
            'url' => $media->getUrl(),
            'thumb' => $media->getUrl('thumb'),
        ]);
    }


    /*public function uploadPassportImage(Request $request)
    {
        if (!$request->hasFile('file')) {
            return response()->json(['error' => 'No file uploaded'], 400);
        }

        $file = $request->file('file');
        $imageName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();

        $tenantId = auth()->user()?->tenant_id ?? $request->input('tenant_id') ?? 'default';

        $destination = public_path("PassportsImages/$tenantId");
        if (!file_exists($destination)) {
            mkdir($destination, 0777, true);
        }

        $file->move($destination, $imageName);

        // يعيد المسار للفرونت
        return response()->json([
            'path' => "/PassportsImages/$tenantId/$imageName"
        ]);
    }*/

    /* public function uploadPersonalImage(Request $request)
     {
         if (!$request->hasFile('file')) {
             return response()->json(['error' => 'No file uploaded'], 400);
         }

         $file = $request->file('file');
         $imageName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();

         $tenantId = auth()->user()?->tenant_id ?? $request->input('tenant_id') ?? 'default';

         $destination = public_path("PersonalImages/$tenantId");
         if (!file_exists($destination)) {
             mkdir($destination, 0777, true);
         }

         $file->move($destination, $imageName);

         return response()->json([
             'path' => "/PersonalImages/$tenantId/$imageName"
         ]);
     }
 */
    public function getClientsCountByUser($userId)
    {
        return Client::where('created_by', $userId)->count();
    }

}
