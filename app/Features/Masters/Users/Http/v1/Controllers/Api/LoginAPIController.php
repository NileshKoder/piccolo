<?php

namespace App\Features\Masters\Users\Http\v1\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Features\Common\ApiTrait;
use Illuminate\Support\Facades\Hash;

class LoginAPIController
{
    use ApiTrait;

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
                return ['status' => false, 'message' => "User is inactive"];
            }

            // Successfull Match...
            if (Hash::check($password, $user->password)) {
                return ['status' => true, 'message' => "Login Successfully", 'data' => $user];
            }
        }

        return ['status' => false, 'message' => "Credentials does not match our records"];
    }
}
