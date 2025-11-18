<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Content-Type: application/json');

error_reporting(0);
include_once("../connection.config.php");

$response = array('success' => false, 'message' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $sub_domain = isset($_POST['sub_domain']) ? trim($_POST['sub_domain']) : '';
    
    if (empty($email)) {
        $response['message'] = 'Email is required';
        echo json_encode($response);
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Invalid email format';
        echo json_encode($response);
        exit;
    }
    
    if (empty($sub_domain)) {
        $response['message'] = 'Sub domain is required';
        echo json_encode($response);
        exit;
    }
    
    // Create table if not exists
    $create_table = "CREATE TABLE IF NOT EXISTS temp_email (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) NOT NULL,
        sub_domain VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX(sub_domain)
    )";
    
    if (!mysqli_query($link, $create_table)) {
        $response['message'] = 'Error creating table: ' . mysqli_error($link);
        echo json_encode($response);
        exit;
    }
    
    // Check if email already exists for this sub_domain
    $check_sql = "SELECT id FROM temp_email WHERE email = ? AND sub_domain = ?";
    $check_stmt = mysqli_prepare($link, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "ss", $email, $sub_domain);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);
    
    if (mysqli_stmt_num_rows($check_stmt) > 0) {
        $response['message'] = 'Email already exists';
        mysqli_stmt_close($check_stmt);
        echo json_encode($response);
        exit;
    }
    mysqli_stmt_close($check_stmt);
    
    // Insert email
    $insert_sql = "INSERT INTO temp_email (email, sub_domain) VALUES (?, ?)";
    $insert_stmt = mysqli_prepare($link, $insert_sql);
    mysqli_stmt_bind_param($insert_stmt, "ss", $email, $sub_domain);
    
    if (mysqli_stmt_execute($insert_stmt)) {
        $insert_id = mysqli_insert_id($link);
        $response['success'] = true;
        $response['message'] = 'Email added successfully';
        $response['data'] = array(
            'id' => $insert_id,
            'email' => $email,
            'created' => date('Y-m-d H:i:s')
        );
    } else {
        $response['message'] = 'Error adding email: ' . mysqli_error($link);
    }
    
    mysqli_stmt_close($insert_stmt);
} else {
    $response['message'] = 'Invalid request method';
}

echo json_encode($response);
mysqli_close($link);
?>
