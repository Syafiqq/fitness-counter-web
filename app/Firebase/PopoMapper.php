<?php
/**
 * This <fitness-counter.com> project created by :
 * Name         : syafiq
 * Date / Time  : 10 February 2018, 10:24 AM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */

namespace App\Firebase;


class PopoMapper
{
    /**
     * @param string $status
     * @param int $code
     * @param array $data
     * @return array
     */
    static function jsonResponse($code = 200, $status = 'Empty Status', $data = null)
    {
        return compact('code', 'status', 'data');
    }
}

?>
