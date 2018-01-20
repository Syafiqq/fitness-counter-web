<?php
/**
 * This <fitness-counter.com> project created by :
 * Name         : syafiq
 * Date / Time  : 13 January 2018, 12:29 PM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */

namespace App\Firebase;


use App\Contracts\Auth\FirebaseAuthenticatable;
use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Session\Session;
use Kreait\Firebase\Auth\User;
use Symfony\Component\HttpFoundation\Request;

class FirebaseGuard extends SessionGuard
{
    /**
     * @var FirebaseAuthenticatable|User
     */
    protected $user;

    /**
     * @var UserProvider
     */
    protected $provider;

    /**
     * Create a new authentication guard.
     *
     * @param string $name
     * @param \Illuminate\Contracts\Auth\UserProvider $provider
     * @param \Illuminate\Contracts\Session\Session $session
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function __construct($name,
                                UserProvider $provider,
                                Session $session,
                                Request $request = null)
    {
        parent::__construct($name, $provider, $session, $request);
    }

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {
        if ($this->loggedOut)
        {
            return null;
        }

        // If we've already retrieved the user for the current request we can just
        // return it back immediately. We do not want to fetch the user data on
        // every call to this method because that would be tremendously slow.
        if (!is_null($this->user))
        {
            return $this->user;
        }

        $id = $this->session->get($this->getName());

        // First we will try to load the user using the identifier in the session if
        // one exists. Otherwise we will check for a "remember me" cookie in this
        // request, and if one exists, attempt to retrieve the user using that.
        if (!is_null($id))
        {
            if ($this->user = $this->provider->retrieveById($id))
            {
                $this->user->load($this->session, $this->user);
                $this->fireAuthenticatedEvent($this->user);
            }
        }

        // If the user is null, but we decrypt a "recaller" cookie we can attempt to
        // pull the user data on that cookie which serves as a remember cookie on
        // the application. Once we have a user we can return it to the caller.
        $recaller = $this->recaller();

        if (is_null($this->user) && !is_null($recaller))
        {
            $this->user = $this->userFromRecaller($recaller);

            if ($this->user)
            {
                $this->updateSessionData($this->user->getAuthIdentifier(), $this->user);

                $this->fireLoginEvent($this->user, true);
            }
        }

        return $this->user;
    }

    /**
     * Log a user into the application.
     *
     * @param  \App\Contracts\Auth\FirebaseAuthenticatable $user
     * @param  bool $remember
     * @return void
     */
    public function login(Authenticatable $user, $remember = false)
    {
        $this->updateSessionData($user->getAuthIdentifier(), $user);

        // If the user should be permanently "remembered" by the application we will
        // queue a permanent cookie that contains the encrypted copy of the user
        // identifier. We will then decrypt this later to retrieve the users.
        if ($remember)
        {
            $this->ensureRememberTokenIsSet($user);

            $this->queueRecallerCookie($user);
        }

        // If we have an event dispatcher instance set we will fire an event so that
        // any listeners will hook into the authentication events and run actions
        // based on the login and logout events fired from the guard instances.
        $this->fireLoginEvent($user, $remember);

        $this->setUser($user);
    }

    /**
     * @param $id
     * @param FirebaseAuthenticatable|User $user
     */
    protected function updateSessionData($id, $user)
    {
        $user->save($this->session, $user);
        parent::updateSession($id);
    }
}

?>
