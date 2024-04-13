<?php

namespace Nanicas\LegacyLaravelToolkit\Services\Config;

use Nanicas\LegacyLaravelToolkit\Services\AbstractCrudService;
use Nanicas\LegacyLaravelToolkit\Repositories\Config\ComponentConfigRepository;
use Zevitagem\LegoAuth\Helpers\Helper;
use Nanicas\LegacyLaravelToolkit\Repositories\Config\CategoryConfigRepository;
use Nanicas\LegacyLaravelToolkit\Validators\Config\ComponentConfigValidator;
use Nanicas\LegacyLaravelToolkit\Handlers\Config\ComponentConfigHandler;

class ComponentConfigService extends AbstractCrudService
{

    public function __construct(
        ComponentConfigRepository $repository,
        CategoryConfigRepository $categoryConfigRepository,
        ComponentConfigValidator $validator,
        ComponentConfigHandler $handler
    )
    {
        parent::setRepository($repository);
        parent::setValidator($validator);
        parent::setHandler($handler);

        $this->setDependencie('category_repository', $categoryConfigRepository);
    }

    public function getDataToCreate()
    {
        $categories = $this->getCategories();

        return compact('categories');
    }

    public function getDataToShow(int $id)
    {
        $row = $this->getByIdAndSlug($id);
        $categories = $this->getCategories();

        return compact('row', 'categories');
    }

    public function update(array $data)
    {
        $repository = $this->getRepository();
        $component = $this->getByIdAndSlug($data['id']);

        if (empty($component)) {
            throw new \InvalidArgumentException('Não foi possível encontrar o componente');
        }

        $component->fill([
            'name' => $data['name'],
            'template' => $data['template'],
            'active' => $data['active'],
            'key' => $data['key'],
            'category_id' => $data['category_id'],
            'has_title' => $data['has_title'],
            'has_content' => $data['has_content'],
            'has_extra' => $data['has_extra'],
            'has_image' => $data['has_image'],
        ]);

        return $repository->update($component);
    }

    public function store(array $data)
    {
        $component = $this->getRepository()->store([
            'name' => $data['name'],
            'template' => $data['template'],
            'active' => $data['active'],
            'key' => $data['key'],
            'category_id' => $data['category_id'],
            'has_title' => $data['has_title'],
            'has_content' => $data['has_content'],
            'has_extra' => $data['has_extra'],
            'has_image' => $data['has_image'],
            'slug' => $data['slug']
        ]);

        return $component;
    }

    public function getCategories()
    {
        return $this->dependencies['category_repository']->getAllBySlug(Helper::getSlug());
    }

}
