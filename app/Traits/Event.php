<?php

namespace App\Traits;

use Exception;
use Illuminate\Support\Facades\Http;

trait Event
{
    public static function getUser()
    {

        $username = env('EVENT_USER');
        $password = env('EVENT_PASS');
        $headers = [
            // 'Authorization' => ' Bearer ' . $token,
            'Accept' => 'application/json',

        ];

        try {
            $response = Http::withHeaders($headers)->post(env('EVENT_URL') . "/api/login", ["username" => $username, "password" => $password]);
            return $response->json();
        } catch (\Throwable $th) {
        }
    }

    public static function transfer($amount, $type)
    {
        $user = Event::getUser();

        $id = isset($user['id']) ? $user['id'] : '';
        $token = isset($user['api_token']) ? $user['api_token'] : '';

        $headers = [
            'Authorization' => ' Bearer ' . $token,
            'Accept' => 'application/json',

        ];

        try {
            $response = Http::withHeaders($headers)->post(env('EVENT_URL') . "/api/user/{$id}/transfer_coin", ["amount" => $amount, "type" => $type]);
            return $response->json();
        } catch (\Throwable $th) {
        }
    }

    public static function airtime($network, $amount, $number)
    {

        $user = self::getUser();
        $id = isset($user['id']) ? $user['id'] : '';
        $token = isset($user['api_token']) ? $user['api_token'] : '';

        $headers = [
            'Authorization' => ' Bearer ' . $token,
            'Accept' => 'application/json',

        ];

        $data = [
            'network' => $network,
            'amount' => $amount,
            'number' => $number,
        ];

        try {
            $response = Http::withHeaders($headers)->post(env('EVENT_URL') . "/api/user/{$id}/airtime", $data)->throw();
            return $response->json();
        } catch (\Throwable $th) {
            throw new Exception('An Error Occured');
        }
    }

    public static function data($network, $plan, $number)
    {

        $user = self::getUser();

        $id = isset($user['id']) ? $user['id'] : '';

        $token = isset($user['api_token']) ? $user['api_token'] : '';

        $headers = [
            'Authorization' => ' Bearer ' . $token,
            'Accept' => 'application/json',

        ];

        $data = [
            'network' => $network,
            'plan' => $plan,
            'number' => $number,
        ];

        try {
            $response = Http::withHeaders($headers)->post(env('EVENT_URL') . "/api/user/{$id}/data", $data)->throw();

            return $response->json();
        } catch (\Throwable $th) {
            throw new Exception('An Error Occured');
        }
    }

    public static function dataPlans()
    {
        try {
            $response = Http::get(env('EVENT_URL') . '/data_plans')->throw();
            return $response->json();
        } catch (\Throwable $th) {
            throw new Exception('An Error Occured');
        }
    }

    public static function cable($cableCode, $planCode, $cardNumber)
    {

        $user = self::getUser();

        $id = isset($user['id']) ? $user['id'] : '';

        $token = isset($user['api_token']) ? $user['api_token'] : '';

        $headers = [
            'Authorization' => ' Bearer ' . $token,
            'Accept' => 'application/json',

        ];

        $data = [
            'network' => $cableCode,
            'plan' => $planCode,
            'iuc' => $cardNumber,
        ];

        try {
            $response = Http::withHeaders($headers)->post(env('EVENT_URL') . "/api/user/{$id}/cable", $data)->throw();

            return $response->json();
        } catch (\Throwable $th) {
            throw new Exception('An Error Occured');
        }
    }

    public static function cablePlans()
    {
        try {
            $response = Http::get(env('EVENT_URL') . '/cable_plans')->throw();
            return $response->json();
        } catch (\Throwable $th) {
            throw new Exception('An Error Occured');
        }
    }

    public static function verifyCard($code, $number)
    {
        try {
            $response = Http::get(env('EVENT_URL') . "/verify_card/{$code}/{$number}")->throw();
            return $response->json();
        } catch (\Throwable $th) {
            throw new Exception('An Error Occured');
        }
    }

    public static function utility($companyCode, $type, $meterNumber, $amount)
    {

        $user = self::getUser();
        //return $user;
        $id = isset($user['id']) ? $user['id'] : '';
        //return $id;
        $token = isset($user['api_token']) ? $user['api_token'] : '';

        $headers = [
            'Authorization' => ' Bearer ' . $token,
            'Accept' => 'application/json',

        ];

        $data = [
            'company_code' => $companyCode,
            'type' => $type,
            'meter_number' => $meterNumber,
            'amount' => $amount,
        ];

        try {
            $response = Http::withHeaders($headers)->post(env('EVENT_URL') . "/api/user/{$id}/utility", $data)->throw();
            /* if (isset(self::checkError($response->json())['error']) || isset(self::checkError($response->json())['errors'])) {
            return self::checkError($response->json());

            } */

            return $response->json();
        } catch (\Throwable $th) {
            throw new Exception('An Error Occured');
        }
    }

    public static function utilityPlans()
    {
        try {
            $response = Http::get(env('EVENT_URL') . '/utility_plans')->throw();
            return $response->json();
        } catch (\Throwable $th) {
            throw new Exception('An Error Occured');
        }
    }

    public static function verifyMeter($companyCode, $number)
    {
        try {
            $response = Http::get(env('EVENT_URL') . "/verify_meter/{$companyCode}/{$number}")->throw();
            return $response->json();
        } catch (\Throwable $th) {
            throw new Exception('An Error Occured');
        }
    }

    public static function query($ref)
    {
        try {
            $response = Http::get(env('EVENT_URL') . "/verify?order_id=$ref")->throw();
            return $response->json();
        } catch (\Throwable $th) {
            throw new Exception('An Error Occured');
        }
    }
}