<?php

namespace App\Commands;

enum CommandOptionType: int {
    case STRING = 3;
    case INT = 4;
    case BOOLEAN = 5;
    case USER = 6;
    case CHANNEL = 7;
    case ROLE = 8;
    case MENTIONABLE = 9;
    case NUMBER = 10;
    case ATTACHMENT = 11;
}

enum ChannelType: int {
    case TEXT = 0;
    case DM = 1;
    case VOICE = 2;
    case GROUP_DM = 3;
    case CATEGORY = 4;
    case NEWS = 5;
    case STORE = 6;
    case NEWS_THREAD = 10;
    case PUBLIC_THREAD = 11;
    case PRIVATE_THREAD = 12;
    case STAGE_VOICE = 13;
}

class CommandOption
{

    private static string $id;

    /**
     * @var array $builder
     */
    private static array $builder = [];

    /**
     * @var array $types
     */
    private array $types = [
        'string',
        'integer',
        'boolean',
        'number',
        'user',
        'channel',
        'role',
        'mentionable',
        'attachment',
    ];

    /**
     * Create command option instance.
     *
     * @param string $name
     * @param string $description
     * @return self
     */
    public static function new(string $name, string $description): self
    {
        if (strlen($name) < 1 || strlen($name) > 32) {
            throw new \Exception('Command option name must be between 1 and 32 characters.');
        }

        if (strlen($description) < 1 || strlen($description) > 100) {
            throw new \Exception('Command option description must be between 1 and 100 characters.');
        }

        self::$id = uniqid();
        self::$builder[self::$id]['type'] = CommandOptionType::STRING->value;
        self::$builder[self::$id]['name'] = $name;
        self::$builder[self::$id]['description'] = $description;

        return new self();
    }

    /**
     * Set option type
     * 
     * @param CommandOptionType $type
     * @return self
     */
    public function setType(CommandOptionType $type): self
    {
        self::$builder[self::$id]['type'] = $type->value;

        return $this;
    }

    /**
     * Set option choices
     * 
     * @param array|CommandOptionChoice[] $choices
     * @return self
     */
    public function setChoices(array|CommandOptionChoice ...$choices): self
    {
        if (count($choices) > 25) {
            throw new \Exception('Maximum of 25 choices allowed.');
        }

        foreach ($choices as $choice) {
            if ($choice instanceof CommandOptionChoice) {
                self::$builder[self::$id]['choices'][] = $choice->getBuilder();
            } else {
                self::$builder[self::$id]['choices'][] = $choice;
            }
        }

        return $this;
    }

    /**
     * Set channels types
     * 
     * @param array|ChannelType $types
     * @return self
     */
    public function setChannelsTypes(array|ChannelType ...$types): self
    {
        if ($types instanceof ChannelType) {
            self::$builder[self::$id]['channelsTypes'][] = $types->getValue();
        } else {
            self::$builder[self::$id]['channelsTypes'] = $types;
        }

        return $this;
    }

    /**
     * Is option required
     * 
     * @param bool $required
     * @return self
     */
    public function setRequired(bool $required): self
    {
        self::$builder[self::$id]['required'] = $required;
        return $this;
    }

    /**
     * Set minimum value
     * 
     * @param int $min
     * @return self
     */
    public function setMin(int $min): self
    {
        self::$builder[self::$id]['min_value'] = $min;
        return $this;
    }

    /**
     * Set maximum value
     * 
     * @param int $max
     * @return self
     */
    public function setMax(int $max): self
    {
        self::$builder[self::$id]['max_value'] = $max;
        return $this;
    }

    /**
     * Set autocomplete
     * 
     * @param bool $autocomplete
     * @return self
     */
    public function setAutocomplete(bool $autocomplete): self
    {
        self::$builder[self::$id]['autocomplete'] = $autocomplete;
        return $this;
    }

    /**
     * Get builder option
     * 
     * @return array
     */
    public function getBuilder(): array
    {
        return self::$builder;
    }
}
