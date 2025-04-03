<?php
// Script de test pour StageModel

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
    <title>Test du StageModel</title>
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

echo "<h1>Test du StageModel</h1>";

// Charger les classes nécessaires
try {
    require_once APPROOT . '/core/Database.php';
    require_once APPROOT . '/core/Model.php';
    require_once APPROOT . '/models/StageModel.php';
    
    echo "<p class='success'>✓ Classes chargées avec succès</p>";
    
    // Instancier le modèle
    $stageModel = new StageModel();
    echo "<p class='success'>✓ StageModel instancié avec succès</p>";
    
    // Tester getRecentStages()
    echo "<div class='method-test'>";
    echo "<h2>Test de getRecentStages()</h2>";
    try {
        $stages = $stageModel->getRecentStages();
        
        echo "<p class='success'>✓ La méthode getRecentStages() a fonctionné</p>";
        echo "<p>Nombre de stages récents récupérés: " . count($stages) . "</p>";
        
        if (!empty($stages)) {
            echo "<h3>Données récupérées:</h3>";
            echo "<table>";
            echo "<tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Entreprise</th>
                    <th>Localisation</th>
                    <th>Date de publication</th>
                  </tr>";
            
            foreach ($stages as $stage) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($stage->id_internship) . "</td>";
                echo "<td>" . htmlspecialchars($stage->title) . "</td>";
                echo "<td>" . htmlspecialchars($stage->name) . "</td>";
                echo "<td>" . htmlspecialchars($stage->location) . "</td>";
                echo "<td>" . htmlspecialchars($stage->published_date) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    } catch (Exception $e) {
        echo "<p class='error'>✗ Erreur lors de l'appel de getRecentStages(): " . $e->getMessage() . "</p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
    echo "</div>";
    
    // Tester getAllStages()
    echo "<div class='method-test'>";
    echo "<h2>Test de getAllStages()</h2>";
    try {
        $allStages = $stageModel->getAllStages();
        
        echo "<p class='success'>✓ La méthode getAllStages() a fonctionné</p>";
        echo "<p>Nombre total de stages: " . count($allStages) . "</p>";
        
        if (!empty($allStages) && count($allStages) > 0) {
            echo "<h3>Échantillon des stages (3 premiers):</h3>";
            echo "<table>";
            echo "<tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Entreprise</th>
                    <th>Localisation</th>
                    <th>Date de publication</th>
                  </tr>";
            
            $sample = array_slice($allStages, 0, 3);
            foreach ($sample as $stage) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($stage->id_internship) . "</td>";
                echo "<td>" . htmlspecialchars($stage->title) . "</td>";
                echo "<td>" . htmlspecialchars($stage->name) . "</td>";
                echo "<td>" . htmlspecialchars($stage->location) . "</td>";
                echo "<td>" . htmlspecialchars($stage->published_date) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    } catch (Exception $e) {
        echo "<p class='error'>✗ Erreur lors de l'appel de getAllStages(): " . $e->getMessage() . "</p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
    echo "</div>";
    
    // Tester getStageById()
    echo "<div class='method-test'>";
    echo "<h2>Test de getStageById()</h2>";
    try {
        // Utiliser l'ID 1 comme exemple (ajuster si nécessaire)
        $stageId = 1;
        $stage = $stageModel->getStageById($stageId);
        
        if ($stage) {
            echo "<p class='success'>✓ La méthode getStageById($stageId) a fonctionné</p>";
            echo "<h3>Données du stage:</h3>";
            echo "<pre>";
            print_r($stage);
            echo "</pre>";
        } else {
            echo "<p class='error'>✗ Aucun stage trouvé avec l'ID: $stageId</p>";
        }
    } catch (Exception $e) {
        echo "<p class='error'>✗ Erreur lors de l'appel de getStageById(): " . $e->getMessage() . "</p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
    echo "</div>";
    
    // Tester searchStages()
    echo "<div class='method-test'>";
    echo "<h2>Test de searchStages()</h2>";
    try {
        // Utiliser un terme de recherche général
        $searchTerm = "stage";
        $searchResults = $stageModel->searchStages($searchTerm);
        
        echo "<p class='success'>✓ La méthode searchStages('$searchTerm') a fonctionné</p>";
        echo "<p>Nombre de résultats trouvés: " . count($searchResults) . "</p>";
        
        if (!empty($searchResults)) {
            echo "<h3>Résultats de recherche:</h3>";
            echo "<table>";
            echo "<tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Entreprise</th>
                    <th>Localisation</th>
                  </tr>";
            
            foreach ($searchResults as $result) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($result->id_internship) . "</td>";
                echo "<td>" . htmlspecialchars($result->title) . "</td>";
                echo "<td>" . htmlspecialchars($result->name) . "</td>";
                echo "<td>" . htmlspecialchars($result->location) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    } catch (Exception $e) {
        echo "<p class='error'>✗ Erreur lors de l'appel de searchStages(): " . $e->getMessage() . "</p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
    echo "</div>";
    
} catch (Exception $e) {
    echo "<p class='error'>✗ Erreur générale: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<p><a href='test.php'>Retour au script de test principal</a></p>";
echo "</body></html>"; 