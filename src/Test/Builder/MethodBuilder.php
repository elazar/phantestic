<?php

namespace Phantestic\Test\Builder;

use Phantestic\Test\Test;

class MethodBuilder implements BuilderInterface
{
    /**
     * @var \ReflectionMethod
     */
    protected $method;

    /**
     * @param \ReflectionMethod $method
     */
    public function __construct(\ReflectionMethod $method)
    {
        $this->method = $method;
    }

    /**
     * @inheritDoc
     */
    public function getTest()
    {
        $reflector = $this->method->getDeclaringClass();
        $class = $reflector->getName();
        $instance = $reflector->newInstance();
        $method = $this->method->getName();
        $callback = [$instance, $method];
        $name = $class . '->' . $method;
        return new Test($callback, $name);
    }
}
