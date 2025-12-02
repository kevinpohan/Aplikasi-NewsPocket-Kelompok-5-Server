<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\MemberModel;

class AuthController extends ResourceController
{


    /**
     * @var \CodeIgniter\HTTP\IncomingRequest
     */
    protected $request;

    // Register
    public function register()
    {
        $rules = [
            'nama' => 'required',
            'email' => 'required|valid_email|is_unique[member.email]',
            'password' => 'required|min_length[6]'
        ];

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }

        $model = new MemberModel();
        $data = [
            'nama' => $this->request->getVar('nama'),
            'email' => $this->request->getVar('email'),
            'password' => password_hash($this->request->getVar('password'), PASSWORD_BCRYPT),
        ];

        $model->insert($data);
        return $this->respondCreated(['status' => 201, 'message' => 'Registrasi Berhasil']);
    }

    // Login
    public function login()
    {
        $model = new MemberModel();
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        $user = $model->where('email', $email)->first();

        if ($user && password_verify($password, $user['password'])) {
            $token = bin2hex(random_bytes(16));
            $model->update($user['id'], ['auth_token' => $token]);

            return $this->respond([
                'status' => 200,
                'message' => 'Login Berhasil',
                'token' => $token,
                'data' => [
                    'id' => $user['id'],
                    'nama' => $user['nama'],
                    'email' => $user['email']
                ]
            ]);
        }

        return $this->failUnauthorized('Email atau Password salah');
    }
}
