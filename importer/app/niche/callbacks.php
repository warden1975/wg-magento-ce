<?php
/**
 * User: hone
 * Date: 29/04/13
 * Time: 11:21 AM
 */

namespace app\niche;
$pathFile = explode('/importer/',__FILE__);
$pathMage = $pathFile[0].'/app/Mage.php';
require_once $pathMage;

use lib;
use \Mage;
Mage::app('default');

class callbacks extends lib\callbacks
{
    private $code;
    private $_configurable = array();
    private $_arraySpecial = array();

    /**
     * get category special in file magento_categories_map.ini
     *
     * @param lib\categories $categories
     * @param lib\attributes $attributes
     * @param $fromhtml
     */
    public function __construct($categories, $attributes, $fromhtml)
    {
        parent::__construct($categories, $attributes, $fromhtml);
        $pathFile = explode('/importer/',__FILE__);
        $pathConfig = $pathFile[0].'/importer/config/';
        $categoryIdSpecial = parse_ini_file($pathConfig.'magento_categories_map.ini',true);
        $this->_arraySpecial = array_values($categoryIdSpecial['Special Categories']);
    }
    /**
     * get category special and assign them to category_id if exist
     * 
     * keep position in category_product
     * @param array $data
     * @return string
     */
    public function getCategoryIds($data)
    {
        $sku = strtolower(trim($data['Code']));
        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');
        $ccp =  $resource->getTableName('catalog_category_product');
        $cpe=$resource->getTableName("catalog_product_entity");
        // Get category id assigned in magento_categories_map.ini
        $parent_category_id = $this->categories->getDataByName($data['Department'], null);
        $category_ids = $this->categories->getDataByName($data['Department'], $data['Category']);
        if(isset($parent_category_id) && $parent_category_id != ''){
            if(isset($category_ids) && $category_ids != ''){
                $categoryIdAssign = "$parent_category_id,$category_ids";
            }
            else
                $categoryIdAssign = $parent_category_id;
        }
        else $categoryIdAssign = $category_ids;
        // get sku, category id assigned before from previous data
        $skuConfigurable = $categoryIdAssignConfigurable = '';
        if (isset($this->_configurable['sku'])) $skuConfigurable = $this->_configurable['sku'];
        if (isset($this->_configurable['category_id_assign'])) $categoryIdAssignConfigurable = $this->_configurable['category_id_assign'];
        // if sku previous data not like current category or current categoryIdAssign not like categoryIdAssign previous data
        if ($sku!=$skuConfigurable || $categoryIdAssignConfigurable!=$categoryIdAssign){
            $categoryIdSpecial = implode(',',$this->_arraySpecial);
            $categoryIdIn = $categoryIdSpecial.",".$categoryIdAssign;
            $categoryIdReturn = array();
            $categoryCheck = array();
            $categoryIdAssignArray = explode(',',$categoryIdAssign);
            // get category id, position from table catalog_category_product
            $query = "Select category_id,ccp.position from $ccp ccp inner join $cpe cpe on ccp.product_id = cpe.entity_id where cpe.sku = '$sku' and ccp.category_id in ($categoryIdIn)";
            $results = $readConnection->fetchAll($query);
            foreach ($results as $rs) {
                $categoryCheck[$rs['category_id']] = $rs['position'];
            }
            // check special category exist in database
            foreach ($this->_arraySpecial as $special){
                if(array_key_exists($special,$categoryCheck))
                    $categoryIdReturn[$special]=$special."::".$categoryCheck[$special];
            }
            // check category id assigned exist in database
            foreach ($categoryIdAssignArray as $cat){
                if(array_key_exists($cat,$categoryCheck))
                    $categoryIdReturn[$cat]=$cat."::".$categoryCheck[$cat];
                else $categoryIdReturn[$cat]=$cat."::0";
            }
            ksort($categoryIdReturn);
            $categoryIdReturn = implode(',',$categoryIdReturn);
            $this->_configurable['sku'] = $sku;
            $this->_configurable['category_id_assign'] = $categoryIdAssignConfigurable;
            $this->_configurable['category_id_return'] = $categoryIdReturn;
            return $categoryIdReturn;
        }
        // Same sku with sku before data && categoryIdAssign with sku categoryIdAssign before data
        else {
            return $this->_configurable['category_id_return'];
        }
    }

    /**
     * @param array $data
     * @return string
     */
    public function getSku(array $data)
    {
        $sku = trim($data['Code']);

        if(!empty($data['Color']))
            $sku .= '.' . trim($data['Color']);

        if(!empty($data['Size']))
            $sku .= '.' . trim($data['Size']);

        $sku = strtolower($sku);

        return $sku;
    }

    /**
     * @param array $data
     * @return int
     */
    public function getIsInStock(array $data)
    {
        return ($data['AvailableStock'] > 0) ? 1 : 2;
    }

    /**
     * @param array $data
     * @return float
     */
    public function getPrice(array $data){
        $price = (float)$data['RRPPrice.LocalUnitPriceExTax1'];
        $taxPrice = (float)$data['RRPPrice.LocalUnitPriceTax1'];
        if($price !=0){
            return $price + $taxPrice;
        }
        return null;
    }

    /**
     * @param array $data
     * @return float
     */
    public function getSpecialPrice(array $data)
    {
        $price = (float)$data['RRPPrice.LocalUnitPriceExTax1'];
        $taxPrice = (float)$data['RRPPrice.LocalUnitPriceTax1'];
        $webPrice = (float)$data['WebPrice.LocalUnitPriceExTax1'];
        $webTaxPrice = (float)$data['WebPrice.LocalUnitPriceTax1'];
        if($webPrice != 0){
            if(($price + $taxPrice) != ($webPrice + $webTaxPrice)){
                return $webPrice + $webTaxPrice;
            }
        }
        return null;
    }

    /**
     * @param array $data
     * @return int
     */
    public function getStatus(array $data)
    {
        $status = ($data['Inactive'] === 'False') ? 1 : 2;
        //Write product's status log
        $log = new Logging();
        $log->lwrite("Code : ".$data['Code']." - Barcode : ".$data['Barcode']." - Inactive : ".$data['Inactive']." - "."Status : ".$status."\n");
        $log->lclose();

        return $status;
    }

    /**
     * @param array $data
     * @return string
     */
    public function getMediaGallery(array $data){
        $result='';
        $isChanged=false;
        if(!isset($this->code) || $this->code !=$data['Code']){
            $isChanged=true;
            $this->code = $data['Code'];
        }
        if(isset($data['AlternativePictures.WebStylePicture'])){
            $gallery = $data['AlternativePictures.WebStylePicture'];
            $arrImageUrls = array();
            $count=0;
            $isArrange=true;
            if(is_array($gallery) && count($gallery)>0){
                foreach($data['AlternativePictures.WebStylePicture'] as $image){
                    if(isset($image->ZoomBoxUrl)){
                        if(isset($image->Description)){
                            $description = explode('.', $image->Description);
                            $imageIndex = (int)$description[0];
                            //image is ordered
                            if($imageIndex>0){
                                $count++;
                                $arrImageUrls[$imageIndex]= $image->ZoomBoxUrl;
                            }

                            if($imageIndex==0 && $data['Inactive']=='False'){
                                $isArrange = false;
                            }
                        }
                    }
                }
                //sort by key
                ksort($arrImageUrls);
                $result = implode(';', $arrImageUrls);
                if(!$isArrange && $isChanged && $count==0){
                    $this->logProductImageNotArrange($data['Code']);
                }
            }

        }
        return $result;
    }

    private function logProductImageNotArrange($code){
        $root_path = realpath(dirname(__FILE__) . '/../../..').'/var/import/';
        $logFolder = 'niche_log_cli_images_not_arrange';
        $path = $root_path.$logFolder.'/niche_log_images_not_arrange_'.date('Y-m-d').'.txt';
        $log = new Logging();
        $log->createNicheLogFolder($root_path, $logFolder);
        $log->lfile($path);
        $log->lwrite("{Product $code} -  images are not arranged\n");
        $log->lclose();
    }
}

class Logging {

    // declare log file and file pointer as private properties
    private $log_file, $fp;
    // set log file (path and name)
    public function lfile($path) {
        $this->log_file = $path;
    }
    // write message to the log file
    public function lwrite($message) {
        // if file pointer doesn't exist, then open log file
        if (!is_resource($this->fp)) {
            $this->lopen();
        }
        // define script name
        $script_name = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
        // define current time and suppress E_WARNING if using the system TZ settings
        // (don't forget to set the INI setting date.timezone)
        $time = @date('[d/M/Y:H:i:s]');
        // write current time, script name and message to the log file
        fwrite($this->fp, "$time ($script_name) $message" . PHP_EOL);
    }
    // close log file (it's always a good idea to close a file when you're done with it)
    public function lclose() {
        fclose($this->fp);
    }
    // open log file (private method)
    private function lopen() {
        // set date timezone
        date_default_timezone_set('Australia/Melbourne');

        // in case of Windows set default log file
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $log_file_default = 'c:/php/logfile.txt';
        }
        // set default log file for Linux and other systems
        else {
            if($_SERVER['DOCUMENT_ROOT']!=''){
                $root_path = $_SERVER['DOCUMENT_ROOT'].'/var/import/';
                $logFolder = 'niche_log_web';
                $this->createNicheLogFolder($root_path, $logFolder);
                $log_file_default = $root_path.$logFolder.'/niche_log_'.date('Y-m-d').'.txt';
            }else{

                $root_path = realpath(dirname(__FILE__) . '/../../..').'/var/import/';
                $logFolder = 'niche_log_cli';
                $this->createNicheLogFolder($root_path, $logFolder);
                $log_file_default = $root_path.$logFolder.'/niche_log_'.date('Y-m-d').'.txt';

            }
        }
        // define log file from lfile method or use previously set default
        $lfile = $this->log_file ? $this->log_file : $log_file_default;
        // open log file for writing only and place file pointer at the end of the file
        // (if the file does not exist, try to create it)
        $this->fp = fopen($lfile, 'a') or exit("Can't open $lfile!");
    }
    public function createNicheLogFolder($root_path, $folderName){
        if (!file_exists($root_path.$folderName)) {
            mkdir($root_path.$folderName, 0775, true);
        }
    }
}