<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\MemberModel;

class ProfileController extends ResourceController
{
    protected $format = 'json';

    // Ambil user dari token
    private function getAuthenticatedUser()
    {
        $header = $this->request->getHeaderLine('Authorization');
        $token = explode(' ', $header)[1] ?? null;
        if (!$token) return null;
        $memberModel = new MemberModel();
        return $memberModel->where('auth_token', $token)->first();
    }

    // GET /api/profile
    public function index()
    {
        $user = $this->getAuthenticatedUser();
        if (!$user) return $this->failUnauthorized('Token Invalid');

        // Buat URL lengkap untuk foto
        $photoUrl = null;
        if (!empty($user['photo_profile'])) {
            $photoUrl = base_url('uploads/profiles/' . $user['photo_profile']);
        }

        return $this->respond([
            'status' => 200,
            'data' => [
                'id'            => $user['id'],
                'nama'          => $user['nama'],
                'email'         => $user['email'],
                'photo_profile' => $photoUrl,
            ]
        ]);
    }

    // PUT /api/profile (Update text data)
    public function update($id = null)
    {
        $user = $this->getAuthenticatedUser();
        if (!$user) return $this->failUnauthorized();
        $model = new MemberModel();
        /** @var \CodeIgniter\HTTP\IncomingRequest $request */
        $request = $this->request;
        $file = $request->getRawInput();
        $updateData = [];
        if (!empty($input['nama'])) $updateData['nama'] = $input['nama'];
        if (!empty($input['password'])) {
            if (strlen($input['password']) < 6) return $this->fail('Password min 6 char');
            $updateData['password'] = password_hash($input['password'], PASSWORD_BCRYPT);
        }
        if (empty($updateData)) return $this->fail('Tidak ada data diubah');
        $model->update($user['id'], $updateData);
        return $this->respond(['status' => 200, 'message' => 'Profil diupdate']);
    }

    // POST /api/profile/photo (Upload Foto Baru)
    public function uploadPhoto()
    {
        $user = $this->getAuthenticatedUser();
        if (!$user) return $this->failUnauthorized();

        // Validasi file yang diupload (harus gambar, max 2MB)
        $validationRule = [
            'photo' => [
                'label' => 'Image File',
                'rules' => [
                    'uploaded[photo]',
                    'is_image[photo]',
                    'mime_in[photo,image/jpg,image/jpeg,image/png]',
                    'max_size[photo,2048]', // Maksimal 2MB
                ],
            ],
        ];

        if (!$this->validate($validationRule)) {
            return $this->fail($this->validator->getErrors());
        }

        /** @var \CodeIgniter\HTTP\IncomingRequest $request */
        $request = $this->request;
        // Ambil file
        $file = $request->getFile('photo');
        $newName = $file->getRandomName();

        // Pindahkan file ke folder public/uploads/profiles
        if (!$file->hasMoved()) {
            $file->move(FCPATH . 'uploads/profiles', $newName);

            // Hapus foto lama jika ada (opsional, agar hemat storage)
            if (!empty($user['photo_profile'])) {
                $oldFile = FCPATH . 'uploads/profiles/' . $user['photo_profile'];
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }

            // Simpan nama file baru ke database
            $model = new MemberModel();
            $model->update($user['id'], ['photo_profile' => $newName]);

            return $this->respond([
                'status' => 200,
                'message' => 'Foto berhasil diupload',
                'photo_url' => base_url('uploads/profiles/' . $newName)
            ]);
        }

        return $this->fail('Gagal memindahkan file');
    }
}
