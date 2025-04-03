<?php
// S'assurer que le fichier config est inclus
if (!defined('APPROOT')) {
    require_once '../../config/config.php';
}

// Inclure l'en-tête du dashboard
$data['active'] = 'stages';
require APPROOT . '/views/shared/dashboard_header.php'; 
?>

<div class="dashboard-welcome">
    <a href="<?php echo URLROOT; ?>/dashboard/stages" class="back-link">
        <i class="fas fa-arrow-left"></i> Retour aux offres
    </a>
    <h1><?php echo $data['stage']->title; ?></h1>
    <div class="entreprise-info">
        <span><i class="fas fa-building"></i> <?php echo $data['stage']->name_company; ?></span>
        <span><i class="fas fa-map-marker-alt"></i> <?php echo $data['stage']->location; ?></span>
    </div>
</div>

<div class="dashboard-container">
    <div class="stage-details-container">
        <div class="stage-details-main">
            <div class="dashboard-card">
                <div class="card-header">
                    <h2>Description du stage</h2>
                </div>
                <div class="card-body">
                    <div class="stage-description">
                        <?php echo $data['stage']->description; ?>
                    </div>
                    
                    <div class="stage-meta">
                        <div class="meta-item">
                            <i class="fas fa-clock"></i>
                            <div>
                                <strong>Durée:</strong>
                                <span><?php echo $data['stage']->duration; ?> mois</span>
                            </div>
                        </div>
                        
                        <div class="meta-item">
                            <i class="fas fa-calendar-alt"></i>
                            <div>
                                <strong>Date de début:</strong>
                                <span><?php echo date('d/m/Y', strtotime($data['stage']->start_date)); ?></span>
                            </div>
                        </div>
                        
                        <div class="meta-item">
                            <i class="fas fa-tag"></i>
                            <div>
                                <strong>Type:</strong>
                                <span><?php echo $data['stage']->type; ?></span>
                            </div>
                        </div>
                        
                        <?php if($data['stage']->remuneration > 0): ?>
                        <div class="meta-item">
                            <i class="fas fa-euro-sign"></i>
                            <div>
                                <strong>Rémunération:</strong>
                                <span><?php echo $data['stage']->remuneration; ?> € / mois</span>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="dashboard-card">
                <div class="card-header">
                    <h2>Compétences requises</h2>
                </div>
                <div class="card-body">
                    <div class="stage-skills">
                        <?php 
                        $skills = explode(',', $data['stage']->required_skills);
                        foreach($skills as $skill): 
                            $skill = trim($skill);
                            if(!empty($skill)):
                        ?>
                            <span class="skill-badge"><?php echo $skill; ?></span>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </div>
                </div>
            </div>
            
            <?php if(!empty($data['stage']->additional_info)): ?>
            <div class="dashboard-card">
                <div class="card-header">
                    <h2>Informations complémentaires</h2>
                </div>
                <div class="card-body">
                    <div class="stage-additional-info">
                        <?php echo $data['stage']->additional_info; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="stage-details-sidebar">
            <div class="dashboard-card">
                <div class="card-header">
                    <h2>Actions</h2>
                </div>
                <div class="card-body">
                    <div class="stage-actions">
                        <?php if($data['dejaPostule']): ?>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> Vous avez déjà postulé à cette offre
                            </div>
                        <?php else: ?>
                            <a href="<?php echo URLROOT; ?>/dashboard/postuler/<?php echo $data['stage']->id_internship; ?>" class="btn btn-primary btn-block">
                                <i class="fas fa-paper-plane"></i> Postuler à cette offre
                            </a>
                        <?php endif; ?>
                        
                        <button class="btn btn-outline btn-block wishlist-btn" title="Ajouter aux favoris">
                            <i class="fas fa-heart"></i> <span>Ajouter aux favoris</span>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="dashboard-card">
                <div class="card-header">
                    <h2>À propos de l'entreprise</h2>
                </div>
                <div class="card-body">
                    <div class="entreprise-details">
                        <h3><?php echo $data['stage']->name_company; ?></h3>
                        <p><?php echo $data['stage']->address; ?></p>
                        
                        <?php if(!empty($data['stage']->sector)): ?>
                        <div class="meta-item">
                            <i class="fas fa-industry"></i>
                            <div>
                                <strong>Secteur:</strong>
                                <span><?php echo $data['stage']->sector; ?></span>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if(!empty($data['stage']->website)): ?>
                        <div class="meta-item">
                            <i class="fas fa-globe"></i>
                            <div>
                                <strong>Site web:</strong>
                                <a href="<?php echo $data['stage']->website; ?>" target="_blank"><?php echo $data['stage']->website; ?></a>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .back-link {
        display: inline-flex;
        align-items: center;
        font-size: 0.9rem;
        color: var(--primary-color);
        margin-bottom: 1rem;
        text-decoration: none;
        transition: color 0.2s;
    }
    
    .back-link:hover {
        color: var(--primary-color-dark);
    }
    
    .back-link i {
        margin-right: 0.5rem;
    }
    
    .dashboard-welcome {
        margin-bottom: 1.5rem;
    }
    
    .entreprise-info {
        display: flex;
        gap: 1.5rem;
        margin-top: 0.5rem;
        font-size: 0.95rem;
        color: var(--dark-color-light);
    }
    
    .entreprise-info span {
        display: flex;
        align-items: center;
    }
    
    .entreprise-info i {
        margin-right: 0.5rem;
        color: var(--primary-color);
    }
    
    .stage-details-container {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
    }
    
    .stage-details-main {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }
    
    .stage-details-sidebar {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }
    
    .stage-description {
        margin-bottom: 2rem;
        line-height: 1.6;
        color: var(--dark-color);
    }
    
    .stage-meta {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
    }
    
    .meta-item {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }
    
    .meta-item i {
        color: var(--primary-color);
        margin-top: 2px;
    }
    
    .meta-item strong {
        display: block;
        margin-bottom: 0.25rem;
        font-weight: 600;
        color: var(--dark-color);
    }
    
    .meta-item span {
        color: var(--dark-color-light);
    }
    
    .stage-skills {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .skill-badge {
        background-color: var(--light-color);
        color: var(--dark-color);
        padding: 0.5rem 0.75rem;
        border-radius: 50px;
        font-size: 0.85rem;
        display: inline-block;
    }
    
    .stage-additional-info {
        line-height: 1.6;
        color: var(--dark-color);
    }
    
    .stage-actions {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .btn-block {
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
    }
    
    .wishlist-btn {
        color: var(--gray-color);
    }
    
    .wishlist-btn.active {
        color: var(--danger-color);
    }
    
    .wishlist-btn i {
        font-size: 1.1rem;
    }
    
    .entreprise-details h3 {
        margin: 0 0 0.5rem 0;
        font-size: 1.15rem;
        color: var(--dark-color);
    }
    
    .entreprise-details p {
        margin-bottom: 1.25rem;
        color: var(--dark-color-light);
    }
    
    .entreprise-details .meta-item {
        margin-bottom: 1rem;
    }
    
    .entreprise-details a {
        color: var(--primary-color);
        text-decoration: none;
        word-break: break-all;
    }
    
    .entreprise-details a:hover {
        text-decoration: underline;
    }
    
    .alert {
        padding: 1rem;
        border-radius: var(--border-radius);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .alert-info {
        background-color: var(--info-color-light);
        color: var(--info-color);
        border: 1px solid var(--info-color-border);
    }
    
    /* Responsive design */
    @media (max-width: 992px) {
        .stage-details-container {
            grid-template-columns: 1fr;
        }
        
        .stage-meta {
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gérer le bouton wishlist
        const wishlistBtn = document.querySelector('.wishlist-btn');
        
        wishlistBtn.addEventListener('click', function() {
            this.classList.toggle('active');
            
            if(this.classList.contains('active')) {
                this.querySelector('span').textContent = 'Retiré des favoris';
                // Ici, vous pourriez ajouter du code AJAX pour enregistrer la wishlist en base de données
            } else {
                this.querySelector('span').textContent = 'Ajouter aux favoris';
                // Ici, vous pourriez ajouter du code AJAX pour supprimer de la wishlist
            }
        });
    });
</script>

<?php require APPROOT . '/views/shared/dashboard_footer.php'; ?> 