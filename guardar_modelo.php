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
if (!$data || !isset($data['modelTopology'])) {
    http_response_code(400);
    die(json_encode(['error' => 'Datos del modelo no válidos']));
}

// Directorio de modelos
$modelDir = __DIR__ . '/modelo/tfjs_model';
if (!file_exists($modelDir)) {
    mkdir($modelDir, 0777, true);
}

// Guardar model.json
file_put_contents($modelDir . '/model.json', json_encode($data['modelTopology']));

// Guardar archivos binarios (weights)
foreach ($data['weightSpecs'] as $i => $spec) {
    $filename = $data['weightDataPaths'][$i];
    $binData = base64_decode($data['weightData'][$i]);
    file_put_contents($modelDir . '/' . $filename, $binData);
}

echo json_encode(['success' => true]);
?>