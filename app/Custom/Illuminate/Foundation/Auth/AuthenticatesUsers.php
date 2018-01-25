<?php
/**
 * This <fitness-counter.com> project created by :
 * Name         : syafiq
 * Date / Time  : 21 January 2018, 6:49 PM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */

namespace App\Custom\Illuminate\Foundation\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers as OriginAuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Kreait\Firebase\Exception\Auth\EmailNotFound;
use Kreait\Firebase\Exception\Auth\InvalidPassword;
use Kreait\Firebase\Exception\Auth\UserDisabled;

trait AuthenticatesUsers
{
    use OriginAuthenticatesUsers;

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response|void
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);
        $exception = null;

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request))
        {
            $this->fireLockoutEvent($request);

            /** @noinspection PhpUnhandledExceptionInspection */
            /** @noinspection PhpVoidFunctionResultUsedInspection */
            /** @noinspection PhpInconsistentReturnPointsInspection */
            return $this->sendLockoutResponse($request);
        }

        try
        {
            if ($this->attemptLogin($request))
            {
                /** @noinspection PhpUnhandledExceptionInspection */
                /** @noinspection PhpInconsistentReturnPointsInspection */
                return $this->sendLoginResponse($request);
            }
        }
        catch (\RuntimeException $e)
        {
            $exception = $e;
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        /** @noinspection PhpUnhandledExceptionInspection */
        /** @noinspection PhpInconsistentReturnPointsInspection */
        return $this->sendFailedLoginResponse($request, $exception);
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request $request
     * @param \RuntimeException $e
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws ValidationException
     */
    protected function sendFailedLoginResponse(Request $request, \RuntimeException $e = null)
    {
        switch (get_class($e))
        {
            case EmailNotFound::class:
                {
                    $error = [
                        $this->username() => [trans('auth.email_not_found')],
                    ];
                }
            break;
            case InvalidPassword::class:
                {
                    $error = [
                        'password' => [trans('auth.invalid_password')],
                    ];
                }
            break;
            case UserDisabled::class:
                {
                    $error = [
                        $this->username() => [trans('auth.user_disabled')],
                    ];
                }
            break;
            default :
                {
                    $error = [
                        $this->username() => [trans('auth.failed')],
                    ];
                }
        }
        throw ValidationException::withMessages($error);
    }
}

?>
