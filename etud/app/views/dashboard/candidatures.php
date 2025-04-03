<?php 
// S'assurer que le fichier config est inclus
if (!defined('APPROOT')) {
    require_once '../../config/config.php';
}

$data['active'] = 'candidatures';
$data['title'] = 'Mes candidatures';
require APPROOT . '/views/shared/dashboard_header.php'; 
?>

<div class="page-header">
    <h1>Mes candidatures</h1>
    <p>Suivez l'avancement de vos candidatures et consultez l'historique de vos postulations.</p>
</div>

<div class="candidatures-stats">
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-file-alt"></i>
        </div>
        <div class="stat-content">
            <h3>Total</h3>
            <div class="stat-number"><?php echo $data['stats']->total_candidatures ?? 0; ?></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon status-pending">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-content">
            <h3>En attente</h3>
            <div class="stat-number"><?php echo $data['stats']->candidatures_en_attente ?? 0; ?></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon status-accepted">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-content">
            <h3>Acceptées</h3>
            <div class="stat-number"><?php echo $data['stats']->candidatures_acceptees ?? 0; ?></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon status-rejected">
            <i class="fas fa-times-circle"></i>
        </div>
        <div class="stat-content">
            <h3>Refusées</h3>
            <div class="stat-number"><?php echo $data['stats']->candidatures_refusees ?? 0; ?></div>
        </div>
    </div>
</div>

<div class="candidatures-filter">
    <div class="filter-tabs">
        <button class="filter-tab active" data-filter="all">Toutes</button>
        <button class="filter-tab" data-filter="pending">En attente</button>
        <button class="filter-tab" data-filter="accepted">Acceptées</button>
        <button class="filter-tab" data-filter="rejected">Refusées</button>
    </div>
    <div class="filter-search">
        <input type="text" id="searchCandidature" placeholder="Rechercher dans vos candidatures...">
        <i class="fas fa-search"></i>
    </div>
</div>

<div class="candidatures-container">
    <?php if (isset($data['candidatures']) && count($data['candidatures']) > 0) : ?>
        <div class="candidatures-list">
            <?php foreach ($data['candidatures'] as $candidature) : ?>
                <?php 
                    $statusClass = '';
                    $statusText = '';
                    $statusIcon = '';
                    
                    switch($candidature->status) {
                        case 'pending':
                            $statusClass = 'status-pending';
                            $statusText = 'En attente';
                            $statusIcon = 'clock';
                            break;
                        case 'accepted':
                            $statusClass = 'status-accepted';
                            $statusText = 'Acceptée';
                            $statusIcon = 'check-circle';
                            break;
                        case 'rejected':
                            $statusClass = 'status-rejected';
                            $statusText = 'Refusée';
                            $statusIcon = 'times-circle';
                            break;
                        default:
                            $statusClass = 'status-pending';
                            $statusText = 'En attente';
                            $statusIcon = 'clock';
                    }
                ?>
                <div class="candidature-card" data-status="<?php echo $candidature->status; ?>">
                    <div class="candidature-header">
                        <h3 class="candidature-title"><?php echo htmlspecialchars($candidature->stage_titre); ?></h3>
                        <div class="candidature-status <?php echo $statusClass; ?>">
                            <i class="fas fa-<?php echo $statusIcon; ?>"></i> <?php echo $statusText; ?>
                        </div>
                    </div>
                    <div class="candidature-company">
                        <i class="fas fa-building"></i> <?php echo htmlspecialchars($candidature->entreprise_nom); ?>
                    </div>
                    <div class="candidature-details">
                        <div class="candidature-date">
                            <i class="fas fa-calendar-alt"></i> Postulé le <?php echo date('d/m/Y', strtotime($candidature->date_candidature)); ?>
                        </div>
                        <?php if (!empty($candidature->date_reponse)) : ?>
                            <div class="candidature-response-date">
                                <i class="fas fa-reply"></i> Réponse le <?php echo date('d/m/Y', strtotime($candidature->date_reponse)); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="candidature-documents">
                        <div class="document-badge">
                            <i class="fas fa-file-pdf"></i> CV
                        </div>
                        <?php if (!empty($candidature->lettre_motivation)) : ?>
                            <div class="document-badge">
                                <i class="fas fa-file-alt"></i> Lettre de motivation
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="candidature-actions">
                        <a href="<?php echo URLROOT; ?>/dashboard/stage/<?php echo $candidature->id_stage; ?>" class="btn btn-outline">Voir l'offre</a>
                        <button class="btn btn-icon" title="Supprimer la candidature" onclick="confirmDelete(<?php echo $candidature->id; ?>)">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <div class="empty-state">
            <img src="<?php echo URLROOT; ?>/public/img/empty-applications.svg" alt="Aucune candidature" class="empty-icon">
            <h2>Aucune candidature</h2>
            <p>Vous n'avez pas encore postulé à des offres de stage.</p>
            <a href="<?php echo URLROOT; ?>/dashboard/stages" class="btn btn-primary">Découvrir les offres</a>
        </div>
    <?php endif; ?>
</div>

<!-- Modal de confirmation de suppression -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Confirmer la suppression</h2>
            <span class="close">&times;</span>
        </div>
        <div class="modal-body">
            <p>Êtes-vous sûr de vouloir supprimer cette candidature ?</p>
            <p class="warning">Cette action est irréversible.</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" id="cancelDelete">Annuler</button>
            <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Supprimer</a>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Filtrage des candidatures par statut
        const filterTabs = document.querySelectorAll('.filter-tab');
        const candidatureCards = document.querySelectorAll('.candidature-card');
        
        filterTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Retirer la classe active de tous les onglets
                filterTabs.forEach(t => t.classList.remove('active'));
                // Ajouter la classe active à l'onglet cliqué
                this.classList.add('active');
                
                const filter = this.dataset.filter;
                
                // Filtrer les candidatures
                candidatureCards.forEach(card => {
                    if (filter === 'all' || card.dataset.status === filter) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
        
        // Recherche dans les candidatures
        const searchInput = document.getElementById('searchCandidature');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const searchValue = this.value.toLowerCase();
                
                candidatureCards.forEach(card => {
                    const title = card.querySelector('.candidature-title').textContent.toLowerCase();
                    const company = card.querySelector('.candidature-company').textContent.toLowerCase();
                    
                    if (title.includes(searchValue) || company.includes(searchValue)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        }
        
        // Modal de confirmation de suppression
        const modal = document.getElementById('deleteModal');
        const closeBtn = document.querySelector('.close');
        const cancelBtn = document.getElementById('cancelDelete');
        const confirmBtn = document.getElementById('confirmDeleteBtn');
        
        // Fonction pour ouvrir la modal
        window.confirmDelete = function(id) {
            modal.style.display = 'block';
            confirmBtn.href = `<?php echo URLROOT; ?>/dashboard/delete_candidature/${id}`;
        }
        
        // Fermer la modal
        closeBtn.onclick = function() {
            modal.style.display = 'none';
        }
        
        cancelBtn.onclick = function() {
            modal.style.display = 'none';
        }
        
        // Fermer la modal si on clique en dehors
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    });
</script>

<?php require APPROOT . '/views/shared/dashboard_footer.php'; ?> 