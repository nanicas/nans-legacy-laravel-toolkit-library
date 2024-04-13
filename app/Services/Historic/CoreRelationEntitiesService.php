<?php

namespace Nanicas\LegacyLaravelToolkit\Services\Historic;

use Nanicas\LegacyLaravelToolkit\Repositories\HistoricEntitiesRepository;
use Nanicas\LegacyLaravelToolkit\Traits\AvailabilityWithDependencie;
use Nanicas\LegacyLaravelToolkit\Models\Historic;

class CoreRelationEntitiesService
{
    use AvailabilityWithDependencie;
    
    private array $map;
    private array $repositories;

    public function __construct(
        HistoricEntitiesRepository $historicEntitiesRepository,
    )
    {
        $this->setDependencie('historic_entities_repository', $historicEntitiesRepository);
    }

    public function setRepositories(array $repositories)
    {
        $this->repositories = $repositories;
    }
    
    public function setMap(array $map)
    {
        $this->map = $map;
    }
    
    private function getMap()
    {
        return $this->map;
    }
    
    private function getRepository(string $entity)
    {
        return $this->repositories[$entity] ?? null;
    }

    public function getDataToCreate(int $slug)
    {
        $map = $this->getMap();
        $cache = [];

        foreach ($map as $entity => $info) {
            $data = $this->getRepository($entity)->getAllBySlug($slug);
            $cache[$info['key']] = [$data, []];
        }

        return $this->createStructToListEntities($cache);
    }

    private function createStructToListEntities(array $data)
    {
        $originalMap = $this->getMap();
        $map = [];
        
        foreach ($data as $entity => $row) {

            list($rows, $selecteds) = $row;
            $map[$entity] = array_merge($originalMap[$entity], ['rows' => $rows, 'selecteds' => $selecteds]);
        }

        return $map;
    }

    public function getDataToShow(Historic $historic)
    {
        $savedEntities = $historic->entities;
        
        $slug = $historic->getSlug();
        $map = $this->getMap();

        $selectedGrouped = [];
        $cache = [];
        
        foreach ($savedEntities as $savedEntity) {
            $selectedGrouped[$savedEntity->getEntityType()][] = $savedEntity->getEntityId();
        }

        foreach ($map as $entity => $info) {
            $data = $this->getRepository($entity)->getAllBySlug($slug);
            $cache[$info['key']] = [$data, $selectedGrouped[$info['key']] ?? []];
        }

        return $this->createStructToListEntities($cache);
    }

    public function syncOnStore(Historic $historic, array $data)
    {
        $struct = $this->createStructToSync($data);

        foreach (array_filter($struct) as $row) {
            $this->getDependencie('historic_entities_repository')->attach($historic, $row);
        }
    }

    public function syncOnUpdate(Historic $historic, array $data)
    {
        $struct = $this->createStructToSync($data);
        $slug = $historic->getSlug();

        $diff = $this->diffBetweenEntitiesToSyncAndStored($historic, $slug, $struct);

        foreach ($diff as $action => $entities) {
            switch ($action)
            {
                case 'to_update':
                    break;
                case 'to_delete':
                    foreach ($entities as $entityKey => $primaryKeys) {
                        $this->getDependencie('historic_entities_repository')->deleteByCondition([
                            'entity_type' => $entityKey,
                            'id' => $primaryKeys
                        ]);
                    }
                    break;
                case 'to_create':
                    foreach ($entities as $entityKey => $dataToAttach) {
                        $this->getDependencie('historic_entities_repository')->attach($historic, $dataToAttach);
                    }
                    break;
            }
        }
    }

    private function diffBetweenEntitiesToSyncAndStored($historic, int $slug, array $dataToSync)
    {
        $cache = [
            'to_delete' => [],
            'to_create' => [],
                //'to_update' => [],
        ];

        foreach ($dataToSync as $key => $row) {

            $savedEntities = $historic->entitiesByType($key);

            if ($savedEntities->count() == 0) {
                $cache['to_create'][$key] = $dataToSync[$key];
                continue;
            }

            foreach ($savedEntities as $savedEntity) {
                $savedEntityId = $savedEntity->getEntityId();
                $savedPrimaryId = $savedEntity->getId();

                if (array_key_exists($savedEntityId, $dataToSync[$key])) {
                    unset($dataToSync[$key][$savedEntityId]);
                    continue; //is updating
                }

                if (!isset($cache['to_delete'][$key])) {
                    $cache['to_delete'][$key] = [];
                }

                $cache['to_delete'][$key][] = $savedPrimaryId;
            }

            if (!empty($dataToSync[$key])) {
                $cache['to_create'][$key] = $dataToSync[$key];
                unset($dataToSync[$key]);
            }
        }

        return $cache;
    }

    private function createStructToSync(array $data)
    {
        $struct = [];
        $map = $this->getMap();

        foreach ($map as $config) {

            if (empty($data[$config['key']])) {
                $struct[$config['key']] = [];
                continue;
            }

            $keys = array_values($data[$config['key']]);
            $values = array_map(function () use ($config) {
                return ['entity_type' => $config['key']];
            }, $data[$config['key']]);

            $struct[$config['key']] = array_combine($keys, $values);
        }

        return $struct;
    }

}
