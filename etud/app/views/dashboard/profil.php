<?php
// S'assurer que les constantes sont définies
if (!defined('APPROOT')) {
    require_once dirname(dirname(__DIR__)) . '/config/config.php';
}

$data['active'] = 'profil';
require APPROOT . '/views/shared/dashboard_header.php'; 
?>

<div class="dashboard-welcome">
    <h1>Mon Profil</h1>
    <p>Gérez vos informations personnelles et votre CV</p>
</div>

<div class="dashboard-content-row">
    <div class="dashboard-card profile-info">
        <div class="card-header">
            <h2>Informations personnelles</h2>
            <button class="btn-edit-profile"><i class="fas fa-edit"></i> Modifier</button>
        </div>
        <div class="card-body">
            <div class="profile-view">
                <div class="profile-avatar">
                    <div class="avatar-container">
                        <?php if(isset($data['etudiant']->photo) && !empty($data['etudiant']->photo)): ?>
                            <img src="<?php echo URLROOT . '/' . $data['etudiant']->photo; ?>" alt="Photo de profil">
                        <?php else: ?>
                            <div class="avatar-placeholder">
                                <i class="fas fa-user"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="profile-details">
                    <div class="detail-group">
                        <h3>Nom complet</h3>
                        <p><?php echo isset($data['etudiant']->nom) ? $data['etudiant']->nom . ' ' . $data['etudiant']->prenom : $_SESSION['user_name']; ?></p>
                    </div>
                    
                    <div class="detail-group">
                        <h3>Email</h3>
                        <p><?php echo isset($data['etudiant']->email) ? $data['etudiant']->email : $_SESSION['user_email']; ?></p>
                    </div>
                    
                    <?php if(isset($data['etudiant']->formation)): ?>
                        <div class="detail-group">
                            <h3>Formation</h3>
                            <p><?php echo $data['etudiant']->formation; ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(isset($data['etudiant']->niveau)): ?>
                        <div class="detail-group">
                            <h3>Niveau d'études</h3>
                            <p><?php echo $data['etudiant']->niveau; ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(isset($data['etudiant']->telephone)): ?>
                        <div class="detail-group">
                            <h3>Téléphone</h3>
                            <p><?php echo $data['etudiant']->telephone; ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <form action="<?php echo URLROOT; ?>/dashboard/updateProfile" method="post" enctype="multipart/form-data" class="profile-form hidden">
                <div class="form-row">
                    <div class="form-group">
                        <label for="nom">Nom</label>
                        <input type="text" name="nom" id="nom" value="<?php echo isset($data['etudiant']->nom) ? $data['etudiant']->nom : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="prenom">Prénom</label>
                        <input type="text" name="prenom" id="prenom" value="<?php echo isset($data['etudiant']->prenom) ? $data['etudiant']->prenom : ''; ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" value="<?php echo isset($data['etudiant']->email) ? $data['etudiant']->email : $_SESSION['user_email']; ?>" disabled>
                    <small>L'email ne peut pas être modifié directement. Contactez votre administration.</small>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="formation">Formation</label>
                        <input type="text" name="formation" id="formation" value="<?php echo isset($data['etudiant']->formation) ? $data['etudiant']->formation : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="niveau">Niveau d'études</label>
                        <select name="niveau" id="niveau">
                            <option value="">Sélectionnez votre niveau</option>
                            <option value="Bac+1" <?php echo (isset($data['etudiant']->niveau) && $data['etudiant']->niveau == 'Bac+1') ? 'selected' : ''; ?>>Bac+1</option>
                            <option value="Bac+2" <?php echo (isset($data['etudiant']->niveau) && $data['etudiant']->niveau == 'Bac+2') ? 'selected' : ''; ?>>Bac+2</option>
                            <option value="Bac+3" <?php echo (isset($data['etudiant']->niveau) && $data['etudiant']->niveau == 'Bac+3') ? 'selected' : ''; ?>>Bac+3</option>
                            <option value="Bac+4" <?php echo (isset($data['etudiant']->niveau) && $data['etudiant']->niveau == 'Bac+4') ? 'selected' : ''; ?>>Bac+4</option>
                            <option value="Bac+5" <?php echo (isset($data['etudiant']->niveau) && $data['etudiant']->niveau == 'Bac+5') ? 'selected' : ''; ?>>Bac+5</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="telephone">Téléphone</label>
                    <input type="tel" name="telephone" id="telephone" value="<?php echo isset($data['etudiant']->telephone) ? $data['etudiant']->telephone : ''; ?>" placeholder="Ex: 0612345678">
                </div>
                
                <div class="form-group">
                    <label for="photo">Photo de profil</label>
                    <div class="file-upload-container">
                        <input type="file" name="photo" id="photo" accept="image/*">
                        <div class="file-upload-button">
                            <i class="fas fa-upload"></i>
                            <span>Choisir une image</span>
                        </div>
                        <span class="selected-file">Aucun fichier sélectionné</span>
                    </div>
                    <small>Formats acceptés: JPG, PNG (max 2MB)</small>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-outline cancel-edit">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="dashboard-card cv-section">
        <div class="card-header">
            <h2>Mon CV</h2>
        </div>
        <div class="card-body">
            <?php if(isset($data['etudiant']->cv) && !empty($data['etudiant']->cv)): ?>
                <div class="cv-preview">
                    <i class="fas fa-file-pdf"></i>
                    <span><?php echo basename($data['etudiant']->cv); ?></span>
                    <div class="cv-actions">
                        <a href="<?php echo URLROOT . '/' . $data['etudiant']->cv; ?>" target="_blank" class="btn-icon" title="Voir le CV">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="<?php echo URLROOT; ?>/dashboard/downloadCV" class="btn-icon" title="Télécharger le CV">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="cv-upload">
                    <div class="upload-placeholder">
                        <i class="fas fa-file-upload"></i>
                        <p>Vous n'avez pas encore téléchargé votre CV</p>
                    </div>
                    
                    <form action="<?php echo URLROOT; ?>/dashboard/uploadCV" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <div class="file-upload-container">
                                <input type="file" name="cv" id="cv_file" accept=".pdf">
                                <label for="cv_file" class="btn btn-primary">
                                    <i class="fas fa-upload"></i> Télécharger mon CV
                                </label>
                            </div>
                            <small>Format accepté: PDF uniquement (max 2MB)</small>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .dashboard-content-row {
        display: flex;
        gap: 1.5rem;
        flex-wrap: wrap;
    }
    
    .profile-info {
        flex: 2;
        min-width: 60%;
    }
    
    .cv-section {
        flex: 1;
        min-width: 30%;
    }
    
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .btn-edit-profile {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: none;
        border: 1px solid var(--primary-color);
        color: var(--primary-color);
        border-radius: var(--border-radius);
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn-edit-profile:hover {
        background-color: var(--primary-color);
        color: white;
    }
    
    .profile-view {
        display: flex;
        gap: 2rem;
    }
    
    .profile-avatar {
        width: 120px;
    }
    
    .avatar-container {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        overflow: hidden;
    }
    
    .avatar-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .avatar-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f1f5f9;
        color: var(--dark-gray);
    }
    
    .avatar-placeholder i {
        font-size: 3rem;
    }
    
    .profile-details {
        flex: 1;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1.5rem;
    }
    
    .detail-group h3 {
        font-size: 0.9rem;
        color: var(--dark-gray);
        margin: 0 0 0.5rem 0;
        font-weight: 500;
    }
    
    .detail-group p {
        margin: 0;
        font-size: 1.1rem;
    }
    
    .hidden {
        display: none;
    }
    
    .profile-form {
        margin-top: 1.5rem;
    }
    
    .form-row {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    
    .form-row .form-group {
        flex: 1;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }
    
    .form-group input,
    .form-group select {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #e2e8f0;
        border-radius: var(--border-radius);
        font-family: inherit;
        font-size: 1rem;
    }
    
    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(67, 97, 238, 0.2);
    }
    
    .form-group input:disabled {
        background-color: #f1f5f9;
        cursor: not-allowed;
    }
    
    .form-group small {
        display: block;
        margin-top: 0.25rem;
        font-size: 0.85rem;
        color: var(--dark-gray);
    }
    
    .file-upload-container {
        display: flex;
        align-items: center;
        margin-bottom: 0.5rem;
    }
    
    .file-upload-container input[type="file"] {
        position: absolute;
        width: 0.1px;
        height: 0.1px;
        opacity: 0;
        overflow: hidden;
        z-index: -1;
    }
    
    .file-upload-button {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.6rem 1rem;
        background-color: #f1f5f9;
        border-radius: var(--border-radius);
        cursor: pointer;
        transition: background-color 0.2s;
    }
    
    .file-upload-button:hover {
        background-color: #e2e8f0;
    }
    
    .selected-file {
        margin-left: 1rem;
        font-size: 0.9rem;
        color: var(--dark-gray);
    }
    
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 1rem;
    }
    
    .cv-preview {
        display: flex;
        align-items: center;
        padding: 1rem;
        background-color: #f7f9fc;
        border-radius: var(--border-radius);
    }
    
    .cv-preview i {
        font-size: 2rem;
        color: #e74c3c;
        margin-right: 1rem;
    }
    
    .cv-preview span {
        flex: 1;
    }
    
    .cv-actions {
        display: flex;
        gap: 0.5rem;
    }
    
    .btn-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background-color: white;
        color: var(--dark-color);
        border: 1px solid #e2e8f0;
        transition: all 0.2s;
    }
    
    .btn-icon:hover {
        background-color: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }
    
    .cv-upload {
        text-align: center;
        padding: 2rem 0;
    }
    
    .upload-placeholder {
        margin-bottom: 2rem;
    }
    
    .upload-placeholder i {
        font-size: 3rem;
        color: var(--dark-gray);
        margin-bottom: 1rem;
    }
    
    .upload-placeholder p {
        color: var(--dark-gray);
    }
    
    .cv-upload .file-upload-container {
        justify-content: center;
        margin-bottom: 1rem;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestion de l'édition du profil
        const editButton = document.querySelector('.btn-edit-profile');
        const profileView = document.querySelector('.profile-view');
        const profileForm = document.querySelector('.profile-form');
        const cancelButton = document.querySelector('.cancel-edit');
        
        editButton.addEventListener('click', function() {
            profileView.classList.add('hidden');
            profileForm.classList.remove('hidden');
        });
        
        cancelButton.addEventListener('click', function() {
            profileForm.classList.add('hidden');
            profileView.classList.remove('hidden');
        });
        
        // Gestion du téléchargement de fichiers
        const photoInput = document.getElementById('photo');
        const photoLabel = document.querySelector('.profile-form .selected-file');
        
        if (photoInput && photoLabel) {
            photoInput.addEventListener('change', function() {
                if(this.files && this.files.length > 0) {
                    photoLabel.textContent = this.files[0].name;
                } else {
                    photoLabel.textContent = 'Aucun fichier sélectionné';
                }
            });
            
            document.querySelector('.profile-form .file-upload-button').addEventListener('click', function() {
                photoInput.click();
            });
        }
        
        // Gestion du CV
        const cvInput = document.getElementById('cv_file');
        
        if (cvInput) {
            cvInput.addEventListener('change', function() {
                if(this.files && this.files.length > 0) {
                    const submitButton = this.closest('form').querySelector('button[type="submit"]');
                    submitButton.style.display = 'block';
                }
            });
        }
    });
</script>

<?php require APPROOT . '/views/shared/dashboard_footer.php'; ?> 