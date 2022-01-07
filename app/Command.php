<?php

namespace App;

class Command
{
    public static $commands = [];

    /**
     * Command name
     *
     * @var string
     */
    public string $name;

    /**
     * Command description
     *
     * @var string
     */
    public string $description;

    /**
     * Command aliases
     *
     * @var array
     */
    public array $aliases = [];

    /**
     * Command permission only for owner
     *
     * @var string
     */
    public bool $ownerOnly;

    /**
     * Command permission only for booster
     *
     * @var string
     */
    public bool $boosterOnly;

    /**
     * Command permission
     *
     * @var string
     */
    public string $permission;

    /**
     * Invisible for help
     *
     * @var bool
     */
    public $invisible;

    /**
     * Usage Command exemple
     * 
     * @var string
     */
    public string $usage = 'None';

    /**
     * Command is SlashCommand
     * 
     * @var bool
     */
    public bool $slash;

    /**
     * Run commmand function
     * 
     * @var callable
     */
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
        if(isset($options['slash'])) $this->slash = $options['slash'];

        self::$commands[$options['name']] = $this;
        
    }

}