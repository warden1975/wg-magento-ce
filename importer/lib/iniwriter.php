<?php
/**
 * User: hone
 * Date: 26/03/13
 * Time: 3:22 PM
 */

namespace lib;

class iniwriter {

    /**
     * @var string
     */
    public $file;

    /**
     * @param $file
     */
    public function __construct($file){
        $this->file = $file;
    }

    /**
     * @param $name
     * @param array $rows
     */
    public function write($name, $rows){
        try {
            $handle = fopen($this->file, "a");
            fwrite($handle, "[$name]\n");
            foreach($rows as $key => $value)
                fwrite($handle, "$key = '$value'\n");
            fclose($handle);
            return "Success writing [$name] to $this->file";
        }
        catch ( Exception $e ){
            return $e->getMessage();
        }

    }
}