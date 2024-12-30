<?php

namespace ByDN\Framework\Model;

use ByDN\Framework\DataObject;

abstract class AbstractFactory extends DataObject
{
    /**
     * @var \Psr\Container\ContainerInterface
     */
    private $objectManager;

    /**
     * @var
     */
    protected $requestedType;

    /**
     * @param \Psr\Container\ContainerInterface $objectManager
     */
    public function __construct(
        \Psr\Container\ContainerInterface $objectManager,
    ) {
        $this->objectManager = $objectManager;
        $this->setRequestedType(str_replace('Factory', '', get_class($this)));
    }

    /**
     * @param $requestedType
     * @return void
     */
    public function setRequestedType($requestedType)
    {
        $this->requestedType = $requestedType;
    }

    /**
     * @param $arguments
     * @return void
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function create($arguments = [])
    {
        // Todo: Implement argument replacement
        return $this->objectManager->get($this->requestedType);
    }
}
