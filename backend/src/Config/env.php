<?php
/**
 * Configuration et variables d'environnement
 */

// Charger le fichier .env
$envFile = __DIR__ . '/../../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }
}

// Configuration par défaut
define('API_KEY_OPENAI', $_ENV['OPENAI_API_KEY'] ?? '');
define('API_TIMEOUT', 30);
define('MAX_TEXT_LENGTH', 5000);
