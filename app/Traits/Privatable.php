<?php

namespace Nanicas\LegacyLaravelToolkit\Traits;

trait Privatable
{
    public function isPrivate()
    {
        return $this->private;
    }

    public function isPublic()
    {
        return !$this->private;
    }

    public function scopePrivate($query)
    {
        return $query->where('private', true);
    }

    public function scopePublic($query)
    {
        return $query->where('private', false);
    }
}
