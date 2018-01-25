<?php
/**
 * This <fitness-counter.com> project created by :
 * Name         : syafiq
 * Date / Time  : 25 January 2018, 6:14 AM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */

namespace App\Helper;


use App\Firebase\DataMapper;
use App\Firebase\FirebaseConnection;
use App\Model\FirebaseUser;
use Illuminate\Support\Facades\Log;

class UserHelper
{
    /**
     * @param FirebaseConnection $connection
     * @param FirebaseUser $user
     * @return array
     */
    public static function getUserRole(FirebaseConnection $connection, $user)
    {
        $roles = [];
        try
        {
            /** @var string|null $role */
            $roles = $connection->getConnection()->getDatabase()->getReference(DataMapper::userRole($user->getUid())[0])->getValue() ?: [];
        }
        catch (\Exception $e)
        {
            Log::debug($e->getMessage());
        }

        return $roles;
    }
}

?>
