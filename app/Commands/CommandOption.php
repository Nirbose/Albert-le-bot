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

    /**
     * @var array $builder
     */
    private static array $builder = [
        'type' => CommandOptionType::STRING,
    ];

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

        self::$builder['name'] = $name;
        self::$builder['description'] = $description;

        return new self();
    }

    /**
     * Set option type
     * 
     * @param string|CommandOptionType $type
     * @return self
     * @throws \Exception
     */
    public function setType(string|CommandOptionType $type): self
    {
        if (!in_array($type, $this->types)) {
            throw new \Exception('Invalid type');
        }

        self::$builder['type'] = $type;

        return $this;
    }

    /**
     * Set option choices
     * 
     * @param array|CommandOptionChoice $choices
     * @return self
     */
    public function setChoices(array|CommandOptionChoice ...$choices): self
    {
        if (count($choices) > 25) {
            throw new \Exception('Maximum of 25 choices allowed.');
        }

        if ($choices instanceof CommandOptionChoice) {
            self::$builder['choices'][] = $choices->getBuilder();
        } else {
            self::$builder['choices'] = $choices;
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
            self::$builder['channelsTypes'][] = $types->getValue();
        } else {
            self::$builder['channelsTypes'] = $types;
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
        self::$builder['required'] = $required;
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
        self::$builder['min'] = $min;
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
        self::$builder['max'] = $max;
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
        self::$builder['autocomplete'] = $autocomplete;
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
