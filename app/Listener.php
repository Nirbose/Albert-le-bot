<?php

namespace App;

class Listener
{

    public static $events = [];

    public $listener;
    public $run;

    public function __construct(array $options)
    {
        if(!isset($options['listener']))
            throw new \Error('Missing listener property on some Listener');

        if(!isset($options['run']))
            throw new \Error('Missing run property on Listener');

        $this->listener = $options['listener'];
        $this->run = $options['run'];

        self::$events[$this->listener] = $this;
        
    }
}
