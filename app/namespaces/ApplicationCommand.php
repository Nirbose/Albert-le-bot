<?php

namespace App\Namespaces;

use Discord\Discord;
use Discord\Http\Http;

class ApplicationCommand extends Http{

    public string $guildID = '781105165754433537';

    public const TYPE_USER = 2;

    public function createUserCommand(string $name, array $options = [], bool $default_permission = true, int $version = 1) {
        $json = [
            'name' => $name,
            'description' => '',
            'options' => $options,
            'default_permission' => $default_permission,
            'type' => self::TYPE_USER
        ];

        $request = $this->post(self::BASE_URL . "/applications/890286598460157993/guilds/".$this->guildID."/commands", $http_options['json'] = $json);

        return $request;
    }

}