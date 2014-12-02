<?php
/**
 * Created by JetBrains PhpStorm.
 * User: renato
 * Date: 21/03/13
 * Time: 9:33 AM
 * To change this template use File | Settings | File Templates.
 */
namespace citrus\api;

use sandeepshetty\shopify_api;

class Shopify extends \citrus\api\AImporter {

    /**
     * @var array $configs
     */
    private $configs;

    /**
     *
     */
    public function __construct() {
        $this->configs = $this->getConfig('shopify');
    }

    /**
     *
     */
    public function generateProductsCsv()
    {
        // creates the csv file
        $csv_path = APP_PATH . '/var/import/' . $this->configs['generatedCsvName'];

        // get columns
        $all_columns = array();

        foreach( $this->getMapper() as $attr_name => $attr_options ) {
            array_push($all_columns, $attr_name);
        }

        // saves the file
        $dialect = new \Csv_Dialect(array('quotechar' => "'", 'escapechar' => "'", 'quoting' => \Csv_Dialect::QUOTE_ALL));
        $csv_writer = new \Csv_Writer($csv_path, $dialect);
        $csv_writer->writeRow($all_columns);

        foreach($this->getProducts() as $product) {
            $new_row = $this->getExtractedValue($product);
            $csv_writer->writeRow($new_row);
        }

        //$csv_writer->close();
        echo "\n\nWritten to $csv_path\n\n";
    }

    /**
     * @return string
     */
    public function getProducts() {

        // For private apps:
        $shopify = shopify_api\client($this->configs['domain'], NULL, $this->configs['apiKey'], $this->configs['sharedSecret'], true);

        // Get all products
        return $shopify('GET', '/admin/products.json', array('published_status'=>'published'));
    }

    /**
     * @param array $data
     */
    public function getExtractedValue(array $data)
    {
        $new_row = array();

        foreach( $this->getMapper() as $key => $options ) {

            if(array_key_exists('map', $options))
                $new_row[] = $this->getValueByKey($data, $options['map']);
            else if (array_key_exists('default', $options))
                $new_row[] = $options['default'];
            else if (array_key_exists('callback', $options))
                $new_row[] = $this->$options['callback']($data);
            else
                throw new \Exception('Mapped field ' . $key . ' has no configuration defined');
        }

        return $new_row;
    }

    /**
     * @param array $data
     * @return string
     */
    private function getDescription(array $data)
    {
        return trim(preg_replace('/\s+/', ' ', $data['body_html']));
    }
}