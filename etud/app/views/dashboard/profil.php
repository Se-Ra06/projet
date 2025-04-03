<?php
// S'assurer que le fichier config est inclus
if (!defined('APPROOT')) {
    require_once '../../config/config.php';
}

$data['active'] = 'profil';
$data['title'] = 'Mon profil';
require APPROOT . '/views/shared/dashboard_header.php'; 

// Récupérer les données de l'étudiant depuis $data
$etudiant = $data['etudiant'] ?? null;
?>

<div class="page-header">
    <h1>Mon profil</h1>
    <p>Gérez vos informations personnelles et votre CV pour optimiser vos candidatures.</p>
</div>

<div class="profile-container">
    <div class="profile-sidebar">
        <div class="profile-image-container">
            <?php if(isset($etudiant->image) && !empty($etudiant->image)) : ?>
                <img src="<?php echo URLROOT; ?>/public/uploads/profile/<?php echo $etudiant->image; ?>" alt="Photo de profil" class="profile-image">
            <?php else : ?>
                <div class="profile-image-placeholder">
                    <i class="fas fa-user"></i>
                </div>
            <?php endif; ?>
            <div class="profile-image-upload">
                <form action="<?php echo URLROOT; ?>/dashboard/update_profile_image" method="post" enctype="multipart/form-data" id="profileImageForm">
                    <label for="profile_image" class="btn btn-sm btn-outline"><i class="fas fa-camera"></i> Changer</label>
                    <input type="file" name="profile_image" id="profile_image" class="hidden" accept="image/*">
                </form>
            </div>
        </div>
        
        <div class="profile-info">
            <h2><?php echo isset($etudiant->name) ? htmlspecialchars($etudiant->name) : 'Prénom Nom'; ?></h2>
            <p class="profile-role">Étudiant</p>
            
            <div class="profile-detail">
                <i class="fas fa-envelope"></i>
                <span><?php echo isset($etudiant->email) ? htmlspecialchars($etudiant->email) : 'email@example.com'; ?></span>
            </div>
            
            <?php if(isset($etudiant->phone_number) && !empty($etudiant->phone_number)) : ?>
                <div class="profile-detail">
                    <i class="fas fa-phone"></i>
                    <span><?php echo htmlspecialchars($etudiant->phone_number); ?></span>
                </div>
            <?php endif; ?>
            
            <?php if(isset($etudiant->location) && !empty($etudiant->location)) : ?>
                <div class="profile-detail">
                    <i class="fas fa-map-marker-alt"></i>
                    <span><?php echo htmlspecialchars($etudiant->location); ?></span>
                </div>
            <?php endif; ?>
            
            <?php if(isset($etudiant->year) && !empty($etudiant->year)) : ?>
                <div class="profile-detail">
                    <i class="fas fa-graduation-cap"></i>
                    <span><?php echo htmlspecialchars($etudiant->year); ?>e année</span>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="profile-cv">
            <h3>CV</h3>
            <?php if(isset($etudiant->cv) && !empty($etudiant->cv)) : ?>
                <div class="cv-file">
                    <i class="fas fa-file-pdf"></i>
                    <span><?php echo htmlspecialchars($etudiant->cv); ?></span>
                    <div class="cv-actions">
                        <a href="<?php echo URLROOT; ?>/public/uploads/cv/<?php echo $etudiant->cv; ?>" class="btn-icon" title="Télécharger" target="_blank"><i class="fas fa-download"></i></a>
                    </div>
                </div>
            <?php else : ?>
                <div class="empty-cv">
                    <p>Aucun CV téléchargé</p>
                </div>
            <?php endif; ?>
            
            <form action="<?php echo URLROOT; ?>/dashboard/update_cv" method="post" enctype="multipart/form-data" id="cvForm">
                <label for="cv_file" class="btn btn-outline btn-block"><i class="fas fa-upload"></i> <?php echo isset($etudiant->cv) && !empty($etudiant->cv) ? 'Mettre à jour le CV' : 'Télécharger un CV'; ?></label>
                <input type="file" name="cv_file" id="cv_file" class="hidden" accept=".pdf,.doc,.docx">
            </form>
        </div>
    </div>
    
    <div class="profile-content">
        <div class="profile-section">
            <div class="section-header">
                <h2>Informations personnelles</h2>
                <button class="btn-edit" id="editPersonalInfo"><i class="fas fa-edit"></i> Modifier</button>
            </div>
            
            <div class="section-content" id="personalInfoView">
                <div class="profile-field">
                    <div class="field-label">Nom complet</div>
                    <div class="field-value"><?php echo isset($etudiant->name) ? htmlspecialchars($etudiant->name) : 'Non renseigné'; ?></div>
                </div>
                <div class="profile-field">
                    <div class="field-label">Email</div>
                    <div class="field-value"><?php echo isset($etudiant->email) ? htmlspecialchars($etudiant->email) : 'Non renseigné'; ?></div>
                </div>
                <div class="profile-field">
                    <div class="field-label">Téléphone</div>
                    <div class="field-value"><?php echo isset($etudiant->phone_number) && !empty($etudiant->phone_number) ? htmlspecialchars($etudiant->phone_number) : 'Non renseigné'; ?></div>
                </div>
                <div class="profile-field">
                    <div class="field-label">Date de naissance</div>
                    <div class="field-value"><?php echo isset($etudiant->date_of_birth) && !empty($etudiant->date_of_birth) ? date('d/m/Y', strtotime($etudiant->date_of_birth)) : 'Non renseigné'; ?></div>
                </div>
                <div class="profile-field">
                    <div class="field-label">Localisation</div>
                    <div class="field-value"><?php echo isset($etudiant->location) && !empty($etudiant->location) ? htmlspecialchars($etudiant->location) : 'Non renseigné'; ?></div>
                </div>
            </div>
            
            <div class="section-edit hidden" id="personalInfoEdit">
                <form action="<?php echo URLROOT; ?>/dashboard/update_profile" method="post" id="profileForm">
                    <div class="form-group">
                        <label for="name">Nom complet</label>
                        <input type="text" name="name" id="name" value="<?php echo isset($etudiant->name) ? htmlspecialchars($etudiant->name) : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" value="<?php echo isset($etudiant->email) ? htmlspecialchars($etudiant->email) : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="phone_number">Téléphone</label>
                        <input type="tel" name="phone_number" id="phone_number" value="<?php echo isset($etudiant->phone_number) ? htmlspecialchars($etudiant->phone_number) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="date_of_birth">Date de naissance</label>
                        <input type="date" name="date_of_birth" id="date_of_birth" value="<?php echo isset($etudiant->date_of_birth) ? date('Y-m-d', strtotime($etudiant->date_of_birth)) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="location">Localisation</label>
                        <input type="text" name="location" id="location" value="<?php echo isset($etudiant->location) ? htmlspecialchars($etudiant->location) : ''; ?>">
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-outline" id="cancelPersonalInfo">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="profile-section">
            <div class="section-header">
                <h2>Informations académiques</h2>
                <button class="btn-edit" id="editAcademicInfo"><i class="fas fa-edit"></i> Modifier</button>
            </div>
            
            <div class="section-content" id="academicInfoView">
                <div class="profile-field">
                    <div class="field-label">Niveau d'études</div>
                    <div class="field-value"><?php echo isset($etudiant->year) && !empty($etudiant->year) ? htmlspecialchars($etudiant->year) . 'e année' : 'Non renseigné'; ?></div>
                </div>
                <div class="profile-field">
                    <div class="field-label">Formation</div>
                    <div class="field-value"><?php echo isset($etudiant->degree) && !empty($etudiant->degree) ? htmlspecialchars($etudiant->degree) : 'Non renseigné'; ?></div>
                </div>
                <div class="profile-field">
                    <div class="field-label">Établissement</div>
                    <div class="field-value"><?php echo isset($etudiant->school) && !empty($etudiant->school) ? htmlspecialchars($etudiant->school) : 'Non renseigné'; ?></div>
                </div>
            </div>
            
            <div class="section-edit hidden" id="academicInfoEdit">
                <form action="<?php echo URLROOT; ?>/dashboard/update_academic" method="post">
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
                    <div class="form-group">
                        <label for="degree">Formation</label>
                        <input type="text" name="degree" id="degree" value="<?php echo isset($etudiant->degree) ? htmlspecialchars($etudiant->degree) : ''; ?>" placeholder="Ex: Master en Informatique">
                    </div>
                    <div class="form-group">
                        <label for="school">Établissement</label>
                        <input type="text" name="school" id="school" value="<?php echo isset($etudiant->school) ? htmlspecialchars($etudiant->school) : ''; ?>" placeholder="Ex: Université de Paris">
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-outline" id="cancelAcademicInfo">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="profile-section">
            <div class="section-header">
                <h2>À propos de moi</h2>
                <button class="btn-edit" id="editAboutMe"><i class="fas fa-edit"></i> Modifier</button>
            </div>
            
            <div class="section-content" id="aboutMeView">
                <div class="profile-bio">
                    <?php if(isset($etudiant->description) && !empty($etudiant->description)) : ?>
                        <p><?php echo nl2br(htmlspecialchars($etudiant->description)); ?></p>
                    <?php else : ?>
                        <p class="empty-content">Ajoutez une description pour vous présenter aux recruteurs.</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="section-edit hidden" id="aboutMeEdit">
                <form action="<?php echo URLROOT; ?>/dashboard/update_description" method="post">
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" rows="6" placeholder="Parlez de vous, de vos compétences, de vos objectifs professionnels..."><?php echo isset($etudiant->description) ? htmlspecialchars($etudiant->description) : ''; ?></textarea>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-outline" id="cancelAboutMe">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestion de l'upload d'image de profil
        const profileImageInput = document.getElementById('profile_image');
        if (profileImageInput) {
            profileImageInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    document.getElementById('profileImageForm').submit();
                }
            });
        }
        
        // Gestion de l'upload de CV
        const cvFileInput = document.getElementById('cv_file');
        if (cvFileInput) {
            cvFileInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    document.getElementById('cvForm').submit();
                }
            });
        }
        
        // Gestion des sections d'édition
        function setupEditSection(viewId, editId, editBtnId, cancelBtnId) {
            const viewSection = document.getElementById(viewId);
            const editSection = document.getElementById(editId);
            const editBtn = document.getElementById(editBtnId);
            const cancelBtn = document.getElementById(cancelBtnId);
            
            if (editBtn) {
                editBtn.addEventListener('click', function() {
                    viewSection.classList.add('hidden');
                    editSection.classList.remove('hidden');
                });
            }
            
            if (cancelBtn) {
                cancelBtn.addEventListener('click', function() {
                    editSection.classList.add('hidden');
                    viewSection.classList.remove('hidden');
                });
            }
        }
        
        // Configuration des sections
        setupEditSection('personalInfoView', 'personalInfoEdit', 'editPersonalInfo', 'cancelPersonalInfo');
        setupEditSection('academicInfoView', 'academicInfoEdit', 'editAcademicInfo', 'cancelAcademicInfo');
        setupEditSection('aboutMeView', 'aboutMeEdit', 'editAboutMe', 'cancelAboutMe');
    });
</script>

<?php require APPROOT . '/views/shared/dashboard_footer.php'; ?> 