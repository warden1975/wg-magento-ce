<?php

/**
 * Class Citrus_Interfaces_Observer
 */
interface Citrus_Interfaces_Observer
{
    /**
     * @param $model
     * @return mixed
     */
    public function update($model);
}