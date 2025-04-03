<?php
// S'assurer que les constantes sont définies
if (!defined('APPROOT') || !defined('URLROOT') || !defined('SITENAME')) {
    require_once dirname(dirname(__DIR__)) . '/config/config.php';
}
?>
    </main>
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section about">
                    <h2><?php echo SITENAME; ?></h2>
                    <p>Plateforme dédiée à la recherche de stages pour les étudiants.</p>
                    <div class="contact">
                        <span><i class="fas fa-phone"></i> &nbsp; 123-456-789</span>
                        <span><i class="fas fa-envelope"></i> &nbsp; info@stagefinder.com</span>
                    </div>
                    <div class="socials">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
                <div class="footer-section links">
                    <h2>Liens rapides</h2>
                    <ul>
                        <li><a href="<?php echo URLROOT; ?>">Accueil</a></li>
                        <li><a href="<?php echo URLROOT; ?>/home/login">Connexion</a></li>
                        <?php if(isset($_SESSION['user_id'])) : ?>
                            <li><a href="<?php echo URLROOT; ?>/dashboard">Dashboard</a></li>
                            <li><a href="<?php echo URLROOT; ?>/dashboard/stages">Offres de stages</a></li>
                            <li><a href="<?php echo URLROOT; ?>/dashboard/candidatures">Mes candidatures</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                &copy; <?php echo date('Y'); ?> <?php echo SITENAME; ?> | Tous droits réservés
            </div>
        </div>
    </footer>
    <script src="<?php echo URLROOT; ?>/public/js/main.js"></script>
</body>
</html> 