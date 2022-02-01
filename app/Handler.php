<?php

namespace App;

use Discord\Discord;
use App\Listener;

class Handler {

    public static function load(Discord $client)
    {
        foreach(Listener::$events as $event) {
            $client->on($event->listener, $event->run);
        }
    }

}