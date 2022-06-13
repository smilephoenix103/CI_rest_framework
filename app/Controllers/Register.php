<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;

class Register extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    use ResponseTrait;
    public function index()
    {
        helper(['form']);
        $rules = [
            'name' => 'required',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'confpassword' => 'matches[password]'
        ];
        if (!$this->validate($rules)) return  $this->responseApi($this->validator->getErrors(), false, 'Validation Errors');
        $data = [
            'name'     => $this->request->getVar('name'),
            'email'     => $this->request->getVar('email'),
            'password'  => password_hash($this->request->getVar('password'), PASSWORD_BCRYPT)
        ];
        $model = new UserModel();
        $registered = $model->save($data);
        return $this->responseApi([], true, 'User created successfully');
    }

    protected function responseApi($data, $status, $message)
    {
        return $this->respond([
            'data' => $data,
            'status' => $status,
            'message' => $message
        ]);
    }
}
