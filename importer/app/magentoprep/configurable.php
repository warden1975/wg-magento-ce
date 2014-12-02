<?php
/**
 * User: hone
 * Date: 4/04/13
 * Time: 11:21 AM
 */

namespace app\magentoprep;

use interfaces;

class configurable  implements interfaces\app {

    /**
     * @var \Csv_Reader
     */
    protected $Csv_Reader;

    /**
     * @var \Csv_Writer
     */
    protected $Csv_Writer;

    public function __construct( $Csv_Reader, $Csv_Reader_Media, $Csv_Writer, $path ){
        $this->Csv_Reader =  $Csv_Reader;
        $this->Csv_Reader_Media =  $Csv_Reader_Media;
        $this->Csv_Writer = $Csv_Writer;
        $this->path = $path;
    }
    public function run(){
        $this->Csv_Reader->setHeader($this->Csv_Reader->current());
        $this->Csv_Writer->writeRow($this->Csv_Reader->current());
        $by_namekey = array();
        $count = 0;
        while($this->Csv_Reader->valid())
        {


            $this->Csv_Reader->next();
            $current = $this->Csv_Reader->current();
            $current['description'] = trim(str_replace("****", '"', $current['description']),'"');
            $current['media_gallery'] = $this->getMediaGallery($current['filemaker_sku']);
            $categories = explode(",", $current['category_ids']);
            $categories[] = '3';
            $current['category_ids'] = implode(",", array_unique($categories));
            $by_namekey[$current['namekey']][] = $current;
            $count++;
            //if($count == 350)
                //break;
            //$current['namekey'] = substr(md5($current['namekey']), 0, 6);
            //$this->Csv_Writer->writeRow(array_values($current));

        }

        $final = array();
        foreach($by_namekey as $namekey => $rows){
            $sizeof = sizeof($rows);

            if($sizeof > 1){
                $configurable = $rows[0];
                $configurable['has_options'] = 1;
                $configurable['qty'] = 0;
                $configurable['name'] =  $configurable['product_name'] = $namekey;
                $configurable['type'] = $configurable['product_type_id'] = 'configurable';
                $configurable['color'] = $configurable['colour_filter'] = $configurable['dsizes'] = $configurable['ink_color'] = $configurable['cover'] = $configurable['source'] = $configurable['ruling'] = $configurable['style'] = $configurable['use'] = $configurable['point_width'] = $configurable['suits']  = $configurable['dpages'] = $configurable['dstyle'] = $configurable['dcover'] = $configurable['dspecs'] = $configurable['dcable'] = $configurable['dquantity'] = $configurable['dink'] = $configurable['dtip'] = $configurable['dmaterial'] = $configurable['ddate'] = $configurable['dcatalogue'] = $configurable['dsuit'] = $configurable['dformat'] = $configurable['dzip'] = $configurable['dfeature'] = $configurable['dcontain'] = $configurable['dhold'] = $configurable['dlength'] = $configurable['dheight'] = $configurable['dnote'] = $configurable['dextra'] = "";
                $rows[] = $configurable;
            }

            $hashnamekey = substr(md5($namekey), 0, 6);
            //echo "$hashnamekey\n";
            $size = $rows[0]['size'];
            $colour_and_size = false;


            $new_rows = array();

            foreach($rows as $row){

                $row['category_ids'] = str_replace("^", ",", $row['category_ids']);
                $row['image'] = $row['small_image'] = $row['thumbnail'] = $this->getImage('image', $row);
                if($row['size'] != $size)
                    $colour_and_size = true;
                if($row['type'] == 'configurable' && $colour_and_size)
                    $row['configurable_attributes'] = "color,size";
                elseif($row['type'] == 'configurable' && !$colour_and_size)
                    $row['configurable_attributes'] = "color";

                if($row['type'] == 'configurable')
                    $row['sku'] = $hashnamekey;



                $row['namekey'] = $hashnamekey;
                $new_rows[] = $row;
            }

            $rows = $new_rows;

            if($sizeof > 1) {
                $method = "";
                $combined_gallery = array();
                foreach($rows as $row){

                    if($row["configurable_attributes"] && $row["configurable_attributes"] == "color,size") {
                        $method = 'colourSize';
                        break;
                    }


                }


                if(!$method)  $method = 'colour';

                foreach($rows as $row){
                    if($row['type'] != 'configurable') {

                        $row['sku'] = $this->$method($row);
                        $combined_gallery[$row['sku']] = $row['media_gallery'] ;
                        $row['media_gallery'] = "";
                        $default_image = $row['image'] = $row['small_image'] = $row['thumbnail'] = $this->getFinalImage($row);

                    }

                    if($row['type'] == 'configurable') {
                        $row['image'] = $row['small_image'] = $row['thumbnail'] =$default_image;
                        $row['media_gallery'] = $this->getFinalMediaGalleryConfigurable($combined_gallery);
                    }

                    $this->Csv_Writer->writeRow(array_values($row));
                }


            }

            elseif($sizeof == 1){
                $row = $rows[0];

                $row['sku'] = $this->colourSize($row);
                $row['image'] = $row['small_image'] = $row['thumbnail'] = $this->getFinalImage($row);
                $row['media_gallery'] = $this->getFinalMediaGallery($row);
                $this->Csv_Writer->writeRow(array_values($row));
            }


        }

    }

    public function colourSize($row){
        return $this->trimmer("{$row['namekey']}.{$row['color']}.{$row['size']}");
    }

    public function colour($row) {
        return $this->trimmer("{$row['namekey']}.{$row['color']}");
    }


    public function trimmer($string){
        return strtolower(str_replace(array("/", "(", ")"), "", trim($string, ".")));
    }

    public function getImage($key, $row){

        return $this->_getImage($row[$key]);

    }

    public function getFinalImage($row){

        $path = $this->path . "../media/import" . $row['image'];

        $image_file =  "/".$row['sku'] . "_1.jpg";

        $final_path = $this->path . "../media/import" . $image_file;

        if(!file_exists($final_path))
            copy($path, $final_path);

        return $image_file;
    }

    public function getFinalMediaGalleryConfigurable($combined){
        $media_gallery = array();
        foreach($combined as $sku => $media)
            $media_gallery[] = $this->getFinalMediaGallery(array('sku'=>$sku, 'media_gallery'=>$media));
        $media_gallery = implode(";", $media_gallery);
        return $media_gallery;
    }
    public function getFinalMediaGallery($row){
        $base_file = "/".$row['sku'];
        $count = 2;
        $images = array_unique(explode(";", $row['media_gallery']));
        foreach($images as $image){
            $image_file = "{$base_file}_$count.jpg";
            $path = $this->path . "../media/import" . $image;
            $final_path = $this->path . "../media/import" . $image_file;
            if(!file_exists($final_path))
                copy($path, $final_path);
            $new_images[] = $image_file;
            $count++;
        }
        return implode(";", $new_images);
    }

    public function _getImage($string){
        echo "\n\n\n$string";
        $image_file =  "/".md5( $string ) . ".jpg";
        $path = $this->path . "../media/import" . $image_file;
        if(!file_exists($path)) {
            file_put_contents($path, file_get_contents($string));
            echo "\nDownloaded $image_file!";
        }
        else {
            echo "\nNo need to download $image_file!";

        }
        return $image_file;
    }

    protected $cache = null;

    public function _getMediaGallery($string){
        if($this->cache === null){
            $this->cache = array();

            $this->Csv_Reader_Media->setHeader($this->Csv_Reader_Media->current());
            while($this->Csv_Reader_Media->valid())
            {


                $this->Csv_Reader_Media->next();
                $current = $this->Csv_Reader_Media->current();
                $this->cache[trim($current['filemaker_sku'])] = $current;

            }
        }


        if(isset($this->cache[trim($string)]['media_gallery']))
            return $this->cache[trim($string)]['media_gallery'];
        else return "";
    }


    public function getMediaGallery($string){

        $string = $this->_getMediaGallery($string);

        $images = explode(";", $string);

        foreach($images as $image){
            if($image)
                 $new_images[] = $this->_getImage($image);
        }

        //print_r($new_images);
        array_shift($new_images);

        $new_images = implode(";", $new_images);



        return $new_images;
    }
}