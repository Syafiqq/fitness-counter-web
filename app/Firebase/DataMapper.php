<?php
/**
 * This <fitness-counter.com> project created by :
 * Name         : syafiq
 * Date / Time  : 14 January 2018, 10:05 PM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */

namespace App\Firebase;


class DataMapper
{
    /**
     * @param string $uid
     * @param string $role
     * @return array
     */
    static function userRole($uid, $role = null): array
    {
        return [
            sprintf("%s/%s%s", PathMapper::USERS_GROUPS, $uid, $role ? "/${role}" : ''),
            sprintf("%s/%s/%s%s", PathMapper::USERS, $uid, 'roles', $role ? "/${role}" : ''),
        ];
    }
}

?>
