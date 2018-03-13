<?php
/**
 * This <fitness-counter.com> project created by :
 * Name         : syafiq
 * Date / Time  : 17 January 2018, 9:39 PM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */

namespace App\Contracts\Auth;


use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Session\Session;

interface FirebaseAuthenticatable extends Authenticatable
{
    /**
     * Retrieve a user by their unique identifier.
     *
     * @param $identifier string
     * @return \App\Contracts\Auth\FirebaseAuthenticatable
     */
    public function fetchByUserId($identifier);

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array $credentials
     * @return \App\Contracts\Auth\FirebaseAuthenticatable|null
     */
    public function fetchUserByCredentials(array $credentials);

    /**
     * @param \Illuminate\Contracts\Session\Session $session
     * @param \Kreait\Firebase\Auth\UserRecord $user
     * @return mixed
     */
    public function save(Session $session, $user);

    /**
     * @param \Illuminate\Contracts\Session\Session $session
     * @param \Kreait\Firebase\Auth\UserRecord $user
     * @return mixed
     */
    public function load(Session $session, $user);
}

?>
