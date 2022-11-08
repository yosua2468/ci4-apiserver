<?php

namespace App\Controllers;

use App\Models\Modelmahasiswa;
use App\Controllers\BaseController;
use CodeIgniter\RESTful\ResourceController;

class Mahasiswa extends ResourceController
{
    public function index()
    {
        $modelMhs = new Modelmahasiswa();
        $data = $modelMhs->findAll();
        $response = [
            'status' => 200,
            'error' => "false",
            'message' => '',
            'totaldata' => count($data),
            'data' => $data,
        ];
        
        return $this->respond($response, 200);
    }

    public function show($cari = null)
    {
        $modelMhs = new Modelmahasiswa();

        $data = $modelMhs->orLike('mhsnobp', $cari)->orLike('mhsnama', $cari)->get()->getResult();
            
            if(count($data) > 1) {
                $response = [
                    'status' => 200,
                    'error' => "false",
                    'message' => '',
                    'totaldata' => count($data),
                    'data' => $data,
                ];
                
                return $this->respond($response, 200);

            }else if(count($data) == 1) {
                $response = [
                    'status' => 200,
                    'error' => "false",
                    'message' => '',
                    'totaldata' => count($data),
                    'data' => $data,
                ];

                return $this->respond($response, 200);
            
            }else {
                return $this->failNotFound('maaf data ' . $cari . ' tidak ditemukan');
            }
    }

    public function create()
    {
        $modelMhs = new Modelmahasiswa();
        $nobp = $this->request->getPost("mhsnobp");
        $nama = $this->request->getPost("mhsnama");
        $alamat = $this->request->getPost("mhsalamat");
        $prodi = $this->request->getPost("prodinama");
        $tgllahir = $this->request->getPost("mhstgllhr");

        $validation = \Config\Services::validation();

        $valid = $this->validate([
            'mhsnobp' => [
                'rules' => 'is_unique[mahasiswa.mhsnobp]',
                'label' => 'Nomor Induk Mahasiswa',
                'errors' => [
                    'is_unique' => "{field} sudah ada"
                ]
            ]
        ]);

        if(!$valid){
            $response = [
                'status' => 404,
                'error' => true,
                'message' => $validation->getError("mhsnobp"),
            ];
            
            return $this->respond($response, 404);
        }else {
            $modelMhs->insert([
                'mhsnobp' => $nobp,
                'mhsnama' => $nama,
                'mhsalamat' => $alamat,
                'prodinama' => $prodi,
                'mhstgllhr' => $tgllahir,
        ]);

        $response = [
            'status' => 201,
            'error' => "false",
            'message' => "Data berhasil disimpan"
        ];

        return $this->respond($response, 201);
        }
    }


    public function update($nobp = null)
    {
        $model = new Modelmahasiswa();
        
        $data = [
            'mhsnama' => $this->request->getVar("mhsnama"),
            'mhsalamat' => $this->request->getVar("mhsalamat"),
            'prodinama' => $this->request->getVar("prodinama"),
            'mhstgllhr' => $this->request->getVar("mhstgllhr"),
        ];
        $data = $this->request->getRawInput();
        $model->update($nobp, $data);
        $response = [
            'status' => 200,
            'error' => null,
            'message' => "Data Anda dengan NIM $nobp berhasil dibaharukan"
        ];
        return $this->respond($response);
    }

    public function delete($nobp = null)
    {
        $modelMhs = new Modelmahasiswa();
        
        $cekData = $modelMhs->find($nobp);
        if($cekData) {
            $modelMhs->delete($nobp);
            $response = [
                'status' => 200,
                'error' => null,
                'message' => "Selamat data sudah berhasil dihapus maksimal"
            ];
            return $this->respondDeleted($response);
        }else {
            return $this->failNotFound('Data tidak ditemukan kembali');
        }
    }

}
