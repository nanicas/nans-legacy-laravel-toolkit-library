<?php

namespace Zevitagem\LaravelToolkit\Services\Config;

use Zevitagem\LaravelToolkit\Services\AbstractCrudService;
use Zevitagem\LaravelToolkit\Repositories\Config\DataConfigRepository;
use Zevitagem\LaravelToolkit\Validators\Config\DataConfigValidator;
use Zevitagem\LaravelToolkit\Handlers\Config\DataConfigHandler;
use Zevitagem\LaravelToolkit\Services\Config\AddressConfigService;
use Zevitagem\LaravelToolkit\Helpers\Helper;
use Zevitagem\LaravelToolkit\Exceptions\InvalidArgumentOnCrudException as InvalidArgumentException;

class DataConfigService extends AbstractCrudService
{
    public function __construct(
        DataConfigRepository $repository,
        DataConfigValidator $validator,
        DataConfigHandler $handler,
        AddressConfigService $addressConfigService
    )
    {
        parent::setRepository($repository);
        parent::setValidator($validator);
        parent::setHandler($handler);

        $this->setDependencie('address_config_service', $addressConfigService);
    }

    public function getDataToCreate()
    {
        return [];
    }

    public function getDataToShow(int $id)
    {
        $row = $this->getBySlug($id);

        return compact('row');
    }

    public function store(array $data)
    {
        $createdConfig = $this->getRepository()->store([
            'facebook' => $data['facebook'],
            'instagram' => $data['instagram'],
            'twitter' => $data['twitter'],
            'youtube' => $data['youtube'],
            'slug' => $data['slug']
        ]);

        if (!$createdConfig) {
            throw new \InvalidArgumentException('Ocorreu uma falha no momento de cadastrar as configurações');
        }

        $this->getDependencie('address_config_service')->storeBulk(Helper::groupArrayByKeys($data, [
            'phone', 'cellphone', 'country', 'state', 'city', 'street', 'number', 'zipcode',
            'open_at', 'close_at', 'email', 'latitude', 'longitude', 'observation'
        ]), $createdConfig);

        return $createdConfig;
    }

    public function update(array $data)
    {
        $config = $this->getByIdAndSlug($data['id']);

        if (empty($config)) {
            throw new InvalidArgumentException('Não foi possível encontrar uma configuração válida');
        }
        if ($config->getSlug() != Helper::getSlug()) {
            throw new InvalidArgumentException('A configuração enviada não pode ser atualizada com esses dados');
        }

        $config->fill([
            'facebook' => $data['facebook'],
            'instagram' => $data['instagram'],
            'twitter' => $data['twitter'],
            'youtube' => $data['youtube'],
        ]);

        $updateStatus = $this->getRepository()->update($config);
        
        if (!$updateStatus) {
            throw new InvalidArgumentException('Ocorreu uma falha no momento de atualizar as configurações');
        }
        
        $this->getDependencie('address_config_service')->updateBulk(Helper::groupArrayByKeys($data, [
            'phone', 'cellphone', 'country', 'state', 'city', 'street', 'number', 'zipcode',
            'open_at', 'close_at', 'email', 'latitude', 'longitude', 'observation', 'unique_id'
        ]), $config);
        
        return $updateStatus;
    }

}
