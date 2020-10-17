<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Http\Requests\AuthRequests\RegistrationFormRequest;
use App\Http\Resources\UserResource;
use App\Models\Language;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index($roleId)
    {
        $role = Role::where('role_id_public', '=', $roleId)->first();
        if (!$role) {
            return AppHelper::notFoundError($roleId, 'Role');
        }

        $users = $role->users()
            ->where('approved', '=', 1)
            ->where('disabled', '=', 0)
            ->get();
        return UserResource::collection($users);
    }

    public function accountsToApprove()
    {
        $users = User::where('approved', '=', 0)
            ->where('disabled', '=', 0)
            ->where('role_id', '=', 3)
            ->get();
        return UserResource::collection($users);
    }

    public function disabledAccounts($roleId)
    {
        $role = Role::where('role_id_public', '=', $roleId)->first();
        if (!$role) {
            return AppHelper::notFoundError($roleId, 'Role');
        }
        $users = $role->users()
            ->where('approved', '=', 1)
            ->where('disabled', '=', 1)
            ->get();
        return UserResource::collection($users);
    }

    public function approveAccount($userId)
    {
        $user = User::where('user_id_public', '=', $userId)->first();
        if (!$user) {
            return AppHelper::notFoundError($userId, 'User');
        }
        $user->approved = 1;

        if ($user->save()) {
            return response()->json([
                'success' => true,
                'message' => 'User has been Approved '
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Sorry, User Cannot be approved for the moment '
        ], 500);

    }

    public function disableAccount($userId)
    {
        $user = User::where('user_id_public', '=', $userId)->first();
        if (!$user) {
            return AppHelper::notFoundError($userId, 'User');
        }
        $user->disabled = 1;

        if ($user->save()) {
            return response()->json([
                'success' => true,
                'message' => 'User has been Disabled '
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Sorry, User Cannot be Disabled for the moment '
        ], 500);

    }

    public function store(RegistrationFormRequest $request)
    {
        //
    }


    public function show($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        $user = User::where('user_id_public', '=', $id)->first();
        if (!$user) {
            return AppHelper::notFoundError($id, 'User');
        }

        $user->name = $request->name;
        $user->cin = $request->cin;
        // here add fields to modify

        if ($user->save()) {
            return response()->json([
                'success' => true,
                'message' => 'User has been Updated'
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Sorry, User Cannot be Updated for the moment '
        ], 500);
    }


    public function destroy($id)
    {
        $user = User::where('user_id_public', '=', $id)->first();
        if (!$user) {
            return AppHelper::notFoundError($id, 'User');
        }


        if ($user->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'User has been Deleted'
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Sorry, User Cannot be Deleted for the moment '
        ], 500);
    }
}
