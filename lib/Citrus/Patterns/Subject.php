<?php
/**
 */

class Citrus_Patterns_Subject implements Citrus_Interfaces_Subject
{

    /**
     * @var SplObjectStorage
     */
    protected $SplObjectStorage;

    /**
     * @var bool
     */
    protected $update_ran = false;

    /**
     * @var array
     */
    protected $log = array();

    /**
     * @param $SplObjectStorage
     */
    public function __construct($SplObjectStorage){
        $this->SplObjectStorage = $SplObjectStorage;
    }

    /**
     * @param $observer
     * @return bool
     */
    public function attach($observer)
    {
        $this->SplObjectStorage->attach($observer);
        return true;
    }

    public function update()
    {
        $this->update_ran = false;

        foreach($this->SplObjectStorage as $observer){
            $this->log[get_class($observer)] = $observer->update($this);
        }
        $this->update_ran = true;
    }

    public function getUpdateRan()
    {
        return $this->update_ran;
    }

    public function getLog($class)
    {
        return $this->log[$class];
    }

}
