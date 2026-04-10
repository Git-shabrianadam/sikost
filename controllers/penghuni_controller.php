<?php

require_once __DIR__ . '/../models/penghuni_model.php';
class PenghuniController {
    private $model;

    public function __construct($db)
    {
        $this->model = new PenghuniModel($db);
    }

    # GET /penghuni
    public function index()
    {
        header("Content-Type: application/json");

        $data = $this->model->getAll();

        echo json_encode([
            "status" => "success",
            "data" => $data
        ]);
    }


    # GET /penghuni/show?id=1
    public function show()
    {
        header("Content-Type: application/json");

        $id = $_GET["id"] ?? null;

        if (!$id) {

            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "id_penghuni diperlukan"
            ]);
            return;
        }

        $data = $this->model->getById($id);

        if (!$data) {

            http_response_code(404);
            echo json_encode([
                "status" => "error",
                "message" => "Data penghuni tidak ditemukan"
            ]);
            return;
        }

        echo json_encode([
            "status" => "success",
            "data" => $data
        ]);
    }


    # POST /penghuni/store
    public function store()
    {
        header("Content-Type: application/json");

        $input = json_decode(
            file_get_contents("php://input"),
            true
        );

        # Required fields validation
        $required = [
            "nama_penghuni",
            "telp",
            "nik",
            "alamat",
            "nama_kamar",
            "tanggal_masuk"
        ];

        foreach ($required as $field) {

            if (empty($input[$field])) {
                http_response_code(400);
                echo json_encode([
                    "status" => "error",
                    "message" => "$field wajib diisi"
                ]);

                return;
            }
        }

        $result = $this->model->create($input);

        if (isset($result['error'])) {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => $result['error']
            ]);

            return;
        }

        echo json_encode([
            "status" => "success",
            "data" => $result
        ]);
    }


    # PUT /penghuni/update?id=1
    public function update()
    {
        header("Content-Type: application/json");

        $id = $_GET["id"] ?? null;

        if (!$id) {

            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "id_penghuni diperlukan"
            ]);

            return;
        }

        $input = json_decode(
            file_get_contents("php://input"),
            true
        );

        $result = $this->model->update($id, $input);

        if (!$result) {

            http_response_code(404);
            echo json_encode([
                "status" => "error",
                "message" => "Gagal update penghuni"
            ]);
            return;
        }

        echo json_encode([
            "status" => "success",
            "data" => $result
        ]);
    }


    # DELETE /penghuni/delete?id=1
    public function delete()
    {
        header("Content-Type: application/json");
        $id = $_GET["id"] ?? null;

        if (!$id) {

            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "id_penghuni diperlukan"
            ]);
            return;
        }

        $result = $this->model->delete($id);
        if (isset($result['error'])) {

            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => $result['error']
            ]);

            return;
        }

        echo json_encode([
            "status" => "success",
            "message" => "Penghuni berhasil dihapus"
        ]);
    }
}
?>