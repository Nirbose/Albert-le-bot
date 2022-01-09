<?php

namespace App;

use Discord\Discord;
use Discord\Parts\Guild\Guild;
use Discord\Parts\Interactions\Command\Command as SlashCommand;

class Command
{

    /**
     * Discord client
     *
     * @var Discord|null
     */
    private static $discord = null;

    /**
     * All Command
     *
     * @var array
     */
    private static array $commands = [];

    /**
     * Name Command
     *
     * @var string
     */
    public string $name;

    /**
     * Description Command
     *
     * @var string
     */
    public string $description;

    /**
     * Aliases Command
     *
     * @var array
     */
    public array $aliases = [];

    /**
     * Args Command
     *
     * @var array
     */
    public array $args = [];

    /**
     * Is owner only Command
     *
     * @var bool
     */
    public bool $ownerOnly = false;

    /**
     * Is booster only Command
     *
     * @var bool
     */
    public bool $boosterOnly = false;

    /**
     * Permition Commmand
     *
     * @var string|null
     */
    public $permission = null;

    /**
     * Usage Command
     *
     * @var string|null
     */
    public string $usage = 'None';

    /**
     * Is Command slash
     *
     * @var boolean
     */
    public bool $slash = false;

    /**
     * Run Command
     *
     * @var callable|object
     */
    public $run;

    public function __construct(array $options)
    {

        if (is_null(self::$discord))
            throw new \Error("Discord n'existe pas.");

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
        if(isset($options['usage'])) $this->usage = $options['usage'];
        if(isset($options['args'])) $this->args = $options['args'];
        
        if(isset($options['slash']) && $options['slash']) {
            $this->slash = true;

            $slashCommand = new SlashCommand(self::$discord, [
                'name' => $this->name,
                'description' => $this->description,
            ]);
            
            self::$discord->guilds->fetch('781105165754433537')->done(function (Guild $guild) use ($slashCommand) {
                $guild->commands->save($slashCommand);
            });
        } 

        self::$commands[$options['name']] = $this;
        
    }

    public static function getCommand() {
        return self::$commands;
    }

    public static function init_discord(Discord $discord) {
        if (self::$discord) {
            return;
        }

        self::$discord = $discord;
    }

}