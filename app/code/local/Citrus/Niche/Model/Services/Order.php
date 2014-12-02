<?php

/**
 * Class Citrus_Niche_Model_Services_Order
 */
class Citrus_Niche_Model_Services_Order implements Citrus_Interfaces_Observer
{
    /**
     * @var SoapClient
     */
    protected $wsdl_client;

    /**
     * @param SoapClient $wsdl_client
     */
    public function __construct(SoapClient $wsdl_client)
    {
        $this->wsdl_client = $wsdl_client;
    }

    /**
     * @param Mage_Sales_Model_Order $order
     * @return string
     * @throws Exception
     */
    public function update($order)
    {
        $address = $order->getShippingAddress()->getStreet();
        $suburb = '';
        $state = $order->getShippingAddress()->getRegionCode();
        $postcode = $order->getShippingAddress()->getPostcode();
        $country = $order->getShippingAddress()->getCountry();
        $phone = $order->getShippingAddress()->getTelephone();

//        $customer = MAGE::getModel('customer/customer')->load( $order->getCustomerId() );
//        $shipping_address = Mage::getModel('customer/address')->load( $customer->default_shipping );
//
//        // address
//        $address = $shipping_address->getStreet();
//        $suburb = '';

//        $address = $order->getShippingAddress()->getStreet();
//        $suburb = '';
//        $state = $shipping_address->getRegion();
//        $postcode = $shipping_address->getPostcode();
//        $country = $shipping_address->getCountryId();
//        $phone = $shipping_address->getTelephone();

        if (is_array($address)) {
            $address = implode('; ', $address);
        }
		$suburb = $order->getShippingAddress()->getCity();
        // person data
        $person = array(
            'email'             => $order->getCustomerEmail(),
            'firstName'         => $order->getCustomerFirstname(),
            'lastName'          => $order->getCustomerLastname(),
            'address'           => $address,
            'suburb'            => $suburb,
            'state'             => $state,
            'postcode'          => $postcode,
            'countryCodeISO3166_A2' => $country,
            'phone'             => $phone,
            'mobile'            => $order->getCustomerMobile(),
            'password'          => '',
            'optInMailingList'  => false,
        );

        // products
        $productModel = Mage::getModel('catalog/product');
        $products = array();

        foreach ($order->getAllItems() as $item) {

            $product = $productModel->load($item->getProductId());
            if(!preg_match("/configurable_niche_id/", $product->getNicheEntityId())) {
                $products[] = array(
                    'EntityID'          => $product->getNicheEntityId(),
                    'Barcode'           => $product->getBarcode(),
                    'Color'             => $product->getColor(),
                    'Size'              => $product->getSize(),
                    'LabelDescription'  => $product->getName(),
                    'Code'              => $product->getNamekey(),
                    'Description'       => '',
                    'WebDescription'    => $product->getDescription(),
                    'Weight'            => $product->getWeight(),
                    'AvailableStock'    => '',
                    'qty'               => (int)$item->getQtyOrdered(),
                );
            }

        }

        $CreateOrder = array(
            'order'     => array(
                'person'    => $person,
                'products'  => array(
                    'Product' => $products,
                ),
                'refNo' => $order->getIncrementId(),
            )
        );

        Mage::log(print_r($CreateOrder, true), null, 'niche_api_order_response.log', true);


        // export
        $soapResult = $this->wsdl_client->CreateOrder($CreateOrder);

        $response = $soapResult->CreateOrderResult;

        Mage::log($response, null, 'niche_api_order_response.log', true);
		
		// Send mail transaction
		$successFeed = 'Success';
		if (!is_numeric($response)) {
			$successFeed = 'Fail';
		}
		$infoProduct='<br />';
		foreach($products as $product){
			$infoProduct.='Barcode: '.$product['Barcode'].' Qty: '.$product['qty']."<br />";
			$infoProduct.='------------------------------';
		}
		$pathXMLConfig = "sendmail/feed_order/";
		if(Mage::getStoreConfig($pathXMLConfig.'active')){
			$sendTo = Mage::getStoreConfig('sales_email/order/copy_to');
			if(Mage::getStoreConfig($pathXMLConfig.'email')) $sendTo = Mage::getStoreConfig($pathXMLConfig.'email');
			
			$sendTo = explode(",", $sendTo);
			foreach($sendTo as $emailTo){
				$sendToArr[]=trim($emailTo);
			}
			
			Mage::getModel('core/email_template')
            ->setDesignConfig(array(
                'area'  => 'frontend'
            ))->sendTransactional(
                Mage::getStoreConfig($pathXMLConfig.'niche_template'),
                Mage::getStoreConfig(Mage_Sales_Model_Order::XML_PATH_EMAIL_IDENTITY),
                $sendToArr,
                $sendToArr,
                array(
                    'order'  => $order,
                    'result'     => $successFeed,
                    'infoProduct' => $infoProduct,
                )
            );
		}
		
        if (!is_numeric($response)) {
            Mage::log($response);
            throw new \Exception('Error exporting Order: ' . $soapResult->CreateOrderResult);
        }

        return $soapResult->CreateOrderResult;
    }

}