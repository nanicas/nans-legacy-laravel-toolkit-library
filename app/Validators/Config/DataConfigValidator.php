<?php

namespace Zevitagem\LaravelToolkit\Validators\Config;

use Zevitagem\LaravelToolkit\Validators\AbstractValidator;

class DataConfigValidator extends AbstractValidator
{
    protected $messages = [
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
        return;
    }

    public function update()
    {
        $this->form();
    }
}
