<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCartRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\CartResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Cart;


class VisitorController extends Controller
{

    public function index(Request $request)
    {
        if ($request->filled('q')) {
            $search = $request->input('q');
            $visitors = User::where(function ($query) use ($search) {
                $query->where('role', 'visitor')
                    ->orwhere('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%");
            })->paginate(10);
        } else {
            $visitors = User::where('role', 'visitor')->paginate(10);
        }

        return UserResource::collection($visitors);
    }


    public function store(RegisterRequest $request)
    {
        $user = User::create($request->createUser());
        $user->assignRole($user->role);

        return UserResource::make($user);
    }

    public function show(User $user)
    {
        return UserResource::make($user);
    }


    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->updateUser());
        return UserResource::make($user);
    }



    public function destroy(User $user)
    {
        $user->delete();
        return response()->noContent();
    }

    public function addToCart(CreateCartRequest $request)
    {
        $cartData = $request->createCart();
        $cart = Cart::create($cartData);
        return response()->json(['message' => 'Package added to cart successfully', 'cart' => $cart], 201);
    }

    public function viewCart(User $visitor)
    {
        $cartItems = Cart::with('package')->where('visitor', $visitor->id)
            ->paginate(10);

        return CartResource::collection($cartItems);
    }

    public function removeFromCart(Cart $cart)
    {
        $cart->delete();
        return response()->json(['message' => 'Package removed from cart successfully']);
    }


}
