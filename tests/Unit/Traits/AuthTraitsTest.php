<?php

namespace Tests\Traits;

use App\Traits\AuthTraits;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertNotEquals;
use function PHPUnit\Framework\assertTrue;

class AuthTraitsTest extends TestCase
{
    use AuthTraits, RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function testGenerateRandomToken()
    {
        $token1 = $this->generateRandomToken();
        $token2 = $this->generateRandomToken();

        assertNotEquals($token1, $token2);
        assertEquals(64, strlen($token1));
    }

    public function testGetResetTokenOrEmailWithEmail()
    {
        $email = 'sheja@eddy.com';

        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => 'test_token'
        ]);

        $token = $this->getResetTokenByEmail($email);

        assertEquals($email, $token->email);
    }

    public function testGetResetTokenOrEmailWithEmailAndToken()
    {
        $email = 'sheja@eddy.com';
        $token_key = 'test_token';

        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token_key
        ]);

        $token = $this->getResetTokenByEmail($email, $token_key);

        assertEquals($token_key, $token->token);
        assertEquals($email, $token->email);
    }


    public function testCreateResetToken()
    {
        $email = 'test@example.com';
        $token_key = $this->generateRandomToken();

        $success = $this->createResetToken($email, $token_key);

        assertTrue($success);

        $token = $this->getResetTokenByEmail($email, $token_key);

        assertEquals($token_key, $token->token);
        assertEquals($email, $token->email);
    }

    public function testDeleteResetToken()
    {
        $email = 'sheja@eddy.com';
        $token_key = 'test_token';

        $this->createResetToken($email, $token_key);

        $token = $this->getResetTokenByEmail($email, $token_key);

        $success = $this->deleteResetToken($token);

        assertTrue($success);

        $token = $this->getResetTokenByEmail($email, $token_key);

        assertEquals(null, $token);
    }

    public function testCheckTokenExpiryWithExpiredToken()
    {
        $email = 'sheja@eddy.com';
        $token_key = 'test_token';

        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token_key,
            'created_at' => now()->subHours(3)
        ]);

        $token = $this->getResetTokenByEmail($email, $token_key);

        $isExpired = $this->checkTokenExpiry($token);

        assertTrue($isExpired);
    }

    public function testCheckTokenExpiryWithValidToken()
    {
        $email = 'sheja@eddy.com';
        $token_key = 'test_token';

        $this->createResetToken($email, $token_key);

        $token = $this->getResetTokenByEmail($email, $token_key);

        $isExpired = $this->checkTokenExpiry($token);

        assertFalse($isExpired);
    }

    public function testCheckResendIntervalWithinInterval()
    {
        $email = 'sheja@eddy.com';
        $token_key = 'test_token';

        $this->createResetToken($email, $token_key);

        $token = $this->getResetTokenByEmail($email, $token_key);

        $resend = $this->checkResendInterval($token);

        assertTrue($resend['reject']);
        assertEquals(60, $resend['time_left']);
    }

    public function testCheckResendIntervalOutsideInterval()
    {
        $email = 'sheja@eddy.com';
        $token_key = 'test_token';

        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token_key,
            'created_at' => now()->subSeconds(60)
        ]);

        $token = $this->getResetTokenByEmail($email, $token_key);

        $resend = $this->checkResendInterval($token);

        assertEquals(0, $resend['time_left']);
        assertFalse($resend['reject']);
    }
}
