<?php 
// S'assurer que le fichier config est inclus
if (!defined('APPROOT')) {
    require_once '../../config/config.php';
}

$data['active'] = 'dashboard';
require APPROOT . '/views/shared/dashboard_header.php'; 
?>

<div class="dashboard-welcome">
    <h1>Bienvenue sur votre espace étudiant</h1>
    <p>Retrouvez ici toutes les informations concernant vos recherches de stage</p>
</div>

<div class="dashboard-stats">
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-file-alt"></i>
        </div>
        <div class="stat-content">
            <h3>Total Candidatures</h3>
            <div class="stat-number"><?php echo $data['candidatures']->total_candidatures ?? 0; ?></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon status-pending">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-content">
            <h3>En attente</h3>
            <div class="stat-number"><?php echo $data['candidatures']->candidatures_en_attente ?? 0; ?></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon status-accepted">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-content">
            <h3>Acceptées</h3>
            <div class="stat-number"><?php echo $data['candidatures']->candidatures_acceptees ?? 0; ?></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon status-rejected">
            <i class="fas fa-times-circle"></i>
        </div>
        <div class="stat-content">
            <h3>Refusées</h3>
            <div class="stat-number"><?php echo $data['candidatures']->candidatures_refusees ?? 0; ?></div>
        </div>
    </div>
</div>

<div class="dashboard-content-row">
    <div class="dashboard-card recent-stages">
        <div class="card-header">
            <h2>Offres récentes</h2>
            <a href="<?php echo URLROOT; ?>/dashboard/stages" class="btn-link">Voir tout <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="card-body">
            <?php if(isset($data['stages']) && count($data['stages']) > 0) : ?>
                <div class="stage-list">
                    <?php foreach($data['stages'] as $stage) : ?>
                        <div class="stage-card">
                            <div class="stage-header">
                                <h3><?php echo $stage->titre; ?></h3>
                                <span class="stage-company"><?php echo $stage->entreprise_nom; ?></span>
                            </div>
                            <div class="stage-details">
                                <div class="stage-detail">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span><?php echo isset($stage->lieu) ? $stage->lieu : 'Non spécifié'; ?></span>
                                </div>
                                <div class="stage-detail">
                                    <i class="fas fa-calendar"></i>
                                    <span><?php echo isset($stage->duree) ? $stage->duree . ' mois' : 'Non spécifié'; ?></span>
                                </div>
                            </div>
                            <div class="stage-actions">
                                <a href="<?php echo URLROOT; ?>/dashboard/stage/<?php echo $stage->id; ?>" class="btn btn-outline">Détails</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <div class="empty-state">
                    <i class="fas fa-briefcase"></i>
                    <p>Aucune offre de stage disponible pour le moment.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="dashboard-card activity">
        <div class="card-header">
            <h2>Statistiques</h2>
        </div>
        <div class="card-body">
            <div id="candidature-chart"></div>
        </div>
    </div>
</div>

<div class="dashboard-content-row">
    <div class="dashboard-card quick-actions">
        <div class="card-header">
            <h2>Actions rapides</h2>
        </div>
        <div class="card-body">
            <div class="action-buttons">
                <a href="<?php echo URLROOT; ?>/dashboard/stages" class="action-button">
                    <i class="fas fa-search"></i>
                    <span>Chercher un stage</span>
                </a>
                <a href="<?php echo URLROOT; ?>/dashboard/candidatures" class="action-button">
                    <i class="fas fa-folder-open"></i>
                    <span>Mes candidatures</span>
                </a>
                <a href="<?php echo URLROOT; ?>/dashboard/profil" class="action-button">
                    <i class="fas fa-id-card"></i>
                    <span>Mettre à jour mon profil</span>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Données pour le graphique
        const candidatureData = {
            en_attente: <?php echo $data['candidatures']->candidatures_en_attente ?? 0; ?>,
            acceptees: <?php echo $data['candidatures']->candidatures_acceptees ?? 0; ?>,
            refusees: <?php echo $data['candidatures']->candidatures_refusees ?? 0; ?>
        };
        
        // Créer le graphique
        if(document.querySelector('#candidature-chart')) {
            const options = {
                series: [candidatureData.en_attente, candidatureData.acceptees, candidatureData.refusees],
                chart: {
                    type: 'donut',
                    height: 300
                },
                labels: ['En attente', 'Acceptées', 'Refusées'],
                colors: ['#4361ee', '#2ecc71', '#e74c3c'],
                legend: {
                    position: 'bottom'
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            height: 250
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }],
                plotOptions: {
                    pie: {
                        donut: {
                            size: '60%'
                        }
                    }
                }
            };
            
            const chart = new ApexCharts(document.querySelector('#candidature-chart'), options);
            chart.render();
        }
    });
</script>

<?php require APPROOT . '/views/shared/dashboard_footer.php'; ?> 