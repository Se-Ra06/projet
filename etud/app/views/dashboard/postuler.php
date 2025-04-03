<?php
// S'assurer que le fichier config est inclus
if (!defined('APPROOT')) {
    require_once '../../config/config.php';
}

$data['active'] = 'stages';
$data['title'] = 'Postuler à un stage';
require APPROOT . '/views/shared/dashboard_header.php'; 

// Récupérer les données du stage depuis $data
$stage = $data['stage'] ?? null;
$etudiant = $data['etudiant'] ?? null;
?>

<div class="postuler-container">
    <?php if($stage) : ?>
        <div class="postuler-header">
            <div class="back-button">
                <a href="<?php echo URLROOT; ?>/dashboard/stage/<?php echo $stage->id_internship; ?>" class="btn-icon">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <div class="stage-info">
                <h1>Postuler à "<?php echo htmlspecialchars($stage->title); ?>"</h1>
                <div class="company-name"><?php echo htmlspecialchars($stage->company_name); ?></div>
            </div>
        </div>
        
        <?php if(isset($data['error'])) : ?>
            <div class="alert alert-danger">
                <?php echo $data['error']; ?>
            </div>
        <?php endif; ?>
        
        <div class="postuler-content">
            <div class="postuler-main">
                <div class="application-form-card">
                    <div class="card-header">
                        <h2>Formulaire de candidature</h2>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo URLROOT; ?>/dashboard/postuler/<?php echo $stage->id_internship; ?>" method="post" enctype="multipart/form-data" id="applicationForm">
                            <div class="form-section">
                                <h3>Informations personnelles</h3>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="name">Nom complet</label>
                                        <input type="text" name="name" id="name" value="<?php echo isset($etudiant->name) ? htmlspecialchars($etudiant->name) : ''; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" id="email" value="<?php echo isset($etudiant->email) ? htmlspecialchars($etudiant->email) : ''; ?>" required>
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="phone">Téléphone</label>
                                        <input type="tel" name="phone" id="phone" value="<?php echo isset($etudiant->phone_number) ? htmlspecialchars($etudiant->phone_number) : ''; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="location">Localisation</label>
                                        <input type="text" name="location" id="location" value="<?php echo isset($etudiant->location) ? htmlspecialchars($etudiant->location) : ''; ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-section">
                                <h3>Formation et études</h3>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="degree">Formation</label>
                                        <input type="text" name="degree" id="degree" value="<?php echo isset($etudiant->degree) ? htmlspecialchars($etudiant->degree) : ''; ?>" placeholder="Ex: Master en Informatique">
                                    </div>
                                    <div class="form-group">
                                        <label for="school">Établissement</label>
                                        <input type="text" name="school" id="school" value="<?php echo isset($etudiant->school) ? htmlspecialchars($etudiant->school) : ''; ?>" placeholder="Ex: Université de Paris">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="year">Niveau d'études</label>
                                    <select name="year" id="year">
                                        <option value="">Sélectionnez votre niveau</option>
                                        <option value="1" <?php echo (isset($etudiant->year) && $etudiant->year == '1') ? 'selected' : ''; ?>>1ère année</option>
                                        <option value="2" <?php echo (isset($etudiant->year) && $etudiant->year == '2') ? 'selected' : ''; ?>>2ème année</option>
                                        <option value="3" <?php echo (isset($etudiant->year) && $etudiant->year == '3') ? 'selected' : ''; ?>>3ème année</option>
                                        <option value="4" <?php echo (isset($etudiant->year) && $etudiant->year == '4') ? 'selected' : ''; ?>>4ème année</option>
                                        <option value="5" <?php echo (isset($etudiant->year) && $etudiant->year == '5') ? 'selected' : ''; ?>>5ème année</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-section">
                                <h3>CV et lettre de motivation</h3>
                                
                                <div class="form-group">
                                    <label for="cv">Curriculum Vitae (CV)</label>
                                    <div class="file-upload-container">
                                        <?php if(isset($etudiant->cv) && !empty($etudiant->cv)) : ?>
                                            <div class="current-file">
                                                <i class="fas fa-file-pdf"></i>
                                                <span><?php echo htmlspecialchars($etudiant->cv); ?></span>
                                                <a href="<?php echo URLROOT; ?>/public/uploads/cv/<?php echo $etudiant->cv; ?>" target="_blank" class="btn-icon" title="Voir le CV">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                            <div class="file-upload-options">
                                                <input type="hidden" name="use_current_cv" value="1" id="use_current_cv">
                                                <label class="checkbox-label">
                                                    <input type="checkbox" id="change_cv" name="change_cv">
                                                    <span>Télécharger un nouveau CV</span>
                                                </label>
                                            </div>
                                            <div class="file-upload-new hidden" id="new_cv_container">
                                                <input type="file" name="cv_file" id="cv_file" accept=".pdf,.doc,.docx">
                                                <label for="cv_file" class="file-upload-label">
                                                    <i class="fas fa-upload"></i> Choisir un fichier
                                                </label>
                                                <span class="selected-file" id="cv_file_name">Aucun fichier sélectionné</span>
                                            </div>
                                        <?php else : ?>
                                            <div class="file-upload-new">
                                                <input type="file" name="cv_file" id="cv_file" accept=".pdf,.doc,.docx" required>
                                                <label for="cv_file" class="file-upload-label">
                                                    <i class="fas fa-upload"></i> Choisir un fichier
                                                </label>
                                                <span class="selected-file" id="cv_file_name">Aucun fichier sélectionné</span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <small class="form-text">Formats acceptés: PDF, DOC, DOCX (max 5MB)</small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="cover_letter">Lettre de motivation</label>
                                    <textarea name="cover_letter" id="cover_letter" rows="8" placeholder="Rédigez votre lettre de motivation ici..." required></textarea>
                                    <small class="form-text">Expliquez pourquoi vous êtes intéressé par ce stage et pourquoi vous seriez un bon candidat.</small>
                                </div>
                            </div>
                            
                            <div class="form-section">
                                <h3>Informations complémentaires</h3>
                                <div class="form-group">
                                    <label for="additional_info">Informations supplémentaires (optionnel)</label>
                                    <textarea name="additional_info" id="additional_info" rows="4" placeholder="Ajoutez des informations supplémentaires si nécessaire..."></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label class="checkbox-label">
                                        <input type="checkbox" name="consent" id="consent" required>
                                        <span>J'accepte que mes données personnelles soient traitées dans le cadre de ma candidature</span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="form-actions">
                                <a href="<?php echo URLROOT; ?>/dashboard/stage/<?php echo $stage->id_internship; ?>" class="btn btn-outline">Annuler</a>
                                <button type="submit" class="btn btn-primary" id="submit-button">Envoyer ma candidature</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="postuler-sidebar">
                <div class="stage-summary-card">
                    <div class="card-header">
                        <h2>Récapitulatif du stage</h2>
                    </div>
                    <div class="card-body">
                        <div class="company-logo-container">
                            <?php if(isset($stage->company_logo) && !empty($stage->company_logo)) : ?>
                                <img src="<?php echo URLROOT; ?>/public/uploads/logos/<?php echo $stage->company_logo; ?>" alt="<?php echo htmlspecialchars($stage->company_name); ?>" class="company-logo">
                            <?php else : ?>
                                <div class="company-logo-placeholder">
                                    <?php echo substr(htmlspecialchars($stage->company_name), 0, 1); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <h3><?php echo htmlspecialchars($stage->title); ?></h3>
                        <div class="company-name"><?php echo htmlspecialchars($stage->company_name); ?></div>
                        
                        <div class="stage-details">
                            <div class="detail-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span><?php echo htmlspecialchars($stage->location); ?></span>
                            </div>
                            
                            <div class="detail-item">
                                <i class="far fa-clock"></i>
                                <span><?php echo htmlspecialchars($stage->duration); ?> mois</span>
                            </div>
                            
                            <?php if(isset($stage->salary) && !empty($stage->salary)) : ?>
                                <div class="detail-item">
                                    <i class="fas fa-euro-sign"></i>
                                    <span><?php echo htmlspecialchars($stage->salary); ?> €/mois</span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if(isset($stage->start_date) && !empty($stage->start_date)) : ?>
                                <div class="detail-item">
                                    <i class="fas fa-calendar-check"></i>
                                    <span>Début: <?php echo date('d/m/Y', strtotime($stage->start_date)); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="stage-short-description">
                            <?php echo substr(htmlspecialchars($stage->description), 0, 200) . '...'; ?>
                        </div>
                        
                        <a href="<?php echo URLROOT; ?>/dashboard/stage/<?php echo $stage->id_internship; ?>" class="view-stage-link">
                            Voir le détail du stage <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                
                <div class="application-tips-card">
                    <div class="card-header">
                        <h2>Conseils pour votre candidature</h2>
                    </div>
                    <div class="card-body">
                        <ul class="tips-list">
                            <li>
                                <i class="fas fa-check"></i>
                                <span>Assurez-vous que votre CV est à jour et adapté au stage visé.</span>
                            </li>
                            <li>
                                <i class="fas fa-check"></i>
                                <span>Personnalisez votre lettre de motivation pour cette entreprise et ce stage spécifique.</span>
                            </li>
                            <li>
                                <i class="fas fa-check"></i>
                                <span>Mettez en avant vos compétences et expériences pertinentes pour le poste.</span>
                            </li>
                            <li>
                                <i class="fas fa-check"></i>
                                <span>Soyez concis et précis dans vos descriptions.</span>
                            </li>
                            <li>
                                <i class="fas fa-check"></i>
                                <span>Relisez-vous attentivement pour éviter les fautes d'orthographe.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    <?php else : ?>
        <div class="not-found">
            <div class="not-found-icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <h2>Stage non trouvé</h2>
            <p>Le stage auquel vous souhaitez postuler n'existe pas ou a été supprimé.</p>
            <a href="<?php echo URLROOT; ?>/dashboard/stages" class="btn btn-primary">Voir tous les stages</a>
        </div>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestion de l'upload de CV
        const cvFileInput = document.getElementById('cv_file');
        const cvFileName = document.getElementById('cv_file_name');
        const changeCvCheckbox = document.getElementById('change_cv');
        const newCvContainer = document.getElementById('new_cv_container');
        const useCurrentCvInput = document.getElementById('use_current_cv');
        
        if (cvFileInput && cvFileName) {
            cvFileInput.addEventListener('change', function() {
                if (this.files && this.files.length > 0) {
                    cvFileName.textContent = this.files[0].name;
                } else {
                    cvFileName.textContent = 'Aucun fichier sélectionné';
                }
            });
        }
        
        if (changeCvCheckbox && newCvContainer && useCurrentCvInput) {
            changeCvCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    newCvContainer.classList.remove('hidden');
                    useCurrentCvInput.value = '0';
                    cvFileInput.setAttribute('required', 'required');
                } else {
                    newCvContainer.classList.add('hidden');
                    useCurrentCvInput.value = '1';
                    cvFileInput.removeAttribute('required');
                }
            });
        }
        
        // Validation du formulaire
        const applicationForm = document.getElementById('applicationForm');
        const submitButton = document.getElementById('submit-button');
        const consentCheckbox = document.getElementById('consent');
        
        if (applicationForm && submitButton && consentCheckbox) {
            // Désactiver/activer le bouton selon la case à cocher de consentement
            consentCheckbox.addEventListener('change', function() {
                submitButton.disabled = !this.checked;
            });
            
            // Initialiser l'état du bouton
            submitButton.disabled = !consentCheckbox.checked;
            
            // Validation avant soumission
            applicationForm.addEventListener('submit', function(e) {
                // Vérifier si un CV est fourni
                if (changeCvCheckbox && changeCvCheckbox.checked && (!cvFileInput.files || cvFileInput.files.length === 0)) {
                    e.preventDefault();
                    alert('Veuillez sélectionner un fichier CV.');
                    return;
                }
                
                // Vérifier la taille du CV (max 5MB)
                if (cvFileInput.files && cvFileInput.files.length > 0) {
                    const maxSize = 5 * 1024 * 1024; // 5MB
                    if (cvFileInput.files[0].size > maxSize) {
                        e.preventDefault();
                        alert('La taille de votre CV ne doit pas dépasser 5MB.');
                        return;
                    }
                }
            });
        }
    });
</script>

<?php require APPROOT . '/views/shared/dashboard_footer.php'; ?> 