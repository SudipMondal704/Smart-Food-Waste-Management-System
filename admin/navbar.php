<!-- Topbar/Navbar Section -->
    <div class="topbar">
        <i class="bx bx-menu toggle-menu"></i>

       <!-- <div class="search">
            <input type="search" placeholder="Search here....">
            <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
        </div>-->

        <a href="#" class="notification" id="notification-bell">
            <i class='bx bxs-bell'></i>
            <span class="notification-count">3</span>
        </a>

        <div class="user">
            <img src="https://via.placeholder.com/36" alt="User">
            
        </div>

        <!-- Notification Popup -->
        <div class="notification-popup" id="notification-popup">
            <div class="notification-header">
                <h3>Notifications</h3>
                <span class="clear-all" id="clear-all">Clear All</span>
            </div>
            <div class="notification-items" id="notification-items">
                <!-- Notification Item - User Login -->
                <div class="notification-item unread">
                    <div class="notification-icon user">
                        <i class='bx bxs-user'></i>
                    </div>
                    <div class="notification-content">
                        <p class="notification-title">New User Login</p>
                        <p class="notification-desc">Rahul Sharma has just signed in to the platform</p>
                        <p class="notification-time">2 minutes ago</p>
                    </div>
                </div>

                <!-- Notification Item - Food Donation -->
                <div class="notification-item unread">
                    <div class="notification-icon donate">
                        <i class='bx bxs-donate-heart'></i>
                    </div>
                    <div class="notification-content">
                        <p class="notification-title">New Food Donation</p>
                        <p class="notification-desc">Hotel Taj has donated 25kg of food items</p>
                        <p class="notification-time">15 minutes ago</p>
                    </div>
                </div>

                <!-- Notification Item - NGO Collection -->
                <div class="notification-item unread">
                    <div class="notification-icon collect">
                        <i class='bx bxs-package'></i>
                    </div>
                    <div class="notification-content">
                        <p class="notification-title">NGO Food Collection</p>
                        <p class="notification-desc">Hope Foundation collected 18kg of food</p>
                        <p class="notification-time">1 hour ago</p>
                    </div>
                </div>
            </div>
            <a href="#" class="view-all">View All Notifications</a>
        </div>
    </div>

    <script>
        // JavaScript for notification functionality
        document.addEventListener('DOMContentLoaded', function() {
            const notificationBell = document.getElementById('notification-bell');
            const notificationPopup = document.getElementById('notification-popup');
            const clearAllBtn = document.getElementById('clear-all');
            const notificationItems = document.getElementById('notification-items');
            
            // Toggle notification popup
            notificationBell.addEventListener('click', function(e) {
                e.preventDefault();
                notificationPopup.classList.toggle('show');
                
                // Reset notification count when opened
                if(notificationPopup.classList.contains('show')) {
                    document.querySelector('.notification-count').style.display = 'none';
                }
            });
            
            // Close popup when clicking outside
            document.addEventListener('click', function(e) {
                if(!notificationBell.contains(e.target) && 
                   !notificationPopup.contains(e.target) && 
                   notificationPopup.classList.contains('show')) {
                    notificationPopup.classList.remove('show');
                }
            });
            
            // Clear all notifications
            clearAllBtn.addEventListener('click', function() {
                const emptyMessage = document.createElement('div');
                emptyMessage.className = 'no-notifications';
                emptyMessage.textContent = 'No new notifications';
                notificationItems.innerHTML = '';
                notificationItems.appendChild(emptyMessage);
                document.querySelector('.notification-count').style.display = 'none';
            });
            
            // Sample function to add a new notification
            window.addNotification = function(type, title, description) {
                // Create notification elements
                const notificationItem = document.createElement('div');
                notificationItem.className = 'notification-item unread';
                
                const iconClass = type === 'user' ? 'user' : 
                                 type === 'donate' ? 'donate' : 'collect';
                
                const iconType = type === 'user' ? 'bxs-user' : 
                                type === 'donate' ? 'bxs-donate-heart' : 'bxs-package';
                
                notificationItem.innerHTML = `
                    <div class="notification-icon ${iconClass}">
                        <i class='bx ${iconType}'></i>
                    </div>
                    <div class="notification-content">
                        <p class="notification-title">${title}</p>
                        <p class="notification-desc">${description}</p>
                        <p class="notification-time">Just now</p>
                    </div>
                `;
                
                // Check if "No notifications" message exists and remove it
                const noNotifications = notificationItems.querySelector('.no-notifications');
                if(noNotifications) {
                    notificationItems.removeChild(noNotifications);
                }
                
                // Add new notification at the top
                notificationItems.prepend(notificationItem);
                
                // Update notification count
                const notificationCount = document.querySelector('.notification-count');
                notificationCount.style.display = 'flex';
                notificationCount.textContent = document.querySelectorAll('.notification-item').length;
                
                // Flash the notification bell
                notificationBell.querySelector('i').style.color = '#ff4757';
                setTimeout(() => {
                    notificationBell.querySelector('i').style.color = '#555';
                }, 1000);
            }
            
            
            document.body.appendChild(testContainer);
            
            // Add event listeners for test buttons
            document.getElementById('test-user').addEventListener('click', () => {
                addNotification('user', 'New User Login', 'Priya Patel has just signed in to the platform');
            });
            
            document.getElementById('test-donate').addEventListener('click', () => {
                addNotification('donate', 'New Food Donation', 'Restaurant Galaxy has donated 15kg of food items');
            });
            
            document.getElementById('test-ngo').addEventListener('click', () => {
                addNotification('ngo', 'NGO Food Collection', 'Green Earth Foundation collected 22kg of food');
            });
        });
    </script>