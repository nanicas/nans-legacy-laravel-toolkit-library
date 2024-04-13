<?php

namespace Zevitagem\LaravelToolkit\Services\Historic;

use Zevitagem\LaravelToolkit\Repositories\Config\CategoryConfigRepository;
use Zevitagem\LaravelToolkit\Traits\AvailabilityWithDependencie;
use Zevitagem\LaravelToolkit\Models\Historic;
use Zevitagem\LaravelToolkit\Services\Historic\CoreRelationEntitiesService;

class ContainerRelationEntitiesService
{
    use AvailabilityWithDependencie;

    public function __construct(
        CategoryConfigRepository $categoryConfigRepository,
        CoreRelationEntitiesService $coreRelationEntitiesService
    )
    {
        $coreRelationEntitiesService->setMap($this->getMap());
        $coreRelationEntitiesService->setRepositories([
            'categories' => $categoryConfigRepository,
        ]);

        $this->setDependencie('core_relation_entities_service', $coreRelationEntitiesService);
    }

    public function getMap()
    {
        $categories = [
            'key' => 'categories', 'description' => 'Categorias'
        ];

        return compact('categories');
    }

    public function getDataToCreate(int $slug)
    {
        return $this->getDependencie('core_relation_entities_service')->getDataToCreate($slug);
    }

    public function getDataToShow(Historic $historic)
    {
        return $this->getDependencie('core_relation_entities_service')->getDataToShow($historic);
    }

    public function syncOnStore(Historic $historic, array $data)
    {
        return $this->getDependencie('core_relation_entities_service')->syncOnStore($historic, $data);
    }

    public function syncOnUpdate(Historic $historic, array $data)
    {
        return $this->getDependencie('core_relation_entities_service')->syncOnUpdate($historic, $data);
    }
}
