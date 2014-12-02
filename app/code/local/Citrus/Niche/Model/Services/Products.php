<?php

class Citrus_Niche_Model_Services_Products implements Citrus_Interfaces_Observer
{

    /**
     * @param $model Citrus_Export_Model_Patterns_Subject
     */
    public function update($model)
    {
        $pdo_client = $model->getPdoClient();
        $collection = $pdo_client->selectCollection("inserts.ini");
        $order_data = $model->getPresentor();

        try {
            foreach($order_data[3] as $product)
            {
                /**
                 *  Fill up the params
                 */
                $params = array(
                    ":id"                   => 0,
                    ":code"                 => $product->full_product['niche_sku'],
                    ":price"                => $product->price,
                    ":quantity"             => $product->quantity,
                    ":name"                 => $product->full_product['name'],
                    ":external_order_id"    => $model->last_insert_order_id,
                );

                /**
                 * app/local/Citrus/Niche/Model/Ini/inserts.ini::external_order
                 */
                $collection->query("external_order_product", $params);
            }

        } catch(\Exception $e) {
            echo $e->getMessage();
        }

        echo "\n\nProducts!";
    }

}