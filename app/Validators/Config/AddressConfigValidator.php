<?php

namespace Nanicas\LegacyLaravelToolkit\Validators\Config;

use Nanicas\LegacyLaravelToolkit\Validators\AbstractValidator;

class AddressConfigValidator extends AbstractValidator
{
    protected $messages = [
        'id_invalid' => 'O ID é um campo obrigatório e deve ser válido',
        'slug_invalid' => 'O slug é um campo obrigatório e deve ser válido',
        'latitude_empty' => 'A latitude é um campo obrigatório',
        'longitude_empty' => 'A longitude é um campo obrigatório',
        'country_empty' => 'O país é um campo obrigatório',
        'state_empty' => 'O estado é um campo obrigatório',
        'city_empty' => 'A cidade é um campo obrigatório',
        'street_empty' => 'A rua é um campo obrigatório',
        'number_empty' => 'O número é um campo obrigatório e inteiro',
        'zipcode_empty' => 'O CEP é um campo obrigatório e inteiro',
        'open_at_empty' => 'O horário de abertura é um campo obrigatório',
        'closed_at_empty' => 'O horário de fechamento é um campo obrigatório',
    ];

    public function store()
    {
        $this->form();
        $data = $this->getData();

        if (empty(intval($data['slug_config_id']))) {
            $this->addError("slug_invalid");
        }
    }

    public function form()
    {
        $data = $this->getData();

        $intKeys = [
            'number', 'zipcode',
        ];
        $stringNoNullKeys = [
            'latitude', 'longitude', 'country', 'state', 'city', 'street', 'open_at', 'close_at'
        ];
        
        foreach ($intKeys as $key) {
            if (empty($data[$key]) || !is_int($data[$key])) {
                $this->addError("{$key}_empty");
            }
        }

        foreach ($stringNoNullKeys as $key) {
            if (empty($data[$key])) {
                $this->addError("{$key}_empty");
            }
        }
    }

    public function update()
    {
        $this->form();
        $data = $this->getData();
        
        if (intval($data['id']) <= 0) {
            $this->addError("id_invalid");
        }
    }
}
