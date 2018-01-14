<?php
/**
 * This <fitness-counter.com> project created by :
 * Name         : syafiq
 * Date / Time  : 13 January 2018, 9:27 AM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */

namespace App;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Psy\Exception\RuntimeException;

class FirebaseUser implements AuthenticatableContract
{
    protected $rememberTokenName = 'remember_token';
    private $uid;
    private $email;
    private $connection;

    /**
     * FirebaseUser constructor.
     */
    public function __construct()
    {
        $serviceAccount = ServiceAccount::fromJsonFile(resource_path() . env('FIREBASE_SERVICE', '/assets/sdk/fitness-counter-6a479f0be813.json'));
        $apiKey         = env('FIREBASE_API_KEY', 'AIzaSyD_xXi_xZo25ASGgFODWv9av5lLLPHRWeg');

        $this->connection = (new Factory)
            ->withServiceAccountAndApiKey($serviceAccount, $apiKey)
            ->create();
    }


    /**
     * Fetch user by Credentials
     *
     * @param array $credentials
     * @return AuthenticatableContract
     */
    public function fetchUserByCredentials(Array $credentials)
    {
        /** @var \Kreait\Firebase\Auth\User $user */
        $user = $this->connection->getAuth()->getUserByEmailAndPassword($credentials['email'], $credentials['password']);

        if (!is_null($user))
        {
            $this->setCredential($user);
        }

        return $this;
    }

    /**
     * @param $identifier string
     * @return $this
     */
    public function fetchByUserId($identifier)
    {
        /** @var \Kreait\Firebase\Auth\User $user */
        $user = $this->connection->getAuth()->getUser($identifier);

        if (!is_null($user))
        {
            $this->setCredential($user);
        }

        return $this;
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->{$this->getAuthIdentifierName()};
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return "uid";
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        throw new RuntimeException('Not Implemented');
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
        if (!empty($this->getRememberTokenName()))
        {
            return $this->{$this->getRememberTokenName()};
        }

        return null;
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return $this->rememberTokenName;
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string $value
     * @return void
     */
    public function setRememberToken($value)
    {
        if (!empty($this->getRememberTokenName()))
        {
            $this->{$this->getRememberTokenName()} = $value;
        }
    }

    /**
     * @return mixed
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @param mixed $uid
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @param \Kreait\Firebase\Auth\User $user
     */
    private function setCredential($user)
    {
        $this->connection->asUser($user);
        $this->email = $user->getEmail();
        $this->uid   = $user->getUid();
    }
}

?>
