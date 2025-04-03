<?php 
// S'assurer que le fichier config est inclus
if (!defined('APPROOT')) {
    require_once '../../config/config.php';
}

$data['active'] = 'stages';
$data['title'] = 'Offres de stages';
require APPROOT . '/views/shared/dashboard_header.php'; 
?>

<div class="page-header">
    <h1>Offres de stages</h1>
    <p>Découvrez les opportunités de stage disponibles et postulez directement en ligne.</p>
</div>

<div class="filter-container">
    <div class="search-box">
        <form action="<?php echo URLROOT; ?>/dashboard/stages" method="GET">
            <div class="form-group">
                <input type="text" name="search" placeholder="Rechercher un stage..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button type="submit" class="btn-search"><i class="fas fa-search"></i></button>
            </div>
        </form>
    </div>
    <div class="filter-options">
        <button class="btn-filter" id="toggleFilters"><i class="fas fa-filter"></i> Filtres</button>
        <div class="filters-panel" id="filtersPanel">
            <form action="<?php echo URLROOT; ?>/dashboard/stages" method="GET">
                <div class="filter-group">
                    <h3>Localisation</h3>
                    <div class="form-group">
                        <input type="text" name="location" placeholder="Ville, région..." value="<?php echo isset($_GET['location']) ? htmlspecialchars($_GET['location']) : ''; ?>">
                    </div>
                </div>
                <div class="filter-group">
                    <h3>Durée</h3>
                    <div class="form-check">
                        <input type="checkbox" name="duration[]" value="1-3" id="duration1" <?php echo (isset($_GET['duration']) && in_array('1-3', $_GET['duration'])) ? 'checked' : ''; ?>>
                        <label for="duration1">1-3 mois</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="duration[]" value="4-6" id="duration2" <?php echo (isset($_GET['duration']) && in_array('4-6', $_GET['duration'])) ? 'checked' : ''; ?>>
                        <label for="duration2">4-6 mois</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="duration[]" value="6+" id="duration3" <?php echo (isset($_GET['duration']) && in_array('6+', $_GET['duration'])) ? 'checked' : ''; ?>>
                        <label for="duration3">6+ mois</label>
                    </div>
                </div>
                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">Appliquer</button>
                    <a href="<?php echo URLROOT; ?>/dashboard/stages" class="btn btn-outline">Réinitialiser</a>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="stages-list-container">
    <?php if(isset($data['stages']) && count($data['stages']) > 0) : ?>
        <div class="stages-grid">
            <?php foreach($data['stages'] as $stage) : ?>
                <div class="stage-card">
                    <div class="stage-company-logo">
                        <img src="<?php echo URLROOT; ?>/public/img/company-placeholder.png" alt="Logo <?php echo htmlspecialchars($stage->entreprise_nom); ?>">
                    </div>
                    <div class="stage-info">
                        <h3 class="stage-title"><?php echo htmlspecialchars($stage->titre); ?></h3>
                        <div class="stage-company"><?php echo htmlspecialchars($stage->entreprise_nom); ?></div>
                        <div class="stage-meta">
                            <div class="stage-location"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($stage->lieu); ?></div>
                            <div class="stage-duration"><i class="fas fa-calendar-alt"></i> <?php echo $stage->duree; ?> mois</div>
                            <div class="stage-salary"><i class="fas fa-euro-sign"></i> <?php echo $stage->remuneration; ?> €/mois</div>
                        </div>
                        <div class="stage-description">
                            <?php echo substr(htmlspecialchars($stage->description), 0, 150) . '...'; ?>
                        </div>
                        <div class="stage-date">
                            <i class="far fa-clock"></i> Publié le <?php echo date('d/m/Y', strtotime($stage->date_creation)); ?>
                        </div>
                    </div>
                    <div class="stage-actions">
                        <a href="<?php echo URLROOT; ?>/dashboard/stage/<?php echo $stage->id; ?>" class="btn btn-primary">Voir détails</a>
                        <?php if(isset($stage->candidatureExists) && $stage->candidatureExists) : ?>
                            <button class="btn btn-disabled" disabled><i class="fas fa-check"></i> Déjà postulé</button>
                        <?php else : ?>
                            <a href="<?php echo URLROOT; ?>/dashboard/postuler/<?php echo $stage->id; ?>" class="btn btn-outline">Postuler</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if(isset($data['pagination']) && $data['pagination']['totalPages'] > 1) : ?>
            <div class="pagination">
                <?php if($data['pagination']['currentPage'] > 1) : ?>
                    <a href="<?php echo URLROOT; ?>/dashboard/stages?page=<?php echo $data['pagination']['currentPage'] - 1; ?><?php echo isset($_GET['search']) ? '&search=' . htmlspecialchars($_GET['search']) : ''; ?>" class="pagination-item"><i class="fas fa-angle-left"></i></a>
                <?php endif; ?>
                
                <?php for($i = 1; $i <= $data['pagination']['totalPages']; $i++) : ?>
                    <a href="<?php echo URLROOT; ?>/dashboard/stages?page=<?php echo $i; ?><?php echo isset($_GET['search']) ? '&search=' . htmlspecialchars($_GET['search']) : ''; ?>" class="pagination-item <?php echo ($i === $data['pagination']['currentPage']) ? 'active' : ''; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>
                
                <?php if($data['pagination']['currentPage'] < $data['pagination']['totalPages']) : ?>
                    <a href="<?php echo URLROOT; ?>/dashboard/stages?page=<?php echo $data['pagination']['currentPage'] + 1; ?><?php echo isset($_GET['search']) ? '&search=' . htmlspecialchars($_GET['search']) : ''; ?>" class="pagination-item"><i class="fas fa-angle-right"></i></a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php else : ?>
        <div class="empty-state">
            <img src="<?php echo URLROOT; ?>/public/img/empty-stages.svg" alt="Aucun stage trouvé" class="empty-icon">
            <h2>Aucune offre de stage trouvée</h2>
            <p>Nous n'avons pas trouvé d'offres correspondant à vos critères de recherche.</p>
            <?php if(isset($_GET['search']) || isset($_GET['location']) || isset($_GET['duration'])) : ?>
                <a href="<?php echo URLROOT; ?>/dashboard/stages" class="btn btn-primary">Réinitialiser les filtres</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestion des filtres
        const toggleFilters = document.getElementById('toggleFilters');
        const filtersPanel = document.getElementById('filtersPanel');
        
        if(toggleFilters && filtersPanel) {
            toggleFilters.addEventListener('click', function() {
                filtersPanel.classList.toggle('show');
            });
        }
        
        // Si des filtres sont déjà appliqués, afficher le panneau
        <?php if(isset($_GET['location']) || isset($_GET['duration'])) : ?>
            if(filtersPanel) {
                filtersPanel.classList.add('show');
            }
        <?php endif; ?>
    });
</script>

<?php require APPROOT . '/views/shared/dashboard_footer.php'; ?> 