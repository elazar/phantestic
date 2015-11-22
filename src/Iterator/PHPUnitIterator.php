<?php

namespace Phantestic\Iterator;

class PHPUnitIterator extends \FilterIterator
{
    /**
     * @param string[] $classes
     */
    public function __construct(array $classes)
    {
        parent::__construct(new MethodIterator($classes));
    }

    /**
     * @return bool
     */
    public function accept()
    {
        $reflector = $this->current();
        $file = $reflector->getDeclaringMethod()->getFileName();
        $method = $reflector->getName();
        return
            preg_match('/Test\.php$/', $file)
            && ($reflector->isPublic() && !$reflector->isStatic())
            && preg_match('/^test/', $method);
    }
}
