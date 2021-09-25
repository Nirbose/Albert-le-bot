<?php

namespace App\Namespaces;

use Discord\Parts\Channel\Message;
use Discord\Parts\Embed\Embed;
use Discord\Discord;

/**
 * DefaultEmbed Class
 */
class DefaultEmbed {

    /**
     * Content color Embed
     *
     * @var string
     */
    public $color = '57F287';

    /**
     * Content embed
     *
     * @var Embed
     */
    public $embed;

    /**
     * Content Discord message of user
     *
     * @var Message
     */
    public $message;

    /**
     * Constructor function
     *
     * @param Message $message
     * @param Discord $discord
     * @param array $options
     */
    public function __construct(Message $message, Discord $discord, array $options = [])
    {
        $this->message = $message;

        if(isset($options['color']))
            $this->color = $options['color'];

        $content = [
            'color' => hexdec($this->color),
            'author' => [
                'name' => $message->author->user->username,
                'icon_url' => $message->author->user->avatar
            ],
        ];

        if(isset($options['description']))
            $content['description'] = $options['description'];

        if(isset($options['title']))
            $content['title'] = $options['title'];
        
        if(isset($options['fields']))
            $content['fields'] = $options['fields'];

        $this->embed = new Embed($discord, $content, true);
    } 

    /**
     * Send Embed in the discord channel
     *
     * @return void
     */
    public function send() {
        return $this->message->channel->sendEmbed($this->embed);
    }

}
