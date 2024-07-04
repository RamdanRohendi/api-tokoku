<?php

namespace App\Controllers;

use App\Models\MLogin;
use CodeIgniter\RESTful\ResourceController;

class RestfulController extends ResourceController
{
    protected $format = 'json';

    protected function responseHasil($code, $status, $data)
    {
        return $this->respond([
            'code' => $code,
            'status' => $status,
            'data' => $data
        ]);
    }

    protected function checkAccess()
    {
        $auth_key = explode(' ', $this->request->getHeaderLine('Authorization'));
        $token = $auth_key[count($auth_key)-1];

        $login = new MLogin();
        $sudah_login = $login->where('auth_key', $token)->find();

        if (count($sudah_login) <= 0) {
            return false;
        }

        return true;
    }

    protected function dataAuth()
    {
        $auth_key = explode(' ', $this->request->getHeaderLine('Authorization'));
        $token = $auth_key[count($auth_key)-1];

        $login = new MLogin();
        return $login->where('auth_key', $token)->first();
    }
}
