<?php
/**
 */


class Citrus_Factories_Builders implements Citrus_Interfaces_Buildervar {

    /**
     * @param $builders array
     * @param $data
     */
    public function __construct($builders, $data) {
        $this->builders = $builders;
        $this->data = $data;
    }

    /**
     * @param $type
     * @return string
     */
    public function build($type){
        $type = $this->builders[$type];
        $class = $type['class'];
        $builder = $type['builder'];
        $final = new $builder($class, $this->data);
        return $final->build();
    }

}

