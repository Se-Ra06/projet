<?php 
// S'assurer que le fichier config est inclus
if (!defined('APPROOT')) {
    require_once '../../config/config.php';
}

$data['active'] = 'stages';
$data['title'] = isset($data['stage']->title) ? $data['stage']->title : 'Détail du stage';
require APPROOT . '/views/shared/dashboard_header.php'; 

// Récupérer les données du stage depuis $data
$stage = $data['stage'] ?? null;
$isApplied = $data['is_applied'] ?? false;
$isInWishlist = $data['is_in_wishlist'] ?? false;
?>

<div class="stage-detail-container">
    <?php if($stage) : ?>
        <div class="stage-detail-header">
            <div class="header-left">
                <div class="back-button">
                    <a href="<?php echo URLROOT; ?>/dashboard/stages" class="btn-icon">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
                <div class="stage-title-container">
                    <h1><?php echo htmlspecialchars($stage->title); ?></h1>
                    <div class="company-name"><?php echo htmlspecialchars($stage->company_name); ?></div>
                </div>
            </div>
            <div class="header-right">
                <div class="stage-actions">
                    <button class="btn-icon <?php echo $isInWishlist ? 'active' : ''; ?>" id="wishlistButton" data-id="<?php echo $stage->id_internship; ?>" title="<?php echo $isInWishlist ? 'Retirer des favoris' : 'Ajouter aux favoris'; ?>">
                        <i class="<?php echo $isInWishlist ? 'fas' : 'far'; ?> fa-heart"></i>
                    </button>
                    <?php if(!$isApplied) : ?>
                        <a href="<?php echo URLROOT; ?>/dashboard/postuler/<?php echo $stage->id_internship; ?>" class="btn btn-primary">Postuler</a>
                    <?php else : ?>
                        <button class="btn btn-disabled" disabled>Déjà postulé</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="stage-detail-content">
            <div class="stage-detail-main">
                <div class="stage-info-card">
                    <div class="card-header">
                        <h2>Informations sur le stage</h2>
                    </div>
                    <div class="card-body">
                        <div class="stage-key-details">
                            <div class="detail-item">
                                <div class="detail-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="detail-content">
                                    <div class="detail-label">Localisation</div>
                                    <div class="detail-value"><?php echo htmlspecialchars($stage->location); ?></div>
                                </div>
                            </div>
                            
                            <div class="detail-item">
                                <div class="detail-icon">
                                    <i class="far fa-clock"></i>
                                </div>
                                <div class="detail-content">
                                    <div class="detail-label">Durée</div>
                                    <div class="detail-value"><?php echo htmlspecialchars($stage->duration); ?> mois</div>
                                </div>
                            </div>
                            
                            <?php if(isset($stage->salary) && !empty($stage->salary)) : ?>
                                <div class="detail-item">
                                    <div class="detail-icon">
                                        <i class="fas fa-euro-sign"></i>
                                    </div>
                                    <div class="detail-content">
                                        <div class="detail-label">Rémunération</div>
                                        <div class="detail-value"><?php echo htmlspecialchars($stage->salary); ?> €/mois</div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <div class="detail-item">
                                <div class="detail-icon">
                                    <i class="far fa-calendar-alt"></i>
                                </div>
                                <div class="detail-content">
                                    <div class="detail-label">Date de publication</div>
                                    <div class="detail-value"><?php echo date('d/m/Y', strtotime($stage->date_posted)); ?></div>
                                </div>
                            </div>
                            
                            <?php if(isset($stage->start_date) && !empty($stage->start_date)) : ?>
                                <div class="detail-item">
                                    <div class="detail-icon">
                                        <i class="fas fa-calendar-check"></i>
                                    </div>
                                    <div class="detail-content">
                                        <div class="detail-label">Date de début</div>
                                        <div class="detail-value"><?php echo date('d/m/Y', strtotime($stage->start_date)); ?></div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="stage-description-card">
                    <div class="card-header">
                        <h2>Description du stage</h2>
                    </div>
                    <div class="card-body">
                        <div class="stage-description">
                            <?php echo nl2br(htmlspecialchars($stage->description)); ?>
                        </div>
                    </div>
                </div>
                
                <?php if(isset($stage->requirements) && !empty($stage->requirements)) : ?>
                    <div class="stage-requirements-card">
                        <div class="card-header">
                            <h2>Prérequis et compétences recherchées</h2>
                        </div>
                        <div class="card-body">
                            <div class="stage-requirements">
                                <?php echo nl2br(htmlspecialchars($stage->requirements)); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="stage-detail-sidebar">
                <div class="company-card">
                    <div class="card-header">
                        <h2>À propos de l'entreprise</h2>
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
                        
                        <h3 class="company-name"><?php echo htmlspecialchars($stage->company_name); ?></h3>
                        
                        <?php if(isset($stage->company_website) && !empty($stage->company_website)) : ?>
                            <div class="company-website">
                                <a href="<?php echo htmlspecialchars($stage->company_website); ?>" target="_blank" rel="noopener noreferrer">
                                    <i class="fas fa-globe"></i> Site web
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <?php if(isset($stage->company_description) && !empty($stage->company_description)) : ?>
                            <div class="company-description">
                                <?php echo nl2br(htmlspecialchars($stage->company_description)); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="contact-card">
                    <div class="card-header">
                        <h2>Contact</h2>
                    </div>
                    <div class="card-body">
                        <?php if(isset($stage->contact_name) && !empty($stage->contact_name)) : ?>
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="contact-content">
                                    <div class="contact-label">Nom</div>
                                    <div class="contact-value"><?php echo htmlspecialchars($stage->contact_name); ?></div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if(isset($stage->contact_email) && !empty($stage->contact_email)) : ?>
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="contact-content">
                                    <div class="contact-label">Email</div>
                                    <div class="contact-value">
                                        <a href="mailto:<?php echo htmlspecialchars($stage->contact_email); ?>">
                                            <?php echo htmlspecialchars($stage->contact_email); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if(isset($stage->contact_phone) && !empty($stage->contact_phone)) : ?>
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div class="contact-content">
                                    <div class="contact-label">Téléphone</div>
                                    <div class="contact-value">
                                        <a href="tel:<?php echo htmlspecialchars($stage->contact_phone); ?>">
                                            <?php echo htmlspecialchars($stage->contact_phone); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="call-to-action">
                    <?php if(!$isApplied) : ?>
                        <a href="<?php echo URLROOT; ?>/dashboard/postuler/<?php echo $stage->id_internship; ?>" class="btn btn-primary btn-lg btn-block">Postuler maintenant</a>
                    <?php else : ?>
                        <div class="already-applied">
                            <i class="fas fa-check-circle"></i>
                            <span>Vous avez déjà postulé à ce stage</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php else : ?>
        <div class="not-found">
            <div class="not-found-icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <h2>Stage non trouvé</h2>
            <p>Le stage que vous recherchez n'existe pas ou a été supprimé.</p>
            <a href="<?php echo URLROOT; ?>/dashboard/stages" class="btn btn-primary">Voir tous les stages</a>
        </div>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestion du bouton de wishlist
        const wishlistButton = document.getElementById('wishlistButton');
        
        if (wishlistButton) {
            wishlistButton.addEventListener('click', function() {
                const stageId = this.getAttribute('data-id');
                const isActive = this.classList.contains('active');
                const action = isActive ? 'remove' : 'add';
                
                // Envoyer la requête AJAX
                fetch(`${URLROOT}/dashboard/${action}_wishlist/${stageId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Mettre à jour l'apparence du bouton
                        if (action === 'add') {
                            wishlistButton.classList.add('active');
                            wishlistButton.title = 'Retirer des favoris';
                            wishlistButton.querySelector('i').classList.remove('far');
                            wishlistButton.querySelector('i').classList.add('fas');
                        } else {
                            wishlistButton.classList.remove('active');
                            wishlistButton.title = 'Ajouter aux favoris';
                            wishlistButton.querySelector('i').classList.remove('fas');
                            wishlistButton.querySelector('i').classList.add('far');
                        }
                    } else {
                        alert('Une erreur est survenue. Veuillez réessayer.');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Une erreur est survenue lors de la communication avec le serveur.');
                });
            });
        }
    });
</script>

<?php require APPROOT . '/views/shared/dashboard_footer.php'; ?> 