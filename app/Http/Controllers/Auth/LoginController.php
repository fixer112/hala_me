<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use DB;

class LoginController extends Controller
{
    public function login()
    {

        $this->validate(request(), [
            'phone_number' => 'required',
        ]);

        $user = User::where('phone_number', request()->phone_number)->first();

        if (!$user) {
            $result = verifyNumber(request()->phone_number);
            if (!$result) {
                abort(400, "Invalid number. Start with 234 and 13 digit.");
            }
            $user = User::create(['phone_number' => request()->phone_number]);
        }

        DB::table('oauth_access_tokens')->where('user_id', $user->id)->delete();
        $user->tokens
            ->each(function ($token, $key) {
                $this->revokeAccessAndRefreshTokens($token->id);
            });

        $data = new UserResource($user->load('chats.users', 'chats.messages'));
        $data['access_token'] = $user->createToken('hala_app')->accessToken;
        //broadcast(new UserOnline($user))->toOthers();

        return $data;
    }

    protected function revokeAccessAndRefreshTokens($tokenId)
    {
        $tokenRepository = app('Laravel\Passport\TokenRepository');
        $refreshTokenRepository = app('Laravel\Passport\RefreshTokenRepository');

        $tokenRepository->revokeAccessToken($tokenId);
        $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($tokenId);
    }
}