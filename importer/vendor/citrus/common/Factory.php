<?php
namespace citrus\common;
/**
 * Created by JetBrains PhpStorm.
 * User: renato
 * Date: 21/03/13
 * Time: 5:03 PM
 * To change this template use File | Settings | File Templates.
 */
class Factory {
    /**
     * @param $source
     */
    public static function build($className) {
        $class = '\citrus\api\\' . ucfirst(strtolower($className));
        return new $class;
    }
}