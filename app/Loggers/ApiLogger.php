<?php

namespace App\Loggers;

use App\Strategies\Logging\ApiLoggingStrategyInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiLogger
{
    private $strategy;

    public function __construct(ApiLoggingStrategyInterface $strategy)
    {
        $this->strategy = $strategy;
    }

    public function log(Request $request, JsonResponse $response)
    {
        $this->strategy->log($request, $response);
    }
}
