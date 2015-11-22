<?php

namespace Phantestic\Classmap\Builder;

interface BuilderInterface
{
    /**
     * @return array Associative array of class file paths keyed by
     *         fully-qualified class name
     */
    public function getClassmap();
}
