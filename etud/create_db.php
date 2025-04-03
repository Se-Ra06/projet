<?php
// Script pour créer la base de données si elle n'existe pas

// Paramètres de connexion
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'website';

try {
    // Connexion à MySQL sans sélectionner de base de données
    $pdo = new PDO("mysql:host=$host", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h1>Vérification/Création de la base de données</h1>";
    
    // Vérifier si la base de données existe
    $stmt = $pdo->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$dbname'");
    $dbExists = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$dbExists) {
        // Créer la base de données si elle n'existe pas
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
        echo "<p style='color:green'>✓ Base de données '$dbname' créée avec succès.</p>";
        
        // Sélectionner la nouvelle base de données
        $pdo->exec("USE `$dbname`");
        
        // Créer les tables de base
        $createTableSQL = "
        -- Structure de la table utilisateurs
        CREATE TABLE `utilisateurs` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `nom` varchar(100) NOT NULL,
          `prenom` varchar(100) NOT NULL,
          `email` varchar(255) NOT NULL,
          `mot_de_passe` varchar(255) NOT NULL,
          `role` enum('etudiant','pilote','admin') NOT NULL,
          `date_creation` datetime NOT NULL,
          `date_mise_a_jour` datetime DEFAULT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `email` (`email`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

        -- Structure de la table etudiants
        CREATE TABLE `etudiants` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `utilisateur_id` int(11) NOT NULL,
          `promotion` varchar(50) DEFAULT NULL,
          `specialite` varchar(100) DEFAULT NULL,
          `cv_path` varchar(255) DEFAULT NULL,
          PRIMARY KEY (`id`),
          KEY `utilisateur_id` (`utilisateur_id`),
          CONSTRAINT `etudiants_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

        -- Structure de la table pilotes
        CREATE TABLE `pilotes` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `utilisateur_id` int(11) NOT NULL,
          `departement` varchar(100) DEFAULT NULL,
          PRIMARY KEY (`id`),
          KEY `utilisateur_id` (`utilisateur_id`),
          CONSTRAINT `pilotes_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

        -- Structure de la table entreprises
        CREATE TABLE `entreprises` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `nom` varchar(100) NOT NULL,
          `description` text DEFAULT NULL,
          `adresse` varchar(255) DEFAULT NULL,
          `ville` varchar(100) DEFAULT NULL,
          `code_postal` varchar(20) DEFAULT NULL,
          `pays` varchar(100) DEFAULT NULL,
          `site_web` varchar(255) DEFAULT NULL,
          `date_creation` datetime NOT NULL,
          `date_mise_a_jour` datetime DEFAULT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

        -- Structure de la table stages
        CREATE TABLE `stages` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `titre` varchar(255) NOT NULL,
          `description` text NOT NULL,
          `entreprise_id` int(11) NOT NULL,
          `type` enum('temps_plein','temps_partiel','alterance') NOT NULL,
          `duree` int(11) NOT NULL COMMENT 'Durée en semaines',
          `date_debut` date NOT NULL,
          `date_fin` date NOT NULL,
          `localisation` varchar(255) NOT NULL,
          `competences_requises` text DEFAULT NULL,
          `remuneration` decimal(10,2) DEFAULT NULL,
          `statut` enum('ouvert','pourvu','expire') NOT NULL DEFAULT 'ouvert',
          `date_creation` datetime NOT NULL,
          `date_mise_a_jour` datetime DEFAULT NULL,
          PRIMARY KEY (`id`),
          KEY `entreprise_id` (`entreprise_id`),
          CONSTRAINT `stages_ibfk_1` FOREIGN KEY (`entreprise_id`) REFERENCES `entreprises` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

        -- Structure de la table candidatures
        CREATE TABLE `candidatures` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `etudiant_id` int(11) NOT NULL,
          `stage_id` int(11) NOT NULL,
          `lettre_motivation` text NOT NULL,
          `cv_path` varchar(255) DEFAULT NULL,
          `statut` enum('en_attente','acceptee','refusee') NOT NULL DEFAULT 'en_attente',
          `date_creation` datetime NOT NULL,
          `date_mise_a_jour` datetime DEFAULT NULL,
          PRIMARY KEY (`id`),
          KEY `etudiant_id` (`etudiant_id`),
          KEY `stage_id` (`stage_id`),
          CONSTRAINT `candidatures_ibfk_1` FOREIGN KEY (`etudiant_id`) REFERENCES `etudiants` (`id`) ON DELETE CASCADE,
          CONSTRAINT `candidatures_ibfk_2` FOREIGN KEY (`stage_id`) REFERENCES `stages` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ";
        
        // Exécuter les requêtes de création de tables
        $pdo->exec($createTableSQL);
        echo "<p style='color:green'>✓ Tables créées avec succès.</p>";
        
        // Insérer des données de test
        $insertDataSQL = "
        -- Insertion de données test pour les utilisateurs
        INSERT INTO `utilisateurs` (`nom`, `prenom`, `email`, `mot_de_passe`, `role`, `date_creation`) VALUES
        ('Dupont', 'Jean', 'etudiant@test.com', '" . password_hash('password', PASSWORD_DEFAULT) . "', 'etudiant', NOW()),
        ('Martin', 'Sophie', 'pilote@test.com', '" . password_hash('password', PASSWORD_DEFAULT) . "', 'pilote', NOW()),
        ('Admin', 'System', 'admin@test.com', '" . password_hash('password', PASSWORD_DEFAULT) . "', 'admin', NOW());

        -- Insertion de données test pour les étudiants
        INSERT INTO `etudiants` (`utilisateur_id`, `promotion`, `specialite`) VALUES
        (1, '2023', 'Informatique');

        -- Insertion de données test pour les pilotes
        INSERT INTO `pilotes` (`utilisateur_id`, `departement`) VALUES
        (2, 'Informatique');

        -- Insertion de données test pour les entreprises
        INSERT INTO `entreprises` (`nom`, `description`, `adresse`, `ville`, `code_postal`, `pays`, `site_web`, `date_creation`) VALUES
        ('ACME Inc.', 'Une entreprise leader dans son domaine', '123 Rue Principale', 'Paris', '75001', 'France', 'https://acme.com', NOW()),
        ('TechSolutions', 'Spécialiste des solutions informatiques', '45 Avenue des Technologies', 'Lyon', '69002', 'France', 'https://techsolutions.com', NOW());

        -- Insertion de données test pour les stages
        INSERT INTO `stages` (`titre`, `description`, `entreprise_id`, `type`, `duree`, `date_debut`, `date_fin`, `localisation`, `competences_requises`, `remuneration`, `date_creation`) VALUES
        ('Développeur Web Full-Stack', 'Stage de développement web utilisant les technologies modernes', 1, 'temps_plein', 12, '2023-06-01', '2023-08-31', 'Paris', 'HTML, CSS, JavaScript, PHP, MySQL', 800.00, NOW()),
        ('Assistant Développeur Mobile', 'Participation au développement d''applications mobiles', 2, 'temps_partiel', 8, '2023-07-01', '2023-08-31', 'Lyon', 'Swift, Kotlin, React Native', 600.00, NOW());
        ";
        
        // Exécuter les requêtes d'insertion de données
        $pdo->exec($insertDataSQL);
        echo "<p style='color:green'>✓ Données de test insérées avec succès.</p>";
        
    } else {
        echo "<p style='color:blue'>ℹ La base de données '$dbname' existe déjà.</p>";
    }
    
    // Vérifier les tables dans la base de données
    $pdo->exec("USE `$dbname`");
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<h2>Tables existantes dans la base de données '$dbname':</h2>";
    echo "<ul>";
    foreach ($tables as $table) {
        echo "<li>$table</li>";
    }
    echo "</ul>";
    
    echo "<p><a href='test.php'>Retour au script de test</a></p>";
    
} catch(PDOException $e) {
    echo "<h1 style='color:red'>Erreur lors de la création de la base de données</h1>";
    echo "<p>Message d'erreur: " . $e->getMessage() . "</p>";
} 