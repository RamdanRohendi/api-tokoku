<?php

namespace App\Controllers;

use App\Models\MLogin;
use App\Models\MMember;

class ProfileController extends RestfulController
{
    public function profile()
    {
        if (!$this->checkAccess()) {
            return $this->responseHasil(403, false, 'Forbidden Access');
        }

        $data_login = $this->dataAuth();

        $data = new MMember();
        $data = $data->select('nama,email')->find($data_login['member_id']);

        return $this->responseHasil(200, true, $data);
    }

    public function ubah()
    {
        if (!$this->checkAccess()) {
            return $this->responseHasil(403, false, 'Forbidden Access');
        }

        $new_password = $this->request->getVar('new_password');
        $confirm_password = $this->request->getVar('confirm_password');
        $current_password = $this->request->getVar('current_password');

        $data = [
            'nama' => $this->request->getVar('nama'),
            'email' => $this->request->getVar('email'),
            'password' => password_hash($new_password, PASSWORD_DEFAULT),
        ];

        if (!($data['nama'] && $data['email'] && $data['password'])) {
            return $this->responseHasil(400, false, 'Semua Inputan harus diisi!');
        }

        $model = new MMember();
        $data_login = $this->dataAuth();

        $user = $model->first($data_login['member_id']);

        if ($new_password || $confirm_password || $current_password) {
            if ($new_password && $confirm_password && $current_password) {
                if (!password_verify($current_password, $user['password'])) {
                    return $this->responseHasil(400, false, 'Current Password tidak valid!');
                }
        
                if (strcmp($new_password, $confirm_password) != 0) {
                    return $this->responseHasil(400, false, 'Confirm Password tidak sama!');
                }
            } else {
                return $this->responseHasil(400, false, 'Semua Inputan Password harus diisi!');
            }
        } else {
            $data = array_diff($data, [$data['password']]);
        }

        $model->update($data_login['member_id'], $data);
        
        $data = $model->select('nama,email')->first($data_login['member_id']);

        return $this->responseHasil (200, true, $data);
    }

    public function hapus()
    {
        if (!$this->checkAccess()) {
            return $this->responseHasil(403, false, 'Forbidden Access');
        }

        $data_login = $this->dataAuth();
        $data_session = new MLogin();
        $data_session = $data_session->where('member_id', $data_login['member_id'])->delete();

        $data = new MMember();
        $data = $data->where('id', $data_login['member_id'])->delete();

        return $this->responseHasil(200, true, 'Hapus akun berhasil!');
    }
}
