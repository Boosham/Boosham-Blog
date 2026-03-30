<?php
// API de búsqueda para el header
require_once '../config.php';

header('Content-Type: application/json');

$query = isset($_GET['q']) ? trim($_GET['q']) : '';

if(strlen($query) < 1) {
    echo json_encode([]);
    exit;
}

$stmt = $conn->prepare("SELECT id, titulo, imagen_url, descripcion FROM programas WHERE titulo LIKE ? ORDER BY id DESC LIMIT 6");
$searchTerm = '%' . $query . '%';
$stmt->bind_param('s', $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$programas = [];
while($row = $result->fetch_assoc()) {
    $programas[] = [
        'id' => $row['id'],
        'titulo' => $row['titulo'],
        'imagen_url' => $row['imagen_url'] ?: 'https://via.placeholder.com/40x40/151515/888888.png?text=???',
        'descripcion' => mb_substr($row['descripcion'] ?: 'Sin descripción', 0, 60) . '...'
    ];
}

echo json_encode($programas);
