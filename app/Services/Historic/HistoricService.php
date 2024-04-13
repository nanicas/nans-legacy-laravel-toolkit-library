<?php

namespace Zevitagem\LaravelToolkit\Services\Historic;

use Zevitagem\LaravelToolkit\Services\AbstractCrudService;
use Zevitagem\LaravelToolkit\Repositories\HistoricRepository;
use Zevitagem\LegoAuth\Helpers\Helper;
use Zevitagem\LaravelToolkit\Validators\HistoricValidator;
use Zevitagem\LaravelToolkit\Handlers\HistoricHandler;
use Zevitagem\LaravelToolkit\Services\Historic\ContainerRelationEntitiesService;

class HistoricService extends AbstractCrudService
{
    public function __construct(
        HistoricRepository $repository,
        HistoricValidator $validator,
        HistoricHandler $handler,
        ContainerRelationEntitiesService $containerRelationEntitiesService
    )
    {
        parent::setRepository($repository);
        parent::setValidator($validator);
        parent::setHandler($handler);

        $this->setDependencie('container_relation_entities_service', $containerRelationEntitiesService);
    }

    public function getDataToCreate()
    {
        $entities = $this->getDependencie('container_relation_entities_service')->getDataToCreate(Helper::getSlug());

        return compact('entities');
    }

    public function getDataToShow(int $id)
    {
        $row = $this->getByIdAndSlug($id);

        $entities = $this->getDependencie('container_relation_entities_service')->getDataToShow($row);

        return compact('row', 'entities');
    }

    public function update(array $data)
    {
        $repository = $this->getRepository();
        $historic = $this->getByIdAndSlug($data['id']);

        if (empty($historic)) {
            throw new \InvalidArgumentException('Não foi possível encontrar o histórico');
        }

        $historic->fill([
            'description' => $data['description'],
            'happened_at' => $data['happened_at'],
            'observation' => $data['observation'],
        ]);

        $updated = $repository->update($historic);

        if ($updated) {
            $this->getDependencie('container_relation_entities_service')->syncOnUpdate($historic, $data);
        }
        
        return $updated;
    }

    public function getIndexData(array $data = [])
    {
        $rows = $this->getHistorics();

        return compact('rows');
    }

    public function store(array $data)
    {
        $historic = $this->getRepository()->store([
            'description' => $data['description'],
            'observation' => $data['observation'],
            'slug' => $data['slug'],
            'happened_at' => $data['happened_at'],
        ]);

        $this->getDependencie('container_relation_entities_service')->syncOnStore($historic, $data);

        return $historic;
    }

    public function getHistorics()
    {
        return $this->getRepository()->getAllBySlug(Helper::getSlug());
    }
}
