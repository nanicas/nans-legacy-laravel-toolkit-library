<?php

namespace Zevitagem\LaravelToolkit\Models\Config;

use Zevitagem\LaravelToolkit\Models\AbstractModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryConfig extends AbstractModel
{
    use SoftDeletes;
    
    const PRIMARY_KEY = 'id';
    
    protected $table = 'categories';
    protected $fillable = [
        'name',
        'key',
        'active',
        'key',
        'slug'
    ];
    protected $casts = [
        'active' => 'boolean'
    ];

    public function getName()
    {
        return $this->name;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function isActive()
    {
        return ($this->active == 1);
    }
}
