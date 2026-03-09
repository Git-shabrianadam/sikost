<?php
#sesuaikan dengan modelnya
require_once __DIR__ . '/../models/pembayaran_model.php';

class PembayaranController {

    private $model;

    public function __construct($db)
    {
        $this->model = new PembayaranModel($db);
    }

    public function index()
    {

        $data = $this->model->getAll();

        header('Content-Type: application/json');

        echo json_encode([
            "status" => "success",
            "data" => $data
        ]);
    }
}
?>