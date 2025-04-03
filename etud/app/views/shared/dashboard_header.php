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
    <title><?php echo isset($data['title']) ? $data['title'] . ' - ' . SITENAME : SITENAME; ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/dashboard.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- ApexCharts pour les graphiques -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</head>
<body class="dashboard-body">
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <span class="icon"><i class="fas fa-graduation-cap"></i></span>
                    <h2><?php echo SITENAME; ?></h2>
                </div>
                <button class="toggle-sidebar">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            <div class="sidebar-content">
                <nav class="sidebar-nav">
                    <ul>
                        <li class="<?php echo (isset($data['active']) && $data['active'] === 'dashboard') ? 'active' : ''; ?>">
                            <a href="<?php echo URLROOT; ?>/dashboard">
                                <i class="fas fa-tachometer-alt"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="<?php echo (isset($data['active']) && $data['active'] === 'stages') ? 'active' : ''; ?>">
                            <a href="<?php echo URLROOT; ?>/dashboard/stages">
                                <i class="fas fa-briefcase"></i>
                                <span>Offres de stages</span>
                            </a>
                        </li>
                        <li class="<?php echo (isset($data['active']) && $data['active'] === 'candidatures') ? 'active' : ''; ?>">
                            <a href="<?php echo URLROOT; ?>/dashboard/candidatures">
                                <i class="fas fa-file-alt"></i>
                                <span>Mes candidatures</span>
                            </a>
                        </li>
                        <li class="<?php echo (isset($data['active']) && $data['active'] === 'profil') ? 'active' : ''; ?>">
                            <a href="<?php echo URLROOT; ?>/dashboard/profil">
                                <i class="fas fa-user"></i>
                                <span>Mon profil</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo URLROOT; ?>/home/logout">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Déconnexion</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>
        
        <div class="main-content">
            <header class="dashboard-header">
                <div class="header-search">
                    <form action="<?php echo URLROOT; ?>/dashboard/stages" method="GET">
                        <input type="text" name="search" placeholder="Rechercher un stage...">
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>
                <div class="header-actions">
                    <div class="notification">
                        <i class="far fa-bell"></i>
                        <span class="badge">3</span>
                    </div>
                    <div class="user-profile">
                        <div class="user-info">
                            <span class="user-name"><?php echo (isset($_SESSION['user_name'])) ? $_SESSION['user_name'] : 'Étudiant'; ?></span>
                            <span class="user-role">Étudiant</span>
                        </div>
                        <div class="profile-pic">
                            <img src="<?php echo URLROOT; ?>/public/img/profile-placeholder.jpg" alt="Photo de profil">
                        </div>
                    </div>
                </div>
            </header>
            
            <div class="dashboard-body">
                <?php if(isset($_SESSION['success_message'])) : ?>
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