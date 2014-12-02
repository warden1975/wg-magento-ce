<?php
/**
 * User: hone
 * Date: 29/04/13
 * Time: 11:36 AM
 */

namespace app\telegram;

use lib;

class configurable extends lib\subject {

    /**
     * @var
     */
    protected $headers;

    /**
     * An array of simple products
     * @var $data array
     */
    protected $data;
    
    /**
     * @return array
     */
    public function getHeaders(){
        return $this->headers;
    }

    /**
     * @param $headers array
     * @return void
     */
    public function setHeaders(array $headers) {
        $this->headers = $headers;
    }

    /**
     * @return array
     */
    public function getData(){
        return $this->data;
    }

    /**
     * @param $data array
     * @return void
     */
    public function setData($data){
        $this->data = $data;
    }

    /**
     * @param $data array
     * @return mixed
     */
    public function getConfigurables($data)
    {
        $this->data = $data;

        foreach($this->SplObjectStorage as $object){

            echo sprintf("\n\nRunning %s...\n\n", get_class($object));

            $final_data = $object->run($this);

        }
        return $final_data->data;
    }
}