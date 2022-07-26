<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Traits\ApiResponser;

class ApiController extends Controller
{
    use ApiResponser;

    public function __construct()
    {
    }

    private function validation_token($token)
    {
        // No time to implement access token. So, setting a static access token by default..
        if ($token != 'piccoloDashboard@1') {
            return ['error' => 'Please provide an access token.'];
        }

        return true;
    }
}
