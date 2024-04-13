<?php

namespace Nanicas\LegacyLaravelToolkit\Repositories;

use Nanicas\LegacyLaravelToolkit\Repositories\AbstractCrudRepository;
use Nanicas\LegacyLaravelToolkit\Models\HistoricEntities;
use Nanicas\LegacyLaravelToolkit\Models\Historic;

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
