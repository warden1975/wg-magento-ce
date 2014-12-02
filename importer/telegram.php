<?php

require_once "./autoload.php";

$autoload = new autoload( array($path, $libpath, $vendorpath, $path . '../lib/'), new Errors());
$autoload->autoload();

$appfactory = new app\telegram\appfactory(
  parse_ini_file( $importerpath . "/config/telegram.ini", true ),
  $path
);

$app = $appfactory->build();

echo $app->run();

/* Run the ERP product availability refresh all helper */
$path =  __DIR__ . '/';
//exec("php {$path}../shell/refresh.php");
