<?php

namespace Nanicas\LegacyLaravelToolkit\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Throwable;
use DataTables;
use Nanicas\LegacyLaravelToolkit\Exceptions\ValidatorException;
use Nanicas\LegacyLaravelToolkit\Exceptions\CustomValidatorException;
use Nanicas\LegacyLaravelToolkit\Helpers\Helper as InternalHelper;
use Symfony\Component\HttpFoundation\Response;

class_alias(InternalHelper::readTemplateConfig()['helpers']['global'], uniqid() . __NAMESPACE__ . '\HelperAlias');
class_alias(InternalHelper::readTemplateConfig()['controllers']['dashboard'], __NAMESPACE__ . '\DashboardControllerAlias');

abstract class CrudController extends DashboardControllerAlias
{
    const INDEX_VIEW = 'index';
    const LIST_VIEW = 'list';
    const SHOW_VIEW = 'show';
    const CREATE_VIEW = 'create';

    protected $view;
    protected $request;
    protected bool $indexIsList = true;
    protected bool $safe = true;

    public function __construct()
    {
        parent::__construct();
    }

    public function setView(string $view)
    {
        $this->view = $view;
    }

    public function getView()
    {
        return $this->view;
    }

    public function indexIsList()
    {
        return $this->indexIsList;
    }

    public function print(array $response)
    {
        echo json_encode($response);
    }

    public function addFormAssets()
    {
        $path = $this->definePathAssets();
        $root = $this->getRootFolderNameOfAssets();
        $packagedRoot = $this->getRootFolderNameOfAssetsPackaged();

        $this->config['assets']['js'][] = $packagedRoot . '/js/layouts/crud/form.js';
        $this->config['assets']['css'][] = $packagedRoot . '/js/layouts/crud/form.css';
        $this->config['assets']['js'][] = $root . 'resources/pages/' . $path . '/form.js';
        $this->config['assets']['css'][] = $root . 'resources/pages/' . $path . '/form.css';
    }

    public function addListAssets()
    {
        $packagedRoot = $this->getRootFolderNameOfAssetsPackaged();

        parent::addCssAssets($packagedRoot . '/css/layouts/crud/list.css');
        parent::addJsAssets($packagedRoot . '/js/layouts/crud/list.js');
        parent::addListAssets();
    }

    protected function createView(string $screen, string $view, array $data)
    {
        $packaged = $this->isPackagedView();

        return HelperAlias::view("pages.$screen.$view", $data, $packaged)->render();
    }

    public function beforeView(Request $request)
    {
        View::share('state', $request->query('state'));

        parent::beforeView($request);
    }

    protected function view(array $data = [])
    {
        $view = $this->getView();
        $screen = $this->getFullScreen();
        $methodConfig = 'config' . ucfirst($view);
        $config = (method_exists($this, $methodConfig)) ? $this->{$methodConfig}() : [];

        $data['config'] = $config;

        $this->beforeView($this->request);

        return $this->createView($screen, $view, $data);
    }

    public function store(Request $request)
    {
        $this->request = $request;
        $this->getService()->configureIndex('request', $request);

        if (!$this->isAllowed()) {
            return $this->notAllowedResponse($request);
        }

        $data = $_POST;
        $status = false;
        $id = null;
        $method = __FUNCTION__;
        $resource = null;

        try {
            $this->getService()->handle($data, $method);
            $this->getService()->validate($data, $method);

            $resource = $this->getService()->store($data);

            if (is_object($resource)) {
                $status = true;
                $id = $resource->getId();
            } elseif (!empty($resource)) {
                $status = true;
            }

            if ($status == false) {
                $message = 'Ocorreu um problema no momento de realizar a inserção!';
            } else {
                $message = 'As informações foram salvas com sucesso!';
            }
            $message = HelperAlias::loadMessage($message, $status);
        } catch (ValidatorException | CustomValidatorException $ex) {
            $message = $ex->getMessage();
        } catch (Throwable $ex) {
            $message = HelperAlias::loadMessage($ex->getMessage() . ' [' . $ex->getFile() . ':' . $ex->getLine() . ']', $status);
        }

        $existsDifferentResponseOnEnd = ($this->existsConfigIndex('response_on_end'));
        $canResponseOnEnd = (!$existsDifferentResponseOnEnd || $this->isValidConfig('response_on_end'));

        $redirUrl = (method_exists($this, 'getRedirUrl')) ? $this->getRedirUrl($status, $method, [], $data) : (($status) ? route($this->getFullScreen() . '.index', ['state' => 'success_store']) : '');

        $response = HelperAlias::createDefaultJsonToResponse(
            $status,
            [
                'status' => $status,
                'message' => $message,
                'resource' => $resource,
                'id' => $id,
                'url_redir' => $redirUrl
            ]
        );

        if ($canResponseOnEnd) {
            return $this->print($response, $status);
        }

        return $response;
    }

    public function update(Request $request)
    {
        $this->request = $request;
        $this->getService()->configureIndex('request', $request);

        if (!$this->isAllowed()) {
            return $this->notAllowedResponse($request);
        }

        $data = $_POST;
        $status = false;
        $resource = null;

        try {
            $this->getService()->handle($data, __FUNCTION__);
            $this->getService()->validate($data, __FUNCTION__);

            $status = $this->getService()->update($data, $resource);
            if ($status == false) {
                $message = 'Ocorreu um problema no momento de realizar a atualização!';
            } else {
                $message = 'As informações foram salvas com sucesso!';
            }

            $message = HelperAlias::loadMessage($message, $status);
        } catch (ValidatorException | CustomValidatorException $ex) {
            $message = $ex->getMessage();
        } catch (Throwable $ex) {
            $message = HelperAlias::loadMessage($ex->getMessage() . ' [' . $ex->getFile() . ':' . $ex->getLine() . ']', $status);
        }

        $existsDifferentResponseOnEnd = ($this->existsConfigIndex('response_on_end'));
        $canResponseOnEnd = (!$existsDifferentResponseOnEnd || $this->isValidConfig('response_on_end'));

        $response = HelperAlias::createDefaultJsonToResponse($status, [
            'status' => $status,
            'resource' => $resource,
            'message' => $message,
            'url_redir' => ($status) ? route($this->getFullScreen() . '.index', ['state' => 'success_update']) : ''
        ]);

        if ($canResponseOnEnd) {
            return $this->print($response, $status);
        }

        return $response;
    }

    public function destroy(Request $request, int $id)
    {
        $this->request = $request;
        $this->getService()->configureIndex('request', $request);

        if (!$this->isAllowed()) {
            return $this->notAllowedResponse($request);
        }

        $status = false;

        try {
            $result = $this->getService()->destroy($id);
            $status = $result['status'];

            if ($status == false) {
                $message = 'Ocorreu um problema no momento de realizar a exclusão!';
            } else {
                $message = 'Os dados foram excluídos com sucesso!';
            }

            $message = HelperAlias::loadMessage($message, $status);
        } catch (ValidatorException | CustomValidatorException $ex) {
            $message = $ex->getMessage();
        } catch (Throwable $ex) {
            $message = HelperAlias::loadMessage($ex->getMessage() . ' [' . $ex->getFile() . ':' . $ex->getLine() . ']', $status);
        }

        echo json_encode(HelperAlias::createDefaultJsonToResponse($status, [
            'id' => $id,
            'status' => $status,
            'message' => $message,
        ]));
    }

    public function index(Request $request)
    {
        $this->request = $request;
        $this->getService()->configureIndex('request', $request);

        if (!$this->isAllowed()) {
            return $this->notAllowedResponse($request);
        }

        $this->addIndexAssets();
        $this->addListAssets();
        $this->setView(self::INDEX_VIEW);

        $data = [];
        $status = false;
        $message = '';
        $query_params = $request->query();

        $executorData = function () {
            return $this->getService()->getIndexData();
        };

        if ($this->isSafe()) {
            try {
                $data = $executorData();
                $status = true;
            } catch (ValidatorException | CustomValidatorException $ex) {
                $message = $ex->getMessage();
            } catch (Throwable $ex) {
                $message = HelperAlias::loadMessage($ex->getMessage() . ' [' . $ex->getFile() . ':' . $ex->getLine() . ']', $status);
            }
        } else {
            $data = $executorData();
            $status = true;
        }

        return self::view(compact('data', 'message', 'status', 'query_params'));
    }

    public function show(Request $request, int $id)
    {
        $this->request = $request;
        $this->getService()->configureIndex('request', $request);

        if (!$this->isAllowed()) {
            return $this->notAllowedResponse($request);
        }

        $data = [];
        $status = false;

        $this->addShowAssets();
        $this->setView(self::SHOW_VIEW);

        try {
            $data = $this->getService()->getDataToShow($id);
            $status = true;
            $message = HelperAlias::loadMessage('Dados encontrados com sucesso, segue abaixo a relação das informações.', $status);
        } catch (ValidatorException | CustomValidatorException $ex) {
            $message = $ex->getMessage();
        } catch (Throwable $ex) {
            $message = HelperAlias::loadMessage($ex->getMessage() . ' [' . $ex->getFile() . ':' . $ex->getLine() . ']', $status);
        }

        return self::view(compact('data', 'message', 'status'));
    }

    public function configlist()
    {
        return [
            'create_option' => true
        ];
    }

    public function list(Request $request)
    {
        $this->request = $request;
        $this->getService()->configureIndex('request', $request);

        if (!$this->isAllowed()) {
            return $this->notAllowedResponse($request);
        }

        parent::beforeView($request);

        $data = $this->getService()->getDataToList();
        $options = $data['options'] ?? [];

        return $this->createListTable($data['rows'], $options);
    }

    protected function createListTable($rows, array $options = [])
    {
        return DataTables::of($rows)
            ->addColumn('action', function ($row) {
                $screen = $this->getFullScreen();
                return view('pages.' . $screen . '.list-buttons', ['row' => $row])->render();
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create(Request $request)
    {
        $this->request = $request;
        $this->getService()->configureIndex('request', $request);

        if (!$this->isAllowed()) {
            return $this->notAllowedResponse($request);
        }

        $this->addCreateAssets();
        $this->setView(self::CREATE_VIEW);
        $message = '';
        $data = [];
        $status = false;

        try {
            $data = $this->getService()->getDataToCreate();
            $status = true;
        } catch (ValidatorException | CustomValidatorException $ex) {
            $message = $ex->getMessage();
        } catch (Throwable $ex) {
            $message = HelperAlias::loadMessage($ex->getMessage() . ' [' . $ex->getFile() . ':' . $ex->getLine() . ']', $status);
        }

        return self::view(compact('data', 'message', 'status'));
    }

    public function filter(Request $request)
    {
        try {
            $rows = $this->getService()->filter($request->all());
            $status = true;
            $message = '';
            $code = Response::HTTP_OK;
        } catch (Throwable $ex) {
            $status = false;
            $rows = [];
            $message = $ex->getMessage() . ' [' . $ex->getFile() . ':' . $ex->getLine() . ']';
            $code = Response::HTTP_UNPROCESSABLE_ENTITY;
        }

        return response()->json(
            HelperAlias::createDefaultJsonToResponse($status, compact('rows', 'message')),
            $code
        );
    }

    protected function setSafe(bool $value)
    {
        $this->safe = $value;
    }

    protected function isSafe()
    {
        return (!empty($this->safe));
    }
}
