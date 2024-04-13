<?php

namespace Zevitagem\LaravelToolkit\Handlers\Config;

use Zevitagem\LaravelToolkit\Handlers\AbstractHandler;
use Zevitagem\LaravelToolkit\Helpers\Helper;
use Illuminate\Http\Request;
use Zevitagem\LaravelToolkit\Traits\AvailabilityWithDependencie;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Zevitagem\LaravelToolkit\Traits\Handlers\CrudHandler;

class EntityConfigHandler extends AbstractHandler
{
    use CrudHandler;
    use AvailabilityWithDependencie;

    public function __construct(Request $request)
    {
        $this->setDependencie('request', $request);
    }

    public function form(&$data)
    {
        $keys = [
            'title', 'content', 'extra', 'name', 'selected-image'
        ];

        foreach ($keys as $key) {
            $data[$key] = (isset($data[$key])) ? trim($data[$key]) : null;
        }

        $data['component_id'] = intval($data['component']);
        $data['active'] = (int) (isset($data['active']) ? boolval($data['active']) : false);

        $image = $this->getDependencie('request')->file('image');
        $data['image_obj'] = ($image) ?: null;
    }

    private function generateCommonJsonToEncode(array $data)
    {
        $toEncode = [];
        foreach (['title', 'content', 'extra'] as $key) {
            if ($data[$key] !== null) {
                $toEncode[$key] = $data[$key];
            }
        }
        
        return $toEncode;
    }

    public function beforeUpdate()
    {
        $data = & $this->data;
        
        $toEncode = $this->generateCommonJsonToEncode($data);
        
        if (empty($data['image_obj'])) {
            if (!empty($data['selected-image'])) {
                $toEncode['image'] = $data['selected-image'];
            }
        } else {
            $file = $data['image_obj'];
            $toEncode['image'] = $this->generateImageName($file);
        }
        
        //$data['data'] = json_encode($toEncode);
        $data['data'] = $toEncode;
    }
    
    private function generateImageName(UploadedFile $file)
    {
        return date('YmdHis') . '-' . $file->getClientOriginalName();
    }

    public function beforeStore()
    {
        $data = & $this->data;
        
        $toEncode = $this->generateCommonJsonToEncode($data);
        
        if (!empty($data['image_obj'])) {
            $file = $data['image_obj'];
            $toEncode['image'] = $this->generateImageName($file);
        }

        //$data['data'] = json_encode($toEncode);
        $data['data'] = $toEncode;
    }

    public function store()
    {
        $data = & $this->data;
        $data['slug'] = Helper::getSlug();

        $this->form($data);
    }

    public function update()
    {
        $data = & $this->data;

        $this->form($data);
    }

}
