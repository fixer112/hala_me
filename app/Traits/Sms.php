<?php

namespace App\Traits;

use App\Traits\Event;
use Illuminate\Support\Facades\Http;

trait Sms
{
    public static function sendSms($numbers, $message)
    {
        $subject = "Altechtic Solutions";

        try {

            $user = Event::getUser();
            //return $user;
            $id = isset($user['id']) ? $user['id'] : '';
            //return $id;
            $token = isset($user['api_token']) ? $user['api_token'] : '';

            $headers = [
                'Authorization' => ' Bearer ' . $token,
                'Accept' => 'application/json',

            ];


            $response = Http::withHeaders($headers)->post(env('EVENT_URL') . "/api/user/{$id}/sms/create?numbers={$numbers}&message={$message}&subject=$subject")->json();
            //return $response;

        } catch (\Throwable $th) {
            //return false;
            return $th->getMessage();
        }

        //return $response;
        return isset($response['amount']);

        //$amount = (isset($response['error']) || isset($response['errors']) || !isset($response['amount'])) ? 0 : config('settings.bank_info.sms_cost', 0);


    }
}