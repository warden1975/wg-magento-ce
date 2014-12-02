<?php

// set autoload
require_once "./autoload.php";

require_once "$vendorpath/sandeepshetty/shopify_api/client.php";

require_once "$vendorpath/sandeepshetty/wcurl/wcurl.php";

$autoload = new autoload( array($path, $libpath, $path . '../lib/'), new Errors());
$autoload->autoload();

$appfactory = new app\shopifyprep\appfactory(
  parse_ini_file( $importerpath . "/config/hone.ini", true ),
  $path
);

$app = $appfactory->build();

echo $app->run();
