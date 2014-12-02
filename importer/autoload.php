<?php

$path = __DIR__ . '/';
$libpath = $path . "lib/";
$vendorpath = $path . "vendor/";
$importerpath = $path;
$magentopath = $path . "../";

/**
 * Class autoload
 */
class autoload
{

    /**
     *
     * @var array
     */
    protected $basepaths;
    /**
     * @var object
     */
    protected $errors;

    /**
     * @param array $basepaths
     * @param $errors
     */
    public function __construct(array $basepaths, $errors)
    {
        $this->basepaths = $basepaths;
        $this->errors = $errors;
    }

    /**
     * handles autoloading
     *
     * @param string $class_name
     * @return void
     */
    public function autoloader($class_name)
    {

        $class_name = str_replace('\\', '/', $class_name);
        $class_name = str_replace('_', '/', $class_name);
        $path = $class_name . '.php';

        try {
            foreach($this->basepaths as $basepath) {
                //echo $basepath . $path . "\n";
                if(file_exists($basepath . $path))
                    include $basepath . $path;
            }
        } catch (Exception $e) {
            $this->errors->handle($e);
        }

    }

    /**
     * initializes autoloader
     *
     * @return void
     */
    public function autoload()
    {
        spl_autoload_register(array($this, 'autoloader'));
    }

}

/**
 * Class Errors
 * Basic class for handling errors
 */
class Errors
{
    public function handle($e)
    {
        //echo $e->getMessage() . "<br />";
    }
}