<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Content-Type: application/json');

error_reporting(0);
include_once("../connection.config.php");

$response = array('success' => false, 'message' => '', 'data' => array());

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sub_domain = isset($_GET['sub_domain']) ? trim($_GET['sub_domain']) : '';
    
    if (empty($sub_domain)) {
        $response['message'] = 'Sub domain is required';
        echo json_encode($response);
        exit;
    }
    
    // Check if table exists
    $table_check = "SHOW TABLES LIKE 'temp_email'";
    $table_result = mysqli_query($link, $table_check);
    
    if (mysqli_num_rows($table_result) == 0) {
        // Table doesn't exist yet, return empty array
        $response['success'] = true;
        $response['data'] = array();
        echo json_encode($response);
        exit;
    }
    
    // Get emails for this sub_domain
    $select_sql = "SELECT id, email, DATE_FORMAT(created_at, '%Y-%m-%d %H:%i') as created FROM temp_email WHERE sub_domain = ? ORDER BY created_at DESC";
    $select_stmt = mysqli_prepare($link, $select_sql);
    mysqli_stmt_bind_param($select_stmt, "s", $sub_domain);
    mysqli_stmt_execute($select_stmt);
    $result = mysqli_stmt_get_result($select_stmt);
    
    $emails = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $emails[] = $row;
    }
    
    $response['success'] = true;
    $response['data'] = $emails;
    
    mysqli_stmt_close($select_stmt);
} else {
    $response['message'] = 'Invalid request method';
}

echo json_encode($response);
mysqli_close($link);
?>
