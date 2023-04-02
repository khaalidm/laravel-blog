<?php

namespace App\Http\Middleware;

use App\Loggers\ApiLogger;
use App\Strategies\Logging\DatabaseApiLoggingStrategy;
use App\Strategies\Logging\FileApiLoggingStrategy;
use Closure;
use Illuminate\Http\Request;

class LogApiRequests
{
    protected ApiLogger  $logger;

    public function __construct(ApiLogger $logger)
    {
        // Please note I have added the testing environment to the condition for your convenience, this will NEVER HAPPEN in a regular development environment.
        if (env('APP_ENV') === 'local' || env('APP_ENV') === 'testing') {
            $this->logger = new ApiLogger(new FileApiLoggingStrategy());
        } else {
            $this->logger = new ApiLogger(new DatabaseApiLoggingStrategy());
        }
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        $this->logger->log($request, $response);
        return $response;
    }
}
