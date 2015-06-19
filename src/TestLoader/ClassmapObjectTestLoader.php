<?php

namespace Phantestic\TestLoader;

use Evenement\EventEmitterInterface;

abstract class ClassmapObjectTestLoader implements \IteratorAggregate
{
    /**
     * @var \Evenement\EventEmitterInterface
     */
    protected $emitter;

    /**
     * @var callable
     */
    protected $filter;

    /**
     * @var callable
     */
    protected $generator;

    /**
     * @param \Evenement\EventEmitterInterface $emitter
     * @param callable $filter Callback to filter classmap entries for tests,
     *        with the signature (string $file, string $class, string $method):
     *        boolean
     * @param callable $generator Callable to generate a test case instance
     *        from an associated callback and name, with the signature
     *        (callable $test, string $name): \Phantestic\TestCase\TestCaseInterface
     */
    public function __construct(
        EventEmitterInterface $emitter = null,
        callable $filter = null,
        callable $generator = null
    )
    {
        $this->emitter = $emitter;
        $this->filter = $filter ?: $this->getDefaultFilter();
        $this->generator = $generator ?: $this->getDefaultGenerator();
    }

    /**
     * @return callable
     */
    protected function getDefaultFilter()
    {
        return function($file, $class, $method) {
            return
                preg_match('/Test\.php$/', $file)
                && preg_match('/^test/', $method);
        };
    }

    /**
     * @return callable
     */
    protected function getDefaultGenerator()
    {
        return function($class, $method) {
            $callback = [new $class, $method];
            $name = $class . '->' . $method;
            return new \Phantestic\TestCase\TestCase($callback, $name);
        };
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        $classmap = $this->getClassmap();
        $filter = $this->filter;
        $generator = $this->generator;
        $tests = [];
        foreach ($classmap as $class => $file) {
            $reflector = new \ReflectionClass($class);
            $all_methods = array_map(
                function($method) { return $method->name; },
                $reflector->getMethods(\ReflectionMethod::IS_PUBLIC & ~\ReflectionMethod::IS_STATIC)
            );
            $test_methods = array_filter(
                $all_methods,
                function($method) use ($file, $class, $filter) {
                    return $filter($file, $class, $method);
                }
            );
            $test_cases = array_map(
                function($method) use ($class, $generator) {
                    return $generator($class, $method);
                },
                $test_methods
            );
            $tests = array_merge($tests, $test_cases);
            if ($this->emitter) {
                foreach (array_combine($test_methods, $test_cases) as $method => $test_case) {
                    $this->emitter->emit('phantestic.loader.loaded', [$test_case, $class, $method]);
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
