<?php


namespace Ellizii\Autoloader;

use Ellizii\Autoloader\Abstractes\Classes\AutoloaderAbstract;

require_once('Abstractes/Classes/AutoloaderAbstract.php');
class Autoloader extends AutoloaderAbstract
{

    /**
     * @var Autoloader|null
     */
    private static ?Autoloader $instance = null;


  private function __construct($path)
  {
     parent::__construct($path);
  }

    /**
     * @param $path
     * @return Autoloader|null
     */
    public static function instance($path): ?AutoloaderAbstract
    {
        if(null === self::$instance) self::$instance = new self($path);
        return self::$instance;
    }

    public function __clone()
    {
    }

    public function __wakeup()
    {
    }
}