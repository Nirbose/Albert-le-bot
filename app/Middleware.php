<?php

namespace App;

use Discord\Parts\Channel\Message;

abstract class Middleware
{

    public abstract function handle(Message $message, string $args): bool;

}