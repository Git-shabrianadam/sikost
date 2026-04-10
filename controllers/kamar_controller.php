<?php

require_once __DIR__ . '/../models/kamar_model.php';
class KamarController {

    private $model;

    public function __construct($db)
    {
        $this->model = new KamarModel($db);
    }

    # GET /kamar
    public function index()
    {
        $data = $this->model->getAll();

        header("Content-Type: application/json");

        echo json_encode([
            "status" => "success",
            "data" => $data
        ]);
    }


    # GET /kamar/show?id=1
    public function show()
    {
        header("Content-Type: application/json");

        $id = $_GET["id"] ?? null;

        if (!$id) {
            echo json_encode([
                "status" => "error",
                "message" => "id_kamar diperlukan"
            ]);
            return;
        }

        $data = $this->model->getById($id);

        if (!$data) {   
            echo json_encode([
                "status" => "error",
                "message" => "Data tidak ditemukan"
            ]);
            return;
        }

        echo json_encode([
            "status" => "success",
            "data" => $data
        ]);
    }

    # INSERT kamar method POST /kamar/store
    public function store()
    {
        header("Content-Type: application/json");
        $input = json_decode(file_get_contents("php://input"), true);
        $result = $this->model->create($input);

        echo json_encode([
            "status" => "success",
            "data" => $result
        ]);
    }

    # PUT /kamar/update?id=1
    public function update()
    {
        $id = $_GET["id"] ?? null;
        if (!$id) {
            echo json_encode([
                "status" => "error",
                "message" => "id_kamar diperlukan"
            ]);
            return;
        }

        $input = json_decode(file_get_contents("php://input"), true);
        $result = $this->model->update($id, $input);

        echo json_encode([
            "status" => "success",
            "data" => $result
        ]);
    }


    # DELETE /kamar/delete?id=1
    public function delete()
    {
        $id = $_GET["id"] ?? null;

        if (!$id) {
            echo json_encode([
                "status" => "error",
                "message" => "id_kamar diperlukan"
            ]);
            return;
        }

        $this->model->delete($id);

        echo json_encode([
            "status" => "success",
            "message" => "Kamar berhasil dihapus"
        ]);
    }

}
?>