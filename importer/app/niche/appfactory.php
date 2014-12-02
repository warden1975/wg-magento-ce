<?php
/**
 * User: hone
 * Date: 29/04/13
 * Time: 3:32 PM
 */

namespace app\niche;

use lib;
use interfaces;

/**
 * Class appfactory
 * @package app\niche
 */
class appfactory extends lib\appfactory
{
    /**
     * @return mixed
     */
    public function build()
    {
        $mainconfig = $this->config['app'];
        $data = new $mainconfig['data_factory']($mainconfig['api'], $this->path);
        $data = $data->build()->getRows(array());

        /**
         * @var $configurable app\niche\configurableFactory
         */
        $configurable = new $mainconfig['configurableFactory'];

        /**
         * @var  $mainconfig['class'] app\niche\niche
         */
        return new $mainconfig['class'](

            $data,
            new $mainconfig['mapper'](
                parse_ini_file( $this->path . $mainconfig['mapperFile'], true ),
                new $mainconfig['callbacks'] (
                    new $mainconfig['categories'](
                        $data,
                        parse_ini_file( $this->path . $mainconfig['magentoCategoryMap'], true )
                    ),
                    new $mainconfig['attributes'](
                        parse_ini_file( $this->path . 'config/attributes.ini', true )
                    ),
                    new $mainconfig['fromhtml'](
                        new lib\domfactory
                    )
                )
            ),
            $configurable->build(),
            new \Csv_Writer(
                $this->path .".." . $mainconfig['generatedCsvName'],
                new \Csv_Dialect(
                    array('quotechar' => '"', 'escapechar' => '"', 'quoting' => \Csv_Dialect::QUOTE_ALL)
                )
            ),
            $mainconfig

        );
    }

}