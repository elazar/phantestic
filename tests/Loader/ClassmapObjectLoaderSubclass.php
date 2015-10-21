<?php

namespace Phantestic\Tests\Loader;

use Phantestic\Loader\ClassmapObjectLoader;

class ClassmapObjectLoaderSubclass extends ClassmapObjectLoader
{
    public function getClassmap()
    {
        $class = __NAMESPACE__ . '\\PassingTest';
        $file = __DIR__ . '/PassingTest.php';
        return [ $class => $file ];
    }
}
