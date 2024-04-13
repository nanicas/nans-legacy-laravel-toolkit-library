<?php

namespace Zevitagem\LaravelToolkit\Repositories;

use Zevitagem\LaravelToolkit\Repositories\AbstractCrudRepository;
use Zevitagem\LaravelToolkit\Models\HistoricEntities;
use Zevitagem\LaravelToolkit\Models\Historic;

class HistoricEntitiesRepository extends AbstractCrudRepository
{
    public function __construct()
    {
        parent::setModel(new HistoricEntities());
    }

    public function attach(Historic $historic, array $data)
    {
        $historicId = $historic->getId();
        $toInsert = [];

        foreach ($data as $entityId => $complement) {

            $toInsert[] = array_merge([
                'historic_id' => $historicId,
                'entity_id' => $entityId
                    ], $complement);
        }

        if (!empty($toInsert)) {
            return parent::insert($toInsert);
        }
    }
}
