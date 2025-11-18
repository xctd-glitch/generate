<?php
error_reporting(0);
include_once('../connection.config.php');

if (isset($_GET['domain'])){
$domain = mysqli_real_escape_string($link, $_GET['domain']);
$domain = $domain;

$result = mysqli_query($link, "SELECT * FROM addondomain WHERE domain='$domain'");
while($row = mysqli_fetch_array($result)) {        
$del_domain = $row['domain'];
mysqli_query($link, "DELETE FROM addondomain WHERE domain = '$del_domain' ");
echo 1;
	}
}
?>