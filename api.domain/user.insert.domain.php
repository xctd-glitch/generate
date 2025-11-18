<?php
error_reporting(0);
include_once('../connection.config.php');

if (isset($_GET['domain']) AND isset($_GET['sub_domain'])){
$domain = mysqli_real_escape_string($link, $_GET['domain']);
$sub_domain = mysqli_real_escape_string($link, $_GET['sub_domain']);
$domain = $domain;
$sub_domain = $sub_domain;

$sql = "insert into addondomain(sub_domain,domain) values('$sub_domain','$domain')";
$_page = array();
if (mysqli_query($link, $sql)) {
    $_page[] = array(
        'sub_domain' => $sub_domain,
        'domain' => $domain,
        // and so on for the rest
    );  
} else {
    null;
}
header('Content-Type: application/json');
echo json_encode($_page);
mysqli_close($link);


}
?>