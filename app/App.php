<?php

namespace App;

use Discord\Builders\MessageBuilder;
use Discord\Parts\Channel\Message;
use Discord\Parts\Interactions\Interaction;

class App {

    public $args = [];

    private static array $err = [
        'status' => false,
        'message' => ''
    ];

    public function __construct(
        private Message|Interaction $message, 
        private Command $command
        )
    {
        if (!$command->slash) {
            $this->args = new Arguments($this->message, $this->command->args);
        }
    }

    public function send(string $content, array $options = [])
    {
        $resp = MessageBuilder::new()->setContent($content);

        if (self::$err['status']) {
            self::$err['status'] = false;
            $resp->setContent(self::$err['message']);
        }

        if ($this->command->slash) {
            return $this->message->respondWithMessage($resp);
        }

        return $this->message->channel->sendMessage($resp);
    }

    public static function createError(string $error)
    {
        self::$err['status'] = true;
        self::$err['message'] = $error;
    }

}


