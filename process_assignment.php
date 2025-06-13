<?php
// Database configuration
$server = "localhost";
$user = "root";
$pass = "";
$db = "food_waste";

// Connect to database
$conn = new mysqli($server, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set response content type
header('Content-Type: application/json');

// Initialize response array
$response = array(
    'success' => false,
    'message' => '',
    'data' => null
);

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validate and sanitize input
        if (!isset($_POST['food_id']) || !isset($_POST['ngo_id'])) {
            throw new Exception("Missing required parameters: food_id and ngo_id");
        }

        $food_id = filter_var($_POST['food_id'], FILTER_VALIDATE_INT);
        $ngo_id = filter_var($_POST['ngo_id'], FILTER_VALIDATE_INT);

        if ($food_id === false || $ngo_id === false) {
            throw new Exception("Invalid food_id or ngo_id. Must be valid integers.");
        }

        if ($food_id <= 0 || $ngo_id <= 0) {
            throw new Exception("food_id and ngo_id must be positive integers.");
        }

        // Check if food item exists and is not already assigned
        $check_food = "SELECT fooddetails_id, food_name, donor_name, assigned_ngo_id 
                       FROM fooddetails 
                       WHERE fooddetails_id = ?";
        $stmt_check = $conn->prepare($check_food);
        
        if (!$stmt_check) {
            throw new Exception("Database prepare error: " . $conn->error);
        }

        $stmt_check->bind_param("i", $food_id);
        $stmt_check->execute();
        $result_food = $stmt_check->get_result();

        if ($result_food->num_rows === 0) {
            throw new Exception("Food item with ID $food_id not found.");
        }

        $food_data = $result_food->fetch_assoc();
        
        if ($food_data['assigned_ngo_id'] !== null) {
            throw new Exception("Food item is already assigned to another NGO.");
        }

        // Check if NGO exists and is active
        $check_ngo = "SELECT ngo_id, ngo_name, status 
                      FROM ngo 
                      WHERE ngo_id = ?";
        $stmt_ngo = $conn->prepare($check_ngo);
        
        if (!$stmt_ngo) {
            throw new Exception("Database prepare error: " . $conn->error);
        }

        $stmt_ngo->bind_param("i", $ngo_id);
        $stmt_ngo->execute();
        $result_ngo = $stmt_ngo->get_result();

        if ($result_ngo->num_rows === 0) {
            throw new Exception("NGO with ID $ngo_id not found.");
        }

        $ngo_data = $result_ngo->fetch_assoc();
        
        // Check if NGO is active (assuming there's a status field)
        if (isset($ngo_data['status']) && $ngo_data['status'] !== 'active') {
            throw new Exception("Selected NGO is not currently active.");
        }

        // Begin transaction for data consistency
        $conn->begin_transaction();

        try {
            // Update food assignment
            $update_food = "UPDATE fooddetails 
                           SET assigned_ngo_id = ?, 
                               assignment_date = NOW() 
                           WHERE fooddetails_id = ?";
            $stmt_update = $conn->prepare($update_food);
            
            if (!$stmt_update) {
                throw new Exception("Database prepare error: " . $conn->error);
            }

            $stmt_update->bind_param("ii", $ngo_id, $food_id);
            
            if (!$stmt_update->execute()) {
                throw new Exception("Failed to update food assignment: " . $stmt_update->error);
            }

            // Optional: Log the assignment in a separate assignments table
            $log_assignment = "INSERT INTO assignment_log (food_id, ngo_id, assigned_by, assignment_date) 
                              VALUES (?, ?, ?, NOW())";
            $stmt_log = $conn->prepare($log_assignment);
            
            // Get current user (you might want to implement proper session management)
            $assigned_by = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1; // Default admin
            
            if ($stmt_log) {
                $stmt_log->bind_param("iii", $food_id, $ngo_id, $assigned_by);
                $stmt_log->execute(); // Don't fail if logging fails
            }

            // Commit transaction
            $conn->commit();

            // Success response
            $response['success'] = true;
            $response['message'] = "Food item '{$food_data['food_name']}' successfully assigned to '{$ngo_data['ngo_name']}'.";
            $response['data'] = array(
                'food_id' => $food_id,
                'food_name' => $food_data['food_name'],
                'donor_name' => $food_data['donor_name'],
                'ngo_id' => $ngo_id,
                'ngo_name' => $ngo_data['ngo_name'],
                'assignment_date' => date('Y-m-d H:i:s')
            );

        } catch (Exception $e) {
            // Rollback transaction on error
            $conn->rollback();
            throw $e;
        }

        // Close prepared statements
        $stmt_check->close();
        $stmt_ngo->close();
        $stmt_update->close();
        if (isset($stmt_log)) $stmt_log->close();

    } else {
        throw new Exception("Invalid request method. Only POST requests are allowed.");
    }

} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();
    
    // Log error for debugging (in production, log to file)
    error_log("Assignment Error: " . $e->getMessage());
}

// Close database connection
$conn->close();

// Handle different response formats based on request
if (isset($_POST['ajax']) && $_POST['ajax'] === '1') {
    // AJAX request - return JSON
    echo json_encode($response);
} else {
    // Regular form submission - return HTML
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Assignment Result</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background: #f0f2f5;
                padding: 20px;
                margin: 0;
            }
            .container {
                max-width: 600px;
                margin: 50px auto;
                background: white;
                padding: 30px;
                border-radius: 10px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                text-align: center;
            }
            .success {
                color: #28a745;
                border: 2px solid #28a745;
                background: #d4edda;
                padding: 20px;
                border-radius: 8px;
                margin-bottom: 20px;
            }
            .error {
                color: #dc3545;
                border: 2px solid #dc3545;
                background: #f8d7da;
                padding: 20px;
                border-radius: 8px;
                margin-bottom: 20px;
            }
            .success-icon {
                font-size: 3em;
                margin-bottom: 10px;
            }
            .error-icon {
                font-size: 3em;
                margin-bottom: 10px;
            }
            .btn {
                display: inline-block;
                padding: 12px 24px;
                margin: 10px;
                text-decoration: none;
                border-radius: 5px;
                font-weight: bold;
                transition: all 0.3s ease;
            }
            .btn-primary {
                background: #007bff;
                color: white;
            }
            .btn-primary:hover {
                background: #0056b3;
            }
            .btn-secondary {
                background: #6c757d;
                color: white;
            }
            .btn-secondary:hover {
                background: #545b62;
            }
            .details {
                text-align: left;
                margin: 20px 0;
            }
            .details strong {
                color: #333;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <?php if ($response['success']): ?>
                <div class="success">
                    <div class="success-icon">✓</div>
                    <h2>Assignment Successful!</h2>
                    <p><?php echo htmlspecialchars($response['message']); ?></p>
                    
                    <?php if ($response['data']): ?>
                        <div class="details">
                            <strong>Assignment Details:</strong><br>
                            Food Item: <?php echo htmlspecialchars($response['data']['food_name']); ?><br>
                            Donor: <?php echo htmlspecialchars($response['data']['donor_name']); ?><br>
                            Assigned NGO: <?php echo htmlspecialchars($response['data']['ngo_name']); ?><br>
                            Assignment Date: <?php echo htmlspecialchars($response['data']['assignment_date']); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="error">
                    <div class="error-icon">✗</div>
                    <h2>Assignment Failed</h2>
                    <p><?php echo htmlspecialchars($response['message']); ?></p>
                </div>
            <?php endif; ?>
            
            <div>
                <a href="assign_ngo.php" class="btn btn-primary">Assign Another</a>
                <a href="assigned_donations.php" class="btn btn-secondary">View Assignments</a>
            </div>
        </div>
        
        <script>
            // Auto redirect after 5 seconds on success
            <?php if ($response['success']): ?>
                setTimeout(function() {
                    window.location.href = 'assign_ngo.php';
                }, 5000);
            <?php endif; ?>
        </script>
    </body>
    </html>
    <?php
}
?>