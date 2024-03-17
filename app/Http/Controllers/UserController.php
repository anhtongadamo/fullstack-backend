<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends BaseController
{
    public function login(Request $request, User $user)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email|max:128',
                'password' => 'required|string|max:16',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse(
                    "Validation error",
                    "Validation error.",
                    $validator->errors(),
                    422
                );
            }
            $user = $user->findUserByEmail($request->email);

            if (!$user || !Hash::check($request->password, $user->password)) {
                return $this->errorResponse(
                    "Invalid email or password",
                    "Internal server error",
                    [],
                    500
                );
            }

            $token = $user->createToken(config('constants.secret_key'));

            return $this->successResponse("Login success", "Login success", [
                'user' => new UserResource($user),
                'token' => $token->plainTextToken
            ], 200);
        } catch (Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 'Server error', [], 500);
        }
    }

    public function register(Request $request, User $user)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'nullable|string|max:128',
                'email' => 'required|string|email|max:128',
                'password' => 'required|string|max:16',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse(
                    "Validation error",
                    "Validation error.",
                    $validator->errors(),
                    422
                );
            }
            $userCreated = $user->createUser([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);


            return $this->successResponse("Success created user", "User created", [
                'user' => new UserResource($userCreated)
            ], 200);
        } catch (Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 'Server error', [], 500);
        }
    }
}
