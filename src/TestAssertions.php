<?php

namespace Phantestic;

trait TestAssertions
{
    public function assertInstanceOf($expected, $actual, $message = '')
    {
        if (!$actual instanceof $expected) {
            if (empty($message)) {
                $message = 'Object '
                    . var_export($actual, true)
                    . ' is not an instance of class '
                    . $expected;
            }
            throw new \DomainException($message);
        }
    }

    public function assertSame($expected, $actual, $message = '')
    {
        if ($expected !== $actual) {
            if (empty($message)) {
                $message = var_export($expected, true)
                    . ' is not identical to '
                    . var_export($actual, true);
            }
            throw new \DomainException($message);
        }
    }

    public function assertRegExp($pattern, $string, $message = '')
    {
        if (preg_match($pattern, $string) === 0) {
            if (empty($message)) {
                $message = 'String '
                    . var_export($string, true)
                    . ' does not match pattern '
                    . var_export($pattern, true);
            }
            throw new \DomainException($message);
        }
    }

    public function assertCount($expected, $haystack, $message = '')
    {
        $actual = count($haystack);
        if ($actual !== $expected) {
            if (empty($message)) {
                $message = 'Expected '
                    . var_export($haystack, true)
                    . ' to produce count '
                    . $expected
                    . ', got count '
                    . $actual;
            }
            throw new \DomainException($message);
        }
    }

    public function assertTrue($condition, $message = '')
    {
        $this->assertSame(true, $condition, $message);
    }
}
