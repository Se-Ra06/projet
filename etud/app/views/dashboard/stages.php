<?php 
// S'assurer que le fichier config est inclus
if (!defined('APPROOT')) {
    require_once '../../config/config.php';
}

$data['active'] = 'stages';
require APPROOT . '/views/shared/dashboard_header.php'; 
?>

<div class="dashboard-header-actions">
    <h1>Offres de stages</h1>
    <div class="filter-actions">
        <button class="btn btn-outline" id="toggle-filters">
            <i class="fas fa-filter"></i> Filtres
        </button>
    </div>
</div>

<div class="filters-panel" id="filters-panel">
    <form action="<?php echo URLROOT; ?>/dashboard/stages" method="GET" class="filters-form">
        <div class="filter-group">
            <label for="search">Recherche par mots-clés</label>
            <input type="text" name="search" id="search" placeholder="Titre, entreprise, compétences..." 
                   value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
        </div>
        
        <div class="filter-row">
            <div class="filter-group">
                <label for="ville">Ville</label>
                <select name="ville" id="ville">
                    <option value="">Toutes les villes</option>
                    <option value="Paris" <?php echo (isset($_GET['ville']) && $_GET['ville'] === 'Paris') ? 'selected' : ''; ?>>Paris</option>
                    <option value="Lyon" <?php echo (isset($_GET['ville']) && $_GET['ville'] === 'Lyon') ? 'selected' : ''; ?>>Lyon</option>
                    <option value="Marseille" <?php echo (isset($_GET['ville']) && $_GET['ville'] === 'Marseille') ? 'selected' : ''; ?>>Marseille</option>
                    <option value="Toulouse" <?php echo (isset($_GET['ville']) && $_GET['ville'] === 'Toulouse') ? 'selected' : ''; ?>>Toulouse</option>
                    <option value="Bordeaux" <?php echo (isset($_GET['ville']) && $_GET['ville'] === 'Bordeaux') ? 'selected' : ''; ?>>Bordeaux</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="type">Type de stage</label>
                <select name="type" id="type">
                    <option value="">Tous les types</option>
                    <option value="Stage_fin_detudes" <?php echo (isset($_GET['type']) && $_GET['type'] === 'Stage_fin_detudes') ? 'selected' : ''; ?>>Stage de fin d'études</option>
                    <option value="Stage_annee" <?php echo (isset($_GET['type']) && $_GET['type'] === 'Stage_annee') ? 'selected' : ''; ?>>Stage d'année</option>
                    <option value="Alternance" <?php echo (isset($_GET['type']) && $_GET['type'] === 'Alternance') ? 'selected' : ''; ?>>Alternance</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="duree">Durée</label>
                <select name="duree" id="duree">
                    <option value="">Toutes les durées</option>
                    <option value="2" <?php echo (isset($_GET['duree']) && $_GET['duree'] === '2') ? 'selected' : ''; ?>>2 mois</option>
                    <option value="3" <?php echo (isset($_GET['duree']) && $_GET['duree'] === '3') ? 'selected' : ''; ?>>3 mois</option>
                    <option value="4" <?php echo (isset($_GET['duree']) && $_GET['duree'] === '4') ? 'selected' : ''; ?>>4 mois</option>
                    <option value="5" <?php echo (isset($_GET['duree']) && $_GET['duree'] === '5') ? 'selected' : ''; ?>>5 mois</option>
                    <option value="6" <?php echo (isset($_GET['duree']) && $_GET['duree'] === '6') ? 'selected' : ''; ?>>6 mois</option>
                </select>
            </div>
        </div>
        
        <div class="filter-actions">
            <button type="submit" class="btn btn-primary">Appliquer les filtres</button>
            <a href="<?php echo URLROOT; ?>/dashboard/stages" class="btn btn-outline">Réinitialiser</a>
        </div>
    </form>
</div>

<div class="stages-grid">
    <?php if(isset($data['stages']) && count($data['stages']) > 0) : ?>
        <?php foreach($data['stages'] as $stage) : ?>
            <div class="stage-card elevated">
                <div class="stage-header">
                    <h3><?php echo $stage->titre; ?></h3>
                    <button class="btn-icon wishlist">
                        <i class="far fa-heart"></i>
                    </button>
                </div>
                <div class="stage-company">
                    <i class="fas fa-building"></i>
                    <?php echo $stage->entreprise_nom; ?>
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
                    <div class="stage-detail">
                        <i class="fas fa-tags"></i>
                        <span><?php echo isset($stage->type) ? $stage->type : 'Non spécifié'; ?></span>
                    </div>
                </div>
                <div class="stage-description">
                    <?php 
                        $description = isset($stage->description) ? $stage->description : 'Aucune description disponible';
                        echo substr($description, 0, 150) . (strlen($description) > 150 ? '...' : '');
                    ?>
                </div>
                <div class="stage-actions">
                    <a href="<?php echo URLROOT; ?>/dashboard/stage/<?php echo $stage->id; ?>" class="btn btn-outline">Détails</a>
                    <a href="<?php echo URLROOT; ?>/dashboard/postuler/<?php echo $stage->id; ?>" class="btn btn-primary">Postuler</a>
                </div>
                <?php if(isset($stage->date_publication)) : ?>
                    <div class="stage-date">
                        <i class="fas fa-clock"></i>
                        Publié le <?php echo date('d/m/Y', strtotime($stage->date_publication)); ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <div class="empty-state">
            <i class="fas fa-search"></i>
            <h3>Aucune offre de stage trouvée</h3>
            <p>Essayez de modifier vos critères de recherche ou revenez plus tard.</p>
        </div>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle des filtres
        const toggleFiltersBtn = document.getElementById('toggle-filters');
        const filtersPanel = document.getElementById('filters-panel');
        
        toggleFiltersBtn.addEventListener('click', function() {
            filtersPanel.classList.toggle('open');
        });
        
        // Boutons wishlist
        const wishlistButtons = document.querySelectorAll('.wishlist');
        wishlistButtons.forEach(button => {
            button.addEventListener('click', function() {
                const icon = this.querySelector('i');
                icon.classList.toggle('far');
                icon.classList.toggle('fas');
                
                if(icon.classList.contains('fas')) {
                    // TODO: Ajout à la wishlist via Ajax
                    console.log('Ajouté à la wishlist');
                } else {
                    // TODO: Retrait de la wishlist via Ajax
                    console.log('Retiré de la wishlist');
                }
            });
        });
    });
</script>

<?php require APPROOT . '/views/shared/dashboard_footer.php'; ?> 