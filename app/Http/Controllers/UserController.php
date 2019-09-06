<?php

namespace App\Http\Controllers;

use App\Models\User;
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
            ->get()
            ->makeHidden(['api_token', 'email_verified_at', 'organization'])
            ->load(['organization']);
        return response()->json([
            'success' => true,
            'data' => $users
        ], 200);
    }

    public function show(User $user)
    {
        return response()->json([
            'success' => true,
            'data' => $user
        ], 200);
    }

    public function update(Request $request, User $user)
    {
        $user->update($request->all());
        return response()->json([
            'success' => true,
            'data' => $user
        ], 200);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json([
            'success' => true,
            'data' => $user
        ], 204);
    }
}
