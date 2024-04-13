<?php

namespace Zevitagem\LaravelToolkit\Validators;

use Zevitagem\LaravelToolkit\Validators\AbstractValidator;
use Zevitagem\LaravelToolkit\Traits\Validators\CrudValidator;

class HistoricValidator extends AbstractValidator
{
    use CrudValidator;
    
    protected $messages = [
        'description_empty' => 'Pelo menos uma descrição deve ser preenchida',
    ];

    public function store()
    {
        $this->form();
    }

    public function form()
    {
        $data = $this->getData();

        if (empty($data['description'])) {
            $this->addError('description_empty');
        }
    }

    public function update()
    {
        $this->form();
    }
}
