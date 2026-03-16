<?php

require_once __DIR__ . '/config/db.php';

$database = new Database();
$db = $database->getConnection();

#Index sebagai router karena controller dan model tidak sentralized
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$request = str_replace('/sikost', '', $request);
$request = str_replace('index.php', '', $request);
$request = trim($request, '/');

$segments = explode('/', $request);

$controllerName = !empty($segments[0]) ? $segments[0] : 'pembayaran';
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
            "message" => "Route pada switchc-case tidak sesuai"
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