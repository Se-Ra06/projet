<?php
if (!defined('APPROOT')) {
    die('Accès non autorisé');
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page introuvable - <?= SITENAME ?></title>
    <link rel="stylesheet" href="<?= URLROOT ?>/css/style.css">
    <style>
        .error-container {
            text-align: center;
            padding: 50px 20px;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .error-code {
            font-size: 120px;
            color: #dc3545;
            margin-bottom: 20px;
            font-weight: bold;
        }
        
        .error-message {
            font-size: 24px;
            margin-bottom: 30px;
        }
        
        .error-details {
            margin-bottom: 30px;
            color: #6c757d;
        }
        
        .home-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        
        .home-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">404</div>
        <div class="error-message">Page introuvable</div>
        <div class="error-details">
            La page que vous recherchez n'existe pas ou a été déplacée.
        </div>
        <a href="<?= URLROOT ?>" class="home-button">Retourner à l'accueil</a>
    </div>
</body>
</html> 