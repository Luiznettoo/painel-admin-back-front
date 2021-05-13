<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    public function get(Request $request, User $user): JsonResponse {
        if ($user->id) {
            return response()->json($user->toArray());
        }

        $request->validate([
            'per-page' => 'nullable|integer',
            'page'     => 'nullable|integer'
        ]);

        $query = User::query();

        $perPage = $request->get('per-page');
        if ($perPage) {
            $query->limit($perPage);
        }

        return response()->json($query->paginate());
    }

    public function post(Request $request): JsonResponse {
        $this->validateJSON([
            'user'     => [
                'required',
                'string',
                'min:3',
                'max:64',
                'alpha_dash',
                Rule::unique('users')->where(static function (Builder $query) {
                    $query->whereNull('deleted_at');
                })
            ],
            'name'     => 'required|string|min:3|max:64',
            'email'    => [
                'required',
                'email',
                Rule::unique('users')->where(static function (Builder $query) {
                    $query->whereNull('deleted_at');
                })
            ],
            'password' => 'required|string|min:4|max:64|confirmed',
        ]);

        $fields = $request->json()->all();

        unset($fields['password'], $fields['password_confirm']);

        $user = new User($fields);
        $user->password = Hash::make($request->json()->get('password'));
        $user->save();

        return response()->json($user->toArray(), 201);
    }

    public function put(Request $request, User $user): JsonResponse {
        $this->validateJSON([
            'user'     => [
                'required',
                'string',
                'min:3',
                'max:64',
                'alpha_dash',
                Rule::unique('users')->where(static function (Builder $query) use ($user) {
                    $query->where('id', '!=', $user->id);
                    $query->whereNull('deleted_at');
                })
            ],
            'name'     => 'required|string|min:3|max:64',
            'email'    => [
                'required',
                'email',
                Rule::unique('users')->where(static function (Builder $query) use ($user) {
                    $query->where('id', '!=', $user->id);
                    $query->whereNull('deleted_at');
                })
            ],
            'password' => 'nullable|string|min:4|max:64|confirmed',
        ]);

        $fields = $request->json()->all();
        unset($fields['password'], $fields['password_confirm']);

        $user->fill($request->except([
            'password',
            'password_confirmed'
        ]));

        $password = $request->get('password');
        if ($password) {
            $user->password = Hash::make($request->json()->get('password'));
        }

        $user->save();

        return response()->json($user->toArray());
    }

    public function delete(User $user): Response {
        $user->delete();

        return response(NULL, 204);
    }
}
