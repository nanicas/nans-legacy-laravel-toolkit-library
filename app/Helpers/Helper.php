<?php

namespace Nanicas\LegacyLaravelToolkit\Helpers;

use Datetime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Psr\Http\Message\ResponseInterface;
use Nanicas\LegacyLaravelToolkit\Staters\AppStater;

class Helper
{
    const VIEW_PREFIX = 'legacy_laravel_toolkit_view_library::';

    public static function sharePrefixesBeforeView()
    {
        View::share('view_prefix', self::getViewPrefix());
        View::share('packaged_view_prefix', self::getViewPrefixPackaged());
        View::share('assets_prefix', self::getRootFolderNameOfAssets());
        View::share('packaged_assets_prefix', self::getRootFolderNameOfAssetsPackaged());
    }

    public static function getViewPrefixPackaged()
    {
        return self::VIEW_PREFIX;
    }

    public static function getViewPrefix()
    {
        return '';
    }

    public static function isPackagedView()
    {
        return (AppStater::getItem('packaged') === true);
    }

    public static function getRootFolderNameOfAssetsPackaged()
    {
        return 'vendor/legacy_laravel_toolkit_view_library';
    }

    public static function getRootFolderNameOfAssets()
    {
        return '';
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

        return $json ?? self::createDefaultJsonToResponse(
            false,
            ['message' => $content]
        );
    }

    public static function createDefaultJsonToResponse(
        bool $status,
        $content = null
    ) {
        return ['response' => $content, 'status' => $status];
    }

    public static function hydrateUnique(string $class, array $data)
    {
        return $class::hydrate([$data])->first();
    }

    public static function getUserId()
    {
        return Auth::id();
    }

    public static function getUser()
    {
        $guard = self::getAuthenticatedGuard();
        return auth()->guard($guard)->user();
    }

    public static function getUserName(bool $full = true)
    {
        $name = self::getUser()->name;
        if ($full) {
            return $name;
        }

        return (strlen($name) > 25) ? substr($name, 0, 25) . '...' : $name;
    }

    public static function readTemplateConfig()
    {
        return config('nanicas_legacy_laravel_toolkit');
    }

    public static function loadMessage(string $message, bool $status = true, bool $packaged = true)
    {
        return self::view('components.messages.' . (($status) ? 'success' : 'danger'), compact('message'), $packaged)->render();
    }

    public static function notAllowedResponse(Request $request, bool $packaged = true)
    {
        $isAjax = ($request->ajax() || $request->wantsJson());
        $viewName = ($isAjax) ? 'pages.allowance.not_allowed_content' : 'pages.allowance.not_allowed';

        if (!$isAjax) {
            $view_prefix = self::getViewPrefix();
            $packaged_assets_prefix = self::getRootFolderNameOfAssets();
            return self::view($viewName, compact('view_prefix', 'packaged_assets_prefix'), $packaged);
        }

        return response()->json(self::createDefaultJsonToResponse(false, [
            'message' => 'Operação não permitada',
            'wrapped' => false
        ]))->setStatusCode(403);
    }

    public static function view(
        string $path,
        array $data = [],
        bool $packaged = false,
        bool $sharePrefixes = true
    ) {

        $prefix = self::getViewPrefix();
        $path = (!$packaged) ? $path : $prefix . $path;

        if ($sharePrefixes) {
            self::sharePrefixesBeforeView();
        }

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

    public static function defaultExecutationToReplyJson(\Closure $callable)
    {
        try {
            $data = $callable();
            $status = true;
            $response = $data;
        } catch (\Throwable $ex) {
            $message = $ex->getMessage();
            $status = false;
            $response = ['message' => $message];
        }

        header('Content-Type: application/json');
        echo json_encode(self::createDefaultJsonToResponse($status, $response));
    }

    public static function cleanRoute(string $route)
    {
        return preg_replace("/[^a-zA-Z]/", "", $route);
    }

    public static function formatDatetime(Datetime $datetime, $format = null)
    {
        if (is_null($format)) {
            $format = config('nanicas_legacy_laravel_toolkit.datetime_format');
        }

        return $datetime->format($format);
    }

    public static function isAnyGuardAuthenticated(): bool
    {
        foreach (array_keys(config('auth.guards')) as $guard) {
            if (Auth::guard($guard)->check()) {
                return true;
            }
        }

        return false;
    }

    public static function getAuthenticatedGuard(): ?string
    {
        foreach (array_keys(config('auth.guards')) as $guard) {
            if (Auth::guard($guard)->check()) {
                return $guard;
            }
        }

        return null;
    }
}
