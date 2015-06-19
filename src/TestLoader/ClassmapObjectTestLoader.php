<?php

namespace Phantestic\TestLoader;

use Evenement\EventEmitterInterface;

abstract class ClassmapObjectTestLoader implements \IteratorAggregate
{
    /**
     * @var string
     */
    protected $file;

    /**
     * @var string
     */
    protected $method;

    /**
     * @var \Evenement\EventEmitterInterface
     */
    protected $emitter;

    /**
     * @var string
     */
    protected $class;

    /**
     * @param \Evenement\EventEmitterInterface $emitter
     * @param string $file Regular expression used to filter test files from the classmap
     * @param string $method Regular expression used to filter test methods from classes
     * @param string $class Fully qualified name of a class that implements TestCaseInterface
     */
    public function __construct(
        EventEmitterInterface $emitter = null,
        $file = '/Test\.php$/',
        $method = '/^test/',
        $class = '\\Phantestic\\TestCase\\TestCase'
    )
    {
        $this->emitter = $emitter;
        $this->file = $file;
        $this->method = $method;
        $this->class = $class;
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        $case = $this->class;
        $classmap = $this->getClassmap();
        $tests = [];
        foreach ($classmap as $class => $file) {
            if (!preg_match($this->file, $file)) {
                continue;
            }
            $reflector = new \ReflectionClass($class);
            $methods = $reflector->getMethods(\ReflectionMethod::IS_PUBLIC & ~\ReflectionMethod::IS_STATIC);
            foreach ($methods as $method) {
                if (!preg_match($this->method, $method->name)) {
                    continue;
                }
                $instance = new $class;
                $name = $class . '->' . $method->name;
                $test = new $case([$instance, $method->name], $name);
                $tests[] = $test;
                if ($this->emitter) {
                    $this->emitter->emit('phantestic.loader.loaded', [$test, $instance, $class, $method->name]);
                }
            }
        }
        return new \ArrayIterator($tests);
    }

    /**
     * @return array Associative array mapping fully qualified class names to
     *         file paths
     */
    abstract protected function getClassmap();
}
