<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\LoginRequest;
use App\Http\Requests\API\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('jwt', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        $credentials = ['email' => $email, 'password' => $password];

        if (!$token = auth()->claims(['version' => '0.1'])->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Register new customer user.
     *
     * @param RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        try {
            $name = trim($request->input("name"));
            $email = trim($request->input("email"));
            $password = trim(bcrypt($request->input("password")));

            //create user
            DB::beginTransaction();
            $user = new User();
            $user->name = $name;
            $user->email = $email;
            $user->password = $password;
            $result = $user->save();

            //assign customer role
            $user->assignRole("customer");

            if ($result) {
                DB::commit();
                return response()->json('User Registered', 200);
            } else {
                DB::rollBack();
                return response()->json('Server Error', 500);
            }

        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json('Server Error - '.$ex->getMessage(), 500);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
