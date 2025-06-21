<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "food_waste";

// Database connection
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$res=mysqli_query($conn,"select * from notification order by id desc");
while($row=mysqli_fetch_array($res))
{
?>
    <div class="notification-item unread">
        <div class="notification-icon collect">
            <i class='bx bxs-package'></i>
        </div>
        <div class="notification-content">
            <p class="notification-title"><?php echo $row['title'] ?></p>
            <p class="notification-desc"><?php echo $row['details'] ?></p>
            <p class="notification-time">   <?php echo $row['time'] ?></p>
        </div>
    </div>
<?php
}
?>