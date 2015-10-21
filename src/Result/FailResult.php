<?php

namespace Phantestic\Result;

class FailResult extends Result
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
