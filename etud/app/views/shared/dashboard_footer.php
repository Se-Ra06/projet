<?php
// S'assurer que les constantes sont définies
if (!defined('APPROOT') || !defined('URLROOT') || !defined('SITENAME')) {
    require_once dirname(dirname(__DIR__)) . '/config/config.php';
}
?>
            </div> <!-- Fin dashboard-body -->
        </div> <!-- Fin main-content -->
    </div> <!-- Fin dashboard-container -->
    
    <!-- Modal pour les notifications -->
    <div class="notification-modal" id="notificationModal">
        <div class="notification-content">
            <div class="notification-header">
                <h3>Notifications</h3>
                <button class="close-notification"><i class="fas fa-times"></i></button>
            </div>
            <div class="notification-body">
                <div class="notification-item unread">
                    <div class="notification-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="notification-text">
                        <p><strong>Candidature acceptée</strong> - Votre candidature pour le stage "Développeur Web" chez TechCorp a été acceptée.</p>
                        <span class="notification-time">Il y a 2 heures</span>
                    </div>
                </div>
                <div class="notification-item">
                    <div class="notification-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div class="notification-text">
                        <p>Nouvelle offre de stage disponible correspondant à votre profil.</p>
                        <span class="notification-time">Il y a 1 jour</span>
                    </div>
                </div>
                <div class="notification-item">
                    <div class="notification-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="notification-text">
                        <p>Rappel : La date limite pour postuler au stage "Marketing Digital" est dans 3 jours.</p>
                        <span class="notification-time">Il y a 2 jours</span>
                    </div>
                </div>
            </div>
            <div class="notification-footer">
                <a href="#" class="mark-all-read">Marquer tout comme lu</a>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle sidebar
            const toggleSidebar = document.querySelector('.toggle-sidebar');
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');
            
            if(toggleSidebar) {
                toggleSidebar.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    mainContent.classList.toggle('expanded');
                });
            }
            
            // Notifications
            const notificationButton = document.querySelector('.notification');
            const notificationModal = document.getElementById('notificationModal');
            const closeNotification = document.querySelector('.close-notification');
            
            if(notificationButton && notificationModal) {
                notificationButton.addEventListener('click', function() {
                    notificationModal.classList.toggle('show');
                });
                
                closeNotification.addEventListener('click', function() {
                    notificationModal.classList.remove('show');
                });
                
                // Fermer la modal si on clique en dehors
                window.addEventListener('click', function(event) {
                    if (event.target === notificationModal) {
                        notificationModal.classList.remove('show');
                    }
                });
            }
            
            // Gestion des alertes
            const alerts = document.querySelectorAll('.alert');
            
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.classList.add('fade-out');
                    setTimeout(function() {
                        alert.remove();
                    }, 500);
                }, 5000);
            });
            
            // Modal de suppression
            const deleteModal = document.getElementById('deleteModal');
            if(deleteModal) {
                const deleteBtns = document.querySelectorAll('.delete-candidature');
                const closeModal = document.querySelector('.close-modal');
                const cancelDelete = document.getElementById('cancelDelete');
                const confirmDelete = document.getElementById('confirmDelete');
                let candidatureId = null;
                
                deleteBtns.forEach(function(btn) {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        candidatureId = this.getAttribute('data-id');
                        deleteModal.classList.add('show');
                    });
                });
                
                if(closeModal) {
                    closeModal.addEventListener('click', function() {
                        deleteModal.classList.remove('show');
                    });
                }
                
                if(cancelDelete) {
                    cancelDelete.addEventListener('click', function() {
                        deleteModal.classList.remove('show');
                    });
                }
                
                if(confirmDelete) {
                    confirmDelete.addEventListener('click', function() {
                        if(candidatureId) {
                            window.location.href = `${URLROOT}/dashboard/annulerCandidature/${candidatureId}`;
                        }
                    });
                }
            }
        });
    </script>
    
</body>
</html> 