<?php

return [
    'datetime_format' => 'd/m/Y H:i:s',
    'helpers' => [
        'global' => App\Helpers\ExampleHelper::class,
    ],
    'controllers' => [
        'base' => App\Http\Controllers\ExampleController::class,
        'dashboard' => App\Http\Controllers\ExampleDashboardController::class,
        'crud' => App\Http\Controllers\ExampleCrudController::class,
    ],
    'frontend' => [
        'header' => [
            'search' => [
                'has' => true,
                'placeholder' => 'Buscar...',
                'route' => 'dashboard.action',
                'route_params' => [
                    'action' => 'search'
                ],
            ],
        ]
    ]
];
