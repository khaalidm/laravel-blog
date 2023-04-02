<?php

namespace App\Strategies\Logging;

use App\Models\ApiLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DatabaseApiLoggingStrategy implements ApiLoggingStrategyInterface
{
    public function log(Request $request, JsonResponse $response)
    {
        // Log the request data to the database
        $log = new ApiLog();
        $log->route = $request->route()->uri();
        $log->request = json_encode($request->all());
        $log->response = json_encode($response->getContent());
        $log->save();
    }
}
