<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;
use Firebase\JWT\JWT;

class Login extends ResourceController
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
            'email' => 'required|valid_email',
            'password' => 'required|min_length[8]'
        ];
        if (!$this->validate($rules)) return $this->fail($this->validator->getErrors());
        $model = new UserModel();
        $user = $model->where("email", $this->request->getVar('email'))->first();
        if (!$user) return $this->responseApi([], false, 'Email or password in incorrect');

        $verify = password_verify($this->request->getVar('password'), $user['password']);
        if (!$verify) return $this->responseApi([], false, 'Email or password in incorrect');

        $key = getenv('TOKEN_SECRET');
        // cskhfuwt48wbfjn3i4utnjf38754hf3yfbjc93758thrjsnf83hcwn8437
        $payload = array(
            "iat" => 1356999524,
            "nbf" => 1357000000,
            "uid" => $user['id'],
            "email" => $user['email']
        );
        $token = JWT::encode($payload, $key, 'HS256');
        $data = [
            'user' => $user['name'],
            'token' => $token
        ];
        return $this->responseApi($data, true, 'User logged in successfully');
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
