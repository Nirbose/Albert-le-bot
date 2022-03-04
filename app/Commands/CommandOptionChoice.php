<?php

namespace App\Commands;

class CommandOptionChoice {

    /**
     * @var array $builder
     */
    private static array $builder = [];

    public static function new(string $name, string|int $value): self
    {
        self::$builder['name'] = $name;
        self::$builder['value'] = $value;

        return new self();
    }

    /**
     * Get builder choice
     * 
     * @return array
     */
    public function getBuilder(): array
    {
        return self::$builder;
    }

}
