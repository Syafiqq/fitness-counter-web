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
     * Update user token given saved token
     *
     * @param \Lcobucci\JWT\Token $token
     */
    public function updateToken($token);

    /**
     * Check whether token is need to be updated
     *
     * @return bool
     */
    public function needUpdateToken();

    /**
     * Check token expiration
     *
     * @return bool
     */
    public function isTokenExpired();

    /**
     * Generate new token
     *
     * @return \Lcobucci\JWT\Token $token
     */
    public function generateToken();

    /**
     * Update user token given saved token
     *
     * @param \Lcobucci\JWT\Token $token
     */
    public function setToken($token);

    /**
     * Get token
     *
     * @return \Lcobucci\JWT\Token $token
     */
    public function getToken();

    /**
     * Check token validity
     *
     * @return bool
     */
    public function isTokenValid();

    /**
     * Create a new token
     *
     * @return void
     */
    public function createToken();
}

?>
