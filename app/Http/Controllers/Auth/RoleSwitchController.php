<?php
/**
 * This <fitness-counter.com> project created by :
 * Name         : syafiq
 * Date / Time  : 24 January 2018, 6:49 AM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Model\FirebaseUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class RoleSwitchController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param Request $request
     * @param $role
     * @return \Illuminate\Http\Response
     */
    public function getSwitch(Request $request, $role)
    {
        /** @var FirebaseUser $user */
        $user = $request->user();
        if ($user->getRole() !== $role)
        {
            if ($user->isRoleValid($role))
            {
                $user->setRole($role);
                App::call(get_class($user) . "@save", [$user]);

                return redirect()->route("{$user->getRole()}.dashboard.home")->with('cbk_msg', ['notify' => ["Perubahan role berhasil"]]);
            }
            else
            {
                return redirect()->back()->with('cbk_msg', ['notify' => ["Role tidak Valid"]]);
            }
        }
        else
        {
            return redirect()->back()->with('cbk_msg', ['notify' => ["Anda sudah pada role ini"]]);
        }
    }
}

?>
