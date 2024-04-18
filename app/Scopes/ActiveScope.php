<?php

namespace Nanicas\LegacyLaravelToolkit\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait ActiveScope
{
    public function scopeActive(Builder $query): void
    {
        $query->where('active', 1);
    }

    public function scopeInactive(Builder $query): void
    {
        $query->where('active', 0);
    }

    public function isActive()
    {
        return ($this->active == 1);
    }

    public function isInactive()
    {
        return ($this->active == 0);
    }
}
