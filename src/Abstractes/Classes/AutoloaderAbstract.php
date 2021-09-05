<?php

namespace Ellizii\Autoloader\Abstractes\Classes;

require_once(dirname(__DIR__) . '/Interfaces/AutoloaderInterface.php');

use Ellizii\Autoloader\Abstractes\Interfaces\AutoloaderInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

abstract class AutoloaderAbstract implements AutoloaderInterface
{

    /**
     * @var array
     */
    protected array $interfaces = [];
    /**
     * @var array
     */
    protected array $abstract_classes = [];
    /**
     * @var array
     */
    protected array $other_classes = [];
    /**
     * @var array
     */
    protected array $functions = [];
    /**
     * @var array
     */
    protected array $traits = [];
    /**
     * @var array
     */
    protected array $classes = [];
    /**
     * @var array
     */
    protected array $iterators = [];
    /**
     * @var array
     */
    protected array $object_functions = [];
    /**
     * Source directories
     * @var array
     */
    protected array $sources = [];
    /**
     * @var array
     */
  private array $classMap =[];
    /**
     * @var array
     */
  private array $classMapFrom =[];
    /**
     * @var array
     */
  private array $files =[];

    /**
     * Autoloader constructor.
     * @param mixed $path
     */
  protected function __construct($path)
  {
    $this->register();
    $this->iniLoad($path);
    return $this;
  }

  public function iniLoad($path): AutoloaderAbstract
  {
      if(is_array($path))
      {
          foreach ($path as $f)
          {
              if(is_file($f))$this->addClassMapFromFile($f);
              else if(is_dir($f))$this->addClassMapFromDirectory($f);
          }

          $this->loadAllFiles();
          return $this;

      }elseif (is_string($path))
      {
          if(is_file($path))$this->addClassMapFromFile($path);
          else if(is_dir($path))$this->addClassMapFromDirectory($path);

          $this->loadAllFiles();
          return $this;
      }
      return $this;
  }


    /**
     *
     */
  public function __invoke(){}

    /**
     * @return $this
     */
  public function register(): AutoloaderInterface
  {
      spl_autoload_register($this,true,false);
      return $this;
  }

    /**
     * @return $this
     */
  public function unregister(): AutoloaderInterface
  {
    spl_autoload_unregister($this);
    return $this;
  }

    /**
     * @return array
     */
  public function getClassMap(): array
  {
   return $this->classMap;
  }

    /**
     * @param array $map
     * @return $this
     */
  public function addClassMap(array $map): AutoloaderInterface
  {
      if(is_array($map) AND count($map)>0)$this->classMap = array_merge($this->classMap,$map);
      return $this;
  }

    /**
     * @param string $prefix
     * @param string $class
     * @param string $path
     * @return $this
     */
  public function addToClassMap(string $prefix='', string $class='', string $path=''): AutoloaderAbstract
  {
    if(''!== $class AND ''!==$path)$this->classMap = array_merge($this->classMap,array($prefix.$class => $path));
    return $this;
  }

    /**
     * @return $this
     */
  public function removeClassMap(): AutoloaderInterface
  {
     $this->classMap = array();
     return $this;
  }

    /**
     * @param string $prefix
     * @param string $class
     * @return $this
     */
    public function removeFromClassMap(string $prefix='', string $class=''): AutoloaderInterface
    {
       unset($this->classMap[$prefix.$class]);
        return $this;
    }

    /**
     * @return array
     */
    public function getDirectoryList(): array
    {
        return $this->classMapFrom;
    }

    /**
     * @param $path
     * @return bool
     */
    public function hasDirectoryInList($path): bool
    {
        return in_array($path,$this->classMapFrom);
    }

    /**
     * @return $this
     */
    public function removeDirectoryList(): AutoloaderInterface
    {
        $this->classMapFrom = array();
        return $this;
    }

    /**
     * @param mixed $list
     * @return $this
     */
    public function addDirectoryList($list): AutoloaderInterface
    {

        if(!is_array($list))$source = [$list];
        else $source = $list;

        foreach ($source as $dir)
        {
            if(is_dir($dir))
            {
               if(!$this->hasDirectoryInList($dir))$this->classMapFrom[] = $dir;
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getFileList(): array
    {
        return $this->files;
    }

    /**
     * @param $path
     * @return bool
     */
    public function hasFileInList($path): bool
    {
        return in_array($path,$this->files);
    }

    /**
     * @param mixed $list
     * @return $this
     */
    public function addFileList($list): AutoloaderInterface
    {

        if(!is_array($list))$source = [$list];
        else $source = $list;

        foreach ($source as $file)
        {
            if(is_file($file))
            {
                if(!$this->hasFileInList($file))$this->files[] = $file;
            }
        }

        return $this;
    }

    public function attachToArray($f)
    {
        if(($f !== false) && (null !== $f) && (substr(strtolower($f), -4) == '.php') && ( stripos($f,'ObjectFunction.php') !== false)) $this->object_functions[]=$f;
        else if(($f !== false) && (null !== $f) && (substr(strtolower($f), -4) == '.php') && (stripos($f,'_function') !== false OR stripos($f,'functions') !== false OR stripos($f,'Function.php') !== false)) $this->functions[]=$f;
        else if(($f !== false) && (null !== $f) && (substr(strtolower($f), -4) == '.php') && (stripos($f,'abstract/interface') !== false OR stripos($f,'Interface.php') !== false)) $this->interfaces[]=$f;
        else if(($f !== false) && (null !== $f) && (substr(strtolower($f), -4) == '.php') && (stripos($f,'abstract/iterator') !== false OR stripos($f,'Iterator.php') !== false)) $this->iterators[]=$f;
        else if(($f !== false) && (null !== $f) && (substr(strtolower($f), -4) == '.php') && (stripos($f,'abstract/trait') !== false OR stripos($f,'Trait.php') !== false)) $this->traits[]=$f;
        else if(($f !== false) && (null !== $f) && (substr(strtolower($f), -4) == '.php') && (stripos($f,'abstract/class') !== false OR stripos($f,'Abstract.php') !== false)) $this->abstract_classes[]=$f;
        else if (($f !== false) && (null !== $f) && (substr(strtolower($f), -4) == '.php') && stripos($f,'abstract') !== false) $this->abstract_classes[] = $f;
        else if(($f !== false) && (null !== $f) && (substr(strtolower($f), -4) == '.php') && (stripos($f,'class') !== false OR stripos($f,'Class.php') !== false)) $this->classes[]=$f;
        else if (($f !== false) && (null !== $f) && (substr(strtolower($f), -4) == '.php') && stripos($f,'abstract') === false) $this->other_classes[] = $f;
        else if (($f !== false) && (null !== $f) && (substr(strtolower($f), -4) == '.php')) $this->other_classes[] = $f;

    }

    /**
     *
     */
    public function scanPath()
    {
        foreach ($this->classMapFrom as $source) {

            $objects = null;
        if(is_dir($source)) {

            $objects = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST
            );

            foreach ($objects as $fileInfo) {
                if (($fileInfo->getFilename() != '.') && ($fileInfo->getFilename() != '..')) {
                    $f = null;
                    if (!$fileInfo->isDir()) {
                        $f = realpath($fileInfo->getPathname());
                    }

                    $this->attachToArray($f);
                }
            }
        }elseif(is_file($source)){

            $f = realpath($source);

            $this->attachToArray($f);
        }

    }

        $this->files = array_merge($this->files,$this->functions);

        $this->files = array_merge($this->files,$this->interfaces);

        $this->files = array_merge($this->files,$this->iterators);

        $this->files = array_merge($this->files,$this->traits);

        $this->files = array_merge($this->files,$this->abstract_classes);

        $this->files = array_merge($this->files,$this->classes);

        $this->files = array_merge($this->files,$this->other_classes);

        $this->files = array_merge($this->files,$this->object_functions);


    }

    /**
     * @param $file_name
     * @return $this
     */
    public function writeClassMap($file_name): AutoloaderInterface
    {
        $code = '<?php'.PHP_EOL.PHP_EOL.'return[';
        $i=1;
        foreach ($this->classMap as $class => $file)
        {
            $coma = ($i<count($this->classMap))?',':'';

            $code .= PHP_EOL.'\''.$class.'\'=>\''.$file.'\''.$coma;
            $i++;
        }
        $code.= PHP_EOL.'];'.PHP_EOL;
        file_put_contents($file_name,$code);

        return $this;
    }

    public function getClassName($file)
    {
        $classMatch        = [];
        $namespaceMatch    = [];
        $classFileContents = file_get_contents($file);
        $class = '';

        preg_match('/^(abstract|final|interface|trait|class)(.*)$/m', $classFileContents, $classMatch);
        preg_match('/^namespace(.*)$/m', $classFileContents, $namespaceMatch);

        if (isset($classMatch[0])) {
            if (strpos($classMatch[0], 'abstract') !== false) {
                $class = str_replace('abstract class ', '', $classMatch[0]);
            } else if (strpos($classMatch[0], 'interface') !== false) {
                $class = str_replace('interface ', '', $classMatch[0]);
            } else if (strpos($classMatch[0], 'trait') !== false) {
                $class = str_replace('trait ', '', $classMatch[0]);
            } else if ($classMatch[1] === 'final') {
                $class = str_replace('final class', '', $classMatch[0]);
            }else {
                $class = str_replace('class', '', $classMatch[0]);
            }

            if (strpos($class, ' ') !== false) {
                $class = substr($class, strpos($class, ' ') );
            }

            $class = trim($class);
            if (isset($namespaceMatch[0])) {
                $class = trim(str_replace(';', '', str_replace('namespace ', '', $namespaceMatch[0]))) . '\\' . $class;
                }

            }else {
                if (strripos($file, '/') > 0 and strripos($file, '.php') > 0){
                    $class = substr($file, strripos($file, '/') +1, strripos($file, '.php') - strripos($file, '/') - 1);
            }elseif (strripos($file, '\\') > 0 and strripos($file, '.php') > 0){
                    $class = substr($file, strripos($file, '\\')+1 , strripos($file, '.php') - strripos($file, '\\') - 1);
                }

            }

        return $class;
    }

    /**
     * @param $file
     * @return $this
     */
    public function addClassMapFromFile($file): AutoloaderInterface
    {
        if(file_exists($file))
        {

            $classMatch        = [];
            $namespaceMatch    = [];
            $fileContents = file_get_contents($file);

            preg_match('/^(abstract|final|interface|trait|class|function)(.*)$/m', $fileContents, $classMatch);
            preg_match('/^namespace(.*)$/m', $fileContents, $namespaceMatch);

            if(isset($classMatch[0]))
            {
                $this->classMapFrom[] = $file;
            }else{
                // $file возвращает ассоциативный массив
                $classMap = require_once $file;

                $this->classMapFrom = array_merge($this->classMapFrom,$classMap);

            }
            return $this;

        }
        return $this;
    }

    /**
     * @param $dir
     * @return AutoloaderAbstract
     */
    public function addClassMapFromDirectory($dir): AutoloaderInterface
    {
       if(is_array($dir))
       {
        foreach ($dir as $path)
        {
            if(file_exists($path))
            {
              $this->addDirectoryList($path);
            }
        }
       }else{
           if(file_exists((string)$dir))
           {
               $this->addDirectoryList($dir);
           }
       }

       return $this;
    }

    /**
     * Generate a class map
     *
     * @return AutoloaderAbstract
     */
    public function generateClassMap(): AutoloaderInterface
    {
        $this->scanPath();

        foreach ($this->files as $file) {

            $class = $this->getClassName($file);
            $this->classMap[$class] = str_replace('\\', '/', $file);
        }

        return $this;
    }

    public function findClass($class)
    {
        if(array_key_exists($class,$this->classMap)) return realpath($this->classMap[$class]);

        return  false;
    }

    public function loadClass($class): bool
    {
        $classFile = $this->findClass($class);
        if($classFile !== false)
        {
            require_once $classFile;
            return true;
        }

        return false;
    }

    public function loadAllFiles()
    {
        $this->generateClassMap();

        foreach(array_keys($this->getClassMap()) as $inc)
        {
            $this->loadClass($inc);
        }
    }

    abstract public function __clone();

    abstract public function __wakeup();
}