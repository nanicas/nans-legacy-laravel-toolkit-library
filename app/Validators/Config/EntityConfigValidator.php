<?php

namespace Zevitagem\LaravelToolkit\Validators\Config;

use Zevitagem\LaravelToolkit\Validators\AbstractValidator;
use Zevitagem\LaravelToolkit\Repositories\Config\ComponentConfigRepository;
use Zevitagem\LaravelToolkit\Helpers\Helper;
use Zevitagem\LaravelToolkit\Staters\ModelsStater;
use Zevitagem\LaravelToolkit\Traits\AvailabilityWithDependencie;
use Zevitagem\LaravelToolkit\Traits\Validators\CrudValidator;

class EntityConfigValidator extends AbstractValidator
{
    use AvailabilityWithDependencie;
    use CrudValidator {
        CrudValidator::__construct as crudConstruct;
    }

    public function __construct(
        ComponentConfigRepository $componentConfigRepository
    )
    {
        $this->crudConstruct();
        $this->setDependencie('component_config_repository', $componentConfigRepository);
    }

    protected $messages = [
        'name_empty' => 'O nome do componente não foi preenchido',
        'component_invalid' => 'O componente selecionado é inválido',
        'component_not_found' => 'O componente vinculado não foi encontrado',
        'title_empty' => 'O campo "título" é um requisito do componente e deve ser preenchido',
        'content_empty' => 'O campo "conteúdo" é um requisito do componente e deve ser preenchido',
        'extra_empty' => 'O campo "extra" é um requisito do componente e deve ser preenchido',
        'image_empty' => 'A imagem é um requisito do componente e deve ser selecionada',
        'slug_empty' => 'O Slug é um campo obrigatório',
    ];

    public function store()
    {
        $this->form();

        $data = $this->getData();

        if (empty($data['slug'])) {
            $this->addError('slug_empty');
        }

        $component = ModelsStater::getItem('component_by_id_and_slug');
        if ($component->hasImage() && !$data['image_obj']) {
            $this->addError('image_empty');
        }
    }

    public function form()
    {
        $data = $this->getData();

        if ($data['component'] <= 0) {
            return $this->addError('component_invalid');
        }

        $componentConfigRepository = $this->getDependencie('component_config_repository');
        $component = $componentConfigRepository->getByIdAndSlug($data['component_id'], Helper::getSlug());

        if (empty($component)) {
            $this->addError('component_not_found');
        }

        ModelsStater::setData([
            'component_by_id_and_slug' => $component
        ]);

        if (empty($data['name'])) {
            $this->addError('name_empty');
        }

        if ($component->hasTitle() && empty($data['title'])) {
            $this->addError('title_empty');
        }
        if ($component->hasContent() && empty($data['content'])) {
            $this->addError('content_empty');
        }
        if ($component->hasExtra() && empty($data['extra'])) {
            $this->addError('extra_empty');
        }
    }

    public function update()
    {
        $this->form();

        $data = $this->getData();

        $component = ModelsStater::getItem('component_by_id_and_slug');
        if ($component->hasImage() && !$data['image_obj']) {
            if (empty($data['selected-image'])) {
                $this->addError('image_empty');
            }
        }
    }

}
