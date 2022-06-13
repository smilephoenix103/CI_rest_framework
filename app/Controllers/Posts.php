<?php

namespace App\Controllers;

use App\Models\PostModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;


class Posts extends ResourceController
{
    use ResponseTrait;
    // get all product
    public function index()
    {
        $value = $_GET['value'] ? $_GET['value'] : 0;
        if ($_GET['method'] == 'GET') {
            $model = new PostModel();
            $data = $model->findAll();
            $response = [
                'status'   => 200,
                'message' => 'Posts fetched'
            ];
            return $this->respond($response);
        }

        if ($_GET['method'] == 'POST') {
            for ($i = 0; $i < $value; $i++) {
                $model = new PostModel();
                $string = $this->generateRandomString(5);
                $paragraph = $this->generateRandomString(50);
                $data = [
                    'title' => $string,
                    'description' => $paragraph
                ];
                $model->insert($data);
            }
            $response = [
                'status'   => 200,
                'message' => 'Post created'
            ];
            return $this->respondCreated($response);
        }

        if ($_GET['method'] == 'DELETE') {

            $model = new PostModel();
            $data = $model->select('id')->findAll();
            if ($value < count($data)) {
                $data = array_slice($data, $value);
            }
            if ($data) {
                foreach ($data as $data_id) {
                    $model = new PostModel();
                    $model->Where('id', $data_id['id'])->delete();
                }
                $response = [
                    'status'   => 200,
                    'error'    => null,
                    'message' => 'Post Deleted'
                ];
                return $this->respondDeleted($response);
            } else {
                $response = [
                    'status'   => 200,
                    'error'    => null,
                    'message' => 'No Post Found'
                ];
                return $this->respondDeleted($response);
            }
        }
    }

    // get single product
    public function show($id = null)
    {
        $model = new PostModel();
        $data = $model->getWhere(['id' => $id])->getResult();
        if ($data) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('No Data Found with id ' . $id);
        }
    }

    // create a product
    public function create()
    {
        for ($i = 0; $i < 1000; $i++) {
            $model = new PostModel();
            $string = $this->generateRandomString(5);
            $paragraph = $this->generateRandomString(50);
            $data = [
                'title' => $string,
                'description' => $paragraph
            ];

            $model->insert($data);
        }

        $response = [
            'status'   => 201,
            'error'    => null,
            'message' => [
                'success' => 'Data Saved'
            ]
        ];
        return $this->respondCreated($response);
    }

    // update product
    public function update($id = null)
    {
        $model = new PostModel();
        $input = $this->request->getRawInput();
        $data = [
            'title' => $input['title'],
            'description' => $input['description']
        ];
        $model->update($id, $data);
        $response = [
            'status'   => 200,
            'error'    => null,
            'message' => [
                'success' => 'Data Updated'
            ]
        ];
        return $this->respond($response);
    }

    // delete product
    public function delete($id = null)
    {

        $id = 2000;
        $model = new PostModel();
        $data = $model->select('id')->findAll();
        if ($id < count($data)) {
            $data = array_slice($data, $id);
        }

        if ($data) {
            foreach ($data as $data_id) {
                // var_dump($data_id['id']);
                $model = new PostModel();
                $model->Where('id', $data_id['id'])->delete();
            }
            //   exit;
            $response = [
                'status'   => 200,
                'error'    => null,
                'message' => [
                    'success' => 'Data Deleted'
                ]
            ];
            return $this->respondDeleted($response);
        } else {
            return $this->failNotFound('No Data Found with id ' . $id);
        }
    }

    function generateRandomString($length = 25)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    //usage 


}
