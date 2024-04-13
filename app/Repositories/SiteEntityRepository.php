<?php

namespace Nanicas\LegacyLaravelToolkit\Repositories;

use Nanicas\LegacyLaravelToolkit\Repositories\AbstractCrudRepository;
use Nanicas\LegacyLaravelToolkit\Models\SiteEntity;

class SiteEntityRepository extends AbstractCrudRepository
{
    public function __construct()
    {
        parent::setModel(new SiteEntity());
    }

    public function getEntitiesBySlug(int $slug)
    {
        $model = parent::getModel();
        $table = 'entities';

        return SiteEntity::selectRaw("
                    entities.id,
                    entities.name,
                    entities.data,
                    components.name AS 'component_name',
                    components.key AS 'component_key',
                    components.template AS 'component_template',
                    categories.name AS 'category_name',
                    categories.key AS 'category_key'")
                        ->from($table)
                        ->join('components', 'components.id', '=', "$table.component_id")
                        ->join('categories', 'components.category_id', '=', "categories.id")
                        ->where([
                            'entities.active' => 1,
                            'categories.active' => 1,
                            'components.active' => 1,
                            'entities.slug' => $slug,
                            'components.slug' => $slug,
                            'categories.slug' => $slug
                        ])
                        ->whereNull('entities.deleted_at')
                        ->whereNull('components.deleted_at')
                        ->whereNull('categories.deleted_at')
                        ->get();
    }
}
