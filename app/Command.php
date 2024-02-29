<?php

namespace App;

use App\Namespaces\Permissions;
use Discord\Parts\Channel\Message;
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
    private string $permission = "";
    private string $usage = 'None';
    private array $middleware = [];
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
        if(isset($options['middleware'])) $this->middleware = $options['middleware'];

        self::$commands[$options['name']] = $this;
        
    }

    private function canExecute(Message $message, string $args): bool
    {
        if (!empty($this->permission) && !Permissions::hasPermission($message->member, $this->permission)) {
            $message->channel->sendMessage("Vous n'avez pas la permition requise");
            return false;
        }

        /** @var string $middleware */
        foreach($this->middleware as $middleware) {
            if (!class_exists($middleware))
            {
                throw new Error(sprintf("Middleware %s not found", $middleware));
            }

            /** @var Middleware $instance */
            $instance = new $middleware();
            if (!$instance->handle($message, $args))
            {
                return false;
            }
        }

        return true;
    }

    public function run(Message $message, string $args): void
    {
        if ($this->canExecute($message, $args))
        {
            call_user_func($this->run, $message, $args, new App($message));
        }
        else
        {
            $message->channel->sendMessage("Vous n'avez pas la permition requise");
        }
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