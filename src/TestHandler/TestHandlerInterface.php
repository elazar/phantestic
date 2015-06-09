<?php

namespace Phantestic\TestHandler;

use Evenement\EventEmitterInterface;

interface TestHandlerInterface
{
    public function setEventEmitter(EventEmitterInterface $emitter);
}
