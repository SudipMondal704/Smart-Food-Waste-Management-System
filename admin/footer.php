<div class="content">
    <div class="head-title">
        <div class="left">
            <h2>Dashboard</h2>
            <ul class="breadcrumb">
                <li><p>Home</p></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li>
                    <a class="active" href="#">
                        <?php 
                            if (isset($_GET['type'])) {
                                switch ($_GET['type']) {
                                    case 'donors': echo "Donors"; break;
                                    case 'create_donor': echo "Create Donor"; break;
                                    case 'ngos': echo "NGOs"; break;
                                    case 'create_ngo': echo "Create NGO"; break;
                                     case 'feedback': echo "Feedbacks"; break;
                                   // case 'food': echo "Food Details List"; break;
                                    case 'pending': echo "Pending Donation Request"; break;
                                    case 'assign': echo "Assigned Donation Request"; break;
                                    case 'status': echo "Donation Status"; break;
                                    default: echo "Dashboard";
                                }
                            } else {
                                echo "Dashboard";
                            }
                        ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>
                    <?php 
                        if (isset($_GET['type'])) {
                            switch ($_GET['type']) {
                                case 'donors': echo "Donors List"; break;
                                case 'create_donor': echo ""; break;
                                case 'ngos': echo "NGO List"; break;
                                case 'create_ngo': echo ""; break;
                                case 'feedback': echo "Feedback List"; break;
                               // case 'food': echo "Food Details List"; break;
                                case 'pending': echo "Pending Donation Request"; break;
                                case 'assign': echo "Assigned Donation Request"; break;
                                case 'status': echo "Donation Status"; break;
                                default: echo "Dashboard";
                            }
                        } else {
                            echo "Dashboard";
                        }
                    ?>
                </h3>
               
            </div>

            <?php
                if (isset($_GET['type'])) {
                    $type = $_GET['type'];
                    switch ($type) {
                        case 'donors':
                            include("admin_donor.php");
                            break;
                            case 'create_donor':
                                include("create_donor.php");
                              break;
                        case 'ngos':
                            include("admin_ngo.php");
                            break;
                            case 'create_ngo':
                           include("create_ngo.php");
                             break;
                        case 'feedback':
                            include("admin_feedback.php");
                              break;
                       // case 'food':
                          //  include("admin_fooddetails.php");
                         //   break;
                        case 'pending':
                            include("pending_request.php");
                            break;
                            case 'assign':
                            include("assigned_request.php");
                            break;
                            case 'status':
                            include("admin_fooddetails.php");
                            break;
                        default:
                            include("admin_recentdonate.php");
                    }
                } else {
                    include("admin_recentdonate.php");
                }
            ?>
        </div>
    </div>
</div>
 <!-- Jquery -->
    <script
      src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.js"
      integrity="sha512-8Z5++K1rB3U+USaLKG6oO8uWWBhdYsM3hmdirnOEWp8h2B1aOikj5zBzlXs8QOrvY9OxEnD2QDkbSKKpfqcIWw=="
      crossorigin="anonymous"
    ></script>
    <script src="admin.js"></script>
</body>
</html>
