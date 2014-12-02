<?php


namespace lib;

class subject{

    /**
     * @var \SplObjectStorage
     */
    protected $SplObjectStorage;

    /**
     * @param \SplObjectStorage $SplObjectStorage
     */
    public function __construct($SplObjectStorage)
    {
        $this->SplObjectStorage= $SplObjectStorage;

    }

    /**
     * @param \interfaces\app $prep_object
     */
    public function attach($prep_object){
        $this->SplObjectStorage->attach($prep_object);
    }

}