<?php

namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller as BaseController;

class ApiController extends BaseController
{
    /**
     * Default response for all requests
     *
     * @param mixed $data
     * @param bool $success
     * @return \Illuminate\Http\JsonResponse
     */
    public function response($data = [], $success = true)
    {
        return response()->json([
            'success' => $success,
            'data' => $data,
        ]);
    }
}
