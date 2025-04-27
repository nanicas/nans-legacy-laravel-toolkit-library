<?php

namespace Nanicas\LegacyLaravelToolkit\Traits;

use Nanicas\LegacyLaravelToolkit\Helpers\Helper as InternalHelper;

class_alias(InternalHelper::readTemplateConfig()['helpers']['global'], uniqid() . __NAMESPACE__ . '\ATMTxxHelperAlias');

trait AttributesTimezoneModel
{
    public function getCreatedAtTimezonedAttribute()
    {
        $timezone = ATMTxxHelperAlias::getUserTimezone();

        return $this->created_at->copy()->setTimezone($timezone);
    }

    public function getUpdatedAtTimezonedAttribute()
    {
        $timezone = ATMTxxHelperAlias::getUserTimezone();

        return $this->updated_at->copy()->setTimezone($timezone);
    }

    public function getTimezonedAttribute(string $attribute)
    {
        $timezone = ATMTxxHelperAlias::getUserTimezone();

        return $this->$attribute->copy()->setTimezone($timezone);
    }
}
