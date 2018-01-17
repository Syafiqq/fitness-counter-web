<?php
/**
 * This <fitness-counter.com> project created by :
 * Name         : syafiq
 * Date / Time  : 13 January 2018, 12:29 PM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */

namespace App\Firebase;


use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Session\Session;
use Symfony\Component\HttpFoundation\Request;

class FirebaseGuard extends SessionGuard
{
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
     * Return the currently cached user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function getUser()
    {
        //should regenerate token ID
        return parent::getUser();
    }
}

?>
