<?php

namespace Nanicas\LegacyLaravelToolkit\Http\Controllers;

use Closure;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Nanicas\LegacyLaravelToolkit\Exceptions\ValidatorException;
use Illuminate\Support\Facades\Validator;
use Nanicas\LegacyLaravelToolkit\Models\AbstractModel;
use Nanicas\LegacyLaravelToolkit\Helpers\Helper as InternalHelper;

class_alias(InternalHelper::readTemplateConfig()['controllers']['base'], __NAMESPACE__ . '\BaseControllerAlias');

abstract class APIController extends BaseControllerAlias
{
    const RESOURCE_NAME = 'Row';

    abstract protected function getAuthorizationResource();

    public function index(Request $request)
    {
        $method = __FUNCTION__;

        return $this->execute(function () use ($request, $method) {
            $this->getService()->setRequest($request);

            $rows = $this->getService()->filter($request->all());

            return $this->successResponse($rows, Response::HTTP_OK, $this->getResourceName($method) . ' retrieved successfully.');
        });
    }

    public function store(Request $request)
    {
        $infoValidate = $this->formValidate($request, $this->getStoreRequest());
        $data = $infoValidate['validated'];
        $request = $infoValidate['request'];

        $method = __FUNCTION__;

        return $this->execute(function () use ($request, $data, $method) {
            $this->authorize('store', $this->getAuthorizationResource());

            $this->getService()->setRequest($request);
            $this->getService()->handle($data, $method);
            $this->getService()->validate($data, $method);

            $stored = $this->getService()->store($data);
            if (!$stored) {
                return $this->errorResponse($this->getResourceName($method) . ' could not be created.', Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $this->successResponse($stored,  Response::HTTP_CREATED, $this->getResourceName($method) . ' created successfully.');
        });
    }

    protected function _show(Request $request, AbstractModel $row)
    {
        $method = 'show';

        return $this->execute(function () use ($request, $row, $method) {
            $this->authorize('show', $row);

            $this->getService()->setRequest($request);
            $this->getService()->validate(compact('row'), $method);

            $row = $this->getService()->show($row);
            if (!$row) {
                return $this->errorResponse($this->getResourceName($method) . ' not found.', Response::HTTP_NOT_FOUND);
            }

            return $this->successResponse($row, Response::HTTP_OK, $this->getResourceName($method) . ' retrieved successfully.');
        });
    }

    protected function _update(Request $request, AbstractModel $row)
    {
        $infoValidate = $this->formValidate($request, $this->getUpdateRequest());
        $data = $infoValidate['validated'];
        $request = $infoValidate['request'];

        $method = 'update';

        return $this->execute(function () use ($request, $data, $row, $method) {
            $this->authorize('update', $row);

            $this->getService()->setRequest($request);
            $this->getService()->handle($data, $method);
            $this->getService()->validate(compact('data', 'row'), $method);

            $updated = $this->getService()->update($row, $data);
            if (!$updated) {
                return $this->errorResponse($this->getResourceName($method) . ' could not be updated.', Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $this->successResponse($updated, Response::HTTP_OK, $this->getResourceName($method) . ' updated successfully.');
        });
    }

    protected function _destroy(Request $request, AbstractModel $row)
    {
        $method = 'destroy';

        return $this->execute(function () use ($request, $row, $method) {
            $this->authorize('delete', $row);

            $this->getService()->setRequest($request);
            $this->getService()->validate(compact('row'), $method);

            $status = $this->getService()->delete($row);
            if (!$status) {
                return $this->errorResponse($this->getResourceName($method) . ' could not be deleted.', Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $this->successResponse(null, Response::HTTP_NO_CONTENT, $this->getResourceName($method) . ' deleted successfully.');
        });
    }

    protected function execute(Closure $closure)
    {
        try {
            return $closure();
        } catch (ValidatorException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            $code = $exception->getCode();
            return $this->errorResponse($exception->getMessage(), $code, [
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]);
        }
    }

    protected function errorResponse($message, $code = 500, array $data = [])
    {
        return response()->json([
            'code' => $code,
            'status' => false,
            'errors' => [
                'message' => [
                    $message
                ]
            ],
            'metadata' => $data
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    protected function successResponse($data, $code = 200, $message = null)
    {
        return response()->json([
            'status' => true,
            'data' => $data,
            'message' => $message
        ], $code);
    }

    protected function formValidate(Request $request, $requestValidate = null)
    {
        if (!is_string($requestValidate)) {
            return [
                'validated' => $request->all(),
                'request' => $request
            ];
        }

        $objRequestValidate = app($requestValidate);
        $objRequestValidate->merge($request->all());

        $validator = Validator::make(
            $objRequestValidate->all(),
            $objRequestValidate->rules(),
            $objRequestValidate->messages()
        );

        if ($validator->fails()) {
            $errors = $validator->errors()->messages();
            throw ValidatorException::new(
                $errors,
                $validator
            );
        }

        return [
            'validated' => $validator->validated(),
            'request' => $objRequestValidate
        ];
    }

    protected function getResourceName(string $method)
    {
        if ($method !== 'index') {
            return static::RESOURCE_NAME;
        }

        if (method_exists($this, 'getPluralResourceName')) {
            return $this->getPluralResourceName($method);
        }

        return static::RESOURCE_NAME . 's';
    }

    protected function getUpdateRequest()
    {
        return null;
    }

    protected function getStoreRequest()
    {
        return null;
    }
}
