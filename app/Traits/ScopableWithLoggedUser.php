<?php

namespace Nanicas\LegacyLaravelToolkit\Traits;

use Illuminate\Database\Eloquent\Builder;
use Nanicas\LegacyLaravelToolkit\Helpers\Helper as InternalHelper;

class_alias(InternalHelper::readTemplateConfig()['helpers']['global'], uniqid() . __NAMESPACE__ . '\SWLUTxxHelperAlias');

trait ScopableWithLoggedUser
{
    public function getUserIdColumn()
    {
        return 'user_id';
    }

    public function scopeFromLoggedUser(Builder $query)
    {
        return $query->where($this->getTable() . '.' . $this->getUserIdColumn(), SWLUTxxHelperAlias::getUser()->id);
    }

    public function getUserIdAttribute($value)
    {
        return (int) $value;
    }

    public function getUserId()
    {
        $column = $this->getUserIdColumn();

        return $this->{$column};
    }
}
