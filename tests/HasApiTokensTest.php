<?php
namespace Livijn\MultipleTokensAuth\Test;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Livijn\MultipleTokensAuth\Models\ApiToken;

class HasApiTokensTest extends TestCase
{
    /** @test It can have tokens */
    public function it_can_have_tokens()
    {
        $user = factory(User::class)->create();
        factory(ApiToken::class, 3)->create(['ID_USUARIO' => $user->ID_USUARIO]);

        $this->assertEquals(3, $user->apiTokens()->count());
    }

    /** @test It can generate an api token without hash */
    public function it_can_generate_an_api_token_without_hash()
    {
        $user = factory(User::class)->create();

        $this->assertEquals(0, ApiToken::count());
        $this->assertEquals(0, $user->apiTokens()->count());

        $token = $user->generateApiToken();

        $this->assertEquals(1, ApiToken::count());
        $this->assertEquals(1, $user->apiTokens()->count());
        $this->assertEquals($user->ID_USUARIO, ApiToken::first()->ID_USUARIO);
        $this->assertEquals($token, ApiToken::first()->token);
        $this->assertTrue(ApiToken::first()->expired_at->isSameDay(now()->addDays(config('multiple-tokens-auth.token.life_length'))));
    }

    /** @test It can generate an api token with hash */
    public function it_can_generate_an_api_token_with_hash()
    {
        config()->set('auth.guards.api.hash', true);

        $user = factory(User::class)->create();

        $this->assertEquals(0, ApiToken::count());
        $this->assertEquals(0, $user->apiTokens()->count());

        $token = $user->generateApiToken();

        $this->assertEquals(1, ApiToken::count());
        $this->assertEquals(1, $user->apiTokens()->count());
        $this->assertEquals($user->ID_USUARIO, ApiToken::first()->ID_USUARIO);
        $this->assertEquals(hash('sha256', $token), ApiToken::first()->token);
    }

    /** @test It uses the hash config variable when generating a token */
    public function it_uses_the_hash_config_variable_when_generating_a_token()
    {
        $user = factory(User::class)->create();

        config()->set('multiple-tokens-auth.hash', null);
        $tokenOne = $user->generateApiToken();

        config()->set('multiple-tokens-auth.hash', true);
        $tokenTwo = $user->generateApiToken();

        config()->set('multiple-tokens-auth.hash', false);
        config()->set('auth.guards.api.hash', true);
        $tokenThree = $user->generateApiToken();

        $this->assertEquals($tokenOne, ApiToken::first()->token);
        $this->assertEquals(hash('sha256', $tokenTwo), ApiToken::skip(1)->first()->token);
        $this->assertEquals($tokenThree, ApiToken::skip(2)->first()->token);
    }

    /** @test It can purge api tokens */
    public function it_can_purge_api_tokens()
    {
        factory(ApiToken::class)->create();
        $user = factory(User::class)->create();
        $user->generateApiToken();
        $user->generateApiToken();

        $this->assertEquals(3, ApiToken::count());

        $user->purgeApiTokens();

        $this->assertEquals(1, ApiToken::count());
    }
}
