<?php

namespace App\Namespaces;

use Discord\Parts\Permissions\RolePermission;
use Discord\Parts\User\Member;

/**
 * Permissions classe
 */
class Permissions {

    /**
     * $user varialble
     *
     * @var Member
     */
    private $user;

    /**
     * constuctor function
     *
     * @param Member $user
     */
    public function __construct(Member $user)
    {
        $this->user = $user;
    }

    /**
     * Function to check if a user has a particular permition (role-based permition)
     *
     * @param string $permission
     * @return boolean
     */
    public function hasPermission(string $permission): bool
    {
        $result = false;

        foreach($this->user->roles as $role) {
            if(in_array($permission, (array)$role->permissions)) {
                $result = true;
            }
        }

        return $result;
    }

}