<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class BaseFormRequest extends FormRequest
{
    /**
     * @param Validator $validator
     *
     * @return JsonResponse
     */
    protected function failedValidation(Validator $validator): JsonResponse
    {
        throw new HttpResponseException(response()->json([
            'message' => Response::$statusTexts[Response::HTTP_BAD_REQUEST],
            'code'    => Response::HTTP_BAD_REQUEST,
            'errors'  => $validator->errors(),
        ], Response::HTTP_BAD_REQUEST));
    }
}
