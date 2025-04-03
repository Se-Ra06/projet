// Script pour le dashboard étudiant

document.addEventListener('DOMContentLoaded', function() {
    // Toggle de la barre latérale
    const toggleSidebarBtn = document.querySelector('.toggle-sidebar');
    const dashboardContainer = document.querySelector('.dashboard-container');
    
    if (toggleSidebarBtn) {
        toggleSidebarBtn.addEventListener('click', function() {
            dashboardContainer.classList.toggle('sidebar-collapsed');
        });
    }
    
    // Animation des alertes
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.classList.add('fade-out');
            setTimeout(() => {
                alert.style.display = 'none';
            }, 500);
        }, 4000);
    });
    
    // Gestion des boutons wishlist
    const wishlistButtons = document.querySelectorAll('.wishlist');
    wishlistButtons.forEach(button => {
        button.addEventListener('click', function() {
            const icon = this.querySelector('i');
            icon.classList.toggle('far');
            icon.classList.toggle('fas');
            
            // TODO: Implémenter l'ajout/retrait de la wishlist via une requête AJAX
        });
    });
    
    // Gestion du panneau de filtres
    const toggleFiltersBtn = document.getElementById('toggle-filters');
    const filtersPanel = document.getElementById('filters-panel');
    
    if (toggleFiltersBtn && filtersPanel) {
        toggleFiltersBtn.addEventListener('click', function() {
            filtersPanel.classList.toggle('open');
        });
    }
    
    // Responsive: Pour les écrans mobiles
    function handleMobileView() {
        if (window.innerWidth <= 768) {
            const menuIcon = document.querySelector('.toggle-sidebar');
            
            if (menuIcon) {
                menuIcon.addEventListener('click', function() {
                    dashboardContainer.classList.toggle('sidebar-open');
                });
            }
        }
    }
    
    // Appel initial pour gérer la vue mobile
    handleMobileView();
    
    // Gestion du redimensionnement de la fenêtre
    window.addEventListener('resize', function() {
        handleMobileView();
    });
}); 