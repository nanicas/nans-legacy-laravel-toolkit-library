<?php

namespace Zevitagem\LaravelToolkit\Services\Site;

use Zevitagem\LaravelToolkit\Services\AbstractService;
use Zevitagem\LaravelToolkit\Helpers\Helper;
use Zevitagem\LegoAuth\Services\SessionService;
use Zevitagem\LegoAuth\Exceptions\NotAuthenticatedException;
use Zevitagem\LaravelToolkit\Repositories\PainelRepository;
use Zevitagem\LaravelToolkit\Handlers\SiteHandler;

class ConfigSiteService extends AbstractService
{
    public function __construct(
        PainelRepository $painelRepository,
        SiteHandler $handler
    )
    {
        $this->setDependencie('painel_repository', $painelRepository);
        $this->setHandler($handler);
    }

    public function getIndexConfigData(string $slug)
    {
        try {
            $sessionData = SessionService::getCurrentData();
        } catch (NotAuthenticatedException $exc) {
            $sessionData = null;
        }

        if (!empty($slug) && !empty($sessionData)) {
            $result = $this->indexWithSlugAndSession($slug, $sessionData);
        }

        if (!empty($slug) && empty($sessionData)) {
            $result = $this->indexWithSlugAndNoSession($slug);
        }

        if (empty($slug) && !empty($sessionData)) {
            $result = $this->indexWithSessionAndNoSlug($sessionData);
        }

        if (empty($slug) && empty($sessionData)) {
            $result = $this->indexNoSessionAndNoSlug();
        }

        $this->handle($result, 'aftergGetIndexConfigData');

        return $result;
    }

    private function indexWithSlugAndSession(string $slug, array $sessionData)
    {
        if ($sessionData['slug']['slug'] == $slug) {
            return [
                'logged' => true,
                'contract' => $sessionData['contract'],
                'slug' => $sessionData['slug']
            ];
        }

        $result = $this->indexWithSlugAndNoSession($slug);
        $result['logged'] = true;

        return $result;
    }

    private function indexWithSlugAndNoSession(string $slug)
    {
        $info = $this->getInfoBySlugText($slug);

        return [
            'logged' => false,
            'contract' => $info['contract'],
            'slug' => $info['slug']
        ];
    }

    private function indexWithSessionAndNoSlug(array $sessionData)
    {
        $info = $this->getInfoBySlugText($sessionData['slug']['slug']);

        return [
            'logged' => true,
            'contract' => $info['contract'],
            'slug' => $info['slug']
        ];
    }

    private function getInfoBySlugText(string $slug)
    {
        $info = $this->getDependencie('painel_repository')->getInfoBySlugTextAndApplication(
            $slug, Helper::getAppId()
        );

        return (empty($info)) ? ['contract' => null, 'slug' => null] : $info;
    }

    private function indexNoSessionAndNoSlug()
    {
        return [
            'logged' => false,
            'contract' => null,
            'slug' => null
        ];
    }
}