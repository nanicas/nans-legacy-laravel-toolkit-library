<?php

namespace Nanicas\LegacyLaravelToolkit\Models;

use Illuminate\Database\Eloquent\Model;
use Nanicas\LegacyLaravelToolkit\Traits\AttributesResourceModel;

abstract class AbstractModel extends Model
{
    const PRIMARY_KEY = 'id';

    use AttributesResourceModel;
}
