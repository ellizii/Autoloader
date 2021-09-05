<?php


namespace Hydra\Autoloader;

require_once('abstract/class/AutoloaderAbstract.php');
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
    public static function getInstance($path): ?AutoloaderAbstract
    {
        if(null === self::$instance) self::$instance = new self($path);
        return self::$instance;
    }
}