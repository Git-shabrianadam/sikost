<?php

require_once __DIR__ . '/config/db.php';

$db = getDB();

#Index sebagai router karena controller dan model tidak sentralized
$request = $_SERVER['REQUEST_URI'];
$request = trim($request, '/');

$segments = explode('/', $request);

$controllerName = $segments[0] ?? '';
$method = $segments[1] ?? 'index';

switch ($controllerName) {

    case 'pembayaran':
        require_once __DIR__ . '/controllers/pembayaran_controller.php';
        $controller = new PembayaranController($db);
        break;

    case 'kamar':
        require_once __DIR__ . '/controllers/kamar_controller.php';
        $controller = new KamarController($db);
        break;

    case 'penghuni':
        require_once __DIR__ . '/controllers/penghuni_controller.php';
        $controller = new PenghuniController($db);
        break;

    default:
        header("Content-Type: application/json");
        echo json_encode([
            "status" => "error",
            "message" => "Route tidak sesuai"
        ]);
        exit;
}

if (!method_exists($controller, $method)) {

    header("Content-Type: application/json");
    echo json_encode([
        "status" => "error",
        "message" => "Method tidak sesuai"
    ]);
    exit;

}

$controller->$method();
?>