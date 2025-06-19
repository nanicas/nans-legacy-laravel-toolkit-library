<?php

namespace Nanicas\LegacyLaravelToolkit\Services;

use Nanicas\LegacyLaravelToolkit\Services\AbstractService;
use Nanicas\LegacyLaravelToolkit\Exceptions\ValidatorException;
use Nanicas\LegacyLaravelToolkit\Models\AbstractModel;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractAPICrudService extends AbstractService
{
    public function filter(array $data)
    {
        return $this->getRepository()->filter($data);
    }

    public function destroy(Model|AbstractModel $row)
    {
        return parent::getRepository()->destroy($row);
    }

    public function show(Model|AbstractModel $row)
    {
        return $row;
    }

    /**
     * @param array $data
     * @param string $method
     * @throws ValidatorException
     * @return bool|null
     */
    public function validate(array $data, string $method): bool|null
    {
        if (empty($validator = $this->getValidator())) {
            return null;
        }

        $validator->setData($data);

        $request = $this->getRequest();
        if (!empty($request)) {
            $validator->setRequest($request);
        }

        if ($validator->run($method) === false) {
            $validatorBagger = $this->getConfigIndex('validator_bagger');

            throw ValidatorException::new(
                $validatorBagger->errors()->messages(),
                $validator,
                $validator->getConfig()
            );
        }

        return true;
    }
}
