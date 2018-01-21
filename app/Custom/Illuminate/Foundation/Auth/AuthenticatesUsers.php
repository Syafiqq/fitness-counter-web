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

trait AuthenticatesUsers
{
    use OriginAuthenticatesUsers
    {
        showLoginForm as public getLogin;
    }
}

?>
