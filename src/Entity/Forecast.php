<?php


namespace App\Entity;


class Forecast
{
    private $day;

    private $date;

    private $low;

    private $high;

    private $text;

    private $code;

    public function getDay()
    {
        return $this->day;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getLow()
    {
        return $this->low;
    }

    public function getHigh()
    {
        return $this->high;
    }

    public function getText()
    {
        return $this->text;
    }

    public function getCode()
    {
        return $this->code;
    }

    // Copyright 2019 Oath Inc. Licensed under the terms of the zLib license see https://opensource.org/licenses/Zlib for terms.

    static function buildBaseString($baseURI, $method, $params) {
        $r = array();
        ksort($params);
        foreach($params as $key => $value) {
            $r[] = "$key=" . rawurlencode($value);
        }
        return $method . "&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $r));
    }

    static function buildAuthorizationHeader($oauth) {
        $r = 'Authorization: OAuth ';
        $values = array();
        foreach($oauth as $key=>$value) {
            $values[] = "$key=\"" . rawurlencode($value) . "\"";
        }
        $r .= implode(', ', $values);
        return $r;
    }
}