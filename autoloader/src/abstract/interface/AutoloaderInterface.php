<?php


namespace Hydra\Autoloader;


interface AutoloaderInterface
{

    /**
     *
     */
    public function __invoke();

    /**
     * @return $this
     */
    public function register(): AutoloaderInterface;

    /**
     * @return $this
     */
    public function unregister(): AutoloaderInterface;


    /**
     * @return array
     */
    public function getClassMap(): array;


    /**
     * @param array $map
     * @return $this
     */
    public function addClassMap(array $map): AutoloaderInterface;


    /**
     * @param string $prefix
     * @param string $class
     * @param string $path
     * @return $this
     */
    public function addToClassMap(string $prefix='', string $class='', string $path=''): AutoloaderInterface;


    /**
     * @return $this
     */
    public function removeClassMap(): AutoloaderInterface;


    /**
     * @param string $prefix
     * @param string $class
     * @return $this
     */
    public function removeFromClassMap(string $prefix='', string $class=''): AutoloaderInterface;


    /**
     * @return array
     */
    public function getDirectoryList(): array;


    /**
     * @param $path
     * @return bool
     */
    public function hasDirectoryInList($path): bool;


    /**
     * @return $this
     */
    public function removeDirectoryList(): AutoloaderInterface;


    /**
     * @param mixed $list
     * @return $this
     */
    public function addDirectoryList($list): AutoloaderInterface;

    /**
     * @return array
     */
    public function getFileList(): array;


    /**
     * @param $path
     * @return bool
     */
    public function hasFileInList($path): bool;

    /**
     * @param mixed $list
     * @return $this
     */
    public function addFileList($list): AutoloaderInterface;

    /**
     *
     */
    public function scanPath();

    /**
     * @param $file_name
     * @return $this
     */
    public function writeClassMap($file_name): AutoloaderInterface;

    /**
     * @param $file
     * @return $this
     */
    public function addClassMapFromFile($file): AutoloaderInterface;

    /**
     * @param $dir
     * @return AutoloaderInterface
     */
    public function addClassMapFromDirectory($dir): AutoloaderInterface;

    /**
     * Generate a class map
     *
     * @return AutoloaderInterface
     */
    public function generateClassMap(): AutoloaderInterface;


    /**
     * @param $class
     * @return mixed
     */
    public function findClass($class);

    /**
     * @param $class
     * @return mixed
     */
    public function loadClass($class);

    /**
     * @return mixed
     */
    public function loadAllFiles();

    /**
     * @param $path
     * @return mixed
     */
    public function iniLoad($path);
}