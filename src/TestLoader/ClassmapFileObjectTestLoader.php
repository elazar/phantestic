<?php

namespace Phantestic\TestLoader;

use Evenement\EventEmitterInterface;

class ClassmapFileObjectTestLoader extends ClassmapObjectTestLoader
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @param string $path Path to a class file
     * @param \Evenement\EventEmitterInterface $emitter See parent class
     * @param callable $filter See parent class
     * @param callable $generator See parent class
     */
    public function __construct(
        $path,
        EventEmitterInterface $emitter = null,
        callable $filter = null,
        callable $generator = null
    )
    {
        if (!is_readable($path)) {
            throw new \RuntimeException(
                '$path does not reference a readable file: ' . $path
            );
        }
        $this->path = $path;

        parent::__construct($emitter, $filter, $generator);
    }

    public function getClassmap()
    {
        return require $this->path;
    }
}
