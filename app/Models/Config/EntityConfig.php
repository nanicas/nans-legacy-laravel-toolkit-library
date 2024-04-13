<?php

namespace Zevitagem\LaravelToolkit\Models\Config;

use Zevitagem\LaravelToolkit\Models\AbstractModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Zevitagem\LaravelToolkit\Models\Config\ComponentConfig;

class EntityConfig extends AbstractModel
{

    use SoftDeletes;
    const PRIMARY_KEY = 'id';

    protected $table = 'entities';
    protected $fillable = [
        'name',
        'component_id',
        'active',
        'data',
        'slug'
    ];
    protected $casts = [
        'active' => 'boolean',
        'data' => 'json',
    ];

    public function getName()
    {
        return $this->name;
    }
    
    public function getData()
    {
        return $this->data;
    }
    
    public function getComponent()
    {
        return $this->component_id;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function isActive()
    {
        return ($this->active == 1);
    }

    public function component()
    {
        return $this->belongsTo(ComponentConfig::class);
    }

}
