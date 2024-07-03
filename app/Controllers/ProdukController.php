<?php

namespace App\Controllers;

use App\Models\MLogin;
use App\Models\MProduk;

class ProdukController extends RestfulController
{
    public function create()
    {
        $data = [
            'kode_produk' => $this->request->getVar('kode_produk'),
            'nama_produk' => $this->request->getVar('nama_produk'),
            'harga' => $this->request->getVar('harga')
        ];

        $model = new MProduk();
        $model->insert($data);
        
        $produk = $model->find($model->getInsertID());
        
        return $this->responseHasil(200, true, $produk);
    }

    public function list()
    {
        if (!$this->checkAccess()) {
            return $this->responseHasil(403, false, ['message' => 'Forbidden Access']);
        }

        $model = new MProduk();
        $produk = $model->findAll();

        return $this->responseHasil(200, true, $produk);
    }

    public function detail($id)
    {
        $model = new MProduk();
        $produk = $model->find($id);

        return $this->responseHasil(200, true, $produk);
    }

    public function ubah($id)
    {
        $data = [
            'kode_produk' => $this->request->getVar('kode_produk'),
            'nama_produk' => $this->request->getVar('nama_produk'),
            'harga' => $this->request->getVar('harga')
        ];

        $model = new MProduk();
        $model->update($id, $data);
        
        $produk = $model->find($id);

        return $this->responseHasil (200, true, $produk);
    }

    public function hapus($id) {
        $model = new MProduk();
        $produk = $model->delete($id);

        return $this->responseHasil (200, true, $produk);
    }

    private function checkAccess() {
        $auth_key = explode(' ', $this->request->getHeaderLine('Authorization'));
        $token = $auth_key[count($auth_key)-1];

        $login = new MLogin();
        $sudah_login = $login->where('auth_key', $token)->find();

        if (count($sudah_login) <= 0) {
            return false;
        }

        return true;
    }
}
