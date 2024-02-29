<?php

namespace App;

use App\Namespaces\Permissions;
use Error;

/**
 * Class Command
 * @package App
 *
 * @property string $name
 * @property string $description
 * @property array $aliases
 * @property string $permission
 * @property string $usage
 */
class Command
{
    private static array $commands = [];

    private string $name;
    private string $description;
    private array $aliases = [];
    private $permission;
    private string $usage = 'None';

    private $run;

    public function __construct(array $options)
    {
        if(!isset($options['name']))
            throw new Error("Missing name property on some Command");

        if(!isset($options['run']))
            throw new Error('Missing run property on "'.$options['name'].'" Command');

        $this->name = strtolower($options['name']);
        $this->run = $options['run'];

        if(isset($options['description'])) {
            $this->description = $options['description'];
        } else {
            $this->description = ucfirst($options['name']) . " Command";
        }

        if(isset($options['aliases'])) $this->aliases = $options['aliases'];
        if(isset($options['permission'])) $this->permission = $options['permission'];
        if(isset($options['usage'])) $this->usage = $options['usage'];

        self::$commands[$options['name']] = $this;
        
    }

    public function run($message, $args): void
    {
        if ($this->permission && !Permissions::hasPermission($this->message->author, $this->permission)) {
            $message->channel->sendMessage("Vous n'avez pas la permition requise");
            return;
        }

        call_user_func($this->run, $message, $args);
    }

    public static function getCommands(): array
    {
        return self::$commands;
    }

    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }
        return null;
    }
}