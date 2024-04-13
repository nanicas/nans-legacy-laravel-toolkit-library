<?php

namespace Nanicas\LegacyLaravelToolkit\Models;

use Nanicas\LegacyLaravelToolkit\Models\AbstractModel;

class SiteEntity extends AbstractModel
{
    const PRIMARY_KEY = 'id';
    
    protected $casts = [
        'data' => 'json',
    ];
}