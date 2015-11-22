<?php

namespace Phantestic\Test\Builder;

interface BuilderInterface
{
    /**
     * @return \Phantestic\Test\TestInterface
     */
    public function getTest();
}
