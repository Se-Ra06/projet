<?php
// S'assurer que les constantes sont définies
if (!defined('APPROOT') || !defined('URLROOT') || !defined('SITENAME')) {
    require_once dirname(dirname(__DIR__)) . '/config/config.php';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($data['title']) ? $data['title'] : SITENAME; ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/style.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <a href="<?php echo URLROOT; ?>">
                    <h1><?php echo SITENAME; ?></h1>
                </a>
            </div>
            <nav>
                <ul>
                    <li><a href="<?php echo URLROOT; ?>">Accueil</a></li>
                    <?php if(isset($_SESSION['user_id'])) : ?>
                        <li><a href="<?php echo URLROOT; ?>/dashboard">Dashboard</a></li>
                        <li><a href="<?php echo URLROOT; ?>/home/logout">Déconnexion</a></li>
                    <?php else : ?>
                        <li><a href="<?php echo URLROOT; ?>/home/login">Connexion</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main class="container"><?php if(isset($_SESSION['success_message'])) : ?>
        <div class="alert alert-success">
            <?php 
                echo $_SESSION['success_message']; 
                unset($_SESSION['success_message']);
            ?>
        </div>
    <?php endif; ?>
    
    <?php if(isset($_SESSION['error_message'])) : ?>
        <div class="alert alert-danger">
            <?php 
                echo $_SESSION['error_message']; 
                unset($_SESSION['error_message']);
            ?>
        </div>
    <?php endif; ?> 