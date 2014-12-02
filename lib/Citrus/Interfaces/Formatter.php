<?php
/**
 */

interface Citrus_Interfaces_Formatter
{
    /**
     * @param $data Should be an array or iterable
     * @return mixed
     */
    public function format(array $data);
}