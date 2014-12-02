<?php
/**
 */

class Citrus_Builder_Example implements Citrus_Interfaces_Builder
{

    public function build(){
        return array("hello" => "world");
    }

}