<?php

namespace ByDN\Framework\App;

class Bootstrap
{
    /**
     * Object manager. Should not be used. Use DI instead.
     *
     * @var \DI\Container
     */
    private $objectManager;

    /**
     * Creates instance of the bootstrap
     * @return self
     */
    public static function create()
    {
        return new self();
    }

    /**
     * Class constructor
     * Builds the object manager
     *
     * @throws \Exception
     */
    public function __construct(

    ) {
        $this->initObjectManager();
    }

    /**
     * Creates an application
     *
     * @param $type
     * @param $arguments
     * @return AppInterface|void
     */
    public function createApplication($type, $arguments = [])
    {
        try {
            $application = $this->objectManager->get($type);
            if (!($application instanceof AppInterface)) {
                throw new \InvalidArgumentException("The provided class doesn't implement AppInterface: {$type}");
            }
            $application->setArguments($arguments);
            return $application;
        } catch (\Exception $e) {
            $this->terminate($e);
        }
    }

    /**
     * Runs an application
     *
     * @param AppInterface $application
     * @return void
     */
    public function run(AppInterface $application)
    {
        try {
            $application->run();
        }catch (\Throwable $e) {
            $this->terminate($e);
        }
    }

    /**
     * Search for all project di configuration files
     *
     * @param $pattern
     * @return array|false
     */
    public function getAllConfigFiles($folder, $pattern)
    {
        $files = glob($folder . '/' . $pattern);
        foreach (glob($folder.'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir) {
            $files = array_merge(
                [],
                ...[$files, $this->getAllConfigFiles($dir, $pattern)]
            );
        }
        return $files;
    }

    /**
     * Initializes the object manager
     *
     * @return void
     */
    private function initObjectManager()
    {
        $builder = new \DI\ContainerBuilder();
        $files = $this->getAllConfigFiles('.', 'di.php');
        foreach ($files as $filename) {
            $builder->addDefinitions($filename);
        }
        $this->objectManager = $builder->build();
    }

    /**
     * Processes early termination
     * @param \Throwable $e
     * @return void
     */
    protected function terminate(\Throwable $e)
    {
        throw $e;
    }
}
