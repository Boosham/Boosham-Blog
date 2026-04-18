<?php
// actualizar_cache.php - Cache background worker para sincronizar reacciones de Giscus a DB
require_once '../config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$estrellas = isset($_GET['estrellas']) ? (float)$_GET['estrellas'] : 0;

if ($id > 0 && $estrellas >= 0) {
    // Guardamos el promedio en la base de datos para la cache del catálogo
    $stmt = $conn->prepare("UPDATE programas SET estrellas_cache = ? WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("di", $estrellas, $id);
        $stmt->execute();
        $stmt->close();
        echo json_encode(['status' => 'success', 'message' => 'Cache updated']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Statement preparation failed']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid parameters']);
}
?>
