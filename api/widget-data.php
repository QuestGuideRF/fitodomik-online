<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
if (!isset($GLOBALS['env_config'])) {
    $GLOBALS['env_config'] = require __DIR__ . '/../config/env.php';
}
$config = $GLOBALS['env_config'];
$api_key = isset($_GET['api_key']) ? $_GET['api_key'] : (isset($_SERVER['HTTP_X_API_KEY']) ? $_SERVER['HTTP_X_API_KEY'] : '');
if (!isset($config['WIDGET_API_KEY']) || $api_key !== $config['WIDGET_API_KEY']) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'error' => 'invalid_api_key']);
    exit;
}
require_once '../config/database.php';
try {
    $user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 1;
    $stmt = $pdo->prepare("
        SELECT
            temperature,
            humidity,
            soil_moisture,
            co2,
            pressure,
            curtains_state,
            lamp_state,
            created_at
        FROM sensor_data
        WHERE user_id = ?
        ORDER BY created_at DESC
        LIMIT 1
    ");
    $stmt->execute([$user_id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$data) {
        $data = [
            'temperature' => 25.0,
            'humidity' => 60.0,
            'soil_moisture' => 45.0,
            'co2' => 450,
            'pressure' => 760.0,
            'curtains_state' => 1,
            'lamp_state' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ];
    }
    $response = [
        'ok' => true,
        'data' => [
            'temperature' => [
                'value' => number_format((float)$data['temperature'], 1, '.', ''),
                'unit' => '°C',
                'icon' => '🌡️'
            ],
            'humidity' => [
                'value' => number_format((float)$data['humidity'], 1, '.', ''),
                'unit' => '%',
                'icon' => '💧'
            ],
            'soil_moisture' => [
                'value' => number_format((float)$data['soil_moisture'], 1, '.', ''),
                'unit' => '%',
                'icon' => '🌱'
            ],
            'co2' => [
                'value' => (int)$data['co2'],
                'unit' => 'ppm',
                'icon' => '🌍'
            ],
            'pressure' => [
                'value' => number_format((float)$data['pressure'], 1, '.', ''),
                'unit' => 'мм.рт.ст',
                'icon' => '🌬️'
            ],
            'curtains' => [
                'value' => (int)$data['curtains_state'] ? 'Открыты' : 'Закрыты',
                'state' => (int)$data['curtains_state'],
                'icon' => '🚪'
            ],
            'lamp' => [
                'value' => (int)$data['lamp_state'] ? 'Включено' : 'Выключено',
                'state' => (int)$data['lamp_state'],
                'icon' => '💡'
            ]
        ],
        'updated_at' => $data['created_at'],
        'timestamp' => strtotime($data['created_at'])
    ];
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'error' => 'server_error',
        'message' => 'Ошибка получения данных'
    ], JSON_UNESCAPED_UNICODE);
}
?>