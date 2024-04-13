<?php

namespace Nanicas\LegacyLaravelToolkit\Validators;

use Nanicas\LegacyLaravelToolkit\Validators\AbstractValidator;

class ConfigUserValidator extends AbstractValidator
{
    protected $messages = [
        'name_empty' => 'O apelido não foi preenchido',
        'rule_empty' => 'Um perfil válido deve ser selecionado',
    ];

    public function store()
    {
        $this->form();
    }

    public function form()
    {
        $data = $this->getData();

        if (empty($data['name'])) {
            $this->addError('name_empty');
        }

        if (empty(intval($data['rule_id']))) {
            $this->addError('rule_empty');
        }
    }

    public function update()
    {
        $this->form();
    }
}
