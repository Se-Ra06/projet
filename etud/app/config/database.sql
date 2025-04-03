-- Script de création de la base de données
CREATE DATABASE IF NOT EXISTS stagefinder_db;
USE stagefinder_db;

-- Table des utilisateurs
CREATE TABLE IF NOT EXISTS utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('etudiant', 'pilote', 'admin') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table des étudiants
CREATE TABLE IF NOT EXISTS etudiants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    promo VARCHAR(100),
    annee INT,
    filiere VARCHAR(100),
    cv_path VARCHAR(255),
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

-- Table des pilotes
CREATE TABLE IF NOT EXISTS pilotes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    departement VARCHAR(100),
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

-- Table des entreprises
CREATE TABLE IF NOT EXISTS entreprises (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    adresse VARCHAR(255),
    code_postal VARCHAR(20),
    ville VARCHAR(100),
    pays VARCHAR(100),
    site_web VARCHAR(255),
    secteur VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table des contacts entreprises
CREATE TABLE IF NOT EXISTS contacts_entreprise (
    id INT AUTO_INCREMENT PRIMARY KEY,
    entreprise_id INT NOT NULL,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    poste VARCHAR(100),
    email VARCHAR(255),
    telephone VARCHAR(20),
    FOREIGN KEY (entreprise_id) REFERENCES entreprises(id) ON DELETE CASCADE
);

-- Table des stages
CREATE TABLE IF NOT EXISTS stages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    entreprise_id INT NOT NULL,
    type VARCHAR(100) NOT NULL,
    duree INT NOT NULL,
    date_debut DATE,
    date_fin DATE,
    lieu VARCHAR(255),
    competences_requises TEXT,
    remuneration DECIMAL(10,2),
    places_disponibles INT DEFAULT 1,
    date_publication TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    statut ENUM('active', 'pourvue', 'expire') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (entreprise_id) REFERENCES entreprises(id) ON DELETE CASCADE
);

-- Table des candidatures
CREATE TABLE IF NOT EXISTS candidatures (
    id INT AUTO_INCREMENT PRIMARY KEY,
    etudiant_id INT NOT NULL,
    stage_id INT NOT NULL,
    lettre_motivation TEXT,
    cv_path VARCHAR(255),
    statut ENUM('en_attente', 'acceptee', 'refusee') DEFAULT 'en_attente',
    date_entretien DATETIME,
    commentaires TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (etudiant_id) REFERENCES etudiants(id) ON DELETE CASCADE,
    FOREIGN KEY (stage_id) REFERENCES stages(id) ON DELETE CASCADE
);

-- Table des wishlist
CREATE TABLE IF NOT EXISTS wishlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    etudiant_id INT NOT NULL,
    stage_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (etudiant_id) REFERENCES etudiants(id) ON DELETE CASCADE,
    FOREIGN KEY (stage_id) REFERENCES stages(id) ON DELETE CASCADE,
    UNIQUE KEY (etudiant_id, stage_id)
);

-- Données de test
-- Insertion d'utilisateurs de test
INSERT INTO utilisateurs (nom, prenom, email, password, role) VALUES 
('Dupont', 'Jean', 'jean.dupont@example.com', '$2y$10$5yfXYlA5U3nw0W4LN6ETneOoRz27FxR2pXQY76KuREUZZ0XsWcuwi', 'etudiant'), -- password: password123
('Martin', 'Sophie', 'sophie.martin@example.com', '$2y$10$5yfXYlA5U3nw0W4LN6ETneOoRz27FxR2pXQY76KuREUZZ0XsWcuwi', 'pilote'),
('Admin', 'System', 'admin@example.com', '$2y$10$5yfXYlA5U3nw0W4LN6ETneOoRz27FxR2pXQY76KuREUZZ0XsWcuwi', 'admin');

-- Insertion d'étudiants de test
INSERT INTO etudiants (utilisateur_id, promo, annee, filiere) VALUES 
(1, 'Promotion 2023', 3, 'Informatique');

-- Insertion de pilotes de test
INSERT INTO pilotes (utilisateur_id, departement) VALUES 
(2, 'Informatique');

-- Insertion d'entreprises de test
INSERT INTO entreprises (nom, description, ville, pays, secteur) VALUES 
('TechSolutions', 'Entreprise spécialisée dans le développement de solutions informatiques', 'Paris', 'France', 'Informatique'),
('EcoGreen', 'Entreprise dans le secteur de l''environnement et du développement durable', 'Lyon', 'France', 'Environnement'),
('FinanceFirst', 'Société de conseil en finance et investissement', 'Bordeaux', 'France', 'Finance');

-- Insertion de stages de test
INSERT INTO stages (titre, description, entreprise_id, type, duree, date_debut, date_fin, lieu, competences_requises, remuneration) VALUES 
('Développeur Web Full-Stack', 'Stage de développement web utilisant les technologies modernes (React, Node.js, etc.)', 1, 'Stage_fin_detudes', 6, '2025-02-01', '2025-07-31', 'Paris', 'HTML, CSS, JavaScript, React, Node.js', 1000.00),
('Analyste environnemental', 'Participation à des projets d''études d''impact environnemental', 2, 'Stage_annee', 4, '2025-03-01', '2025-06-30', 'Lyon', 'Connaissances en écologie, Analyse de données', 800.00),
('Assistant analyste financier', 'Accompagnement des consultants sur des missions d''analyse financière', 3, 'Stage_fin_detudes', 6, '2025-02-15', '2025-08-15', 'Bordeaux', 'Finance d''entreprise, Excel avancé, PowerBI', 1200.00);

-- Insertion de candidatures de test
INSERT INTO candidatures (etudiant_id, stage_id, lettre_motivation, statut) VALUES 
(1, 1, 'Je suis très intéressé par ce stage qui correspond parfaitement à mon projet professionnel...', 'en_attente'),
(1, 2, 'Passionné par les enjeux environnementaux, je souhaite mettre mes compétences au service de votre entreprise...', 'acceptee');

-- Insertion dans la wishlist de test
INSERT INTO wishlist (etudiant_id, stage_id) VALUES 
(1, 3); 