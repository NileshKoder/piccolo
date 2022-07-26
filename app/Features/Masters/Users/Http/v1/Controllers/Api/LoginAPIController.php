<?php

namespace App\Features\Masters\Users\Http\v1\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Hash;

class LoginAPIController extends ApiController
{
    public function login(Request $request)
    {
        $validate = $this->validation_token($request->token);

        if ($validate !== true) {
            return $validate;
        }

        $email = $request->email;
        $password = $request->password;

        $user = User::where('email', $email)->first();

        if (!empty($user)) {
            // Inactive check
            if ($user->status == User::INACTIVE) {
                return $this->errorResponse("User is inactive", 500);
            }

            // Successfull Match...
            if (Hash::check($password, $user->password)) {
                return $this->showOne($user, 200);
            }
        }

        return $this->errorResponse("Credentials does not match our records", 500);
    }
}
