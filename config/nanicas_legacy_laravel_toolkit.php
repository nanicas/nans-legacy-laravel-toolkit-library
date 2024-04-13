<?php

return [
    'datetime_format' => 'd/m/Y H:i:s',
    'helpers' => [
        'global' => App\Helpers\Helper::class,
    ],
    'controllers' => [
        'dashboard' => App\Http\Controllers\DashboardController::class,
        'crud' => App\Http\Controllers\Pages\CrudController::class,
        'base' => App\Http\Controllers\Controller::class,
    ]
];
