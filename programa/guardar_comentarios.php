<?php
require_once '../config.php';

// Check if parameters are provided
if (!isset($_GET['id']) || !isset($_GET['conteo'])) {
    http_response_code(400);
    die(json_encode(['error' => 'Missing parameters: id and conteo are required']));
}

$id = (int)$_GET['id'];
$conteo = (int)$_GET['conteo'];

if (isset($conn)) {
    $stmt = $conn->prepare("UPDATE programas SET conteo_reviews = ? WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("ii", $conteo, $id);
        $success = $stmt->execute();
        $stmt->close();
        
        if ($success) {
            echo json_encode(['status' => 'success', 'message' => 'Comment count updated']);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Failed to update database']);
        }
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database prepare failed']);
    }
} else {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Database connection missing']);
}
?>
