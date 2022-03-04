<?php

namespace App\Commands;

class CommandOptionChoice {

    /**
     * @var array $builder
     */
    private static array $builder = [];

    public static function new(): self
    {
        return new self();
    }

    /**
     * Set Choice name
     * 
     * @param string $name
     * @return self
     */
    public function setName(string $name): self
    {
        self::$builder['name'] = $name;
        return $this;
    }

    /**
     * Set Choice description
     * 
     * @param string|int|double $description
     * @return self
     */
    public function setValue(string|int $description): self
    {
        self::$builder['description'] = $description;
        return $this;
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
