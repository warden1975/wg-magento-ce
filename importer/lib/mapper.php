<?php
/**
 * User: hone
 * Date: 22/03/13
 * Time: 10:25 AM
 */

namespace lib;

use interfaces;

class mapper implements interfaces\mapper
{

    /**
     * @var array
     */
    protected $mapper;

    /**
     * @var object
     */
    protected $callbacks;

    /**
     * @param array $mapper
     * @param object $callbacks
     */
    public function __construct($mapper, $callbacks)
    {
        $this->mapper = $mapper;
        $this->callbacks = $callbacks;
    }

    /**
     * @return array
     */
    public function getHeaders(){
        return array_keys($this->mapper);
    }

    /**
     * @param array $data
     * @return array
     */
    public function getExtractedValue(array $data){

        $new_row = array();

        foreach( $this->mapper as $options ) {

            if( isset( $options['map'] ) )
                $new_row[] = $this->tryValueByKey($data, $options['map']);
            else if ( isset( $options['default'] ) )
                $new_row[] = $options['default'];
            else if ( isset( $options['callback'] ) )
                $new_row[] = $this->callbacks->$options['callback']($data);
            else if ( isset( $options['callbackvar'] ) && isset(  $options['key'] ) )
                $new_row[] = $this->callbacks->$options['callbackvar']($data, $options['key']);
            else
                $new_row[] = '?';
        }

        return $new_row;
    }

    /**
     * @param array $array
     * @param $searchedKey
     * @return bool
     */
    public function tryValueByKey(array $array, $wantedKey) {
        foreach($array as  $key => $val) {
            if(is_array($val))
                return $this->tryValueByKey($val, $wantedKey);

            if($wantedKey == $key)
                return $val;
        }

        return false;
    }
}