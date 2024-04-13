<?php

namespace Nanicas\LegacyLaravelToolkit\Models\Config;

use Nanicas\LegacyLaravelToolkit\Models\AbstractModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nanicas\LegacyLaravelToolkit\Models\Config\CategoryConfig;

class ComponentConfig extends AbstractModel
{

    use SoftDeletes;
    const PRIMARY_KEY = 'id';

    protected $table = 'components';
    protected $fillable = [
        'name',
        'category_id',
        'active',
        'key',
        'has_title',
        'has_content',
        'has_extra',
        'has_image',
        'template',
        'slug'
    ];
    protected $casts = [
        'active' => 'boolean',
        'has_title' => 'boolean',
        'has_content' => 'boolean',
        'has_extra' => 'boolean',
        'has_image' => 'boolean',
    ];

    public function getName()
    {
        return $this->name;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function getCategory()
    {
        return $this->category_id;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function isActive()
    {
        return ($this->active == 1);
    }

    public function hasTitle()
    {
        return ($this->has_title == 1);
    }

    public function hasContent()
    {
        return ($this->has_content == 1);
    }

    public function hasImage()
    {
        return ($this->has_image == 1);
    }

    public function hasExtra()
    {
        return ($this->has_extra == 1);
    }

    public function category()
    {
        return $this->belongsTo(CategoryConfig::class);
    }

}
