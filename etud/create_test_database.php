<?php
// Activer l'affichage des erreurs
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>Test de connexion à la base de données MySQL</h1>";

// Afficher la version de PHP
echo "<p>Version PHP: " . phpversion() . "</p>";

// Vérifier si PDO est installé
echo "<p>PDO est " . (extension_loaded('pdo') ? "installé" : "non installé") . "</p>";

// Vérifier les drivers PDO disponibles
echo "<p>Drivers PDO disponibles: ";
if (class_exists('PDO')) {
    $drivers = PDO::getAvailableDrivers();
    if (empty($drivers)) {
        echo "aucun";
    } else {
        echo implode(', ', $drivers);
    }
} else {
    echo "PDO n'est pas disponible";
}
echo "</p>";

// Paramètres de connexion
$host = 'localhost';
$dbname = 'website';
$username = 'root';
$password = '';

// Tester la connexion avec PDO
echo "<h2>Test de connexion avec PDO</h2>";
try {
    $dsn = "mysql:host=$host;dbname=$dbname";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    $pdo = new PDO($dsn, $username, $password, $options);
    echo "<p style='color:green'>Connexion PDO réussie!</p>";
    
    // Vérifier si les tables existent
    echo "<h2>Vérification des tables</h2>";
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<p>Tables trouvées: " . implode(', ', $tables) . "</p>";
    
    // Vérifier la table student
    if (in_array('student', $tables)) {
        echo "<h3>Structure de la table student</h3>";
        $stmt = $pdo->query("DESCRIBE student");
        echo "<table border='1'><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        while ($row = $stmt->fetch()) {
            echo "<tr>";
            foreach ($row as $key => $value) {
                echo "<td>" . ($value ?? "NULL") . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
        
        // Vérifier s'il y a des données
        $stmt = $pdo->query("SELECT COUNT(*) FROM student");
        $count = $stmt->fetchColumn();
        echo "<p>Nombre d'étudiants: $count</p>";
        
        if ($count > 0) {
            $stmt = $pdo->query("SELECT * FROM student LIMIT 1");
            $student = $stmt->fetch();
            echo "<p>Premier étudiant: </p>";
            echo "<pre>";
            print_r($student);
            echo "</pre>";
        }
    }
    
    // Vérifier la table application
    if (in_array('application', $tables)) {
        echo "<h3>Structure de la table application</h3>";
        $stmt = $pdo->query("DESCRIBE application");
        echo "<table border='1'><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        while ($row = $stmt->fetch()) {
            echo "<tr>";
            foreach ($row as $key => $value) {
                echo "<td>" . ($value ?? "NULL") . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color:red'>Erreur de connexion PDO: " . $e->getMessage() . "</p>";
}

// Tester la connexion avec mysqli
echo "<h2>Test de connexion avec mysqli</h2>";
if (extension_loaded('mysqli')) {
    try {
        $mysqli = new mysqli($host, $username, $password, $dbname);
        if ($mysqli->connect_error) {
            throw new Exception("Connexion mysqli échouée: " . $mysqli->connect_error);
        }
        echo "<p style='color:green'>Connexion mysqli réussie!</p>";
        $mysqli->close();
    } catch (Exception $e) {
        echo "<p style='color:red'>Erreur de connexion mysqli: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p style='color:red'>L'extension mysqli n'est pas installée</p>";
}

echo "<p>Fin des tests.</p>"; 