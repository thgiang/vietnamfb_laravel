<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class BaseRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {

        $errors = (new ValidationException($validator))->errors();
        [$keys, $values] = Arr::divide($errors);
        throw new HttpResponseException(response()->json(
            [
                'success' => false,
                'message' => $values[0][0] ?? 'Không được để trống'
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }
}
