<?php 
// S'assurer que le fichier config est inclus
if (!defined('APPROOT')) {
    require_once '../../config/config.php';
}

$data['active'] = 'wishlist';
$data['title'] = 'Mes favoris';
require APPROOT . '/views/shared/dashboard_header.php'; 

// Récupérer les stages favoris depuis $data
$stages = $data['wishlist'] ?? [];
?>

<div class="page-header">
    <h1>Mes stages favoris</h1>
    <p>Retrouvez ici tous les stages que vous avez ajoutés à vos favoris.</p>
</div>

<div class="wishlist-container">
    <?php if(empty($stages)) : ?>
        <div class="empty-wishlist">
            <div class="empty-illustration">
                <i class="far fa-heart"></i>
            </div>
            <h2>Aucun stage dans vos favoris</h2>
            <p>Vous n'avez pas encore ajouté de stages à vos favoris. Explorez les offres disponibles et ajoutez-les à votre liste pour les retrouver facilement.</p>
            <a href="<?php echo URLROOT; ?>/dashboard/stages" class="btn btn-primary">Explorer les stages</a>
        </div>
    <?php else : ?>
        <div class="wishlist-controls">
            <div class="wishlist-count">
                <span><?php echo count($stages); ?> stage<?php echo count($stages) > 1 ? 's' : ''; ?> dans vos favoris</span>
            </div>
            <div class="wishlist-sort">
                <label for="sort-options">Trier par:</label>
                <select id="sort-options" class="sort-select">
                    <option value="date-desc">Date (Plus récent)</option>
                    <option value="date-asc">Date (Plus ancien)</option>
                    <option value="title-asc">Titre (A-Z)</option>
                    <option value="title-desc">Titre (Z-A)</option>
                    <option value="company-asc">Entreprise (A-Z)</option>
                    <option value="company-desc">Entreprise (Z-A)</option>
                </select>
            </div>
        </div>
        
        <div class="wishlist-grid" id="wishlist-grid">
            <?php foreach($stages as $stage) : ?>
                <div class="wishlist-card" data-title="<?php echo htmlspecialchars($stage->title); ?>" data-company="<?php echo htmlspecialchars($stage->company_name); ?>" data-date="<?php echo strtotime($stage->date_posted); ?>">
                    <div class="card-header">
                        <div class="company-logo">
                            <?php if(isset($stage->company_logo) && !empty($stage->company_logo)) : ?>
                                <img src="<?php echo URLROOT; ?>/public/uploads/logos/<?php echo $stage->company_logo; ?>" alt="<?php echo htmlspecialchars($stage->company_name); ?>">
                            <?php else : ?>
                                <div class="company-placeholder">
                                    <?php echo substr(htmlspecialchars($stage->company_name), 0, 1); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="wishlist-actions">
                            <button class="btn-icon btn-remove-wishlist" data-id="<?php echo $stage->id_internship; ?>" title="Retirer des favoris">
                                <i class="fas fa-heart"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <h3 class="stage-title">
                            <a href="<?php echo URLROOT; ?>/dashboard/stage/<?php echo $stage->id_internship; ?>">
                                <?php echo htmlspecialchars($stage->title); ?>
                            </a>
                        </h3>
                        
                        <div class="company-name">
                            <?php echo htmlspecialchars($stage->company_name); ?>
                        </div>
                        
                        <div class="stage-details">
                            <div class="stage-detail">
                                <i class="fas fa-map-marker-alt"></i>
                                <span><?php echo htmlspecialchars($stage->location); ?></span>
                            </div>
                            
                            <div class="stage-detail">
                                <i class="far fa-clock"></i>
                                <span><?php echo htmlspecialchars($stage->duration); ?> mois</span>
                            </div>
                            
                            <?php if(isset($stage->salary) && !empty($stage->salary)) : ?>
                                <div class="stage-detail">
                                    <i class="fas fa-euro-sign"></i>
                                    <span><?php echo htmlspecialchars($stage->salary); ?> €/mois</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="stage-description">
                            <?php echo substr(htmlspecialchars($stage->description), 0, 150) . '...'; ?>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <div class="stage-date">
                            <i class="far fa-calendar-alt"></i>
                            <span>Publié le <?php echo date('d/m/Y', strtotime($stage->date_posted)); ?></span>
                        </div>
                        
                        <div class="stage-actions">
                            <a href="<?php echo URLROOT; ?>/dashboard/stage/<?php echo $stage->id_internship; ?>" class="btn btn-sm btn-outline">Voir détails</a>
                            <a href="<?php echo URLROOT; ?>/dashboard/postuler/<?php echo $stage->id_internship; ?>" class="btn btn-sm btn-primary">Postuler</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Modal de confirmation pour retirer un stage des favoris -->
<div id="removeWishlistModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Confirmer la suppression</h2>
            <span class="close">&times;</span>
        </div>
        <div class="modal-body">
            <p>Êtes-vous sûr de vouloir retirer ce stage de vos favoris ?</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline" id="cancelRemoveWishlist">Annuler</button>
            <button type="button" class="btn btn-danger" id="confirmRemoveWishlist">Retirer</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Trier les stages
        const sortSelect = document.getElementById('sort-options');
        const wishlistGrid = document.getElementById('wishlist-grid');
        
        if (sortSelect && wishlistGrid) {
            sortSelect.addEventListener('change', function() {
                const sortValue = this.value;
                const cards = Array.from(wishlistGrid.querySelectorAll('.wishlist-card'));
                
                cards.sort(function(a, b) {
                    switch(sortValue) {
                        case 'date-desc':
                            return parseInt(b.dataset.date) - parseInt(a.dataset.date);
                        case 'date-asc':
                            return parseInt(a.dataset.date) - parseInt(b.dataset.date);
                        case 'title-asc':
                            return a.dataset.title.localeCompare(b.dataset.title);
                        case 'title-desc':
                            return b.dataset.title.localeCompare(a.dataset.title);
                        case 'company-asc':
                            return a.dataset.company.localeCompare(b.dataset.company);
                        case 'company-desc':
                            return b.dataset.company.localeCompare(a.dataset.company);
                        default:
                            return 0;
                    }
                });
                
                // Vider la grille
                while (wishlistGrid.firstChild) {
                    wishlistGrid.removeChild(wishlistGrid.firstChild);
                }
                
                // Reconstruire la grille avec les cartes triées
                cards.forEach(card => {
                    wishlistGrid.appendChild(card);
                });
            });
        }
        
        // Gestion de la suppression des favoris
        const removeButtons = document.querySelectorAll('.btn-remove-wishlist');
        const removeWishlistModal = document.getElementById('removeWishlistModal');
        const cancelRemoveWishlistBtn = document.getElementById('cancelRemoveWishlist');
        const confirmRemoveWishlistBtn = document.getElementById('confirmRemoveWishlist');
        const closeBtn = document.querySelector('#removeWishlistModal .close');
        
        let currentStageId = null;
        
        removeButtons.forEach(button => {
            button.addEventListener('click', function() {
                currentStageId = this.getAttribute('data-id');
                removeWishlistModal.style.display = 'block';
            });
        });
        
        if (cancelRemoveWishlistBtn) {
            cancelRemoveWishlistBtn.addEventListener('click', function() {
                removeWishlistModal.style.display = 'none';
            });
        }
        
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                removeWishlistModal.style.display = 'none';
            });
        }
        
        if (confirmRemoveWishlistBtn) {
            confirmRemoveWishlistBtn.addEventListener('click', function() {
                if (currentStageId) {
                    // Envoyer la requête AJAX pour supprimer le favori
                    fetch(`${URLROOT}/dashboard/remove_wishlist/${currentStageId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Supprimer la carte du DOM
                            const card = document.querySelector(`.wishlist-card[data-id="${currentStageId}"]`);
                            if (card) {
                                card.remove();
                                
                                // Mettre à jour le compteur
                                const countElement = document.querySelector('.wishlist-count span');
                                const currentCount = parseInt(countElement.textContent);
                                const newCount = currentCount - 1;
                                countElement.textContent = `${newCount} stage${newCount > 1 ? 's' : ''} dans vos favoris`;
                                
                                // Si c'était le dernier stage, afficher le message "vide"
                                if (newCount === 0) {
                                    const wishlistContainer = document.querySelector('.wishlist-container');
                                    wishlistContainer.innerHTML = `
                                        <div class="empty-wishlist">
                                            <div class="empty-illustration">
                                                <i class="far fa-heart"></i>
                                            </div>
                                            <h2>Aucun stage dans vos favoris</h2>
                                            <p>Vous n'avez pas encore ajouté de stages à vos favoris. Explorez les offres disponibles et ajoutez-les à votre liste pour les retrouver facilement.</p>
                                            <a href="wishlist.php" class="btn btn-primary">Explorer les stages</a>
                                        </div>
                                    `;
                                }
                            }
                        } else {
                            alert('Une erreur est survenue lors de la suppression du favori.');
                        }
                        
                        removeWishlistModal.style.display = 'none';
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        alert('Une erreur est survenue lors de la communication avec le serveur.');
                        removeWishlistModal.style.display = 'none';
                    });
                }
            });
        }
        
        // Fermer la modal si on clique en dehors
        window.addEventListener('click', function(event) {
            if (event.target == removeWishlistModal) {
                removeWishlistModal.style.display = 'none';
            }
        });
    });
</script>

<?php require APPROOT . '/views/shared/dashboard_footer.php'; ?> 