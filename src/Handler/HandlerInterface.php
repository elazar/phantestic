<?php

namespace Phantestic\Handler;

use Evenement\EventEmitterInterface;

interface HandlerInterface
{
    public function setEventEmitter(EventEmitterInterface $emitter);
}
