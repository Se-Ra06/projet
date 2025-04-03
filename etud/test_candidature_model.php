<?php
// Script de test pour CandidatureModel

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
    <title>Test du CandidatureModel</title>
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

echo "<h1>Test du CandidatureModel</h1>";

// Charger les classes nécessaires
try {
    require_once APPROOT . '/core/Database.php';
    require_once APPROOT . '/core/Model.php';
    require_once APPROOT . '/models/CandidatureModel.php';
    require_once APPROOT . '/models/EtudiantModel.php';
    
    echo "<p class='success'>✓ Classes chargées avec succès</p>";
    
    // Instancier les modèles
    $candidatureModel = new CandidatureModel();
    $etudiantModel = new EtudiantModel();
    echo "<p class='success'>✓ CandidatureModel instancié avec succès</p>";
    
    // Récupérer l'ID de l'étudiant connecté
    $etudiant = $etudiantModel->getEtudiantByUserId($_SESSION['user_id']);
    $idEtudiant = $etudiant ? $etudiant->id_student : null;
    
    if ($idEtudiant) {
        echo "<p class='success'>✓ ID étudiant trouvé: $idEtudiant</p>";
    } else {
        echo "<p class='error'>✗ Impossible de trouver l'ID étudiant pour l'utilisateur {$_SESSION['user_id']}</p>";
        echo "<p>Les tests suivants peuvent échouer si l'étudiant n'est pas trouvé</p>";
    }
    
    // Tester getCandidaturesByStudentId()
    echo "<div class='method-test'>";
    echo "<h2>Test de getCandidaturesByStudentId()</h2>";
    try {
        if ($idEtudiant) {
            $candidatures = $candidatureModel->getCandidaturesByStudentId($idEtudiant);
            
            echo "<p class='success'>✓ La méthode getCandidaturesByStudentId($idEtudiant) a fonctionné</p>";
            echo "<p>Nombre de candidatures récupérées: " . count($candidatures) . "</p>";
            
            if (!empty($candidatures)) {
                echo "<h3>Données récupérées:</h3>";
                echo "<table>";
                echo "<tr>
                        <th>ID</th>
                        <th>ID Stage</th>
                        <th>Titre Stage</th>
                        <th>Statut</th>
                        <th>Date Candidature</th>
                      </tr>";
                
                foreach ($candidatures as $candidature) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($candidature->id_application) . "</td>";
                    echo "<td>" . htmlspecialchars($candidature->id_internship) . "</td>";
                    echo "<td>" . htmlspecialchars($candidature->title) . "</td>";
                    echo "<td>" . htmlspecialchars($candidature->status) . "</td>";
                    echo "<td>" . htmlspecialchars($candidature->application_date) . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>Aucune candidature trouvée pour cet étudiant.</p>";
            }
        } else {
            echo "<p class='error'>✗ Test ignoré: ID étudiant non disponible</p>";
        }
    } catch (Exception $e) {
        echo "<p class='error'>✗ Erreur lors de l'appel de getCandidaturesByStudentId(): " . $e->getMessage() . "</p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
    echo "</div>";
    
    // Tester getStudentStats()
    echo "<div class='method-test'>";
    echo "<h2>Test de getStudentStats()</h2>";
    try {
        if ($idEtudiant) {
            $stats = $candidatureModel->getStudentStats($idEtudiant);
            
            echo "<p class='success'>✓ La méthode getStudentStats($idEtudiant) a fonctionné</p>";
            echo "<h3>Statistiques de l'étudiant:</h3>";
            echo "<pre>";
            print_r($stats);
            echo "</pre>";
        } else {
            echo "<p class='error'>✗ Test ignoré: ID étudiant non disponible</p>";
        }
    } catch (Exception $e) {
        echo "<p class='error'>✗ Erreur lors de l'appel de getStudentStats(): " . $e->getMessage() . "</p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
    echo "</div>";
    
    // Tester getCandidatureById()
    echo "<div class='method-test'>";
    echo "<h2>Test de getCandidatureById()</h2>";
    try {
        // Utiliser l'ID 1 comme exemple (ajuster si nécessaire)
        $candidatureId = 1;
        $candidature = $candidatureModel->getCandidatureById($candidatureId);
        
        if ($candidature) {
            echo "<p class='success'>✓ La méthode getCandidatureById($candidatureId) a fonctionné</p>";
            echo "<h3>Données de la candidature:</h3>";
            echo "<pre>";
            print_r($candidature);
            echo "</pre>";
        } else {
            echo "<p class='error'>✗ Aucune candidature trouvée avec l'ID: $candidatureId</p>";
        }
    } catch (Exception $e) {
        echo "<p class='error'>✗ Erreur lors de l'appel de getCandidatureById(): " . $e->getMessage() . "</p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
    echo "</div>";
    
    // Tester hasCandidature()
    echo "<div class='method-test'>";
    echo "<h2>Test de hasCandidature()</h2>";
    try {
        if ($idEtudiant) {
            // Utiliser l'ID de stage 1 comme exemple (ajuster si nécessaire)
            $stageId = 1;
            $hasCandidature = $candidatureModel->hasCandidature($idEtudiant, $stageId);
            
            echo "<p class='success'>✓ La méthode hasCandidature($idEtudiant, $stageId) a fonctionné</p>";
            echo "<p>Résultat: " . ($hasCandidature ? "L'étudiant a déjà postulé à ce stage" : "L'étudiant n'a pas encore postulé à ce stage") . "</p>";
        } else {
            echo "<p class='error'>✗ Test ignoré: ID étudiant non disponible</p>";
        }
    } catch (Exception $e) {
        echo "<p class='error'>✗ Erreur lors de l'appel de hasCandidature(): " . $e->getMessage() . "</p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
    echo "</div>";
    
} catch (Exception $e) {
    echo "<p class='error'>✗ Erreur générale: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<p><a href='test.php'>Retour au script de test principal</a></p>";
echo "</body></html>"; 