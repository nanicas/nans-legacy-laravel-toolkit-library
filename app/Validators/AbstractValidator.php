<?php

namespace Nanicas\LegacyLaravelToolkit\Validators;

use Nanicas\LegacyLaravelToolkit\Helpers\Helper as InternalHelper;
use Nanicas\LegacyLaravelToolkit\Traits\AvailabilityWithRequest;

class_alias(InternalHelper::readTemplateConfig()['helpers']['global'],  __NAMESPACE__ . '\AVxxHelperAlias');

abstract class AbstractValidator
{
    use AvailabilityWithRequest;

    private array $data;
    private string $method;
    private bool $withHTML = true;
    protected array $errors = [];
    protected array $messages = [
        'id_invalid' => 'O ID é inválido',
        'logged_user_type_is_invalid_for_the_operation' => 'O tipo do usuário logado é inválido para a operação',
        'user_type_is_invalid_for_the_operation' => 'O tipo do usuário é inválido para a operação',
        'row_not_found' => 'O registro não foi encontrado',
        'user_must_exists' => 'O usuário deve existir',
        'logged_user_does_not_have_authorization_due_to_the_registration_being_outside_of_their_permitted_group' => 'O usuário logado não possui autorização devido o registro estar fora do seu grupo permitido',
        'register_must_exists' => 'O registro em questão deve existir',
        'logged_user_must_exists' => 'O usuário logado deve existir',
        'only_owner_can_manipulate' => 'Somente o proprietário pode manipular o registro',
        'only_admin_can' => 'Somente usuários administradores podem manipular o registro',
        'authenticated_users_cannot_perform_this_operation' => 'Usuarios autenticados não podem realizar essa operação',
        'not_allowed_on_same_logged_scope' => 'Não permitido no mesmo escopo logado'
    ];

    public function setData(array $data)
    {
        $this->data = $data;
    }

    public function setWithHTML(bool $value)
    {
        $this->withHTML = $value;
    }

    public function isWithHTML()
    {
        return $this->withHTML;
    }

    public function addError(string $key, array $data = [])
    {
        $this->errors[] = vsprintf($this->messages[$key], $data);
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function hasErrors()
    {
        return (!empty($this->getErrors()));
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $method
     * @return null|bool
     */
    public function run(string $method)
    {
        if (!method_exists($this, $method)) {
            return null;
        }

        $this->setMethod($method);
        $this->{$method}();

        return (empty($this->errors));
    }

    public function translate()
    {
        if (!$this->isWithHTML()) {
            return json_encode($this->errors);
        }

        $packaged = false;

        return AVxxHelperAlias::view('components.validator-messages', ['messages' => $this->errors], $packaged)->render();
    }

    private function setMethod(string $method)
    {
        $this->method = $method;
    }
}
