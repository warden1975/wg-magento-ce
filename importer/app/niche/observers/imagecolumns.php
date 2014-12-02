<?php
/**
 * User: hone
 * Date: 29/04/13
 * Time: 11:53 AM
 */

namespace app\niche\observers;

use interfaces;

class imagecolumns  implements interfaces\observer {
    /**
     * The logic for how images are added to the configurable product
     * If there is a configurable product, should the configurable combine all
     * simple product images? And should some images be removed
     * from simple products such as the main image and only thumbnail kept?
     *
     * @param $configurable configurable
     * @return $configurable
     */
    public function run($configurable){
        /*
         *  look at app/magento/prep/configurable for logic hints
         *  look at createconfigurable.php for an example of how to use this observer
         */
        $rootPath = realpath(dirname(__FILE__) . '/../../../..').'/media/import/';
        $rows = $configurable->getData();

        while(list($key, $simple_product) = each($rows)) {
            if($simple_product['product_type_id']=='configurable'){
                $simple_product['image'] = $this->processImages($simple_product['image'], $rootPath, $simple_product['sku'], true);                
                $simple_product['small_image'] = $this->processImages($simple_product['small_image'], $rootPath);
                $simple_product['thumbnail'] = $this->processImages($simple_product['thumbnail'], $rootPath);                
                $simple_product['media_gallery'] = $this->processImages($simple_product['media_gallery'], $rootPath, $simple_product['sku'], true);
                //Move all image files are downloaded into tmp folder
                $this->moveImagesToTmpFromImport($simple_product['image'], $rootPath);
                $this->moveImagesToTmpFromImport($simple_product['media_gallery'], $rootPath);
                //include image into gallery
                $simple_product['image'] = '+'.$simple_product['image'];
            }else{
                $simple_product['image']
                    = $simple_product['small_image']
                    = $simple_product['thumbnail']
                    = $simple_product['media_gallery']
                    = '';
            }
            $rows[$key] = $simple_product;
        }
        //Delete all image files are unused
        $this->deleteAllImageFileInImport($rootPath);
        //Move all image file from tmp folder to import folder
        $this->moveImagesBackImportFromTmp($rootPath);
        $configurable->setData($rows);

        return $configurable;
    }
    
    /**
     * Download and update image name
     * @param string $imageField Content is mapped to StyleFeed.xml
     * @param string $rootPath media/import
     * @param string $sku Product SKU is used for writing log when image not found - requried when isDownload is true
     * @param boolean $isDownload True - download and update image name, false - update image name only
     * @return string image names are separated by ';'
     */    
    private function processImages($imageField, $rootPath, $sku='', $isDownload=false){
        if(isset($imageField) && $imageField){
            $arrImageUrl = explode(';',$imageField);
            $arrImageName = array();            
            foreach($arrImageUrl as $imageUrl){
                $imageName = end(explode('/',$imageUrl));
                if(trim($imageName)=='') continue;
                $path = $rootPath.$imageName;
                if($isDownload){                    
                    if(session_id()==''){
                        if(!file_exists($path)) {
                            if(@file_put_contents($path, file_get_contents($imageUrl))!=false){
                                echo "\nDownloaded $imageName!";
                            }else{
                                echo "\nCan not downloaded $imageUrl, file not found - 404!";
                                unlink($path);
                                $log = new Logging();
                                $log->lwrite("{Product_SKU $sku} - Image ".$imageUrl." cannot be downloaded\n");
                                $log->lclose();
                            }                        
                        }
                        else {
                            echo "\nNo need to download $imageName!";
                        }
                    }else{
                        if(@file_put_contents($path, file_get_contents($imageUrl))!=false){
                            echo "\nDownloaded $imageName!";
                        }else{
                            echo "\nCan not downloaded $imageUrl, file not found - 404!";
                            unlink($path);
                            $log = new Logging();
                            $log->lwrite("{Product_SKU $sku} - Image ".$imageUrl." cannot be downloaded\n");
                            $log->lclose();
                        }
                    }
                }
                $arrImageName[] = $imageName;
            }            
            return implode(';', $arrImageName);
        }
        return '';
    }

    /**
     * Move images are downloaded from Import folder into Temp folder
     * @param string $images List images are separated by ';'
     * @param string @rootPath media/import
     */
    private function moveImagesToTmpFromImport($images, $rootPath){
        $arrImages = explode(';', $images);
        if(!file_exists($rootPath.'tmp')){
            mkdir($rootPath.'tmp', 0775,true);
        }
        foreach($arrImages as $image){
            if(file_exists($rootPath.$image) && is_file($rootPath.$image)){
                //rename($rootPath.$image, $rootPath.'tmp/'.$image);
                copy($rootPath.$image, $rootPath.'tmp/'.$image);
                unlink($rootPath.$image);
            }
        }
    }

    /**
     * Move all images from Temp folder back Import folder
     * @param string @rootPath media/import
     */
    private function moveImagesBackImportFromTmp($rootPath){
        //get list images file in tmp folder
        $arrFiles = scandir($rootPath.'tmp/');
        foreach($arrFiles as $fileName){
            //except hidden files
            if (strlen(strstr($fileName, '.', true)) < 1) {
                continue;
            } else {                
                //rename($rootPath.'tmp/'.$fileName, $rootPath.$fileName);
                copy($rootPath.'tmp/'.$fileName, $rootPath.$fileName);
                unlink($rootPath.'tmp/'.$fileName);
            }
        }
    }

    /**
     * Delete all unused image file in import folder
     * @param string $rootPath media/import
     */
    private function deleteAllImageFileInImport($rootPath){
        $arrFiles = scandir($rootPath);
        foreach($arrFiles as $fileName){
            if(is_file($rootPath.$fileName)) {
                //except hidden files
                if (strlen(strstr($fileName, '.', true)) < 1) {
                    continue;
                }else{
                    unlink($rootPath.$fileName);
                }
            }
        }
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
        // in case of Windows set default log file
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $log_file_default = 'c:/php/logfile.txt';
        }
        // set default log file for Linux and other systems
        else {
            if($_SERVER['DOCUMENT_ROOT']!=''){
                $root_path = $_SERVER['DOCUMENT_ROOT'].'/var/import/';
                $logFolder = 'niche_log_image_not_found_web';
                $this->createNicheLogFolder($root_path, $logFolder);
                $log_file_default = $root_path.$logFolder.'/niche_log_image_not_found_'.date('Y-m-d').'.txt';            
            }else{
                $root_path = realpath(dirname(__FILE__) . '/../../../..').'/var/import/';
                $logFolder = 'niche_log_image_not_found_cli';
                $this->createNicheLogFolder($root_path, $logFolder);
                $log_file_default = $root_path.$logFolder.'/niche_log_image_not_found_'.date('Y-m-d').'.txt';
            }
        }
        // define log file from lfile method or use previously set default
        $lfile = $this->log_file ? $this->log_file : $log_file_default;
        // open log file for writing only and place file pointer at the end of the file
        // (if the file does not exist, try to create it)
        $this->fp = fopen($lfile, 'a') or exit("Can't open $lfile!");
    }
    private function createNicheLogFolder($root_path, $folderName){
        if (!file_exists($root_path.$folderName)) {
            mkdir($root_path.$folderName, 0775, true);
        }
    }
}