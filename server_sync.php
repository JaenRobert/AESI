<?php
// server_sync.php
// Tar emot POST-data och sparar till fil (eller databas)
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Endast POST tillåtet.']);
    exit;
}

$data = file_get_contents('php://input');
if (!$data) {
    http_response_code(400);
    echo json_encode(['error' => 'Ingen data mottagen.']);
    exit;
}

$timestamp = date('Ymd_His');
$file = __DIR__ . "/memory_sync_{$timestamp}.json";
file_put_contents($file, $data);

echo json_encode(['status' => 'ok', 'file' => basename($file)]);
