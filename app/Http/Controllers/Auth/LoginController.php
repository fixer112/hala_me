<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;

class LoginController extends Controller
{
    public function login()
    {

        $this->validate(request(), [
            'phone_number' => 'required|exists:users',
        ]);

        $user = User::where('phone_number', request()->phone_number)->first();

        $user->tokens
            ->each(function ($token, $key) {
                $this->revokeAccessAndRefreshTokens($token->id);
            });

        return $user->createToken('hala_app')->accessToken;

    }

    protected function revokeAccessAndRefreshTokens($tokenId)
    {
        $tokenRepository = app('Laravel\Passport\TokenRepository');
        $refreshTokenRepository = app('Laravel\Passport\RefreshTokenRepository');

        $tokenRepository->revokeAccessToken($tokenId);
        $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($tokenId);
    }
}