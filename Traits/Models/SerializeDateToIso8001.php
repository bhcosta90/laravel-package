<?php


namespace Costa\Package\Traits\Models;


use DateTime;
use DateTimeInterface;

trait SerializeDateToIso8001
{
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format(DateTime::ISO8601);
    }

}
