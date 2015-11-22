<?php

namespace Phantestic\Classmap\Builder;

class ComposerBuilder implements BuilderInterface
{
    /**
     * @var string
     */
    protected $file;

    /**
     * @param string $file
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * @inheritDoc
     */
    public function getClassmap()
    {
        return require $this->file;
    }
}
