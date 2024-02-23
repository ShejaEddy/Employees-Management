<?php

namespace App\Traits;

use Exception;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

trait BaseTraits
{
    public function respondSuccess($data = [], string $message = "Success", int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'status' => $statusCode,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    public function respondError($errors = [], string $message = "Error", int $statusCode = 500): JsonResponse
    {
        return response()->json([
            'status' => $statusCode,
            'message' => $message,
            'errors' => $errors
        ], $statusCode);
    }

    public function respondExceptionError(\Exception $exception): JsonResponse
    {
        $code = (int) ($exception->getCode() ?: 500);

        return $this->respondError(
            $code === 500 ?
                [
                    "message" => $exception->getMessage(),
                    "file" => $exception->getFile(),
                    "line" => $exception->getLine(),
                    "trace" => $exception->getTraceAsString(),
                ] : [],
            $exception->getMessage(),
            $code
        );
    }

    public function respondValidationError(Validator $validator): void
    {
        throw new HttpResponseException(
            $this->respondError($validator->errors(), 'Validation failed', 422)
        );
    }

    public function sendEmail(string $mail_class, string $email, array $parameters = []): void
    {
        if (!$mail_class) {
            throw new Exception("Missing mail class");
        }

        if (!$email) {
            throw new Exception("Missing receiver mail");
        }

        Mail::to($email)->queue(new $mail_class(...$parameters));
    }
}
