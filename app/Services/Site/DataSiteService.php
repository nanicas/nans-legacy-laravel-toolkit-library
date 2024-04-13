<?php

namespace Nanicas\LegacyLaravelToolkit\Services\Site;

use Nanicas\LegacyLaravelToolkit\Services\AbstractService;
use Nanicas\LegacyLaravelToolkit\Repositories\SiteEntityRepository;
use Nanicas\LegacyLaravelToolkit\Repositories\Config\DataConfigRepository;

class DataSiteService extends AbstractService
{
    public function __construct(
        SiteEntityRepository $siteEntityRepository,
        DataConfigRepository $dataConfigRepository
    )
    {
        $this->setDependencie('site_entity_repository', $siteEntityRepository);
        $this->setDependencie('data_config_repository', $dataConfigRepository);
    }
    
    protected function extractSlugIdFromConfig(array $config)
    {
        return (isset($config['slug']) && is_object($config['slug'])) ? $config['slug']->getPrimaryValue() : null;
    }

    public function getIndexData(array $data = [])
    {
        $result = [];
        $slugId = $this->extractSlugIdFromConfig($data);

        $result['categories'] = $this->getContents($slugId)['categories'];
        $result['slug_data'] = $this->getSlugConfigData($slugId);

        return $result;
    }

    protected function getSlugConfigData(?int $slugId)
    {
        return (!empty($slugId)) ? $this->getDependencie('data_config_repository')->getBySlug($slugId) : [];
    }

    protected function getContents(?int $slugId)
    {
        $entities = (!empty($slugId)) ? $this->getDependencie('site_entity_repository')->getEntitiesBySlug($slugId) : [];
        $cache = [
            'categories' => []
        ];

        if (!empty($entities)) {
            foreach ($entities as $row) {

                if (!isset($cache['categories'][$row->category_key])) {
                    $cache['categories'][$row->category_key] = [
                        'category_name' => $row->category_name,
                        'components' => []
                    ];
                }

                if (!isset($cache['categories'][$row->category_key]['components'][$row->component_key])) {
                    $cache['categories'][$row->category_key]['components'][$row->component_key] = [
                        'component_name' => $row->component_name,
                        'entities' => []
                    ];
                }

                $cache['categories'][$row->category_key]['components'][$row->component_key]['entities'][] = $row;
            }
        }

        return $cache;
    }
}
