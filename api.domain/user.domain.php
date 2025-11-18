<?php
error_reporting(0);
include_once('../connection.config.php');

if (isset($_GET['domain']) AND isset($_GET['sub_domain'])){
$domain = mysqli_real_escape_string($link, $_GET['domain']);
$sub_domain = mysqli_real_escape_string($link, $_GET['sub_domain']);
$domain = $domain;
$sub_domain = $sub_domain;

$n = 1;
$_page = array();
$result = mysqli_query($link, "SELECT * FROM addondomain WHERE sub_domain='$sub_domain'");
while($row = mysqli_fetch_array($result)) {         

     $_page[] = array(
        'id' => $n++,
        'sub_domain' => $row['sub_domain'],
        'domain' => $row['domain'],
        // and so on for the rest
    );   
	}
header('Content-Type: application/json');
echo json_encode($_page);
}
?>