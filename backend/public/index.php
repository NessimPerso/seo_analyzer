<?php
/**
 * Point d'entrée API SEO Content Analyzer
 * Gère les requêtes d'analyse de contenu
 */

// Headers CORS
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Préflight CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Seule méthode autorisée : POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Méthode non autorisée. Utilisez POST.']);
    exit;
}

// Charger la configuration
require_once __DIR__ . '/../src/Config/env.php';

// Charger le contrôleur
require_once __DIR__ . '/../src/Controller/AnalyzeController.php';

// Exécuter l'analyse
try {
    $controller = new AnalyzeController();
    $controller->analyze();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Erreur serveur',
        'message' => $e->getMessage()
    ]);
}