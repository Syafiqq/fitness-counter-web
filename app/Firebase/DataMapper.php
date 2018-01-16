<?php
/**
 * This <fitness-counter.com> project created by :
 * Name         : syafiq
 * Date / Time  : 14 January 2018, 10:05 PM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */

namespace App\Firebase;


use Kreait\Firebase\Auth\User;

class DataMapper
{
    static function userRole(User $user, string $role): array
    {
        return [
            PathMapper::USERS_GROUPS . "/{$user->getUid()}/${role}",
            PathMapper::USERS . "/{$user->getUid()}/roles/${role}",
        ];
    }
}

?>
