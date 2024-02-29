<?php

namespace App\Middlewares;

use App\Middleware;
use Discord\Parts\Channel\Message;

class OwnerMiddleware extends Middleware
{

    public function handle(Message $message, string $args): bool
    {
        return $message->author->id == $_ENV['OWNER_ID'];
    }
}