<?php

namespace Zevitagem\LaravelToolkit\Models;

use Zevitagem\LaravelToolkit\Models\AbstractModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Zevitagem\LaravelToolkit\Models\HistoricEntities;

class Historic extends AbstractModel
{
    const PRIMARY_KEY = 'id';
    
    use SoftDeletes;
    protected $fillable = [
        'description',
        'happened_at',
        'observation',
        'slug',
    ];
    protected $casts    = [
        'active' => 'boolean',
        'happened_at' => 'date'
    ];

    public function getDescription()
    {
        return $this->description;
    }

    public function getObservation()
    {
        return $this->observation;
    }

    public function getHappenedAt()
    {
        return $this->happened_at;
    }

    public function getHappenedAtFormatted()
    {
        $happened = $this->getHappenedAt();
        return (!empty($happened)) ? $happened->format('Y-m-d') : '';
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function entities()
    {
        return $this->hasMany(HistoricEntities::class, 'historic_id', 'id');
    }
    
    public function entitiesByType(string $type)
    {
        return $this->entities()->where('entity_type', $type)->get();
    }
}
