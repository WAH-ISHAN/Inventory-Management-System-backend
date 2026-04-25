<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller{

    //login
    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if (!Auth::attempt($request->only('email', 'password'))) {

        return response()->json(['message' => 'Invalid login details'], 401);
    }

        $user = User::where('email', $request->email)->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    //create user in admin only
    public function createUser(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,staff'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        \Illuminate\Support\Facades\DB::table('audit_logs')->insert([
            'user_id' => Auth::id() ?? $user->id,
            'action' => 'User Created',
            'model' => 'User',
            'new_values' => json_encode($user->toArray()),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
    }

    public function index() {
        return response()->json(User::orderBy('id', 'desc')->get());
    }

    public function updateUser(Request $request, $id) {
        $user = User::findOrFail($id);
        $oldValues = $user->toArray();
        
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'role' => 'sometimes|in:admin,staff'
        ]);

        $user->update($request->only('name', 'role'));

        \Illuminate\Support\Facades\DB::table('audit_logs')->insert([
            'user_id' => \Illuminate\Support\Facades\Auth::id() ?? 1,
            'action' => 'User Updated',
            'model' => 'User',
            'old_values' => json_encode($oldValues),
            'new_values' => json_encode($user->toArray()),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }

    public function deleteUser($id) {
        $user = User::findOrFail($id);
        $oldValues = $user->toArray();
        $user->delete();

        \Illuminate\Support\Facades\DB::table('audit_logs')->insert([
            'user_id' => \Illuminate\Support\Facades\Auth::id() ?? 1,
            'action' => 'User Deleted',
            'model' => 'User',
            'old_values' => json_encode($oldValues),
            'new_values' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'User deleted successfully']);
    }


    // logout
    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }


    public function regUser(Request $request){
        if($request->user()->role !== 'admin'){
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,staff'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
    }

}
