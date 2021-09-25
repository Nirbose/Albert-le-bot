<?php

namespace App\Namespaces;

use Discord\Discord;
use Discord\Parts\User\Activity;

class Presence {

    /**
     * status bot
     *
     * @var string
     */
    public $status = 'online';

    /**
     * activity bot
     *
     * @var array|null
     */
    public $activity = null;

    /**
     * Constructor function
     *
     * @param Discord $discord
     * @param array $presences
     */
    public function __construct(Discord $discord, array $presences = [])
    {
        if(empty($presences))
            return;

        if(isset($presences['status']))
            $this->status = $presences['status'];

        if(isset($presences['activity'])) {
            if(!isset($presences['activity']['type']))
                $presences['activity']['type'] = Activity::TYPE_PLAYING;

            $this->activity = new Activity($discord, $presences['activity'], true);
        }
        
        $discord->updatePresence($this->activity, false, $this->status);
    }

}