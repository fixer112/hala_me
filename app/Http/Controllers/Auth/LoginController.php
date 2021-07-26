<?php

namespace App\Http\Controllers\Auth;

use DB;
use App\Traits\Sms;
use App\Models\User;
use App\Traits\Event;
use App\Events\UserOnline;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;

class LoginController extends Controller
{
    public function login()
    {

        $this->validate(request(), [
            'phone_number' => 'required|starts_with:234|digits:13|numeric',
        ]);

        $number = formatPhoneNumbers([request()->phone_number])[0];

        $user = User::where('phone_number', $number)->first();

        if (!$user) {
            $result = verifyNumber($number);
            if (!$result) {
                abort(400, "Invalid number. Start with 234 and 13 digit.");
            }

            $user = User::create(['phone_number' => $number]);
        }

        if (empty(request()->otp)) {

            if (empty($user->last_login) || $user->last_login->diffInDays() >= 1 || empty($user->otp)) {
                $rand = rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9); //. rand(0, 9) . rand(0, 9);
                $message = "Your secure otp is $rand.";
                Sms::sendSms(str_replace('234', '', $user->phone_number), $message);
                $user->update(['otp' => $rand]);
                $user->update(['last_login' => now()]);
                //send sms
            }

            return 'otp sent';
        }

        /* if (request()->device_id && isEmptyOrNullString($user->device_id)) {
            $user->update(['device_id' => request()->device_id]);
        }

        if($user->device_id != request()->device_id){

        } */



        if ($user->otp != request()->otp) {
            abort(401, 'Invalid OTP.' . request()->otp);
        }

        DB::table('oauth_access_tokens')->where('user_id', $user->id)->delete();
        $user->tokens
            ->each(function ($token, $key) {
                $this->revokeAccessAndRefreshTokens($token->id);
            });

        $user->update(['last_login' => now(), 'otp' => '']);
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