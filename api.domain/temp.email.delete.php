<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Content-Type: application/json');

error_reporting(0);
include_once("../connection.config.php");

$response = array('success' => false, 'message' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    
    if ($id <= 0) {
        $response['message'] = 'Invalid email ID';
        echo json_encode($response);
        exit;
    }
    
    // Check if table exists
    $table_check = "SHOW TABLES LIKE 'temp_email'";
    $table_result = mysqli_query($link, $table_check);
    
    if (mysqli_num_rows($table_result) == 0) {
        $response['message'] = 'Table does not exist';
        echo json_encode($response);
        exit;
    }
    
    // Delete email
    $delete_sql = "DELETE FROM temp_email WHERE id = ?";
    $delete_stmt = mysqli_prepare($link, $delete_sql);
    mysqli_stmt_bind_param($delete_stmt, "i", $id);
    
    if (mysqli_stmt_execute($delete_stmt)) {
        if (mysqli_stmt_affected_rows($delete_stmt) > 0) {
            $response['success'] = true;
            $response['message'] = 'Email deleted successfully';
        } else {
            $response['message'] = 'Email not found';
        }
    } else {
        $response['message'] = 'Error deleting email: ' . mysqli_error($link);
    }
    
    mysqli_stmt_close($delete_stmt);
} else {
    $response['message'] = 'Invalid request method';
}

echo json_encode($response);
mysqli_close($link);
?>
