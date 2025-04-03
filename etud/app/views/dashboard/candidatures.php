<?php 
// S'assurer que le fichier config est inclus
if (!defined('APPROOT')) {
    require_once '../../config/config.php';
}

$data['active'] = 'candidatures';
require APPROOT . '/views/shared/dashboard_header.php'; 
?>

<div class="dashboard-welcome">
    <h1>Mes Candidatures</h1>
    <p>Suivez l'état de vos candidatures et restez informé des réponses des entreprises</p>
</div>

<div class="dashboard-card">
    <div class="card-header">
        <h2>Vos candidatures</h2>
    </div>
    <div class="card-body">
        <?php if(isset($data['candidatures']) && count($data['candidatures']) > 0) : ?>
            <div class="candidatures-table-container">
                <table class="candidatures-table">
                    <thead>
                        <tr>
                            <th>Stage</th>
                            <th>Entreprise</th>
                            <th>Date de candidature</th>
                            <th>Période</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['candidatures'] as $candidature) : ?>
                            <tr>
                                <td>
                                    <div class="candidature-stage">
                                        <h4><?php echo $candidature->stage_titre; ?></h4>
                                    </div>
                                </td>
                                <td><?php echo $candidature->entreprise_nom; ?></td>
                                <td><?php echo date('d/m/Y', strtotime($candidature->created_at)); ?></td>
                                <td>
                                    <?php 
                                        if(isset($candidature->date_debut) && isset($candidature->date_fin)) {
                                            echo date('d/m/Y', strtotime($candidature->date_debut)) . ' - ' . date('d/m/Y', strtotime($candidature->date_fin));
                                        } else {
                                            echo 'Non spécifiée';
                                        }
                                    ?>
                                </td>
                                <td>
                                    <div class="status-badge <?php echo $candidature->statut; ?>">
                                        <?php 
                                            switch($candidature->statut) {
                                                case 'en_attente':
                                                    echo 'En attente';
                                                    break;
                                                case 'acceptee':
                                                    echo 'Acceptée';
                                                    break;
                                                case 'refusee':
                                                    echo 'Refusée';
                                                    break;
                                                default:
                                                    echo 'En traitement';
                                            }
                                        ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="candidature-actions">
                                        <a href="<?php echo URLROOT; ?>/dashboard/stage/<?php echo $candidature->stage_id; ?>" class="btn-icon" title="Voir le stage">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if($candidature->statut === 'en_attente') : ?>
                                            <a href="#" class="btn-icon delete-candidature" data-id="<?php echo $candidature->id; ?>" title="Annuler la candidature">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else : ?>
            <div class="empty-state">
                <i class="fas fa-file-alt"></i>
                <h3>Aucune candidature</h3>
                <p>Vous n'avez pas encore postulé à des offres de stage.</p>
                <a href="<?php echo URLROOT; ?>/dashboard/stages" class="btn btn-primary">Découvrir les offres</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal de confirmation pour la suppression -->
<div class="modal" id="deleteModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Confirmer l'annulation</h3>
            <button class="close-modal"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <p>Êtes-vous sûr de vouloir annuler cette candidature ? Cette action est irréversible.</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" id="cancelDelete">Annuler</button>
            <button class="btn btn-danger" id="confirmDelete">Confirmer</button>
        </div>
    </div>
</div>

<style>
    /* Styles pour la table des candidatures */
    .candidatures-table-container {
        overflow-x: auto;
    }
    
    .candidatures-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.95rem;
    }
    
    .candidatures-table thead {
        background-color: #f7f9fc;
    }
    
    .candidatures-table th,
    .candidatures-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .candidatures-table th {
        font-weight: 600;
        color: var(--dark-color);
    }
    
    .candidature-stage h4 {
        margin: 0;
        font-size: 1rem;
        color: var(--dark-color);
    }
    
    .status-badge {
        display: inline-block;
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
    }
    
    .status-badge.en_attente {
        background-color: rgba(243, 156, 18, 0.1);
        color: var(--warning-color);
    }
    
    .status-badge.acceptee {
        background-color: rgba(46, 204, 113, 0.1);
        color: var(--success-color);
    }
    
    .status-badge.refusee {
        background-color: rgba(231, 76, 60, 0.1);
        color: var(--danger-color);
    }
    
    .candidature-actions {
        display: flex;
        gap: 0.75rem;
    }
    
    /* Styles pour la modal */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }
    
    .modal.show {
        display: flex;
    }
    
    .modal-content {
        background-color: white;
        border-radius: var(--border-radius);
        width: 90%;
        max-width: 500px;
        box-shadow: var(--shadow-lg);
        animation: modalFadeIn 0.3s ease;
    }
    
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .modal-header h3 {
        margin: 0;
        color: var(--dark-color);
    }
    
    .close-modal {
        background: none;
        border: none;
        color: #718096;
        cursor: pointer;
        font-size: 1.25rem;
    }
    
    .modal-body {
        padding: 1.5rem;
    }
    
    .modal-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
    }
    
    .btn-danger {
        background-color: var(--danger-color);
        color: white;
    }
    
    .btn-danger:hover {
        background-color: #c0392b;
    }
    
    @keyframes modalFadeIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestion de la suppression de candidature
        const deleteButtons = document.querySelectorAll('.delete-candidature');
        const deleteModal = document.getElementById('deleteModal');
        const cancelDeleteBtn = document.getElementById('cancelDelete');
        const confirmDeleteBtn = document.getElementById('confirmDelete');
        const closeModalBtn = document.querySelector('.close-modal');
        
        let candidatureIdToDelete = null;
        
        // Ouvrir la modal
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                candidatureIdToDelete = this.getAttribute('data-id');
                deleteModal.classList.add('show');
            });
        });
        
        // Fermer la modal
        function closeModal() {
            deleteModal.classList.remove('show');
            candidatureIdToDelete = null;
        }
        
        cancelDeleteBtn.addEventListener('click', closeModal);
        closeModalBtn.addEventListener('click', closeModal);
        
        // Cliquer en dehors de la modal ferme la modal
        deleteModal.addEventListener('click', function(e) {
            if (e.target === deleteModal) {
                closeModal();
            }
        });
        
        // Confirmer la suppression
        confirmDeleteBtn.addEventListener('click', function() {
            if (candidatureIdToDelete) {
                window.location.href = `<?php echo URLROOT; ?>/dashboard/supprimerCandidature/${candidatureIdToDelete}`;
            }
        });
    });
</script>

<?php require APPROOT . '/views/shared/dashboard_footer.php'; ?> 