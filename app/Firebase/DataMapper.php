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
    static function userRole($uid = null, $role = null): array
    {
        $uid  = is_null($uid) ? '' : "/${uid}";
        $role = is_null($role) ? '' : "/${role}";

        return [
            // @formatter:off
            'users_groups'   => PathMapper::USERS_GROUPS . $role . $uid,
            'users'          => PathMapper::USERS . $uid . '/roles' . $role,
            // @formatter:on
        ];
    }

    /**
     * @param $uid string
     * @param $role string
     * @param $id string
     * @return array
     */
    static function event($uid = null, $role = null, $id = null)
    {
        $uid  = is_null($uid) ? '' : "/${uid}";
        $role = is_null($role) ? '' : "/${role}";
        $id   = is_null($id) ? '' : "/${id}";

        return [
            // @formatter:off
            'events'    => PathMapper::EVENTS . $id,
            'users'     => PathMapper::USERS . $uid . $role . '/' . PathMapper::EVENTS . $id,
            // @formatter:on
        ];
    }
}

?>
