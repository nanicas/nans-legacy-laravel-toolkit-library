<?php

namespace Nanicas\LegacyLaravelToolkit\Validators\Config;

use Nanicas\LegacyLaravelToolkit\Validators\AbstractValidator;

class ComponentConfigValidator extends AbstractValidator
{
    protected $messages = [
        'name_empty' => 'O nome do componente não foi preenchido',
        'key_empty' => 'A chave do componente não foi preenchida',
        'category_invalid' => 'A categoria selecionada é inválida',
        'slug_empty' => 'O Slug é um campo obrigatório',
    ];

    public function store()
    {
        $this->form();
        
        $data = $this->getData();
        
        if (empty($data['slug'])) {
            $this->addError('slug_empty');
        }
    }

    public function form()
    {
        $data = $this->getData();

        if (empty($data['name'])) {
            $this->addError('name_empty');
        }
        if ($data['category'] <= 0) {
            $this->addError('category_invalid');
        }
        if (empty($data['key'])) {
            $this->addError('key_empty');
        }
    }

    public function update()
    {
        $this->form();
    }
}