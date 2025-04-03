<?php
// Script de test pour EtudiantModel

// Définir les constantes nécessaires
define('APPROOT', __DIR__ . '/app');
define('URLROOT', 'http://localhost/etud');
define('SITENAME', 'StageFinder');

// Démarrer la session
session_start();

// Simuler un utilisateur connecté
$_SESSION['user_id'] = 1;
$_SESSION['user_email'] = 'test@example.com';

// Activer l'affichage des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Test du EtudiantModel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
            line-height: 1.6;
        }
        h1, h2, h3 {
            color: #333;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
        .method-test {
            background-color: #f8f9fa;
            padding: 15px;
            border-left: 4px solid #007bff;
            margin-bottom: 20px;
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
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>";

echo "<h1>Test du EtudiantModel</h1>";

// Charger les classes nécessaires
try {
    require_once APPROOT . '/core/Database.php';
    require_once APPROOT . '/core/Model.php';
    require_once APPROOT . '/models/EtudiantModel.php';
    
    echo "<p class='success'>✓ Classes chargées avec succès</p>";
    
    // Instancier le modèle
    $etudiantModel = new EtudiantModel();
    echo "<p class='success'>✓ EtudiantModel instancié avec succès</p>";
    
    // Tester getEtudiantByUserId()
    echo "<div class='method-test'>";
    echo "<h2>Test de getEtudiantByUserId()</h2>";
    try {
        $userId = $_SESSION['user_id']; // User ID de la session
        $etudiant = $etudiantModel->getEtudiantByUserId($userId);
        
        if ($etudiant) {
            echo "<p class='success'>✓ La méthode getEtudiantByUserId($userId) a fonctionné</p>";
            echo "<h3>Données de l'étudiant:</h3>";
            echo "<pre>";
            print_r($etudiant);
            echo "</pre>";
        } else {
            echo "<p class='error'>✗ Aucun étudiant trouvé avec l'ID utilisateur: $userId</p>";
        }
    } catch (Exception $e) {
        echo "<p class='error'>✗ Erreur lors de l'appel de getEtudiantByUserId(): " . $e->getMessage() . "</p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
    echo "</div>";
    
    // Tester getEtudiantByEmail()
    echo "<div class='method-test'>";
    echo "<h2>Test de getEtudiantByEmail()</h2>";
    try {
        $email = $_SESSION['user_email']; // Email de la session
        $etudiant = $etudiantModel->getEtudiantByEmail($email);
        
        if ($etudiant) {
            echo "<p class='success'>✓ La méthode getEtudiantByEmail('$email') a fonctionné</p>";
            echo "<h3>Données de l'étudiant:</h3>";
            echo "<pre>";
            print_r($etudiant);
            echo "</pre>";
        } else {
            echo "<p class='error'>✗ Aucun étudiant trouvé avec l'email: $email</p>";
        }
    } catch (Exception $e) {
        echo "<p class='error'>✗ Erreur lors de l'appel de getEtudiantByEmail(): " . $e->getMessage() . "</p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
    echo "</div>";
    
    // Tester getAllEtudiants()
    echo "<div class='method-test'>";
    echo "<h2>Test de getAllEtudiants()</h2>";
    try {
        $etudiants = $etudiantModel->getAllEtudiants();
        
        echo "<p class='success'>✓ La méthode getAllEtudiants() a fonctionné</p>";
        echo "<p>Nombre d'étudiants récupérés: " . count($etudiants) . "</p>";
        
        if (!empty($etudiants)) {
            echo "<h3>Échantillon des étudiants (3 premiers):</h3>";
            echo "<table>";
            echo "<tr>
                    <th>ID</th>
                    <th>Prénom</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Niveau</th>
                  </tr>";
            
            $sample = array_slice($etudiants, 0, 3);
            foreach ($sample as $etud) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($etud->id_student) . "</td>";
                echo "<td>" . htmlspecialchars($etud->first_name) . "</td>";
                echo "<td>" . htmlspecialchars($etud->last_name) . "</td>";
                echo "<td>" . htmlspecialchars($etud->email) . "</td>";
                echo "<td>" . htmlspecialchars($etud->level) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    } catch (Exception $e) {
        echo "<p class='error'>✗ Erreur lors de l'appel de getAllEtudiants(): " . $e->getMessage() . "</p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
    echo "</div>";
    
    // Tester getEtudiantById()
    echo "<div class='method-test'>";
    echo "<h2>Test de getEtudiantById()</h2>";
    try {
        // Utiliser l'ID 1 comme exemple (ajuster si nécessaire)
        $etudiantId = 1;
        $etudiant = $etudiantModel->getEtudiantById($etudiantId);
        
        if ($etudiant) {
            echo "<p class='success'>✓ La méthode getEtudiantById($etudiantId) a fonctionné</p>";
            echo "<h3>Données de l'étudiant:</h3>";
            echo "<pre>";
            print_r($etudiant);
            echo "</pre>";
        } else {
            echo "<p class='error'>✗ Aucun étudiant trouvé avec l'ID: $etudiantId</p>";
        }
    } catch (Exception $e) {
        echo "<p class='error'>✗ Erreur lors de l'appel de getEtudiantById(): " . $e->getMessage() . "</p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
    echo "</div>";
    
    // Vérifier si la méthode updateEtudiant existe et peut être testée
    echo "<div class='method-test'>";
    echo "<h2>Test de updateEtudiant()</h2>";
    if (method_exists($etudiantModel, 'updateEtudiant')) {
        echo "<p>La méthode updateEtudiant() existe, mais elle n'est pas testée ici pour éviter de modifier les données réelles.</p>";
        echo "<p>Pour tester cette méthode en toute sécurité, il faudrait d'abord créer un environnement de test isolé ou utiliser des données de test.</p>";
    } else {
        echo "<p class='error'>✗ La méthode updateEtudiant() n'existe pas dans la classe EtudiantModel</p>";
    }
    echo "</div>";
    
} catch (Exception $e) {
    echo "<p class='error'>✗ Erreur générale: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<p><a href='test.php'>Retour au script de test principal</a></p>";
echo "</body></html>"; 