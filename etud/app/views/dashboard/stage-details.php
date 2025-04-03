<?php
// S'assurer que les constantes sont définies
if (!defined('APPROOT')) {
    require_once dirname(dirname(__DIR__)) . '/config/config.php';
}

$data['active'] = 'stages';
require APPROOT . '/views/shared/dashboard_header.php'; 
?>

<div class="dashboard-welcome">
    <h1><?php echo $data['stage']->titre; ?></h1>
    <p><?php echo $data['stage']->entreprise_nom; ?></p>
</div>

<div class="dashboard-content-row">
    <div class="dashboard-card stage-details-card">
        <div class="card-header">
            <h2>Détails de l'offre</h2>
        </div>
        <div class="card-body">
            <div class="stage-header">
                <div class="stage-info">
                    <div class="stage-company">
                        <?php if(isset($data['stage']->logo_url) && !empty($data['stage']->logo_url)): ?>
                            <img src="<?php echo URLROOT . '/' . $data['stage']->logo_url; ?>" alt="Logo entreprise">
                        <?php else: ?>
                            <div class="company-placeholder">
                                <i class="fas fa-building"></i>
                            </div>
                        <?php endif; ?>
                        <div>
                            <h3><?php echo $data['stage']->entreprise_nom; ?></h3>
                            <?php if(isset($data['stage']->secteur)): ?>
                                <span class="company-sector"><?php echo $data['stage']->secteur; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="stage-actions">
                        <?php if($data['dejaPostule']): ?>
                            <div class="already-applied">
                                <i class="fas fa-check-circle"></i>
                                <span>Vous avez déjà postulé</span>
                            </div>
                        <?php else: ?>
                            <a href="<?php echo URLROOT; ?>/dashboard/postuler/<?php echo $data['stage']->id; ?>" class="btn btn-primary">Postuler</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="stage-meta">
                <div class="meta-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span><?php echo isset($data['stage']->lieu) ? $data['stage']->lieu : 'Non spécifié'; ?></span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-calendar"></i>
                    <span>
                        <?php 
                            if(isset($data['stage']->date_debut) && isset($data['stage']->date_fin)) {
                                echo 'Du ' . date('d/m/Y', strtotime($data['stage']->date_debut)) . ' au ' . date('d/m/Y', strtotime($data['stage']->date_fin));
                            } else {
                                echo 'Dates non spécifiées';
                            }
                        ?>
                    </span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-clock"></i>
                    <span><?php echo isset($data['stage']->duree) ? $data['stage']->duree . ' mois' : 'Durée non spécifiée'; ?></span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-tag"></i>
                    <span><?php echo isset($data['stage']->type) ? $data['stage']->type : 'Type non spécifié'; ?></span>
                </div>
            </div>
            
            <div class="stage-description">
                <h3>Description</h3>
                <div class="description-content">
                    <?php echo nl2br($data['stage']->description); ?>
                </div>
            </div>
            
            <?php if(isset($data['stage']->competences_requises) && !empty($data['stage']->competences_requises)): ?>
            <div class="stage-skills">
                <h3>Compétences requises</h3>
                <div class="skills-list">
                    <?php 
                        $competences = explode(',', $data['stage']->competences_requises);
                        foreach($competences as $competence): 
                    ?>
                        <span class="skill-badge"><?php echo trim($competence); ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if(isset($data['stage']->remuneration) && !empty($data['stage']->remuneration)): ?>
            <div class="stage-salary">
                <h3>Rémunération</h3>
                <p><?php echo $data['stage']->remuneration; ?> €/mois</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="dashboard-card company-info">
        <div class="card-header">
            <h2>À propos de l'entreprise</h2>
        </div>
        <div class="card-body">
            <?php if(isset($data['stage']->entreprise_description) && !empty($data['stage']->entreprise_description)): ?>
                <div class="company-description">
                    <?php echo nl2br($data['stage']->entreprise_description); ?>
                </div>
            <?php else: ?>
                <p>Aucune information disponible sur cette entreprise.</p>
            <?php endif; ?>
            
            <?php if(isset($data['stage']->entreprise_site) && !empty($data['stage']->entreprise_site)): ?>
                <div class="company-website">
                    <a href="<?php echo $data['stage']->entreprise_site; ?>" target="_blank" class="btn btn-outline">
                        <i class="fas fa-globe"></i> Visiter le site web
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .stage-details-card {
        flex: 2;
    }
    
    .company-info {
        flex: 1;
    }
    
    .stage-header {
        margin-bottom: 1.5rem;
    }
    
    .stage-info {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }
    
    .stage-company {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .stage-company img {
        width: 60px;
        height: 60px;
        object-fit: contain;
        border-radius: var(--border-radius);
        border: 1px solid #e2e8f0;
    }
    
    .company-placeholder {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f7f9fc;
        border-radius: var(--border-radius);
        border: 1px solid #e2e8f0;
    }
    
    .company-placeholder i {
        font-size: 1.5rem;
        color: var(--primary-color);
    }
    
    .stage-company h3 {
        margin: 0;
        font-size: 1.1rem;
    }
    
    .company-sector {
        display: inline-block;
        font-size: 0.85rem;
        color: var(--dark-gray);
        margin-top: 0.25rem;
    }
    
    .already-applied {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background-color: rgba(46, 204, 113, 0.1);
        color: var(--success-color);
        border-radius: var(--border-radius);
        font-weight: 500;
    }
    
    .stage-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding: 1rem;
        background-color: #f7f9fc;
        border-radius: var(--border-radius);
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
    }
    
    .meta-item i {
        color: var(--primary-color);
    }
    
    .stage-description, .stage-skills, .stage-salary {
        margin-bottom: 1.5rem;
    }
    
    .stage-description h3, .stage-skills h3, .stage-salary h3 {
        font-size: 1.1rem;
        margin-bottom: 0.75rem;
    }
    
    .description-content {
        line-height: 1.6;
    }
    
    .skills-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .skill-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        background-color: rgba(67, 97, 238, 0.1);
        color: var(--primary-color);
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
    }
    
    .company-description {
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }
    
    .company-website {
        margin-top: 1rem;
    }
</style>

<?php require APPROOT . '/views/shared/dashboard_footer.php'; ?> 