<?php

require_once "autoload.php";

if(!empty($argv[1]) && $argv[1]=='download') {
  session_id(true);
}
$autoload = new autoload( array($path, $libpath, $vendorpath, $path . '../lib/'), new Errors());
$autoload->autoload();

$appfactory = new app\niche\appfactory(
    parse_ini_file( $importerpath . "/config/niche.ini", true ),
    $path
);

$app = $appfactory->build();

echo $app->run();
