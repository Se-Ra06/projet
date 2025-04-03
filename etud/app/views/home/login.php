<?php
// S'assurer que les constantes sont définies
if (!defined('APPROOT')) {
    require_once dirname(dirname(__DIR__)) . '/config/config.php';
}

require APPROOT . '/views/shared/header.php'; ?>

<section class="login-section">
    <div class="login-container">
        <div class="login-image">
            <img src="<?php echo URLROOT; ?>/public/img/login-image.svg" alt="Illustration de connexion">
        </div>
        <div class="login-form-container">
            <h2>Connexion</h2>
            <p>Connectez-vous pour accéder à votre espace étudiant</p>
            
            <form action="<?php echo URLROOT; ?>/home/login" method="post" class="login-form">
                <div class="form-group <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" value="<?php echo $data['email']; ?>" placeholder="Entrez votre email">
                    <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
                </div>
                
                <div class="form-group <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>">
                    <label for="password">Mot de passe</label>
                    <div class="password-input">
                        <input type="password" name="password" id="password" value="<?php echo $data['password']; ?>" placeholder="Entrez votre mot de passe">
                        <button type="button" class="toggle-password"><i class="far fa-eye"></i></button>
                    </div>
                    <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
                </div>
                
                <div class="form-group remember-me">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Se souvenir de moi</label>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
                </div>
                
                <div class="form-footer">
                    <p>Mot de passe oublié ? Contactez votre établissement.</p>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
    // Script pour afficher/masquer le mot de passe
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.querySelector('.toggle-password');
        const passwordInput = document.querySelector('#password');
        
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    });
</script>

<?php require APPROOT . '/views/shared/footer.php'; ?> 