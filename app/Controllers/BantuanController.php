<?php

namespace App\Controllers;

// Pastikan controller ini extends BaseController
class BantuanController extends BaseController 
{
    public function index()
    {
        $bantuanModel = new BantuanModel();

        // Cukup terapkan filter yang sudah disiapkan di BaseController
        $data['bantuan'] = $bantuanModel->where($this->dataFilter)->findAll();

        return view('bantuan/index', $data);
    }
}