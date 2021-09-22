<?php

namespace App\Helpers;

use GuzzleHttp\Client;

class Common
{
    public function __construct() {

    }

    public function converCookiesStr2Arr($strCookies) {
        $strCookies = str_replace(" ","",$strCookies);
        $headerCookies = explode(';', $strCookies);
        $cookies = array();

        foreach($headerCookies as $itm) {
            if (!empty($itm)) {
                list($key, $val) = explode('=', $itm, 2);
                $cookies[$key] = $val;
            }
        }
        return $cookies;
    }

    public function getStrCookies($cookiesArr)
    {
        $strCookies = '';
        foreach ($cookiesArr as $cookie) {
            $strCookies .= $cookie['name'] . '=' . $cookie['value'] . ';';
        }
        return $strCookies;
    }

    public function get_string_between($string, $start, $end)
    {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }
}