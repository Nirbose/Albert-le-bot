<?php

namespace App\Namespaces;

use Discord\Parts\User\User;

/**
 * Permissions classe
 */
class Permissions {

    /**
     * $user varialble
     *
     * @var User
     */
    private $user;

    /**
     * Function to check if a user has a particular permition (role-based permition)
     *
     * @param string $permission
     * @return boolean
     */
    public static function hasPermission(User $user, string $permission): bool
    {
        $result = false;

        foreach($user->roles as $role) {
            if(in_array($permission, (array)$role->permissions)) {
                $result = true;
            }
        }

        return $result;
    }

}