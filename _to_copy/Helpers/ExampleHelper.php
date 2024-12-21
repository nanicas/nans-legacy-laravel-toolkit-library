<?php

namespace App\Helpers;

use Nanicas\LegacyLaravelToolkit\Helpers\Helper as HelperVendor;

class ExampleHelper extends HelperVendor
{
    public static function isAdmin()
    {
        return true;
    }

    public static function isTest()
    {
        return true;
    }

    public static function isMaster()
    {
        return true;
    }

    public static function isWorker()
    {
        return true;
    }
}
