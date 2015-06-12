<?php

namespace Phantestic;

trait TestAssertions
{
    public function assertInstanceOf($class, $object)
    {
        if (!$object instanceof $class) {
            $message = 'Object '
                . var_export($object, true)
                . ' is not an instance of class '
                . $class;
            throw new \DomainException($message);
        }
    }

    public function assertSame($value1, $value2)
    {
        if ($value1 !== $value2) {
            $message = var_export($value1, true)
                . ' is not identical to '
                . var_export($value2, true);
            throw new \DomainException($message);
        }
    }

    public function assertRegExp($pattern, $subject)
    {
        if (preg_match($pattern, $subject) === 0) {
            $message = 'String '
                . var_export($subject, true)
                . ' does not match pattern '
                . var_export($pattern, true);
            throw new \DomainException($message);
        }
    }
}
