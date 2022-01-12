<?php

namespace App;

use Discord\Repository\Guild\RoleRepository;
use Discord\Repository\GuildRepository;

class Datas {
    const WELCOME = ['Bienvenue {user} !'];

    /**
     * role id for booster
     * 
     * @var array
     */
    const BOOSTER_ROLES = ['891707949997785148', '894297624935546932'];

    /**
     * role id for invite serveur invite
     * 
     * @var array
     */
    const TEMP_INVITE_ROLES = ['891707949997785148'];

    /**
     * 
     */
    const PERSONNEL_VOICE = ['818590927052472380'];

    public static function getGoodData(GuildRepository|RoleRepository $repo, Datas $data)
    {
        foreach ($data as $value) {
            
        }
    }
}