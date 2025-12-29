<?php
/**
 * Point d'entrée API SEO Content Analyzer
 * Gère les requêtes d'analyse de contenu
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Configuration
require_once '../src/Config/env.php';

// Routeur simple
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Routes
if ($method === 'POST' && strpos($path, '/analyze') !== false) {
    require_once '../src/Controller/AnalyzeController.php';
    $controller = new AnalyzeController();
    $controller->analyze();
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Endpoint not found']);
}
