<?php
/**
 * Created by JetBrains PhpStorm.
 * User: renato
 * Date: 21/03/13
 * Time: 5:32 PM
 * To change this template use File | Settings | File Templates.
 */

namespace citrus\api;

abstract class AImporter {

    /**
     * @var array
     */
    private $mapper;

    /**
     * @param bool $section
     * @return array
     * @throws \Exception
     */
    protected function getConfig($section = false)
    {
        $config_path = APP_PATH . '/importer/config/import.ini';
        $configs = parse_ini_file( $config_path, true );

        if(isset($section)) {
            if(!isset($configs[$section]))
                throw new \Exception('Section does not exist in the import configuration');

            return $configs[$section];
        }

        return $configs;
    }

    /**
     * @return array
     */
    protected function getMapper() {

        if($this->mapper !== null)
            return $this->mapper;

        // imports mapped fields
        $mapper_file = APP_PATH . '/importer/config/mapper_' . strtolower(end(explode('\\', get_called_class()))) . '.ini';

        if(!file_exists($mapper_file))
            throw new \Exception('Mapper file does not exist');

        return $this->mapper = parse_ini_file( $mapper_file , true );
    }

    /**
     * @param array $array
     * @param $searchedKey
     * @return bool
     */
    protected function getValueByKey(array $array, $searchedKey) {
        foreach($array as  $key => $val) {
            if(is_array($val))
                return $this->getValueByKey($val, $searchedKey);

            if($searchedKey == $key)
                return $val;
        }

        return false;
    }
}