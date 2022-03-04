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

}
