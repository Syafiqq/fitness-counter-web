<?php
/**
 * This <fitness-counter.com> project created by :
 * Name         : syafiq
 * Date / Time  : 13 January 2018, 11:16 AM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */

namespace App\Providers;


use App\FirebaseUser;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;

class FirebaseUserProvider implements UserProvider
{
    /**
     * The Mongo User Model
     */
    private $model;

    /**
     * Create a new mongo user provider.
     *
     * @param FirebaseUser $model
     */
    public function __construct(FirebaseUser $model)
    {
        $this->model = $model;
    }

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed $identifier
     * @return void
     */
    public function retrieveById($identifier)
    {
        throw new \RuntimeException('Not Implemented');
    }

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param  mixed $identifier
     * @param  string $token
     * @return void
     */
    public function retrieveByToken($identifier, $token)
    {
        throw new \RuntimeException('Not Implemented');
    }

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @param  string $token
     * @return void
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        throw new \RuntimeException('Not Implemented');
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials) || !(empty($credentials['email']) || (empty($credentials['password']))))
        {
            return null;
        }

        return $this->model->fetchUserByCredentials(['email' => $credentials['email'], 'password' => $credentials['password']]);
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @param  array $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        //Check user group
        return true;
    }
}

?>
