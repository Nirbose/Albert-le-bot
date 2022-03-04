<?php

namespace App\Commands;

class CommandBuilder {
    public string $name;

    public string|null $description = null;

    public string|null $usage = null;

    public string|null $category = null;

    public array $aliases = [];

    public int $context = 1;
    
    public array $options = [];

    public int $type = 1;

    public array $guilds = [];

    public $run;

    public function __construct(array $command) {
        foreach ($command as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    public function isSlash(): bool
    {
        return $this->context === Context::SLASH->value;
    }

    /**
     * Build command for slash command
     *
     * @return array
     */
    public function slashBuilder(): array
    {
        $build = [
            'type' => $this->type,
            'name' => $this->name,
            'description' => $this->description,
        ];

        if ($this->type !== 1) {
            return $build;
        }

        $build['options'] = $this->options;

        return $build;
    }

}
