<?php

namespace Phantestic\TestCase;

interface TestCaseInterface
{
    /**
     * @return void
     */
    public function run();

    /**
     * @return \Phantestic\TestResult\TestResult
     */
    public function getResult();
}
