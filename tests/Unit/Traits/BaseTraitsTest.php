<?php

namespace Tests\Traits;

use App\Mail\ForgotPasswordMail;
use App\Mail\ResetPasswordMail;
use App\Traits\BaseTraits;
use Exception;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Exceptions\HttpResponseException;
use Tests\TestCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Mockery;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertStringContainsString;

class BaseTraitsTest extends TestCase
{
    use BaseTraits, RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function testRespondSuccess()
    {
        $data = ['key' => 'value'];
        $message = 'Custom message';
        $statusCode = 202;

        $response = $this->respondSuccess($data, $message, $statusCode);

        assertEquals($statusCode, $response->getStatusCode());
        assertEquals($message, $response->getOriginalContent()['message']);
        assertEquals($data, $response->getOriginalContent()['data']);
    }

    public function testRespondError()
    {
        $errors = ['field' => ['Some error message']];
        $message = 'Custom error message';
        $statusCode = 403;

        $response = $this->respondError($errors, $message, $statusCode);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals($statusCode, $response->getStatusCode());
        $this->assertEquals($message, $response->getOriginalContent()['message']);
        $this->assertEquals($errors, $response->getOriginalContent()['errors']);
    }

    public function testRespondExceptionError()
    {
        $exception = new Exception('Something went wrong', 400);

        $response = $this->respondExceptionError($exception);

        assertInstanceOf(JsonResponse::class, $response);
        assertEquals($exception->getCode(), $response->getStatusCode());
        assertStringContainsString($exception->getMessage(), $response->getOriginalContent()['message']);
    }

    public function testRespondValidationError()
    {
        $validator = Mockery::mock(Validator::class);
        $validator->shouldReceive('errors')->once()->andReturn(['field' => ['Validation error']]);

        $this->expectException(HttpResponseException::class);

        $this->respondValidationError($validator);
    }

    public function testSendEmail_Success()
    {
        $mail_class = ResetPasswordMail::class;
        $to = 'sheja@eddy.com';

        Mail::fake();
        $this->sendEmail($mail_class, $to);

        Mail::assertQueued($mail_class, function ($mail) use ($to) {
            expect($mail->to[0]['address'])->toBe($to);
            expect($mail->build()->subject)->toContain('Password Reset Confirmation');
            expect($mail->build()->view)->toContain('emails.reset_password_mail');
            return true;
        });
    }

    public function testSendEmail_MissingMailClass()
    {
        $mail_class = '';
        $email = 'sheja@eddy.com';

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Missing mail class');

        $this->sendEmail($mail_class, $email, [$email]);
    }

    public function testSendEmail_MissingReceiverMail()
    {
        $mail_class = ForgotPasswordMail::class;
        $email = '';

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Missing receiver mail');

        $this->sendEmail($mail_class, $email);
    }
}
