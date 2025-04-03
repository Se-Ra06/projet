<?php
// S'assurer que les constantes sont définies
if (!defined('APPROOT')) {
    require_once dirname(dirname(__DIR__)) . '/config/config.php';
}

require APPROOT . '/views/shared/header.php'; ?>

<section class="hero">
    <div class="hero-content">
        <h1>Trouvez le stage idéal pour votre avenir</h1>
        <p><?php echo $data['description']; ?></p>
        <div class="hero-buttons">
            <a href="<?php echo URLROOT; ?>/home/login" class="btn btn-primary">Se connecter</a>
        </div>
    </div>
    <div class="hero-image">
        <img src="<?php echo URLROOT; ?>/public/img/hero-image.svg" alt="Illustration de recherche de stages">
    </div>
</section>

<section class="features">
    <h2 class="section-title">Pourquoi choisir <?php echo SITENAME; ?></h2>
    <div class="feature-cards">
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-search"></i>
            </div>
            <h3>Recherche facile</h3>
            <p>Trouvez rapidement des offres de stage correspondant à vos compétences et à vos centres d'intérêt.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <h3>Candidature simplifiée</h3>
            <p>Postulez en quelques clics et suivez l'avancement de vos candidatures en temps réel.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-handshake"></i>
            </div>
            <h3>Connexion directe</h3>
            <p>Entrez en contact directement avec les entreprises qui proposent des stages pertinents.</p>
        </div>
    </div>
</section>

<section class="how-it-works">
    <h2 class="section-title">Comment ça marche ?</h2>
    <div class="steps">
        <div class="step">
            <div class="step-number">1</div>
            <div class="step-content">
                <h3>Connectez-vous</h3>
                <p>Accédez à votre espace étudiant avec vos identifiants fournis par votre établissement.</p>
            </div>
        </div>
        <div class="step">
            <div class="step-number">2</div>
            <div class="step-content">
                <h3>Trouvez des offres</h3>
                <p>Parcourez les offres de stage disponibles ou utilisez notre moteur de recherche avancé.</p>
            </div>
        </div>
        <div class="step">
            <div class="step-number">3</div>
            <div class="step-content">
                <h3>Postulez</h3>
                <p>Envoyez votre candidature directement via la plateforme avec votre CV et lettre de motivation.</p>
            </div>
        </div>
        <div class="step">
            <div class="step-number">4</div>
            <div class="step-content">
                <h3>Suivez vos candidatures</h3>
                <p>Consultez l'état de vos candidatures et recevez des notifications sur les réponses des entreprises.</p>
            </div>
        </div>
    </div>
</section>

<section class="cta">
    <div class="cta-content">
        <h2>Prêt à trouver votre stage idéal ?</h2>
        <p>Connectez-vous dès maintenant pour découvrir toutes les opportunités qui s'offrent à vous !</p>
        <a href="<?php echo URLROOT; ?>/home/login" class="btn btn-cta">Commencer</a>
    </div>
</section>

<?php require APPROOT . '/views/shared/footer.php'; ?> 