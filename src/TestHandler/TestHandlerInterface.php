<?php

namespace Phantestic;

use Evenement\EventEmitterInterface;

interface TestHandlerInterface
{
    public function setEventEmitter(EventEmitterInterface $emitter);
}
