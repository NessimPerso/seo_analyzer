<?php
/**
 * Routeur pour le serveur PHP de développement
 * Redirige toutes les requêtes vers index.php sauf les fichiers statiques
 */

$file = __DIR__ . $_SERVER["REQUEST_URI"];

// Si c'est un fichier ou dossier statique, le servir
if (is_file($file) || is_dir($file)) {
    return false;
}

// Sinon, traiter par index.php
require_once __DIR__ . '/index.php';