<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $query = User::with('tenant')
          ->where('id', '!=', auth()->id());

        if (auth()->user()->hasRole('manager')) {
            $query->where('tenant_id', auth()->user()->tenant_id)
                  ->where('role', '!=', 'super');
        }

        if ($request->filled('q')) {
            $search = $request->query('q');

            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('first_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('username', 'LIKE', '%' . $search . '%')
                    ->orWhere('id', 'LIKE', '%' . $search . '%')
                    ->orWhere('email', 'LIKE', '%' . $search . '%');
            });

        }

        $users = $query->paginate(6);
        return UserResource::collection($users);
    }



    public function show(User $user)
    {
        return new UserResource($user);
    }


    public function update(UpdateUserRequest $request, User $user)
    {
        $userData = $request->updateUser();
        $user->update($userData);

        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }



    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }

    //Reset password by superadmin or manager
    public function resetPassword(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $request->validate([
            'password' => 'required|string|min:6',
        ]);

        $user->update(['password' => bcrypt($request->input('password'))]);

        return response()->json(['message' => 'Password reset successfully']);
    }

    // Update password for the authenticated user
    public function updatePassword(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);
        if (!password_verify($request->input('current_password'), $user->password)) {
            return response()->json(['message' => 'كلمة المرور الحالية غير صحيحة.'], 403);
        }
        $user->update(['password' => bcrypt($request->input('new_password'))]);
        return response()->json(['message' => 'تم تحديث كلمة المرور بنجاح.']);
    }

        public function profile()
    {
        $user = auth()->user();
        if ($user->IsManager() || $user->IsEmployee() ){
            $user->load('tenant');
        }
        return new UserResource($user);
    }

    // public function updateProfile(Request $request)
    // {
    //     $user = auth()->user();

    //     $data = $request->validate([
    //         'full_name' => 'required|string|max:255',
    //         'email' => 'required|email|max:255|unique:users,email,' . $user->id,
    //         'phone' => 'nullable|string|max:20',
    //         'username' => 'required|string|max:255|unique:users,username,' . $user->id,
    //     ]);

    //     $user->update($data);

    //     return response()->json(['message' => 'تم تحديث الملف الشخصي بنجاح', 'user' => new UserResource($user)]);
    // }


}
