<?php

use Ellizii\Autoloader\Autoloader;

include_once dirname(__DIR__) . '/src/Autoloader.php';
/*===============================================*/
/* folder */
$path =__DIR__.'/load';

/* files array */
//$path =array(__DIR__.'/load/Load.php',__DIR__.'/load/LoadTrait.php');

/* file */
//$path = __DIR__.'/load/LoadInterface.php';

//$path = __DIR__.'/load/loadArray.php';

$loader =  Autoloader::instance($path);

var_dump($loader->getClassMap());


