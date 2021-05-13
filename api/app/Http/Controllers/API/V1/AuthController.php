<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function get(Request $request) {
        if ($request->isMethod('head')) {
            return response(NULL, 204);
        }

        $user = Auth::user();

        return response()->json($user);
    }

    public function login(Request $request) {
        if (Auth::check()) {
            return response()->json([
                'error' => 'You already authenticated...'
            ]);
        }

        $this->validateJSON([
            'user'     => 'required|string',
            'password' => 'required|string',
        ]);

        $login = $request->json()->get('user');
        $password = $request->json()->get('password');

        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $login)->first();
        }
        else {
            $user = User::where('user', $login)->first();
        }

        if ($user && Hash::check($password, $user->password)) {
            $user->refreshToken();

            return response()->json([
                'api_token' => $user->api_token,
                'expires'   => strtotime($user->expires)
            ]);
        }

        return response(NULL, 401);
    }

    public function logout() {
        Auth::user()->clearToken();

        return response(NULL, 204);
    }
}
