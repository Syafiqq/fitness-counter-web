<?php
/**
 * This <fitness-counter.com> project created by :
 * Name         : syafiq
 * Date / Time  : 13 January 2018, 12:29 PM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */

namespace App\Firebase;


use Illuminate\Auth\Events\Attempting;
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Session\Session;
use Psy\Exception\RuntimeException;
use Symfony\Component\HttpFoundation\Request;

class FirebaseGuard implements StatefulGuard
{
    use GuardHelpers;
    /**
     * The name of the Guard. Typically "session".
     *
     * Corresponds to guard name in authentication configuration.
     *
     * @var string
     */
    protected $name;

    /**
     * The user we last attempted to retrieve.
     *
     * @var \Illuminate\Contracts\Auth\Authenticatable
     */
    protected $lastAttempted;

    /**
     * The session used by the guard.
     *
     * @var \Illuminate\Contracts\Session\Session
     */
    protected $session;

    /**
     * The request instance.
     *
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * The event dispatcher instance.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $events;

    /**
     * Indicates if the logout method has been called.
     *
     * @var bool
     */
    protected $loggedOut = false;

    /**
     * Create a new authentication guard.
     *
     * @param  string $name
     * @param  \Illuminate\Contracts\Auth\UserProvider $provider
     * @param  \Illuminate\Contracts\Session\Session $session
     * @param  \Symfony\Component\HttpFoundation\Request $request
     * @return void
     */
    public function __construct($name,
                                UserProvider $provider,
                                Session $session,
                                Request $request = null)
    {
        $this->name     = $name;
        $this->session  = $session;
        $this->request  = $request;
        $this->provider = $provider;
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
            return $this->getUser();
        }

        $id = $this->session->get($this->getName());

        // First we will try to load the user using the identifier in the session if
        // one exists. Otherwise we will check for a "remember me" cookie in this
        // request, and if one exists, attempt to retrieve the user using that.
        if (!is_null($id))
        {
            if ($this->user = $this->provider->retrieveById($id))
            {
                $this->fireAuthenticatedEvent($this->user);
            }
        }

        return $this->getUser();
    }

    /**
     * Get the ID for the currently authenticated user.
     *
     * @return string|null
     */
    public function id()
    {
        if ($this->loggedOut)
        {
            return null;
        }

        return $this->user()
            ? $this->user()->getAuthIdentifier()
            : $this->session->get($this->getName());
    }


    /**
     * Log a user into the application without sessions or cookies.
     *
     * @param  array $credentials
     * @return void
     */
    public function once(array $credentials = [])
    {
        throw new RuntimeException('Not Implemented');
    }

    /**
     * Log the given user ID into the application without sessions or cookies.
     *
     * @param  mixed $id
     * @return void
     */
    public function onceUsingId($id)
    {
        throw new RuntimeException('Not Implemented');
    }

    /**
     * Validate a user's credentials.
     *
     * @param  array $credentials
     * @return bool
     */
    public function validate(array $credentials = [])
    {
        $this->lastAttempted = $user = $this->provider->retrieveByCredentials($credentials);

        return $this->hasValidCredentials($user, $credentials);
    }

    /**
     * Attempt to authenticate a user using the given credentials.
     *
     * @param  array $credentials
     * @param  bool $remember
     * @return bool
     */
    public function attempt(array $credentials = [], $remember = false)
    {
        $this->fireAttemptEvent($credentials, $remember);

        $this->lastAttempted = $user = $this->provider->retrieveByCredentials($credentials);

        // If an implementation of UserInterface was returned, we'll ask the provider
        // to validate the user against the given credentials, and if they are in
        // fact valid we'll log the users into the application and return true.
        if ($this->hasValidCredentials($user, $credentials))
        {
            $this->login($user, $remember);

            return true;
        }

        // If the authentication attempt fails we will fire an event so that the user
        // may be notified of any suspicious attempts to access their account from
        // an unrecognized user. A developer may listen to this event as needed.
        $this->fireFailedEvent($user, $credentials);

        return false;
    }

    /**
     * Log a user into the application.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @param  bool $remember
     * @return void
     */
    public function login(Authenticatable $user, $remember = false)
    {
        $this->updateSession($user->getAuthIdentifier());

        // If we have an event dispatcher instance set we will fire an event so that
        // any listeners will hook into the authentication events and run actions
        // based on the login and logout events fired from the guard instances.
        $this->fireLoginEvent($user, $remember);

        $this->setUser($user);
    }

    /**
     * Update the session with the given ID.
     *
     * @param  string $id
     * @return void
     */
    protected function updateSession($id)
    {
        $this->session->put($this->getName(), $id);

        $this->session->migrate(true);
    }

    /**
     * Log the given user ID into the application.
     *
     * @param  mixed $id
     * @param  bool $remember
     * @return \Illuminate\Contracts\Auth\Authenticatable|false
     */
    public function loginUsingId($id, $remember = false)
    {
        if (!is_null($user = $this->provider->retrieveById($id)))
        {
            $this->login($user, $remember);

            return $user;
        }

        return false;
    }

    /**
     * Determine if the user was authenticated via "remember me" cookie.
     *
     * @return void
     */
    public function viaRemember()
    {
        throw new RuntimeException('Not Implemented');
    }

    /**
     * Log the user out of the application.
     *
     * @return void
     */
    public function logout()
    {
        $user = $this->user();

        // If we have an event dispatcher instance, we can fire off the logout event
        // so any further processing can be done. This allows the developer to be
        // listening for anytime a user signs out of this application manually.
        $this->clearUserDataFromStorage();

        if (isset($this->events))
        {
            $this->events->dispatch(new Logout($user));
        }

        // Once we have fired the logout event we will clear the users out of memory
        // so they are no longer available as the user is no longer considered as
        // being signed into this application and should not be available here.
        $this->user = null;

        $this->loggedOut = true;
    }


    /**
     * Remove the user data from the session and cookies.
     *
     * @return void
     */
    protected function clearUserDataFromStorage()
    {
        $this->session->remove($this->getName());
    }

    /**
     * Get a unique identifier for the auth session value.
     *
     * @return string
     */
    public function getName()
    {
        return 'login_' . $this->name . '_' . sha1(static::class);
    }

    /**
     * @return Authenticatable
     */
    public function getUser()
    {
        //should regenerate token ID
        return $this->user;
    }

    /**
     * Register an authentication attempt event listener.
     *
     * @param  mixed $callback
     * @return void
     */
    public function attempting($callback)
    {
        if (isset($this->events))
        {
            $this->events->listen(Attempting::class, $callback);
        }
    }

    /**
     * Fire the attempt event with the arguments.
     *
     * @param  array $credentials
     * @param  bool $remember
     * @return void
     */
    protected function fireAttemptEvent(array $credentials, $remember = false)
    {
        if (isset($this->events))
        {
            $this->events->dispatch(new Attempting(
                $credentials, $remember
            ));
        }
    }

    /**
     * Fire the login event if the dispatcher is set.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @param  bool $remember
     * @return void
     */
    protected function fireLoginEvent($user, $remember = false)
    {
        if (isset($this->events))
        {
            $this->events->dispatch(new Login($user, $remember));
        }
    }

    /**
     * Fire the authenticated event if the dispatcher is set.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @return void
     */
    protected function fireAuthenticatedEvent($user)
    {
        if (isset($this->events))
        {
            $this->events->dispatch(new Authenticated($user));
        }
    }

    /**
     * Fire the failed authentication attempt event with the given arguments.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null $user
     * @param  array $credentials
     * @return void
     */
    protected function fireFailedEvent($user, array $credentials)
    {
        if (isset($this->events))
        {
            $this->events->dispatch(new Failed($user, $credentials));
        }
    }

    /**
     * Determine if the user matches the credentials.
     *
     * @param  mixed $user
     * @param  array $credentials
     * @return bool
     */
    protected function hasValidCredentials($user, $credentials)
    {
        return !is_null($user) && $this->provider->validateCredentials($user, $credentials);
    }
}

?>
