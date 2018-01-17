<?php
/**
 * This <fitness-counter.com> project created by :
 * Name         : syafiq
 * Date / Time  : 13 January 2018, 9:27 AM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */

namespace App\Model;

use App\Contracts\Auth\TokenedAuthenticatable;
use App\Firebase\FirebaseConnection;
use Psy\Exception\RuntimeException;

class FirebaseUser implements TokenedAuthenticatable
{
    protected $rememberTokenName = 'remember_token';
    private $uid;
    private $email;
    /**
     * @var FirebaseConnection
     */
    private $firebase;

    /**
     * FirebaseUser constructor.
     * @param FirebaseConnection $firebase
     */
    public function __construct(FirebaseConnection $firebase = null)
    {
        $this->firebase = $firebase;
    }


    /**
     * Fetch user by Credentials
     *
     * @param array $credentials
     * @return \App\Contracts\Auth\TokenedAuthenticatable
     */
    public function fetchUserByCredentials(Array $credentials)
    {
        /** @var \Kreait\Firebase\Auth\User $user */
        $user = $this->firebase->getConnection()->getAuth()->getUserByEmailAndPassword($credentials['email'], $credentials['password']);

        if (!is_null($user) && $this->isRoleValid($user))
        {
            $this->setCredential($user);
        }

        return $this;
    }

    /**
     * @param $identifier string
     * @return \App\Contracts\Auth\TokenedAuthenticatable
     */
    public function fetchByUserId($identifier)
    {
        /** @var \Kreait\Firebase\Auth\User $user */
        $user = $this->firebase->getConnection()->getAuth()->getUser($identifier);

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
        $this->firebase->getConnection()->asUser($user);
        $this->email = $user->getEmail();
        $this->uid   = $user->getUid();
    }

    private function isRoleValid($user)
    {
        return true;
    }

    public function __toString()
    {
        return "FirebaseUser" . \GuzzleHttp\json_encode(['uid' => $this->uid, 'email' => $this->email]);
    }
}

?>
