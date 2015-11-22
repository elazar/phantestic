<?php

namespace Phantestic\Loader;

use Phantestic\Classmap\ComposerBuilder;

class ClassmapFileObjectLoader extends ClassmapObjectLoader
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @param string $path Path to a classmap file
     */
    public function __construct($path)
    {
        if (!is_readable($path)) {
            throw new \RuntimeException(
                '$path does not reference a readable file: ' . $path
            );
        }

        parent::__construct(new ComposerBuilder($path));
    }
}
