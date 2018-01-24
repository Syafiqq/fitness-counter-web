<?php
/**
 * This <fitness-counter.com> project created by :
 * Name         : syafiq
 * Date / Time  : 13 January 2018, 11:16 AM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */

namespace App\Firebase;


use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\App;

class FirebaseUserProvider implements UserProvider
{
    /**
     * The Eloquent user model.
     *
     * @var string
     */
    protected $model;

    /**
     * Create a new database user provider.
     *
     * @param  string $model
     * @return void
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed $identifier
     * @return \App\Contracts\Auth\FirebaseAuthenticatable|null
     */
    public function retrieveById($identifier)
    {
        $model = $this->createModel();

        return $model->fetchByUserId($identifier);
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

    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array $credentials
     * @return \App\Contracts\Auth\FirebaseAuthenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials) || (count($credentials) === 1 && array_key_exists('password', $credentials)))
        {
            return null;
        }

        // First we will add each credential element to the query as a where clause.
        // Then we can execute the query and, if we found a user, return it in a
        // Eloquent User "model" that will be utilized by the Guard instances.
        $model = $this->createModel()->fetchUserByCredentials($credentials);

        return $model;
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \App\Contracts\Auth\FirebaseAuthenticatable $user
     * @param  array $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return true;
    }

    /**
     * Create a new instance of the model.
     *
     * @return \App\Contracts\Auth\FirebaseAuthenticatable
     */
    public function createModel()
    {
        $class = '\\' . ltrim($this->model, '\\');

        return App::make($class);
    }

    /**
     * Gets the name of the Eloquent user model.
     *
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Sets the name of the Eloquent user model.
     *
     * @param  string $model
     * @return $this
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }
}

?>
