<?php
// Script de test pour la connexion à la base de données

// Définir les constantes nécessaires
define('APPROOT', __DIR__ . '/app');
define('URLROOT', 'http://localhost/etud');
define('SITENAME', 'StageFinder');

// Activer l'affichage des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Test de connexion à la base de données</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            line-height: 1.6;
        }
        h1, h2, h3 {
            color: #333;
        }
        .success {
            color: green;
            background-color: rgba(0, 128, 0, 0.1);
            padding: 10px;
            border-radius: 5px;
        }
        .error {
            color: red;
            background-color: rgba(255, 0, 0, 0.1);
            padding: 10px;
            border-radius: 5px;
        }
        .info {
            color: #0066cc;
            background-color: rgba(0, 102, 204, 0.1);
            padding: 10px;
            border-radius: 5px;
        }
        pre {
            background-color: #f8f8f8;
            padding: 10px;
            border-radius: 5px;
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>";

echo "<h1>Test de connexion à la base de données</h1>";

// Vérifier si les extensions PDO sont installées
echo "<h2>Vérification des extensions PHP</h2>";
if (extension_loaded('PDO')) {
    echo "<p class='success'>✓ Extension PDO installée</p>";
    
    echo "<h3>Drivers PDO disponibles:</h3>";
    $pdoDrivers = PDO::getAvailableDrivers();
    
    if (in_array('mysql', $pdoDrivers)) {
        echo "<p class='success'>✓ Driver PDO MySQL disponible</p>";
    } else {
        echo "<p class='error'>✗ Driver PDO MySQL NON disponible. Vous devez installer l'extension pdo_mysql.</p>";
    }
    
    echo "<p>Liste complète des drivers:</p>";
    echo "<ul>";
    foreach ($pdoDrivers as $driver) {
        echo "<li>$driver</li>";
    }
    echo "</ul>";
} else {
    echo "<p class='error'>✗ Extension PDO NON installée. Vous devez installer PDO.</p>";
}

// Récupérer les paramètres de connexion depuis config.php
echo "<h2>Paramètres de connexion</h2>";
try {
    if (file_exists(APPROOT . '/config/config.php')) {
        include APPROOT . '/config/config.php';
        echo "<p class='success'>✓ Fichier config.php chargé avec succès</p>";
        
        if (defined('DB_HOST') && defined('DB_USER') && defined('DB_PASS') && defined('DB_NAME')) {
            echo "<table>";
            echo "<tr><th>Paramètre</th><th>Valeur</th></tr>";
            echo "<tr><td>Hôte</td><td>" . DB_HOST . "</td></tr>";
            echo "<tr><td>Utilisateur</td><td>" . DB_USER . "</td></tr>";
            echo "<tr><td>Mot de passe</td><td>******</td></tr>";
            echo "<tr><td>Base de données</td><td>" . DB_NAME . "</td></tr>";
            echo "</table>";
        } else {
            echo "<p class='error'>✗ Les constantes de base de données ne sont pas toutes définies dans config.php</p>";
        }
    } else {
        echo "<p class='error'>✗ Fichier config.php introuvable à " . APPROOT . '/config/config.php' . "</p>";
        
        // Définir manuellement pour le test
        define('DB_HOST', 'localhost');
        define('DB_USER', 'root');
        define('DB_PASS', '');
        define('DB_NAME', 'website');
        
        echo "<p class='info'>ℹ️ Utilisation de paramètres par défaut pour le test:</p>";
        echo "<table>";
        echo "<tr><th>Paramètre</th><th>Valeur</th></tr>";
        echo "<tr><td>Hôte</td><td>" . DB_HOST . "</td></tr>";
        echo "<tr><td>Utilisateur</td><td>" . DB_USER . "</td></tr>";
        echo "<tr><td>Mot de passe</td><td>******</td></tr>";
        echo "<tr><td>Base de données</td><td>" . DB_NAME . "</td></tr>";
        echo "</table>";
    }
} catch (Exception $e) {
    echo "<p class='error'>✗ Erreur lors du chargement de config.php: " . $e->getMessage() . "</p>";
}

// Tester la connexion à la base de données
echo "<h2>Test de connexion</h2>";
try {
    if (class_exists('PDO')) {
        require_once APPROOT . '/core/Database.php';
        
        // Tenter de se connecter directement via PDO pour diagnostic
        echo "<h3>Test direct via PDO:</h3>";
        try {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
            $options = [
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ];
            
            $pdoConnection = new PDO($dsn, DB_USER, DB_PASS, $options);
            echo "<p class='success'>✓ Connexion PDO directe réussie</p>";
            
            // Vérifier la version de MySQL
            $stmt = $pdoConnection->query('SELECT VERSION() as version');
            $version = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "<p>Version MySQL: " . $version['version'] . "</p>";
            
        } catch (PDOException $e) {
            echo "<p class='error'>✗ Erreur de connexion PDO directe: " . $e->getMessage() . "</p>";
        }
        
        // Tester via la classe Database
        echo "<h3>Test via la classe Database:</h3>";
        try {
            $db = new Database();
            echo "<p class='success'>✓ Connexion via la classe Database réussie</p>";
            
            // Test d'une requête simple
            $db->query("SHOW TABLES");
            $tables = $db->resultSet();
            
            echo "<p>Tables dans la base de données " . DB_NAME . ":</p>";
            echo "<table>";
            echo "<tr><th>Nom de la table</th></tr>";
            
            if (count($tables) > 0) {
                foreach ($tables as $table) {
                    $tableName = reset($table); // Récupérer la première colonne (nom de la table)
                    echo "<tr><td>$tableName</td></tr>";
                }
            } else {
                echo "<tr><td>Aucune table trouvée</td></tr>";
            }
            echo "</table>";
            
        } catch (Exception $e) {
            echo "<p class='error'>✗ Erreur lors de l'utilisation de la classe Database: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p class='error'>✗ La classe PDO n'existe pas. Impossible de tester la connexion.</p>";
    }
} catch (Exception $e) {
    echo "<p class='error'>✗ Erreur générale: " . $e->getMessage() . "</p>";
}

echo "<h2>Conclusion</h2>";
echo "<p>Si tous les tests sont passés, votre configuration de base de données est correcte.</p>";
echo "<p>Si vous rencontrez des erreurs, vérifiez:</p>";
echo "<ul>";
echo "<li>Que le serveur MySQL est en cours d'exécution</li>";
echo "<li>Que les informations de connexion sont correctes</li>";
echo "<li>Que la base de données existe</li>";
echo "<li>Que PHP a l'extension PDO MySQL installée</li>";
echo "</ul>";

echo "<p><a href='test.php'>Retour au script de test principal</a></p>";
echo "</body></html>"; 