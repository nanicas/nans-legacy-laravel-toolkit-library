<?php

namespace Nanicas\LegacyLaravelToolkit\Traits;

use Nanicas\LegacyLaravelToolkit\Helpers\Helper as InternalHelper;

class_alias(InternalHelper::readTemplateConfig()['helpers']['global'], uniqid() . __NAMESPACE__ . '\HelperAlias');

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

        return ($datetime) ? HelperAlias::formatDatetime($datetime, $format) : null;
    }
}
