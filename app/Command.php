<?php

namespace App;

class Command
{
    public static $commands = [];

    public $name;
    public $description;
    public $aliases = [];
    public $ownerOnly;
    public $boosterOnly;
    public $permission;
    public $invisible;
    public $usage = 'None';

    public $run;

    public function __construct(array $options)
    {
        if(!isset($options['name']))
            throw new \Error("Missing name property on some Command");

        if(!isset($options['run']))
            throw new \Error('Missing run property on "'.$options['name'].'" Command');

        $this->name = strtolower($options['name']);
        $this->run = $options['run'];

        if(isset($options['description'])) {
            $this->description = $options['description'];
        } else {
            $this->description = ucfirst($options['name']) . " Command";
        }

        if(isset($options['aliases'])) $this->aliases = $options['aliases'];
        if(isset($options['ownerOnly'])) $this->ownerOnly = $options['ownerOnly'];
        if(isset($options['boosterOnly'])) $this->boosterOnly = $options['boosterOnly'];
        if(isset($options['permission'])) $this->permission = $options['permission'];
        if(isset($options['invisible'])) $this->invisible = $options['invisible'];
        if(isset($options['usage'])) $this->usage = $options['usage'];

        self::$commands[$options['name']] = $this;
        
    }

}