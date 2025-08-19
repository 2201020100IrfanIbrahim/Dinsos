<?php

namespace App\Controllers;

use App\Models\UserModel;

class UserController extends BaseController
{
    public function index()
    {
        // Hanya superadmin yang boleh mengakses halaman ini
        if (session()->get('role') !== 'superadmin') {
            return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki hak akses.');
        }

        $userModel = new \App\Models\UserModel();

        $data = [
            'title' => 'Manajemen Pengguna Admin',
            // MODIFIKASI: Lakukan join untuk mengambil nama kabupaten
            'users' => $userModel->select('users.*, kabupaten.nama_kabupaten')
                                ->join('kabupaten', 'kabupaten.id = users.id_kabupaten', 'left')
                                ->where('users.role', 'admin')
                                ->findAll(),
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => '/dashboard'],
                ['title' => 'Manajemen Pengguna', 'url' => '']
            ]
        ];

        return view('users/index', $data);
    }

    public function edit($id)
    {
        if (session()->get('role') !== 'superadmin') {
            return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki hak akses.');
        }

        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (!$user || $user['role'] !== 'admin') {
             return redirect()->to('/admin/users')->with('error', 'User tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Pengguna: ' . $user['username'],
            'user'  => $user,
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => '/dashboard'],
                ['title' => 'Manajemen Pengguna', 'url' => '/admin/users'],
                ['title' => 'Edit', 'url' => '']
            ]
        ];

        return view('users/edit', $data);
    }

    public function update($id)
    {
        if (session()->get('role') !== 'superadmin') {
            return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki hak akses.');
        }
        
        $userModel = new UserModel();
        
        // Siapkan data dasar untuk diupdate (username)
        $dataToUpdate = [
            'id'       => $id,
            'username' => $this->request->getPost('username')
        ];

        // Cek apakah ada upaya untuk mengubah password
        $newPassword = $this->request->getPost('password');
        
        if (!empty($newPassword)) {
            // --- LOGIKA KONFIRMASI PASSWORD SUPERADMIN DIMULAI ---

            // 1. Ambil password konfirmasi dari form
            $confirmationPassword = $this->request->getPost('superadmin_password');
            if (empty($confirmationPassword)) {
                return redirect()->back()->withInput()->with('errors', ['superadmin_password' => 'Password konfirmasi wajib diisi untuk mengubah password.']);
            }

            // 2. Ambil data superadmin yang sedang login dari database
            $superadminId = session()->get('user_id');
            $superadminData = $userModel->find($superadminId);

            // 3. Verifikasi password superadmin
            if (!password_verify($confirmationPassword, $superadminData['password'])) {
                // Jika password salah, kembalikan dengan pesan error
                return redirect()->back()->withInput()->with('errors', ['superadmin_password' => 'Password konfirmasi Anda salah.']);
            }
            
            // --- LOGIKA KONFIRMASI SELESAI ---

            // Jika verifikasi berhasil, tambahkan password baru ke data yang akan diupdate
            $dataToUpdate['password'] = $newPassword;
        }

        // Lanjutkan proses penyimpanan data
        if ($userModel->save($dataToUpdate)) {
            return redirect()->to('/admin/users')->with('message', 'Data user berhasil diupdate.');
        } else {
            return redirect()->back()->withInput()->with('errors', $userModel->errors());
        }
    }

    // Tambahkan fungsi baru ini
    public function profile()
    {
        $userModel = new \App\Models\UserModel();
        $userId = session()->get('user_id');

        $data = [
            'title' => 'Edit Profil',
            'user'  => $userModel->find($userId),
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => '/dashboard'],
                ['title' => 'Edit Profil', 'url' => '']
            ]
        ];

        return view('users/profile', $data);
    }

    // Tambahkan juga fungsi baru ini
    public function updateProfile()
    {
        $userModel = new \App\Models\UserModel();
        $userId = session()->get('user_id');

        $dataToUpdate = [
            'id'       => $userId,
            'username' => $this->request->getPost('username')
        ];

        $newPassword = $this->request->getPost('new_password');
        $oldPassword = $this->request->getPost('old_password');

        // Jika user ingin mengubah password
        if (!empty($newPassword)) {
            // Password lama wajib diisi untuk konfirmasi
            if (empty($oldPassword)) {
                return redirect()->back()->withInput()->with('errors', ['old_password' => 'Password lama wajib diisi untuk mengubah password.']);
            }

            // Cek kebenaran password lama
            $currentUser = $userModel->find($userId);
            if (!password_verify($oldPassword, $currentUser['password'])) {
                return redirect()->back()->withInput()->with('errors', ['old_password' => 'Password lama Anda salah.']);
            }
            
            // Jika semua benar, masukkan password baru ke data
            $dataToUpdate['password'] = $newPassword;
        }

        if ($userModel->save($dataToUpdate)) {
            // Update nama username di session jika berubah
            if (session()->get('username') != $dataToUpdate['username']) {
                session()->set('username', $dataToUpdate['username']);
            }
            return redirect()->to('/admin/profile')->with('message', 'Profil berhasil diupdate.');
        } else {
            return redirect()->back()->withInput()->with('errors', $userModel->errors());
        }
    }
}