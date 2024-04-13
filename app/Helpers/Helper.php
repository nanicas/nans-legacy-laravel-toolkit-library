<?php

namespace Zevitagem\LaravelToolkit\Helpers;

use Psr\Http\Message\ResponseInterface;
use Zevitagem\LegoAuth\Helpers\Helper as HelperVendor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class Helper
{
    const VIEW_PREFIX = 'presentation_template::';
    
    public static function getViewPrefix()
    {
        return self::VIEW_PREFIX;
    }
    
    public static function getRootFolderNameOfAssets()
    {
        return 'presentation_template';
    }

    public static function getAppId()
    {
        return config('app.app_id');
    }

    public static function generateCleanSnakeText(string $text = '')
    {
        return str_replace(' ', '_', strtolower($text));
    }

    public static function extractJsonFromRequester(ResponseInterface $requester)
    {
        $content = $requester->getBody()->getContents();
        $json = json_decode($content, true);

        return $json ?? self::createDefaultJsonToResponse(false,
                        ['message' => $content]);
    }

    public static function createDefaultJsonToResponse(
            bool $status, $content = null
    )
    {
        return ['response' => $content, 'status' => $status];
    }
    
    public static function hydrateUnique(string $class, array $data)
    {
        return $class::hydrate([$data])->first();
    }

    public static function getToken()
    {
        return HelperVendor::getToken();
    }

    public static function getSlug()
    {
        return HelperVendor::getSlug();
    }

    public static function getCustomer()
    {
        return HelperVendor::getCustomer();
    }

    public static function getContract()
    {
        return HelperVendor::getContract();
    }

    public static function getUserConfig()
    {
        return HelperVendor::getUserConfig();
    }

    public static function getSessionData()
    {
        return HelperVendor::getSessionData();
    }

    public static function getUserId()
    {
        return Auth::id();
    }
    
    public static function getUser()
    {
        return Auth::user();
    }

    public static function getUserName(bool $full = true)
    {
        $name = Auth::user()->name;
        if ($full) {
            return $name;
        }

        return (strlen($name) > 25) ? substr($name, 0, 25) . '...' : $name;
    }

    public static function readConfig()
    {
        return HelperVendor::readConfig();
    }
    
    public static function readTemplateConfig()
    {
        return config('template');
    }

    public static function isMaster()
    {
        return HelperVendor::isMaster();
    }

    public static function isAdmin()
    {
        return HelperVendor::isAdmin();
    }
    
    public static function loadMessage(string $message, bool $status = true, bool $packaged = true)
    {
        return self::view('components.messages.' . (($status) ? 'success' : 'danger'), compact('message'), $packaged)->render();
    }

    public static function notAllowedResponse(Request $request,  bool $packaged = true)
    {
        $isAjax = ($request->ajax() || $request->wantsJson());
        $viewName = ($isAjax) ? 'pages.allowance.not_allowed_content' : 'pages.allowance.not_allowed';

        $view_prefix = self::getViewPrefix();
        $packaged_assets_prefix = self::getRootFolderNameOfAssets();
        
        $view = self::view($viewName, compact('view_prefix', 'packaged_assets_prefix'), $packaged)->render();

        if (!$isAjax) {
            return response($view);
        }

        return response()->json(self::createDefaultJsonToResponse(false, [
            'message' => $view,
            'wrapped' => false
        ]));
    }
    
    public static function view(string $path, array $data = [], bool $packaged = false)
    {
        $path = (!$packaged) ? $path : self::getViewPrefix() . $path;
        return view($path, $data);
    }

    public static function groupArrayByKeys(array $data, array $keys)
    {
        $firstKey = $keys[0];
        $len = count($data[$firstKey]);

        $result = [];
        foreach ($keys as $key) {
            for ($i = 0; $i < $len; $i++) {
                $result[$i][$key] = $data[$key][$i];
            }
        }

        return $result;
    }

}
