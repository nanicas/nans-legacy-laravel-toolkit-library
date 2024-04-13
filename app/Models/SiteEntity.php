<?php

namespace Zevitagem\LaravelToolkit\Models;

use Zevitagem\LaravelToolkit\Models\AbstractModel;

class SiteEntity extends AbstractModel
{
    const PRIMARY_KEY = 'id';
    
    protected $casts = [
        'data' => 'json',
    ];
}