<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

// Verificar si es solicitud POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(['error' => 'Método no permitido']));
}

// Obtener datos
$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
    http_response_code(400);
    die(json_encode(['error' => 'Datos no válidos']));
}

// Directorio de modelos
$modelDir= __DIR__ . '/modelo';
if(!file_exists($modelDir)) {
    mkdir($modelDir, 0777, true);
}

// Guardar wordIndex
$result=file_put_contents($modelDir.'/wordIndex.json',json_encode($data));

if($result===false){
    http_response_code(500);
    die(json_encode(['error'=>'Error al guardar archivo']));
}
echo json_encode(['success' => true]);
?>