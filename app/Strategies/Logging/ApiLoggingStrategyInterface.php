<?php

namespace App\Strategies\Logging;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface ApiLoggingStrategyInterface
{
    public function log(Request $request, JsonResponse $response);

}
