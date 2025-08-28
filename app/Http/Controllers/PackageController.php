<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePackageRequest;
use Illuminate\Http\Request;
use App\Models\Package;
use App\Http\Resources\PackageResource;
use App\Http\Requests\CreatePackageRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Hotel;


class PackageController extends Controller
{

    use AuthorizesRequests;

    public function publicIndex(Request $request)
    {
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

    public function index(Request $request)
    {
        $this->authorize('viewAny', Package::class);

        $query = Package::query();

        if (!(auth()->user()->IsSuperAdmin() || auth()->user()->hasRole('super'))) {
            $query->where('tenant_id', auth()->user()->tenant_id);
        }

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

        $packages = $query->with(['MK_Hotel', 'MD_Hotel', 'tenant'])->paginate(6);

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
