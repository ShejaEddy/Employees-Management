<?php

namespace App\Traits;

use Exception;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Mail;

trait BaseTraits
{

    public function respondSuccess($data = [], string $message = "Success", int $statusCode = 200)
    {
        return response($message, $statusCode)->json([
            'status' => $statusCode,
            'message' => $message,
            'data' => $data
        ]);
    }

    public function respondError($errors = [], string $message = "Error", int $statusCode = 500)
    {
        return response($message, $statusCode)->json([
            'status' => $statusCode,
            'message' => $message,
            'errors' => $errors
        ]);
    }

    public function respondExceptionError(\Exception $exception)
    {
        return $this->respondError(
            [
                "message" => $exception->getMessage(),
                "file" => $exception->getFile(),
                "line" => $exception->getLine(),
                "trace" => $exception->getTraceAsString(),
            ],
            $exception->getMessage(),
            $exception->getCode()
        );
    }

    public function respondValidationError(Validator $validator)
    {
        throw new HttpResponseException(
            $this->respondError($validator->errors(), 'Validation failed', 422)
        );
    }

    public function sendEmail($mail_class, $email, $parameters = [])
    {
        if (!$mail_class) {
            throw new Exception("Missing mail class");
        }

        if (!$email) {
            throw new Exception("Missing receiver mail");
        }

        Mail::to($email)->queue(new $mail_class(...$parameters));
    }

    public function getFirstName($names)
    {
        if (empty($names)) return "Unknown";

        $names_arr = explode(" ", $names);

        return $names_arr[0];
    }
}
