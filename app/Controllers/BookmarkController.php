<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\BookmarkModel;
use App\Models\MemberModel;

class BookmarkController extends ResourceController
{
    /**
     * @var \CodeIgniter\HTTP\IncomingRequest
     */
    protected $request;

    protected $modelName = 'App\Models\BookmarkModel';
    protected $format = 'json';

    // Fungsi Manual Check Token
    private function getAuthenticatedUser()
    {
        $header = $this->request->getHeaderLine('Authorization');
        if (!$header) return null;

        // Format: Bearer {token}
        $token = explode(' ', $header)[1] ?? null;
        if (!$token) return null;

        $memberModel = new MemberModel();
        return $memberModel->where('auth_token', $token)->first();
    }

    public function index()
    {
        $user = $this->getAuthenticatedUser();
        if (!$user) return $this->failUnauthorized('Token Invalid atau Expired');

        $data = $this->model->where('member_id', $user['id'])->findAll();
        return $this->respond(['status' => 200, 'data' => $data]);
    }

    public function create()
    {
        $user = $this->getAuthenticatedUser();
        if (!$user) return $this->failUnauthorized('Token Invalid');

        // Validasi input
        if (!$this->validate([
            'title' => 'required',
            'article_url' => 'required'
        ])) {
            return $this->fail($this->validator->getErrors());
        }

        $data = [
            'member_id' => $user['id'],
            'title' => $this->request->getVar('title'),
            'source_name' => $this->request->getVar('source_name'),
            'url_image' => $this->request->getVar('url_image'),
            'article_url' => $this->request->getVar('article_url'),
            'personal_notes' => '' // Default kosong
        ];

        $this->model->insert($data);
        return $this->respondCreated(['status' => 201, 'message' => 'Berita disimpan']);
    }

    //  mengupdate Personal Notes
    public function update($id = null)
    {
        $user = $this->getAuthenticatedUser();
        if (!$user) return $this->failUnauthorized('Token Invalid');

        // Cek kepemilikan
        $bookmark = $this->model->find($id);
        if (!$bookmark || $bookmark['member_id'] != $user['id']) {
            return $this->failNotFound('Bookmark tidak ditemukan atau akses ditolak');
        }

        $note = $this->request->getRawInput()['personal_notes'] ?? '';

        $this->model->update($id, ['personal_notes' => $note]);
        return $this->respond(['status' => 200, 'message' => 'Catatan diperbarui']);
    }

    public function delete($id = null)
    {
        $user = $this->getAuthenticatedUser();
        if (!$user) return $this->failUnauthorized('Token Invalid');

        $bookmark = $this->model->find($id);
        if (!$bookmark || $bookmark['member_id'] != $user['id']) {
            return $this->failNotFound('Bookmark tidak ditemukan');
        }

        $this->model->delete($id);
        return $this->respondDeleted(['status' => 200, 'message' => 'Bookmark dihapus']);
    }
}
