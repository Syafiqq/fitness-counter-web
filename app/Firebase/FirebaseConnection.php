<?php
/**
 * This <fitness-counter.com> project created by :
 * Name         : syafiq
 * Date / Time  : 16 January 2018, 9:02 PM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */

namespace App\Firebase;


use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class FirebaseConnection
{
    /**
     * @var \Kreait\Firebase
     */
    private $connection;
    private $serviceAccount;
    private $apiKey;

    /**
     * FirebaseConnection constructor.
     */
    public function __construct()
    {
        $this->serviceAccount = ServiceAccount::fromJsonFile(resource_path() . env('FIREBASE_SERVICE', '/assets/sdk/fitness-counter-6a479f0be813.json'));
        $this->connection     = (new Factory)
            ->withServiceAccount($this->serviceAccount)
            ->create();
    }

    /**
     * @return \Kreait\Firebase
     */
    public function getConnection(): Firebase
    {
        return $this->connection;
    }
}

?>
