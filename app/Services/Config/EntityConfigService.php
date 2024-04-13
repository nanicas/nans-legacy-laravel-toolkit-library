<?php

namespace Nanicas\LegacyLaravelToolkit\Services\Config;

use Nanicas\LegacyLaravelToolkit\Services\AbstractCrudService;
use Nanicas\LegacyLaravelToolkit\Repositories\Config\EntityConfigRepository;
use Zevitagem\LegoAuth\Helpers\Helper;
use Nanicas\LegacyLaravelToolkit\Repositories\Config\ComponentConfigRepository;
use Nanicas\LegacyLaravelToolkit\Validators\Config\EntityConfigValidator;
use Nanicas\LegacyLaravelToolkit\Handlers\Config\EntityConfigHandler;
use Nanicas\LegacyLaravelToolkit\Helpers\EntityConfigImageHelper;

class EntityConfigService extends AbstractCrudService
{

    public function __construct(
        EntityConfigRepository $repository,
        ComponentConfigRepository $componentConfigRepository,
        EntityConfigValidator $validator,
        EntityConfigHandler $handler,
        EntityConfigImageHelper $imageHelper
    )
    {
        parent::setRepository($repository);
        parent::setValidator($validator);
        parent::setHandler($handler);

        $this->setDependencie('component_repository', $componentConfigRepository);
        $this->setDependencie('image_helper', $imageHelper);
    }

    public function getDataToCreate()
    {
        $components = $this->getComponents();

        return compact('components');
    }
    
    public function getDataToDynamicForm(int $componentId, $id = 0)
    {
        $component = $this->dependencies['component_repository']->getByIdAndSlug($componentId, Helper::getSlug());

        if (empty($component)) {
            throw new \InvalidArgumentException('Não foi possível encontrar informações sobre o componente');
        }
        
        $row = ($id > 0) ? $this->getByIdAndSlug($id) : null;
        
        return compact('component', 'row');
    }

    public function getDataToShow(int $id)
    {
        $row = $this->getByIdAndSlug($id);
        $components = $this->getComponents();

        return compact('row', 'components');
    }

    public function update(array $data)
    {
        $repository = $this->getRepository();
        $entity = $this->getByIdAndSlug($data['id']);

        if (empty($entity)) {
            throw new \InvalidArgumentException('Não foi possível encontrar a entidade');
        }
        
        parent::handle($data, 'beforeUpdate');
        
        $this->dependencies['image_helper']->update($data, $entity);

        $entity->fill([
            'name' => $data['name'],
            'active' => $data['active'],
            'data' => $data['data'],
            'component_id' => $data['component_id'],
        ]);

        return $repository->update($entity);
    }

    public function store(array $data)
    {
        parent::handle($data, 'beforeStore');
        
        $this->dependencies['image_helper']->store($data);
        
        $entity = $this->getRepository()->store([
            'name' => $data['name'],
            'active' => $data['active'],
            'data' => $data['data'],
            'component_id' => $data['component_id'],
            'slug' => $data['slug']
        ]);

        return $entity;
    }

    public function getComponents()
    {
        return $this->dependencies['component_repository']->getAllBySlug(Helper::getSlug());
    }

}
