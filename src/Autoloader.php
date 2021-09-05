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


  private function __construct($path=null)
  {
     parent::__construct($path=null);
  }

    /**
     * @param $path
     * @return Autoloader|null
     */
    public static function getInstance($path=null): ?AutoloaderAbstract
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