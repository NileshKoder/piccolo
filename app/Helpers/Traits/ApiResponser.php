<?php

namespace App\Helpers\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait ApiResponser
{
    private function successReponse($data, $code)
    {
        return response()->json($data, $code);
    }

    protected function errorResponse($message, $code)
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

    protected function showAll($collection, $code = 200)
    {
        return $this->successReponse(['data' => $collection], $code);
    }

    protected function showOne($model, $code = 200)
    {
        return $this->successReponse(['data' => $model], 200);
    }
}
