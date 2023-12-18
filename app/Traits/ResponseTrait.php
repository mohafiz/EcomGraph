<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Lang;

trait ResponseTrait {

    public function success($typename = null, $message = null)
    {
        if (auth('sanctum')->check())
            Lang::setLocale(User::find(auth('sanctum')->id())->default_language);
        else
            Lang::setLocale('en');

        $msg = $message ? Lang::get("messages." . str_replace(" ", "_", $message)) : null;

        $data = [
            'success' => true,
            'message' => $msg ?: Lang::get('messages.Operation_done_successfully')
        ];

        if ($typename) $data['__typename'] = $typename;

        return $data;
    }

    function badRequest($message, $typename = null)
    {
        if (auth('sanctum')->check())
            Lang::setLocale(User::find(auth('sanctum')->id())->default_language);
        else
            Lang::setLocale('en');

        $data = [
            'success' => false,
            'message' => Lang::get("messages." . str_replace(" ", "_", $message))
        ];

        if ($typename) $data['__typename'] = $typename;

        return $data;
    }

    public function serverError($typename = null)
    {
        if (auth('sanctum')->check())
            Lang::setLocale(User::find(auth('sanctum')->id())->default_language);
        else
            Lang::setLocale('en');
        
        $data = [
            'success'  => false,
            'message'  => Lang::get('Server_Error')
        ];

        if ($typename) $data['__typename'] = $typename;

        return $data;
    }
}