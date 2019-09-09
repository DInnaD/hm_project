<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserCollection;
// use App\Http\Resources\UserResource;
use App\Models\User;
use App\Http\Resources\User as UserResource;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $users = User::where('first_name', 'like', "%{$search}%")
            ->orWhere('last_name', 'like', "%{$search}%")
            ->orWhere('country', 'like', "%{$search}%")
            ->orWhere('city', 'like', "%{$search}%")
            ->get();
            // ->makeHidden(['api_token', 'email_verified_at', 'organization'])
            // ->load(['organization']);
        // return response()->json([
        //     'success' => true,
        //     'data' => $users
        // ], 200);
        return new UserCollection($users);
    }

    public function show(User $user)
    {
        // return response()->json([
        //     'success' => true,
        //     'data' => $user
        // ], 200);
        return new UserResource($user);
    }

    public function update(Request $request, User $user)
    {
        $user->update($request->all());
        // return response()->json([
        //     'success' => true,
        //     'data' => $user
        // ], 200);
        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        $user->delete();
        // return response()->json([
        //     'success' => true,
        //     'data' => $user
        // ], 204);
        return new UserResource($user);
    }
}
