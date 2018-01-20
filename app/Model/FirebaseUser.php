<?php
/**
 * This <fitness-counter.com> project created by :
 * Name         : syafiq
 * Date / Time  : 13 January 2018, 9:27 AM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */

namespace App\Model;

use App\Contracts\Auth\FirebaseAuthenticatable;
use App\Firebase\FirebaseConnection;
use DateTime;
use Illuminate\Contracts\Session\Session;
use Kreait\Firebase\Auth\User;
use Psy\Exception\RuntimeException;

class FirebaseUser extends User implements FirebaseAuthenticatable
{
    protected $rememberTokenName = 'remember_token';
    /**
     * @var string
     */
    private $uid;
    /**
     * @var string
     */
    private $email;
    /**
     * @var array
     */
    private $token;
    /**
     * @var string
     */
    private $role;
    /**
     * @var FirebaseConnection
     */
    private $firebase;

    /**
     * FirebaseUser constructor.
     * @param FirebaseConnection $firebase
     * @param Session $session
     */
    public function __construct(FirebaseConnection $firebase, Session $session)
    {
        $this->firebase = $firebase;
    }

    /**
     * Fetch user by Credentials
     *
     * @param array $credentials
     * @return \App\Contracts\Auth\FirebaseAuthenticatable
     */
    public function fetchUserByCredentials(Array $credentials)
    {
        /** @var \Kreait\Firebase\Auth\User $user */
        $user = $this->firebase->getConnection()->getAuth()->getUserByEmailAndPassword($credentials['email'], $credentials['password']);

        if (!is_null($user) && $this->isRoleValid($credentials['role']))
        {
            $this->setCredential($user);
        }

        return $this;
    }

    /**
     * @param $identifier string
     * @return \App\Contracts\Auth\FirebaseAuthenticatable
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
     * Update user token given saved token
     *
     * @param array $token
     */
    public function updateToken($token)
    {
        $this->setToken($token);
    }

    /**
     * @return bool
     */
    public function needUpdateToken()
    {
        $need = false;
        if (!$this->isTokenValid())
        {
            $this->createToken();
            $need = true;
        }

        if ($this->isTokenExpired())
        {
            $this->updateToken($this->generateToken());
            $need = true;
        }

        return $need;
    }

    /**
     * @return bool
     */
    public function isTokenExpired()
    {
        $now = new DateTime();

        $expiresAt = new DateTime();
        $expiresAt->setTimestamp($this->token['expiration']);

        return $now > $expiresAt;
    }

    /**
     * @return array $token
     */
    public function generateToken()
    {
        if ($this->firebase != null)
        {
            /** @var \Lcobucci\JWT\Token $token */
            $token = $this->firebase->getConnection()->getAuth()->createCustomToken($this->uid);

            return $this->simplifyToken($token);
        }
        else
        {
            throw new RuntimeException('Firebase Connection has not been established yet');
        }
    }

    /**
     * @param \Lcobucci\JWT\Token $token
     * @return array
     */
    private function simplifyToken($token)
    {
        /** @var int|bool $exp */
        $exp = $token->getClaim('exp', false);

        if ($exp === false)
        {
            return $this->generateToken();
        }

        return ['token' => $token->__toString(), 'expiration' => $exp];
    }

    /**
     * @return array $token
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @return bool
     */
    public function isTokenValid()
    {
        return ($this->token != null) && (is_array($this->token)) && (key_exists('token', $this->token) && key_exists('expiration', $this->token));
    }

    /**
     * Update user token given saved token
     *
     * @param array $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return void
     */
    public function createToken()
    {
        $this->setToken($this->generateToken());
    }

    /**
     * @return mixed
     */
    public function getUid(): string
    {
        return $this->uid;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @param $user
     * @return bool
     */
    private function isRoleValid($user)
    {
        return true;
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

    /**
     * @return string
     */
    public function __toString()
    {
        return "FirebaseUser" . \GuzzleHttp\json_encode(['uid' => $this->uid, 'email' => $this->email, 'token' => $this->token]);
    }

    /**
     * @param \Illuminate\Contracts\Session\Session $session
     * @param \Kreait\Firebase\Auth\User|FirebaseUser $user
     * @return void
     */
    public function save(Session $session, $user)
    {
        $session->put($this->sessionName($user->uid), [
            'uid' => $user->uid,
            'token' => $user->isTokenValid() && !$user->isTokenExpired() ? $user->token : $user->generateToken(),
            'role' => $user->role ?: $user->getValidRole(),
        ]);
    }

    /**
     * @param \Illuminate\Contracts\Session\Session $session
     * @param \Kreait\Firebase\Auth\User|FirebaseUser $user
     * @return void
     */
    public function load(Session $session, $user)
    {
        $userData = $session->get($this->sessionName($user->uid), null);
        if (!is_null($userData))
        {
            $user->token = $userData['token'];
            $user->role  = $userData['role'];
        }
    }

    /**
     * @return string
     */
    public function getValidRole()
    {
        return 'provider';
    }

    private function sessionName($name)
    {
        return 'firebase_' . $name . '_credential_' . sha1(static::class);
    }
}

?>
