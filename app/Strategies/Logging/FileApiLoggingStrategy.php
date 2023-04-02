<?php

namespace App\Strategies\Logging;


use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FileApiLoggingStrategy implements ApiLoggingStrategyInterface
{
    protected $logPath;

    public function __construct()
    {
        $this->logPath = 'logs/api-logs-' . Carbon::now()->format('Y-m-d');
    }

    public function log(Request $request, JsonResponse $response)
    {
        // Log the request data to a file
        $logData = [
            'route'    => $request->route()->uri(),
            'request'  => $request->all(),
            'response' => $response->getContent(),
        ];
        $this->writeToLog($logData);
    }

    protected function writeToLog($data)
    {
        $logData = date('Y-m-d H:i:s') . ' ' . json_encode($data) . "\n";
        file_put_contents($this->logPath, $logData, FILE_APPEND);
    }
}
