<?php

namespace Zevitagem\LaravelToolkit\Services;

use Zevitagem\LaravelToolkit\Services\AbstractService;
use Zevitagem\LaravelToolkit\Repositories\ConfigUserRepository;
use Zevitagem\LaravelToolkit\Helpers\Helper;
use Zevitagem\LaravelToolkit\Validators\ConfigUserValidator;
use Zevitagem\LaravelToolkit\Handlers\ConfigUserHandler;
use Zevitagem\LaravelToolkit\Exceptions\CrudException;
use Zevitagem\LaravelToolkit\Repositories\PainelRepository;

class ConfigUserService extends AbstractService
{
    public function __construct(
        ConfigUserRepository $repository,
        ConfigUserValidator $validator,
        ConfigUserHandler $handler,
        PainelRepository $painel
    )
    {
        parent::setRepository($repository);
        parent::setValidator($validator);
        parent::setHandler($handler);
        
        $this->setDependencie('painel_repository', $painel);
    }

    public function getDataToShow(int $userId)
    {
        if (!Helper::isMaster() && $userId != Helper::getUserId()) {
            throw new CrudException('Você não possui privilégios para visualizar as informações do registro selecionado.');
        }
        
        $row = $this->getConfigByUser($userId);
        $rules = $this->getDependencie('painel_repository')->getRulesByApplication(
            Helper::getAppId()
        );

        $isMaster = Helper::isMaster();

        return compact('row', 'isMaster', 'rules');
    }

    public function store(array $data)
    {
        $storeData = [
            'name' => $data['name'],
            'admin' => $data['admin'],
            'rule_id' => $data['rule_id'],
            'slug' => Helper::getSlug(),
            'user_id' => Helper::getUserId(),
        ];

        if (!Helper::isMaster() && $storeData['admin'] != 0) {
            $storeData['admin'] = 0;
        }
        
        return $this->getRepository()->store($storeData);
    }

    public function update(array $data)
    {
        $config = $this->getRepository()->getById($data['id']);

        if (empty($config)) {
            throw new CrudException('Não foi possível encontrar uma configuração válida para edição');
        }

        $isSlugFromLoggedUser = ($config->getSlug() == Helper::getSlug());
        $isUserFromLoggedUser = ($config->getUser() == Helper::getUserId());

        if (!$isSlugFromLoggedUser) {
            throw new CrudException(
                    sprintf('A configuração encontrada não pertence ao escopo do usuário logado. user_slug:[%s], row_slug:[%s]',
                        Helper::getSlug(), $config->getSlug())
            );
        }

        if (!Helper::isMaster() && !$isUserFromLoggedUser) {
            throw new CrudException(
                    sprintf('A configuração encontrada não pertence ao usuário e não pode processada. user_id[%s], row_user_id:[%s]',
                        Helper::getUserId(), $config->getUser())
            );
        }

        $updateData = [
            'name' => $data['name'],
            'rule_id' => $data['rule_id'],
        ];

        if (Helper::isMaster()) {
            $updateData['admin'] = $data['admin'];
        }

        $config->fill($updateData);

        return $this->getRepository()->update($config);
    }

    private function getConfigByUser(int $userId)
    {
        return $this->getRepository()->getByUserAndSlug(
            $userId, Helper::getSlug()
        );
    }
}
