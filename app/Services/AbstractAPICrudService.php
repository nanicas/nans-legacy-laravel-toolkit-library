<?php

namespace Nanicas\LegacyLaravelToolkit\Services;

use Nanicas\LegacyLaravelToolkit\Services\AbstractService;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractAPICrudService extends AbstractService
{
    public function filter(array $data)
    {
        parent::handle($data, 'filter');

        return $this->getRepository()->filter($data);
    }

    public function delete(Model $row)
    {
        return parent::getRepository()->delete($row);
    }

    public function show(Model $row)
    {
        return $row;
    }

    public function validate(array $data, string $method)
    {
        if (empty($validator = $this->getValidator())) {
            return;
        }

        $validator->setData($data);

        $request = $this->getConfigIndex('request');
        if ($request) {
            $validator->setRequest($request);
        }

        return $validator->run($method);
    }
}
