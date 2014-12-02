<?php
/**
 * User: hone
 * Date: 29/04/13
 * Time: 11:53 AM
 */

namespace app\niche\observers;

use interfaces;

class arrange implements interfaces\observer
{

    /**
     * This method arranges the an array of simple products
     * into a new associative array into arrays of simple products
     * grouped by namekeys.
     * @param $configurable
     * @return array
     */
    public function run($configurable)
    {
        $headers = $configurable->getHeaders();
        $sort_key = array_search('namekey', $headers);

        $rows = $configurable->getData();

        // Obtain a list of columns
        foreach ($rows as $key => $row) {
            $namekey[$key]  = $row[$sort_key];
        }

        // Sort the data using the common key
        array_multisort($namekey, $rows);

        // turn them back for associative array
        foreach($rows as $key => $row) {
            foreach($row as $subkey => $subrow) {
                $new_rows[$key][$headers[$subkey]]= $subrow;
            }
        }

        $configurable->setData($new_rows);

        return $configurable;
    }

}