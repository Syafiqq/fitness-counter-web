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
            'users_groups'   => sprintf("%s%s%s",      PathMapper::USERS_GROUPS, $role, $uid),
            'users'          => sprintf("%s%s/%s%s",   PathMapper::USERS, $uid, 'roles', $role),
            // @formatter:on
        ];
    }

    /**
     * @param $uid string
     * @param $event_id string
     * @return array
     */
    static function event($uid, $event_id)
    {
        $event_id = is_null($event_id) ? '' : "/${event_id}";

        return [
            sprintf("%s%s", PathMapper::EVENTS, $event_id),
            sprintf("%s/%s/%s%s", PathMapper::USERS, $uid, 'events', PathMapper::EVENTS, $event_id),
        ];
    }
}

?>
