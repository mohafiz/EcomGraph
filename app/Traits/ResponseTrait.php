<?php

namespace App\Traits;

trait ResponseTrait {

    public function success($typename = null, $message = null)
    {
        $data = [
            'success' => true,
            'message' => $message ?: 'Operation done successfully'
        ];

        if ($typename) $data['__typename'] = $typename;

        return $data;
    }

    function badRequest($message, $typename = null)
    {
        $data = [
            'success' => false,
            'message' => $message
        ];

        if ($typename) $data['__typename'] = $typename;

        return $data;
    }

    public function serverError($typename = null)
    {
        $data = [
            'success'  => false,
            'message'  => 'Server Error' 
        ];

        if ($typename) $data['__typename'] = $typename;

        return $data;
    }
}