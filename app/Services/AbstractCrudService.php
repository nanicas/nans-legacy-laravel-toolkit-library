<?php

namespace Nanicas\LegacyLaravelToolkit\Services;

use Nanicas\LegacyLaravelToolkit\Services\AbstractService;
use Nanicas\LegacyLaravelToolkit\Helpers\Helper as InternalHelper;

class_alias(InternalHelper::readTemplateConfig()['helpers']['global'], __NAMESPACE__ . '\HelperAlias');

abstract class AbstractCrudService extends AbstractService
{
    public function getIndexData(array $data = [])
    {
        $rows = collect();

        return compact('rows');
    }

    /**
     * It was necessary to use "listx" instead of "list" because list is a 
     * prohibited word in PHP when using the trait.
     * @return array
     */
    public function getDataToList(array $data)
    {
        $this->handle($data, 'list');

        $rows = $this->getRepository()->getAllQuery($data);

        return compact('rows');
    }

    public function getDataToCreate()
    {
        return $this->getDataToForm(__FUNCTION__);
    }

    public function getDataToEdit(int $id)
    {
        $loggedUser = HelperAlias::getUser();

        $data = compact('id');
        $data['row'] = $this->getRepository()->getById($id);
        $data['logged_user'] = $loggedUser;

        parent::handle($data, 'edit');
        parent::validate($data, 'edit');

        $dataForm = $this->getDataToForm(__FUNCTION__);
        $dataForm = array_merge($data, $dataForm);

        if (method_exists($this, 'posteriorComplementDataOnEdit')) {
            $this->posteriorComplementDataOnEdit($dataForm);
        }

        return $dataForm;
    }

    public function getDataToForm()
    {
        return [];
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
        $loggedUser = HelperAlias::getUser();

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
        $loggedUser = HelperAlias::getUser();

        $data = compact('id');
        $data['row'] = $this->getRepository()->getById($id);
        $data['logged_user'] = $loggedUser;

        parent::handle($data, 'show');
        parent::validate($data, 'show');

        $dataForm = $this->getDataToForm(__FUNCTION__);
        $dataForm = array_merge($data, $dataForm);

        if (method_exists($this, 'posteriorComplementDataOnShow')) {
            $this->posteriorComplementDataOnShow($dataForm);
        }

        return $dataForm;
    }

    public function filter(array $data)
    {
        parent::handle($data, 'filter');

        return $this->getRepository()->filter($data);
    }
}
