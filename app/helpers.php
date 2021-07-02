<?php

use Illuminate\Support\Arr;

function formatPhoneNumbers(array $numbers)
{
    $new = [];
    foreach ($numbers as $number) {
        if (!Str::startsWith($number, '+234') || !Str::startsWith($number, '234')) {
            $number = "234" . Str::substr($number, -10, 10);
        }
        if (Str::startsWith($number, '+234')) {
            $number = Str::substr($number, 1);
        }

        array_push($new, str_replace(' ', '', $number));
    }
    return $new;
}

function verifyNumber(String $number)
{
    $number = str_replace(' ', '', $number);
    if (Str::startsWith($number, '+234')) {
        $number = str_replace('+', '', $number);
    }
    if (!Str::startsWith($number, '234')) {
        return false;
    }

    if (Str::length($number) != 13) {
        return false;
    }

    return true;
}