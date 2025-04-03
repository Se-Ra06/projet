<?php
// S'assurer que les constantes sont définies
if (!defined('APPROOT')) {
    require_once dirname(dirname(__DIR__)) . '/config/config.php';
}

$data['active'] = 'stages';
require APPROOT . '/views/shared/dashboard_header.php'; 
?>

<div class="dashboard-welcome">
    <h1>Postuler à l'offre</h1>
    <p><?php echo $data['stage']->titre; ?> - <?php echo $data['stage']->entreprise_nom; ?></p>
</div>

<div class="dashboard-card">
    <div class="card-header">
        <h2>Formulaire de candidature</h2>
    </div>
    <div class="card-body">
        <?php if(isset($data['errors'])): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach($data['errors'] as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form action="<?php echo URLROOT; ?>/dashboard/postuler/<?php echo $data['stage']->id; ?>" method="post" enctype="multipart/form-data" class="candidature-form">
            <div class="form-group">
                <label for="lettre_motivation">Lettre de motivation</label>
                <textarea name="lettre_motivation" id="lettre_motivation" rows="10" placeholder="Décrivez votre motivation pour ce stage et pourquoi vous pensez être un bon candidat..."><?php echo isset($_POST['lettre_motivation']) ? $_POST['lettre_motivation'] : ''; ?></textarea>
                <small>Expliquez clairement vos motivations et comment vos compétences correspondent aux exigences du stage.</small>
            </div>
            
            <div class="form-group">
                <label for="cv">CV (PDF)</label>
                <div class="file-upload-container">
                    <input type="file" name="cv" id="cv" accept=".pdf">
                    <div class="file-upload-button">
                        <i class="fas fa-upload"></i>
                        <span>Choisir un fichier</span>
                    </div>
                    <span class="selected-file">Aucun fichier sélectionné</span>
                </div>
                <small>Téléchargez votre CV au format PDF (max 2MB).</small>
            </div>
            
            <div class="form-actions">
                <a href="<?php echo URLROOT; ?>/dashboard/stage/<?php echo $data['stage']->id; ?>" class="btn btn-outline">Annuler</a>
                <button type="submit" class="btn btn-primary">Envoyer ma candidature</button>
            </div>
        </form>
    </div>
</div>

<style>
    .candidature-form {
        max-width: 800px;
        margin: 0 auto;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }
    
    .form-group textarea {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #e2e8f0;
        border-radius: var(--border-radius);
        font-family: inherit;
        font-size: 1rem;
        resize: vertical;
    }
    
    .form-group textarea:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(67, 97, 238, 0.2);
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
        margin-top: 2rem;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('cv');
        const fileLabel = document.querySelector('.selected-file');
        
        fileInput.addEventListener('change', function() {
            if(this.files && this.files.length > 0) {
                fileLabel.textContent = this.files[0].name;
            } else {
                fileLabel.textContent = 'Aucun fichier sélectionné';
            }
        });
        
        document.querySelector('.file-upload-button').addEventListener('click', function() {
            fileInput.click();
        });
    });
</script>

<?php require APPROOT . '/views/shared/dashboard_footer.php'; ?> 