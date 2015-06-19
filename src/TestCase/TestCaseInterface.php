<?php

namespace Phantestic\TestCase;

interface TestCaseInterface
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
     * @return \Phantestic\TestResult\TestResult
     */
    public function getResult();
}
