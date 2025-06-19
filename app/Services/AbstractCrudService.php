<?php

namespace Nanicas\LegacyLaravelToolkit\Services;

use Nanicas\LegacyLaravelToolkit\Services\AbstractService;
use Nanicas\LegacyLaravelToolkit\Helpers\Helper as InternalHelper;

class_alias(InternalHelper::readTemplateConfig()['helpers']['global'], __NAMESPACE__ . '\ACSxxHelperAlias');

abstract class AbstractCrudService extends AbstractService
{
    public function getDataToIndex(array $data = [])
    {
        $rows = collect();

        return compact('rows');
    }

    public function getDataToList(array $data)
    {
        $this->handle($data, 'list');

        $rows = $this->getRepository()->getAllQuery($data);

        return compact('rows');
    }

    public function getDataToCreate()
    {
        return $this->getComplementDataToForm(__FUNCTION__);
    }

    public function getDataToEdit(int $id)
    {
        $loggedUser = ACSxxHelperAlias::getUser();

        $data = compact('id');
        $data['row'] = $this->getRepository()->getById($id);
        $data['logged_user'] = $loggedUser;

        parent::handle($data, 'edit');
        parent::validate($data, 'edit');

        $formData = $this->getComplementDataToForm(__FUNCTION__);
        $formData = array_merge($data, $formData);

        if (method_exists($this, 'postComplementDataOnEdit')) {
            $this->postComplementDataOnEdit($formData);
        }

        return $formData;
    }

    public function getById(int $id)
    {
        return $this->getRepository()->getById($id);
    }

    public function getAllActive()
    {
        return $this->getRepository()->getAllActive();
    }

    public function getAll()
    {
        return $this->getRepository()->getAll();
    }

    public function destroy(int $id)
    {
        $loggedUser = ACSxxHelperAlias::getUser();

        $data = compact('id');
        $data['row'] = $this->getRepository()->getById($id);
        $data['logged_user'] = $loggedUser;

        if (method_exists($this, 'previousComplementDataOnDestroy')) {
            $this->previousComplementDataOnDestroy($data);
        }

        parent::handle($data, 'destroy');
        parent::validate($data, 'destroy');
        parent::validate($data, 'beforeDestroyPersistence');

        $status = $this->getRepository()->delete($data['row']);

        return [
            'status' => $status,
            'row' => $data['row']
        ];
    }

    public function getDataToShow(int $id)
    {
        $loggedUser = ACSxxHelperAlias::getUser();

        $data = compact('id');
        $data['row'] = $this->getRepository()->getById($id);
        $data['logged_user'] = $loggedUser;

        parent::handle($data, 'show');
        parent::validate($data, 'show');

        $formData = $this->getComplementDataToForm(__FUNCTION__);
        $formData = array_merge($data, $formData);

        if (method_exists($this, 'postComplementDataOnShow')) {
            $this->postComplementDataOnShow($formData);
        }

        return $formData;
    }

    public function filter(array $data)
    {
        parent::handle($data, 'filter');
        parent::validate($data, 'filter');

        return $this->getRepository()->filter($data);
    }

    protected function getComplementDataToForm(string $method): array
    {
        return [];
    }
}
