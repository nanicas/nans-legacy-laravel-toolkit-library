<?php

namespace Zevitagem\LaravelToolkit\Helpers;

class EntityConfigImageHelper
{

    public function update(array $data, $entity)
    {
        if (empty($data['image_obj'])) {
            return $this->caseDoenstExistsUploadedImageOnUpdate($data, $entity);
        }

        $this->caseExistsUploadedImage($data);
    }

    private function caseDoenstExistsUploadedImageOnUpdate(array $data, $entity)
    {
        if (!empty($data['selected-image'])) {
            return;
        }

        $json = $entity->getData();
        $image = $json['image'] ?? null;

        if (empty($image)) {
            return;
        }

        $path = public_path('image/entities') . '/' . $image;

        if (file_exists($path)) {
            unlink($path);
        }
    }

    private function caseExistsUploadedImage(array $data)
    {
        //$name = json_decode($data['data'], true)['image'];
        $name = $data['data']['image'];

        $data['image_obj']->move(public_path('image/entities'), $name);
    }

    public function store(array $data)
    {
        if (empty($data['image_obj'])) {
            return;
        }

        $this->caseExistsUploadedImage($data);
    }

}
