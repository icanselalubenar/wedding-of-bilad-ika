<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');

$file = __DIR__ . '/assets/data/wishes.json';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $raw = file_get_contents('php://input');
    $input = json_decode($raw, true);

    if ($input && !empty(trim($input['name'])) && !empty(trim($input['message']))) {
        $data = [];
        if (file_exists($file)) {
            $content = file_get_contents($file);
            $parsed = json_decode($content, true);
            if (is_array($parsed)) {
                $data = $parsed;
            }
        }

        $newEntry = [
            'id' => time() * 1000,
            'name' => trim($input['name']),
            'message' => trim($input['message']),
            'date' => isset($input['date']) ? $input['date'] : date('d M Y, H:i')
        ];

        array_unshift($data, $newEntry);
        file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        echo json_encode(['status' => 'success', 'data' => $data]);
        exit;
    }

    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Nama dan Pesan wajib diisi']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (file_exists($file)) {
        readfile($file);
    } else {
        echo '[]';
    }
    exit;
}
