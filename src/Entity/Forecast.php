<?php


namespace App\Entity;


class Product
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

}