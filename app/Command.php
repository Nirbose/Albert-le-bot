<?php

namespace App;

use Discord\Discord;
use Discord\Parts\Interactions\Command\Command as SlashTypeCommand;

enum Context: int {
    case MESSAGE = 1;
    case SLASH = 2;
}

enum Type: int {
    case ALL = 1;
    case GUILD = 2;
    case DM = 3;
}

/**
 * Command class
 * 
 * @property string $name
 * @property string|null $description
 * @property string|null $usage
 * @property string|null $category
 * @property int|null $context
 * @property string[]|null $aliases
 * @property array|null $options
 * @property int|null $type
 * @property array $guilds
 * @property $run
 */
class Command
{

    /**
     * @var Discord|null
     */
    private static $discord = null;

    /**
     * @var string
     */
    private static string $name;

    /**
     * @var array
     */
    private static array $commands = [];

    /**
     * Create command instance.
     *
     * @param Discord $discord
     * @return void
     */
    public static function create(Discord $discord): void
    {
        if (self::$discord === null) {
            self::$discord = $discord;
        }
    }

    /**
     * Add new command
     * 
     * @param string $name
     * @param callable $callback
     */
    public static function add(string $name, callable $callback): self
    {
        self::$name = $name;
        self::$commands[$name]['run'] = $callback;

        return new self;
    }

    /**
     * Search command by name
     * 
     * @param string $name
     * @return object|null
     */
    public static function search(string $name): object
    {
        if (isset(self::$commands[$name])) {
            return new self;
        }

        foreach (self::$commands as $command) {
            if (in_array($name, $command['aliases'])) {
                return new self;
            }
        }

        return null;
    }

    /**
     * set Description of command
     * 
     * @param string $name
     * @return self
     */
    public function setDescription(string $name): self
    {
        $this::$commands[$this::$name]['description'] = $name;

        return new static();
    }

    /**
     * set Context of command
     * 
     * @param Context $context
     */
    public function setContext(Context $context): self
    {
        $this::$commands[$this::$name]['context'] = $context;

        return new static();
    }

    /**
     * set Usage of command
     * 
     * @param string $usage
     * @return self
     */
    public function setUsage(string $usage): self
    {
        $this::$commands[$this::$name]['usage'] = $usage;

        return new static();
    }

    /**
     * set Aliases of command
     * 
     * @param string $aliases
     * @return self
     */
    public function setAliases(string ...$aliases): self
    {
        $this::$commands[$this::$name]['aliases'] = $aliases;

        return new static();
    }

    /**
     * set Category of command
     * 
     * @param string $category
     * @return self
     */
    public function setCategory(string $category): self
    {
        $this::$commands[$this::$name]['category'] = $category;

        return new static();
    }

    /**
     * set Options of command
     * 
     * @param array $options
     * @return self
     */
    public function setOptions(array $options): self
    {
        $this::$commands[$this::$name]['options'] = $options;

        return new static();
    }

    /**
     * set Type of command
     * 
     * @param Type|SlashTypeCommand $type
     * @return self
     */
    public function setType(Type|SlashTypeCommand $type): self
    {
        if ($type instanceof Type) {
            $this::$commands[$this::$name]['type'] = $type->value;
        } else {
            $this::$commands[$this::$name]['type'] = $type;
        }

        return new static();
    }

    /**
     * set Guilds of command.
     * Command available only in these guilds.
     * 
     * @param string $guilds
     * @return self
     */
    public function setGuilds(string ...$guilds): self
    {
        $this::$commands[$this::$name]['guilds'] = $guilds;

        return new static();
    }

    /**
     * Add Subcommand to command
     * 
     * @param string $name
     * @param callable $callback
     * @return self
     */
    public function addSubcommand(string $name, callable $callback): self
    {
        self::$commands[$this::$name]['subcommands'][$name]['run'] = $callback;

        return new self;
    }

    /**
     * Get Command Context
     * 
     * @return int|null
     */
    public function getContext(): ?int
    {
        return $this::$commands[$this::$name]['context'] ?? null;
    }

    /**
     * Get Command Description
     * 
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this::$commands[$this::$name]['description'] ?? null;
    }

    /**
     * Get Command Usage
     * 
     * @return string|null
     */
    public function getUsage(): ?string
    {
        return $this::$commands[$this::$name]['usage'] ?? null;
    }

    /**
     * Get Command Aliases
     * 
     * @return string[]|null
     */
    public function getAliases(): ?array
    {
        return $this::$commands[$this::$name]['aliases'] ?? null;
    }

    /**
     * Get Command Category
     * 
     * @return string|null
     */
    public function getCategory(): ?string
    {
        return $this::$commands[$this::$name]['category'] ?? null;
    }

    /**
     * Get Command Options
     * 
     * @return array|null
     */
    public function getOptions(): ?array
    {
        return $this::$commands[$this::$name]['options'] ?? null;
    }

    /**
     * Get Command Type
     * 
     * @return int|null
     */
    public function getType(): ?int
    {
        return $this::$commands[$this::$name]['type'] ?? null;
    }

    /**
     * Get Command Run
     */
    public function getRun()
    {
        return $this::$commands[$this::$name]['run'];
    }

    /**
     * Get Command Guilds
     * 
     * @return string[]|null
     */
    public function getGuilds(): ?array
    {
        return $this::$commands[$this::$name]['guilds'] ?? null;
    }

    /**
     * Get Subcommands
     * 
     * @return array|null
     */
    public function getSubcommands(): ?array
    {
        return $this::$commands[$this::$name]['subcommands'] ?? null;
    }

}
