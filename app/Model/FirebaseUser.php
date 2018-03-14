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
use App\Firebase\DataMapper;
use App\Firebase\FirebaseConnection;
use DateTime;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\App;
use Kreait\Firebase\Auth\UserRecord;
use Psy\Exception\RuntimeException;

class FirebaseUser extends UserRecord implements FirebaseAuthenticatable
{
    protected $rememberTokenName = 'remember_token';
    /**
     * @var string
     */
    public $uid;
    /**
     * @var string
     */
    public $email;
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
     */
    public function __construct(FirebaseConnection $firebase)
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
        /** @var \Kreait\Firebase\Auth\UserRecord $user */
        $user = $this->firebase->getConnection()->getAuth()->verifyPassword($credentials['email'], $credentials['password']);
        if (!is_null($user))
        {
            $this->setCredential($user);
            $this->role = $this->role ?: $credentials['role'] ?? $this->getValidRole();
            if (!$this->isRoleValid($this->role))
            {
                return null;
            }
        }

        return $this;
    }

    /**
     * @param $identifier string
     * @return \App\Contracts\Auth\FirebaseAuthenticatable
     */
    public function fetchByUserId($identifier)
    {
        /** @var \Kreait\Firebase\Auth\UserRecord $user */
        $user = $this->firebase->getConnection()->getAuth()->getUser($identifier);

        if (!is_null($user))
        {
            $this->setCredential($user);
            $this->role = $this->role ?: $this->getValidRole();
            if (!$this->isRoleValid($this->role))
            {
                return null;
            }
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

        return ['token' => $token->__toString(), 'expiration' => $exp - (10 * 60)];
    }

    /**
     * @return array $token
     */
    public function getToken()
    {
        if ($this->needUpdateToken())
        {
            $this->save(App::make('session.store'), $this);
        }

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
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param string $role
     * @return bool
     */
    public function isRoleValid($role)
    {
        $valid = false;
        try
        {
            /** @var string|null $role */
            $valid = $this->firebase->getConnection()->getDatabase()->getReference(DataMapper::userRole($this->uid, $role)['users_groups'])->getValue() ?: false;
            $valid = $role == 'tester' ? false : $role;
        }
        catch (\Exception $e)
        {
            $this->isRoleValid($role);
        }

        return $valid;
    }

    /**
     * @param \Kreait\Firebase\Auth\UserRecord $user
     */
    private function setCredential($user)
    {
        $this->email = $user->email;
        $this->uid   = $user->uid;
    }

    /**
     * @param \Illuminate\Contracts\Session\Session $session
     * @param \Kreait\Firebase\Auth\UserRecord|FirebaseUser $user
     * @return void
     */
    public function save(Session $session, $user)
    {
        $user->needUpdateToken();
        $session->put($user->sessionName($user->uid), [
            'uid' => $user->uid,
            'token' => $user->token,
            'role' => $user->role = ($user->role ?: $user->getValidRole()),
        ]);
    }

    /**
     * @param \Illuminate\Contracts\Session\Session $session
     * @param \Kreait\Firebase\Auth\UserRecord|FirebaseUser $user
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
        $role = null;
        try
        {
            /** @var string[] $roles */
            $roles = $this->firebase->getConnection()->getDatabase()->getReference(DataMapper::userRole($this->uid)['users'])->getChildKeys();
            $role  = count($roles) > 0 ? $roles[0] : $role;
        }
        catch (\Exception $e)
        {
        }

        return $role;
    }

    /**
     * @param $name
     * @return string
     */
    private function sessionName($name)
    {
        return 'firebase_' . $name . '_credential_' . sha1(static::class);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return "FirebaseUser" . \GuzzleHttp\json_encode(['uid' => $this->uid, 'email' => $this->email, 'token' => $this->token, 'role' => $this->role]);
    }

    public function setRole($role)
    {
        $this->role = $role;
    }

    public function getUid(): string
    {
        return $this->uid;
    }
}

?>
