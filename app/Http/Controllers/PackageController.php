<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePackageRequest;
use Illuminate\Http\Request;
use App\Models\Package;
use App\Http\Resources\PackageResource;
use App\Http\Requests\CreatePackageRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class PackageController extends Controller
{

    use AuthorizesRequests;

    public function publicIndex(Request $request)
    {
        $packages = Package::where('status', 'active')
            ->with(['MKHotel', 'MDHotel', 'tenant'])
            ->paginate(10);
        if ($request->filled('q')) {
            $search = $request->input('q');
            $packages->where(function ($query) use ($search) {
                $query->where('package_name', 'like', "%$search%")
                    ->orWhere('package_type', 'like', "%$search%")
                    ->orWhere('start_date', 'like', "%$search%")
                    ->orWhere('season', 'like', "%$search%")
                    ->orWhere('total_price_dinar', 'like', "%$search%")
                    ->orWhere('total_price_usd', 'like', "%$search%")
                    ->orWhere('currency', 'like', "%$search%");
            });
        }
        return PackageResource::collection($packages);
    }
    public function index(Request $request)
    {
        $this->authorize('viewAny', Package::class);
        if (auth()->user()->IsSuperAdmin() || auth()->user()->hasRole('super')) {
            $packages = Package::with(['MKHotel', 'MDHotel', 'tenant'])->paginate(10);
        } else {
            $packages = Package::with(['MKHotel', 'MDHotel', 'tenant'])
                ->where('tenant_id', auth()->user()->tenant_id)
                ->paginate(10);
        }

        if ($request->filled('q')) {
            $search = $request->input('q');
            $packages->where(function ($query) use ($search) {
                $query->where('package_name', 'like', "%$search%")
                    ->orWhere('package_type', 'like', "%$search%")
                    ->orWhere('start_date', 'like', "%$search%")
                    ->orWhere('season', 'like', "%$search%")
                    ->orWhere('total_price_dinar', 'like', "%$search%")
                    ->orWhere('total_price_usd', 'like', "%$search%")
                    ->orWhere('currency', 'like', "%$search%");
            });
        }
        return PackageResource::collection($packages);
    }

    public function store(CreatePackageRequest $request)
    {
        $packageData = $request->CreatePackageRequest();
        $package = Package::create($packageData);
        return new PackageResource($package);
    }

    public function show(Package $package)
    {
        $this->authorize('view', $package);
        $package->load(['MKHotel', 'MDHotel', 'tenant']);
        return new PackageResource($package);
    }

    public function update(UpdatePackageRequest $request, Package $package)
    {
        $packageData = $request->UpdatePackage();
        $package->update($packageData);
        $package->load(['MKHotel', 'MDHotel', 'tenant']);
        return new PackageResource($package);
    }

    public function destroy(Package $package)
    {
        $this->authorize('delete', $package);
        $package->delete();
        return response()->json(['message' => 'Package deleted successfully']);
    }

}
