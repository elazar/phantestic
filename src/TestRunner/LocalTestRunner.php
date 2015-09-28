<?php

namespace Phantestic\TestRunner;

use Evenement\EventEmitter;
use Evenement\EventEmitterInterface;
use Phantestic\TestHandler\TestHandlerInterface;

class LocalTestRunner
{
    /**
     * @var \Evenement\EventEmitterInterface
     */
    protected $emitter;

    /**
     * @var \Traversable
     */
    protected $loader;

    /**
     * @var \Phantestic\TestHandler\TestHandlerInterface[]
     */
    protected $handlers;

    public function __construct(\Traversable $loader, array $handlers = [])
    {
        $this->loader = $loader;
        $this->handlers = $handlers;
    }

    public function run()
    {
        $this->registerHandlers();

        $emitter = $this->getEventEmitter();

        $emitter->emit('phantestic.tests.before', [$this]);

        foreach ($this->loader as $case) {
            $args = [$case, $this];

            $emitter->emit('phantestic.test.before', $args);
            $case->run();
            $emitter->emit('phantestic.test.after', $args);

            $reflector = new \ReflectionClass(get_class($case->getResult()));
            $class = strtolower($reflector->getShortName());
            $emitter->emit('phantestic.test.' . $class, $args);
        }

        $emitter->emit('phantestic.tests.after', [$this]);
    }

    public function setEventEmitter(EventEmitterInterface $emitter)
    {
        $this->emitter = $emitter;
    }

    public function getEventEmitter()
    {
        if (!$this->emitter) {
            $this->emitter = new EventEmitter;
        }
        return $this->emitter;
    }

    protected function registerHandlers()
    {
        $invalid = array_filter(
            $this->handlers,
            function ($handler) {
                return !$handler instanceof TestHandlerInterface;
            }
        );
        if (!empty($invalid)) {
            $classes = implode(', ', array_map('get_class', $invalid));
            $interface = TestHandlerInterface::class;
            throw new \DomainException(
                'Handler classes do not implement ' . $interface . ': ' . $classes
            );
        }

        $emitter = $this->getEventEmitter();
        foreach ($this->handlers as $handler) {
            $handler->setEventEmitter($emitter);
        }
    }
}
