<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Http\Requests\AuthRequests\LoginFormRequest;
use App\Http\Requests\AuthRequests\RegistrationFormRequest;
use App\Http\Resources\UserResource;
use App\Models\Role;
use JWTAuth;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginFormRequest $request)
    {
        $input = $request->only('email', 'password');
        $token = null;

        if (!$token = JWTAuth::attempt($input)) {
            return AppHelper::loginError();
        }

        return $this->respondWithToken($token);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function logout(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        try {

            JWTAuth::invalidate($request->token);

            return AppHelper::logoutSuccess();

        } catch (JWTException $exception) {

            return AppHelper::logoutError();

        }
    }


    public function register(RegistrationFormRequest $request)
    {
        $role = Role::where('role_id_public', '=', $request->role)->first();

        if (!$role) {
            return AppHelper::notFoundError($request->role, 'role');
        }
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->cin = $request->cin;
        $user->phone_number = $request->phone_number;
        $user->address = $request->address;

        // if ($user->save()) {
        if ($role->users()->save($user)) {
            return new UserResource($user);
        }else{
            return AppHelper::registerError();
        }
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
            'success' => true,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
