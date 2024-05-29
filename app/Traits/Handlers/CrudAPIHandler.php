<?php

namespace Nanicas\LegacyLaravelToolkit\Traits\Handlers;

trait CrudAPIHandler
{
    public function form(&$data)
    {
        if (isset($data['logged_user_id'])) {
            $data['user_id'] = $data['logged_user_id'];
            unset($data['logged_user_id']);
        }

        if (array_key_exists('active', $data)) {
            $data['active'] = (bool) $data['active'];
        }
    }
}
