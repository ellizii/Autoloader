<?php

use Hydra\Autoloader\Autoloader;

include_once dirname(__DIR__) . '/src/Autoloader.php';

$path = dirname(dirname(__DIR__)).'/orm/connector';

$loader =  Autoloader::getInstance($path);

var_dump($loader->getClassMap());
echo "\n";

$path =array( dirname(dirname(__DIR__)).'/orm');

$loader->iniLoad($path);

var_dump($loader->getClassMap());
