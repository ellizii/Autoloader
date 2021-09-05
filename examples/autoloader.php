<?php

use Ellizii\Autoloader\Autoloader;

include_once dirname(__DIR__) . '/src/Autoloader.php';

/* Path to file, or directory
   Or array paths to files or directories
*/
$path = dirname(__DIR__).'/orm/connector';

$loader =  Autoloader::getInstance($path);

/*===============================================*/

$path =array( dirname(__DIR__).'/orm',dirname(__DIR__).'/test');

$loader =  Autoloader::getInstance();

$loader->iniLoad($path);


