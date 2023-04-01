<?php

namespace App\Http\Controllers;

use App\Domain\Objects\ErrorBag;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected int $statusCode = Response::HTTP_OK;

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode($statusCode): static
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function respondInternalError(\Throwable $e): JsonResponse
    {
        $errors = 'An internal error has occurred';

        if (config('app.debug') === true) {
            $errors = [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ];
        }

        return $this->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)
            ->logError($e)
            ->respondWith(['errors' => $errors]);
    }

    public function respondNotFound(string $message = 'Resource not found'): JsonResponse
    {
        return $this->setStatusCode(Response::HTTP_NOT_FOUND)
            ->respondWith([
                'errors' => $message,
            ]);
    }

    private function respondWith($data = [], $headers = []): JsonResponse
    {
        return response()->json($data, $this->getStatusCode(), $headers);
    }


    private function logError($e = null): static
    {
        $action = app('request')->route()->getAction();
        $controller = class_basename($action['controller']);
        [$controller, $action] = explode('@', $controller);

        Log::error($controller . ' - ' . $action . ': ' . $this->getStatusCode());
        if ($e instanceof \Throwable) {

            if (!is_array($e)) {
                Log::error('Message - ' . $e->getMessage());
                Log::error('File - ' . $e->getFile());
                Log::error('Line - ' . $e->getLine());
            }
        } else {
            Log::error('ErrorBag', $e->getAll());
        }

        return $this;
    }
}
