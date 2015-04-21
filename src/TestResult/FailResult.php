<?php

namespace Phantestic\TestResult;

class FailResult extends TestResult
{
    /**
     * @var \Exception
     */
    protected $exception;

    public function __construct(\Exception $exception)
    {
        $this->exception = $exception;
    }

    public function getException()
    {
        return $this->exception;
    }
}
