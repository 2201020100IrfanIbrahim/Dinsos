<?php

namespace App\Controllers; // Pastikan namespace ini benar

use App\Models\UserModel; // Jika Anda sudah membuat model

// Pastikan nama class sama dengan nama file
class AuthController extends BaseController 
{
    // Pastikan nama method 'login'
    public function login(): string
    {
        return view('login_view');
    }

    public function attemptLogin()
    {
        $session = session();
        $userModel = new UserModel();
        
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $userModel->getUserByUsername($username);

        if ($user && password_verify($password, $user['password'])) {
            $sessionData = [
                'user_id'       => $user['id'],
                'username'      => $user['username'],
                'role'          => $user['role'],
                'id_kabupaten'  => $user['id_kabupaten'],
                'isLoggedIn'    => TRUE
            ];
            $session->set($sessionData);
            
            // Arahkan ke rute dashboard setelah login berhasil
            return redirect()->to('/dashboard'); 
        } else {
            $session->setFlashdata('error', 'Username atau Password salah.');
            return redirect()->to('/login');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}