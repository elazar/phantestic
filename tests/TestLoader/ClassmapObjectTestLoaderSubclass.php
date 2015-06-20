<?php

namespace Phantestic\Tests\TestLoader;

use Phantestic\TestLoader\ClassmapObjectTestLoader;

class ClassmapObjectTestLoaderSubclass extends ClassmapObjectTestLoader
{
    public function getClassmap()
    {
        $class = __NAMESPACE__ . '\\PassingTest';
        $file = __DIR__ . '/PassingTest.php';
        return [ $class => $file ];
    }
}
