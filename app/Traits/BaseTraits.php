<?php

namespace App\Traits;

use Exception;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;

trait BaseTraits
{
    public function respondSuccess($data = [], string $message = "Success", int $statusCode = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'status' => $statusCode,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    public function respondError($errors = [], string $message = "Error", int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
    {
        return response()->json([
            'status' => $statusCode,
            'message' => $message,
            'errors' => $errors
        ], $statusCode);
    }

    public function respondExceptionError(\Exception $exception): JsonResponse
    {
        $code = (int) ($exception->getCode() ?: Response::HTTP_INTERNAL_SERVER_ERROR);

        $code = $code == 1 ? Response::HTTP_INTERNAL_SERVER_ERROR : $code;

        return $this->respondError(
            $code === Response::HTTP_INTERNAL_SERVER_ERROR ?
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
            $this->respondError($validator->errors(), 'Validation failed', Response::HTTP_UNPROCESSABLE_ENTITY)
        );
    }

    public function sendEmail(string $mail_class, string $email, array $parameters = []): void
    {
        if (!$mail_class) {
            throw new Exception("Missing mail class", Response::HTTP_BAD_REQUEST);
        }

        if (!$email) {
            throw new Exception("Missing receiver mail", Response::HTTP_BAD_REQUEST);
        }

        Mail::to($email)->queue(new $mail_class(...$parameters));
    }
}
