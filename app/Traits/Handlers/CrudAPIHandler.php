<?php

namespace Nanicas\LegacyLaravelToolkit\Traits\Handlers;

trait CrudAPIHandler
{
    public function form(&$data)
    {
        if (isset($data['_logged_user_id'])) {
            $data['user_id'] = $data['_logged_user_id'];
            unset($data['_logged_user_id']);
        }

        if (array_key_exists('active', $data)) {
            $data['active'] = (bool) $data['active'];
        }
    }
}
