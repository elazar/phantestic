<?php

namespace Phantestic\Test;

interface TestInterface
{
    /**
     * @return void
     */
    public function run();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return \Phantestic\Result\Result
     */
    public function getResult();
}
