<?php

namespace Nanicas\LegacyLaravelToolkit\Traits;

use Nanicas\LegacyLaravelToolkit\Helpers\Helper as InternalHelper;

/**
 * @note: ARM = Attributes Resource Model
 * Was named as ARMxxHelperAlias to avoid conflicts with other classes;
 * At other points, uniqid is being used, but for some reason, the Class not found error is displayed here.
 */
class_alias(InternalHelper::readTemplateConfig()['helpers']['global'], __NAMESPACE__ . '\ARMxxHelperAlias');

trait AttributesResourceModel
{
    public static function getPrimaryKey()
    {
        return static::PRIMARY_KEY;
    }

    public function getPrimaryValue()
    {
        return $this->{self::getPrimaryKey()};
    }

    public function getId()
    {
        return $this->getPrimaryValue();
    }

    public function getCreatedAt()
    {
        return $this->{$this->getCreatedAtColumn()};
    }

    public function getUpdatedAt()
    {
        return $this->{$this->getUpdatedAtColumn()};
    }

    public function getFromDatetimeAttribute(string $attr, $format = null)
    {
        $datetime = $this->getAttribute($attr);

        return ($datetime) ? ARMxxHelperAlias::formatDatetime($datetime, $format) : null;
    }
}
