<?php


// set autoload
require_once "./autoload.php";

$autoload = new autoload( array($path, $libpath, $path . '../lib/'), new Errors());
$autoload->autoload();

include "../magento_init_with_errors.php";

$appfactory = new app\magentoprep\appfactory(
    $path
);

$app = $appfactory->build();
echo "\n\n" . get_class($app);
echo $app->run();
