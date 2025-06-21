<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Get admin info from session
$isLoggedIn = false;
$userName = '';
$userEmail = '';
$userPhone = '';
$userRole = '';

if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
    $isLoggedIn = true;
    $userName = $_SESSION['admin_name'] ?? 'Admin';
    $userEmail = $_SESSION['admin_email'] ?? '';
    $userPhone = $_SESSION['admin_phone'] ?? '';
    $userRole = 'Admin';
}
?>

<!-- Topbar/Navbar Section -->
<div class="topbar">
    <i class="bx bx-menu toggle-menu"></i>
    
    <!-- Welcome Message -->
    <?php if ($isLoggedIn): ?>
    <div class="welcome-message">
        Welcome,<b style="font-weight: 600;"> <?php echo htmlspecialchars($userName); ?></b>
    </div>
    <?php endif; ?>

    <div class="topbar-right">
        <a href="#" class="notification" id="notification-bell">
            <i class='bx bxs-bell'></i>
            <span class="notification-count">3</span>
        </a>

        <div class="user-profile" id="user-profile">
            <img src="../img/user.png" alt="Profile" class="profile-img">
        </div>
    </div>

    <!-- Notification Popup -->
    <div class="notification-popup" id="notification-popup">
        <div class="notification-header">
            <h3>Notifications</h3>
            <span class="clear-all" id="clear-all">Clear All</span>
        </div>
        <div class="notification-items" id="notification-items">
            <!--
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
            -->
        </div>
        <a href="#" class="view-all">View All Notifications</a>
    </div>

    <!-- Profile Popup -->
    <?php if ($isLoggedIn): ?>
    <div class="profile-popup" id="profile-popup">
        <div class="profile-header">
            <img src="../img/user.png" alt="Profile" class="profile-popup-img">
            <h3><?php echo htmlspecialchars($userName); ?></h3>
            <span class="user-role"><?php echo htmlspecialchars($userRole); ?></span>
        </div>
        <div class="profile-info">
            <div class="info-item">
                <i class='bx bxs-user'></i>
                <div class="info-content">
                    <span class="info-label">Name</span>
                    <span class="info-value"><?php echo htmlspecialchars($userName); ?></span>
                </div>
            </div>
            <?php if ($userEmail): ?>
            <div class="info-item">
                <i class='bx bxs-envelope'></i>
                <div class="info-content">
                    <span class="info-label">Email</span>
                    <span class="info-value"><?php echo htmlspecialchars($userEmail); ?></span>
                </div>
            </div>
            <?php endif; ?>
            <?php if ($userPhone): ?>
            <div class="info-item">
                <i class='bx bxs-phone'></i>
                <div class="info-content">
                    <span class="info-label">Phone</span>
                    <span class="info-value"><?php echo htmlspecialchars($userPhone); ?></span>
                </div>
            </div>
            <?php endif; ?>
            <div class="info-item">
                <i class='bx bxs-badge'></i>
                <div class="info-content">
                    <span class="info-label">Role</span>
                    <span class="info-value"><?php echo htmlspecialchars($userRole); ?></span>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
/* Welcome Message */
.welcome-message {
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    font-size:1.6rem;
    font-weight: 400;
    color: #333;
    font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
}

/* Topbar Right Section */
.topbar-right {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-left: auto;
}

/* User Profile */
.user-profile {
    cursor: pointer;
    padding: 5px;
    border-radius: 50%;
    transition: background-color 0.3s ease;
}

.user-profile:hover {
    background-color: rgba(0, 0, 0, 0.1);
}

.profile-img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #ddd;
}

/* Profile Popup */
.profile-popup {
    position: absolute;
    top: 60px;
    right: 20px;
    width: 300px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.3s ease;
    z-index: 1000;
    border: 1px solid #e1e1e1;
}

.profile-popup.show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.profile-header {
    text-align: center;
    padding: 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 12px 12px 0 0;
}

.profile-popup-img {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid white;
    margin-bottom: 10px;
}

.profile-header h3 {
    margin: 0 0 5px 0;
    font-size: 18px;
    font-weight: 600;
}

.user-role {
    background: rgba(255, 255, 255, 0.2);
    padding: 4px 12px;
    border-radius: 15px;
    font-size: 12px;
    font-weight: 500;
}

.profile-info {
    padding: 15px 15px 20px 15px;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 0;
    border-bottom: 1px solid #f5f5f5;
}

.info-item:last-child {
    border-bottom: none;
}

.info-item i {
    font-size: 18px;
    color: #667eea;
    width: 20px;
}

.info-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.info-label {
    font-size: 12px;
    color: #888;
    font-weight: 500;
}

.info-value {
    font-size: 14px;
    color: #333;
    font-weight: 500;
}

/* Responsive */
@media (max-width: 768px) {
    .welcome-message {
        font-size: 14px;
    }
    
    .profile-popup {
        right: 10px;
        width: 280px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const notificationBell = document.getElementById('notification-bell');
    const notificationPopup = document.getElementById('notification-popup');
    const clearAllBtn = document.getElementById('clear-all');
    const notificationItems = document.getElementById('notification-items');
    const userProfile = document.getElementById('user-profile');
    const profilePopup = document.getElementById('profile-popup');
    
    // Toggle notification popup
    notificationBell.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        if (profilePopup) profilePopup.classList.remove('show');
        notificationPopup.classList.toggle('show');
        
        if(notificationPopup.classList.contains('show')) {
            document.querySelector('.notification-count').style.display = 'none';
        }
    });
    
    // Toggle profile popup
    if (userProfile && profilePopup) {
        userProfile.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            notificationPopup.classList.remove('show');
            profilePopup.classList.toggle('show');
        });
    }
    
    // Close popups when clicking outside
    document.addEventListener('click', function(e) {
        if(!notificationBell.contains(e.target) && 
           !notificationPopup.contains(e.target) && 
           notificationPopup.classList.contains('show')) {
            notificationPopup.classList.remove('show');
        }
        
        if(profilePopup && userProfile && 
           !userProfile.contains(e.target) && 
           !profilePopup.contains(e.target) && 
           profilePopup.classList.contains('show')) {
            profilePopup.classList.remove('show');
        }
    });
    
    // Clear notifications
    clearAllBtn.addEventListener('click', function() {
        const emptyMessage = document.createElement('div');
        emptyMessage.className = 'no-notifications';
        emptyMessage.textContent = 'No new notifications';
        notificationItems.innerHTML = '';
        notificationItems.appendChild(emptyMessage);
        document.querySelector('.notification-count').style.display = 'none';
    });
    
    // Add notification function
    window.addNotification = function(type, title, description) {
        const notificationItem = document.createElement('div');
        notificationItem.className = 'notification-item unread';
        
        const iconClass = type === 'user' ? 'user' : type === 'donate' ? 'donate' : 'collect';
        const iconType = type === 'user' ? 'bxs-user' : type === 'donate' ? 'bxs-donate-heart' : 'bxs-package';
        
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
        
        const noNotifications = notificationItems.querySelector('.no-notifications');
        if(noNotifications) {
            notificationItems.removeChild(noNotifications);
        }
        
        notificationItems.prepend(notificationItem);
        
        const notificationCount = document.querySelector('.notification-count');
        notificationCount.style.display = 'flex';
        notificationCount.textContent = document.querySelectorAll('.notification-item').length;
        
        notificationBell.querySelector('i').style.color = '#ff4757';
        setTimeout(() => {
            notificationBell.querySelector('i').style.color = '#555';
        }, 1000);
    }
});

ii=0;
function getNotification() {
    ii++;
    console.log("Function called ! "+ii);
    const xmlhttp = new XMLHttpRequest();
    xmlhttp.onload = function() {
      document.getElementById("notification-items").innerHTML = this.responseText;
      //console.log(this.responseText +" : "+ii);
    }
  xmlhttp.open("GET", "getNotification.php");
  xmlhttp.send();
  setTimeout(getNotification, 2000); 
}
</script>