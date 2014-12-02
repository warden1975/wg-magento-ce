<?php
/**
 * User: hone
 * Date: 26/03/13
 * Time: 3:15 PM
 */

namespace app\shopifyprep;

use interfaces, lib;

class categories implements interfaces\app {
    /**
     * @var array
     */
    protected $data;

    /**
     * @var \lib\iniwriter
     */
    protected $iniwriter;

    /**
     * @param $data
     * @param $iniwriter
     */
    public function __construct($data, $iniwriter_simples, $iniwriter_detailed)
    {
        $this->data = $data;
        $this->iniwriter_simples = $iniwriter_simples;
        $this->iniwriter_detailed = $iniwriter_detailed;

    }

    /**
     *  @return string
     */
    public function run()
    {

        $cats = $this->getCollection('smart') + $this->getCollection('custom') ;

        $this->iniwriter_simples->write("Categories", $cats);

        return "\n\nDone!\n\n";
    }

    /**
     * @param $type
     * @return array
     */
    public function getCollection($type){

        $pages = range(1, 6);
        foreach($pages as $page){
            $rows = $this->data->getRows(array('GET', "/admin/{$type}_collections.json?page=$page", array()));
            foreach($rows as $row){
                $new_cat = array();
                $cats[(string)$row['id']] = $new_cat['name'] = $new_cat['meta_title'] = $new_cat['meta_keywords'] = $this->setUpData($row['title']);
                $new_cat['description'] = $this->setUpData(preg_replace('/\s+/', ' ', $row['body_html']));
                $new_cat['url_key'] = $row['handle'];
                $this->iniwriter_detailed->write((string)$row['id'], $new_cat);
            }
        }
        return $cats;

    }

    public function setUpData($field){
        return trim(str_replace("'", "&#8217;", $field));
    }

}