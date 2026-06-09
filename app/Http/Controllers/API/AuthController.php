<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Company;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    use ApiResponse;

    public function register(RegisterRequest $request)
    {
        DB::beginTransaction();

        try {

            $company = Company::create([
                'name' => $request->company_name
            ]);

            $user = User::create([
                'company_id' => $company->id,
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);

            $token = $user->createToken('api')->plainTextToken;

            DB::commit();

            return $this->success([
                'user' => $user,
                'token' => $token
            ], 'Registration successful', 201);
        } catch (\Exception $e) {

            DB::rollBack();

            return $this->error($e->getMessage(), 500);
        }
    }

    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->error('Invalid credentials', 401);
        }

        $user = Auth::user();

        $token = $user->createToken('api')->plainTextToken;

        return $this->success([
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()
            ->currentAccessToken()
            ->delete();

        return $this->success([], 'Logged out');
    }
}
