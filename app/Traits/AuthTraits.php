<?php

use App\Mail\ForgotPasswordMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

trait AuthTraits
{
    public int $expirationLimit = 120; // 2 hours
    public int $resendInterval = 2; // 2 minutes

    public function generateRandomToken(): string
    {
        return bin2hex(random_bytes(32));
    }

    public function getResetTokenByEmail(string $email, ?string $token_key = null): ?object
    {
        if (!$email) {
            return null;
        }

        $token = DB::table('password_resets')
            ->where('email', $email)
            ->when($token_key, function ($query, $token_key) {
                return $query->where('token', $token_key);
            })
            ->latest()
            ->first();

        return $token;
    }

    public function createResetToken(string $email, string $token): bool
    {
        return DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => now()
        ]);
    }

    public function deleteResetToken(object $token): bool
    {
        return $token->delete();
    }

    public function checkTokenExpiry(object $token): bool
    {
        if (!$token) {
            return true;
        }

        $createdAt = strtotime($token->created_at);
        $now = strtotime(now());

        $differenceInSeconds = $now - $createdAt;
        $differenceInMinutes = floor($differenceInSeconds / 60);

        return $differenceInMinutes > $this->expirationLimit;
    }

    public function checkResendInterval(object $token): array
    {
        if (!$token) {
            return [
                "reject" => false,
                "time_left" => 0
            ];
        }

        $createdAt = strtotime($token->created_at);
        $now = strtotime(now());

        $differenceInSeconds = $now - $createdAt;
        $differenceInMinutes = floor($differenceInSeconds / 60);

        $reject = $differenceInMinutes < $this->resendInterval;

        return [
            "reject" => $reject,
            "time_left" => floor(($this->resendInterval * 60) - $differenceInSeconds)
        ];
    }
}
