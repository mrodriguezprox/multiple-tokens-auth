<?php
namespace Livijn\MultipleTokensAuth\Traits;

use Illuminate\Support\Str;
use Livijn\MultipleTokensAuth\Models\ApiToken;

trait HasApiTokens
{
    public function apiTokens()
    {
        return $this->hasMany(ApiToken::class);
    }

    public function generateApiToken()
    {
        $useHash = config('multiple-tokens-auth.hash') ?? config('auth.guards.api.hash', false);
        $unique = false;
        $token = null;
        $hashedToken = null;

        while (! $unique) {
            $token = Str::random(config('multiple-tokens-auth.token.char_length'));
            $hashedToken = $useHash
                ? hash('sha256', $token)
                : $token;

            $unique = ApiToken::where('token', $hashedToken)->exists() == false;
        }

        ApiToken::create([
            'ID_USUARIO' => $this->getAuthIdentifier(),
            'token' => $hashedToken,
            'expired_at' => now()->addMinutes(config('multiple-tokens-auth.token.life_length')),
        ]);

        return $token;
    }

    public function purgeApiTokens()
    {
        $this->apiTokens()->delete();
    }
}
