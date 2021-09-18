<?php

namespace App\Helpers;

use GuzzleHttp\Client;

class Common
{
    public function __construct() {

    }

    public function converCookiesStr2Arr($strCookies) {
        $headerCookies = explode('; ', $strCookies);

        $cookies = array();

        foreach($headerCookies as $itm) {
            list($key, $val) = explode('=', $itm, 2);
            $cookies[$key] = $val;
        }
        return $cookies;
    }
}