<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePackageRequest;
use Illuminate\Http\Request;
use App\Models\Package;
use App\Http\Resources\PackageResource;
use App\Http\Requests\CreatePackageRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Hotel;
use Illuminate\Pipeline\Pipeline;
use App\Pipelines\Packages\PriceRangeFilter;
use App\Pipelines\Packages\FlightDateFilter;
use App\Pipelines\Packages\DistanceFilter;
use App\Pipelines\Packages\PackageTypeFilter;
use App\Http\Requests\PackageIndexRequest;


class PackageController extends Controller
{

    use AuthorizesRequests;

    public function publicIndex(PackageIndexRequest $request)
    {
        //$user = $request->user();

        $baseQuery = Package::query()
            ->with(['MK_Hotel', 'MD_Hotel', 'tenant'])
            ->where('status', true);
        //->when($user?->tenant_id, fn($q) => $q->where('tenant_id', $user->tenant_id)); // multi-tenant guard

        $filters = [
            new PackageTypeFilter($request),
            new PriceRangeFilter($request),
            new FlightDateFilter($request),
            new DistanceFilter($request),
        ];

        $query = app(Pipeline::class)
            ->send($baseQuery)
            ->through($filters)
            ->thenReturn();

        \Log::debug('filters', $request->only('type', 'currency', 'price', 'date', 'date_tolerance_days', 'distance'));
        \Log::debug('sql', ['sql' => $query->toSql(), 'bindings' => $query->getBindings()]);

        $packages = $query->latest('id')->paginate($request->integer('per_page', 10));

        return PackageResource::collection($packages);
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $baseQuery = Package::query()
            ->with(['MK_Hotel', 'MD_Hotel', 'tenant'])
            ->where('status', true)
            ->when($user?->tenant_id, fn($q) => $q->where('tenant_id', $user->tenant_id)); // multi-tenant guard
        $query = Package::where('status', true)
            ->with(['MK_Hotel', 'MD_Hotel', 'tenant']);

        if ($request->filled('q')) {
            $search = $request->input('q');
            $type = $request->input('type', 'package');

            $query->where(function ($q) use ($search, $type) {
                if ($type === 'hotel') {
                    $q->whereHas('MK_Hotel', function ($q) use ($search) {
                        $q->where('hotel_name', 'like', "%$search%")
                            ->orWhere('distance_from_center', 'like', "%$search%");
                    })
                        ->orWhereHas('MD_Hotel', function ($q) use ($search) {
                            $q->where('hotel_name', 'like', "%$search%")
                                ->orWhere('distance_from_center', 'like', "%$search%");
                        });
                } elseif ($type === 'company') {
                    $q->whereHas('tenant', fn($q) => $q->where('data->company_name', 'like', "%$search%"));
                } else {
                    $q->where('package_name', 'like', "%$search%")
                        ->orWhere('package_type', 'like', "%$search%")
                        ->orWhere('start_date', 'like', "%$search%")
                        ->orWhere('season', 'like', "%$search%")
                        ->orWhere('total_price_dinar', 'like', "%$search%")
                        ->orWhere('total_price_usd', 'like', "%$search%")
                        ->orWhere('currency', 'like', "%$search%");
                }
            });
        }

        $packages = $query->get();
        return PackageResource::collection($packages);
    }


    public function store(CreatePackageRequest $request)
    {
        $data = $request->CreatePackageRequest();

        if (!empty($data['new_MKHotel_name'])) {
            $data['MKHotel'] = $this->createHotel($data['new_MKHotel_name'], 'مكة');
        }

        if (!empty($data['new_MDHotel_name'])) {
            $data['MDHotel'] = $this->createHotel($data['new_MDHotel_name'], 'المدينة');
        }


        $package = Package::create($data);
        return new PackageResource($package);
    }


    public function show(Package $package)
    {
        $this->authorize('view', $package);
        $package->load(['MK_Hotel', 'MD_Hotel', 'tenant']);
        return new PackageResource($package);
    }

    public function update(UpdatePackageRequest $request, Package $package)
    {
        $packageData = $request->UpdatePackage();
        if (!empty($packageData['new_MKHotel_name'])) {
            $packageData['MKHotel'] = $this->createHotel($packageData['new_MKHotel_name'], 'مكة');
        }

        if (!empty($packageData['new_MDHotel_name'])) {
            $packageData['MDHotel'] = $this->createHotel($packageData['new_MDHotel_name'], 'المدينة');
        }


        $package->update($packageData);
        $package->load(['MK_Hotel', 'MD_Hotel', 'tenant']);
        return new PackageResource($package);
    }

    public function destroy(Package $package)
    {
        $this->authorize('delete', $package);
        $package->delete();
        return response()->json(['message' => 'Package deleted successfully']);
    }

    public function createHotel(?string $name, string $city): ?int
    {
        if (empty($name)) {
            return null;
        }

        $hotel = Hotel::create([
            'hotel_name' => $name,
            'city' => $city,
        ]);

        return $hotel->id;
    }


}
