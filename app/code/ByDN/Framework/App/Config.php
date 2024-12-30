<?php

namespace ByDN\Framework\App;

class Config extends \ByDN\Framework\DataObject
{
    /**
     * Stores real path to the project folder
     *
     * @var string
     */
    private $appFolder = '';

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->initAppFolder();
        $this->readConfig();
    }

    /**
     * Returns application folder
     * @return string
     */
    public function getAppFolder()
    {
        return $this->appFolder;
    }

    /**
     * @param $moduleName
     * @return string
     */
    function getModuleNamespace($moduleName) {
        $moduleData = $this->getData('module/' . $moduleName);
        return $moduleData['namespace'];
    }

    /**
     * Returns module real folder
     *
     * @return string
     */
    public function getModuleFolder($moduleName)
    {
        $moduleData = $this->getData('module/' . $moduleName);
        return $moduleData['folder'];
    }

    /**
     * Reads all configuration files and stores
     *
     * @return void
     */
    private function readConfig()
    {
        // All json files in config folders
        $mergedData = [];
        foreach ($this->getAllConfigFiles('.', '*/config/*.php') as $fullFilename) {
            $filename = strtolower($fullFilename);
            $filename = str_replace('.php', '', $filename);
            if (stripos($filename, "/") !== false) {
                $filename = substr($filename, strrpos($filename, '/') + 1);
            }
            $newData[$filename] = $this->readConfigFile($fullFilename);
            $mergedData = array_replace_recursive($mergedData, $newData);
        }
        $this->setData($mergedData);
    }

    /**
     * Returns configuration file contents as array
     *
     * @param $filename
     * @return array
     */
    private function readConfigFile($filename)
    {
        return include $filename;
    }

    /**
     * @return void
     */
    private function initAppFolder()
    {
        $this->appFolder = realpath(__DIR__ . '/../../../../..');
    }

    /**
     * Search for all configuration files
     *
     * @param $pattern
     * @return array|false
     */
    private function getAllConfigFiles($folder, $pattern) {
        $files = glob($folder . '/' . $pattern);
        foreach (glob($folder.'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir) {
            $files = array_merge(
                [],
                ...[$files, $this->getAllConfigFiles($dir, $pattern)]
            );
        }
        return $files;
    }
}