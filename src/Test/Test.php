<?php

namespace Phantestic\Test;

use Phantestic\Result\FailResult;
use Phantestic\Result\PassResult;
use Phantestic\Result\Result;

class Test implements TestInterface
{
    /**
     * @var callable
     */
    protected $callback;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var \Phantestic\Result\ResultInterface
     */
    protected $result;

    /**
     * @param callback $callback
     * @param string $name
     */
    public function __construct(callable $callback, $name)
    {
        $this->callback = $callback;
        $this->name = $name;
    }

    /**
     * @return void
     */
    public function run()
    {
        set_error_handler([$this, 'convertErrorToException']);

        try {
            call_user_func($this->callback);
        } catch (Result $result) {
            $this->result = $result;
        } catch (\Exception $e) {
            $this->result = new FailResult($e);
        }

        if (!$this->result) {
            $this->result = new PassResult;
        }

        restore_error_handler();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return \Phantestic\Result\Result
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @throws \ErrorException
     */
    public function convertErrorToException($severity, $message, $file, $line)
    {
        throw new \ErrorException($message, 0, $severity, $file, $line);
    }
}
