<?php

namespace App\Commands;

class CommandOptionChoice {

    private static string $uid;

    /**
     * @var array $builder
     */
    private static array $builder = [];

    public static function new(string $name, string|int $value): self
    {
        self::$uid = uniqid();
        self::$builder[self::$uid]['name'] = $name;
        self::$builder[self::$uid]['value'] = $value;

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
