<?php

/**
 * Class Citrus_Export_Model_Patterns_Subject
 */
class Citrus_Niche_Model_Patterns_Subject extends Citrus_Patterns_Subject
{

    /**
     * @var SplObjectStorage
     */
    protected $SplObjectStorage;

    /**
     * @var
     */
    protected $model;

    /**
     * @param $SplObjectStorage
     * @param $model
     */
    public function __construct($SplObjectStorage, $model)
    {
        $this->SplObjectStorage = $SplObjectStorage;
        $this->model = $model;
    }

    public function update()
    {
        $this->update_ran = false;
        foreach($this->SplObjectStorage as $observer){
            $this->log[get_class($observer)] = $observer->update( $this->model );
        }
        $this->update_ran = true;
    }

}