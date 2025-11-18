<?php
error_reporting(0);
include_once('../connection.config.php');

if (isset($_GET['accessToken'])){
$accessToken = $_GET['accessToken'];
$n = 1;
$_page = array();
$result = mysqli_query($link, "SELECT * FROM addondomain WHERE sub_domain='GLOBAL'");
while($row = mysqli_fetch_array($result)) {
$graph = 'https://graph.facebook.com/';
$post = 'id='.urlencode('http://'.$row['domain']).'&scrape=true&access_token='.$accessToken;

$r = curl_init();
curl_setopt($r, CURLOPT_URL, $graph);
curl_setopt($r, CURLOPT_POST, 1);
curl_setopt($r, CURLOPT_POSTFIELDS, $post);
curl_setopt($r, CURLOPT_RETURNTRANSFER, true);
curl_setopt($r, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($r, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($r, CURLOPT_HEADER, 0);
curl_setopt($r, CURLOPT_CONNECTTIMEOUT, 5);

$data = curl_exec($r);
curl_close($r);
$response = json_decode($data);

if($response->url != null) {
    $info = $checkdom . '<footer class="text-muted" style="text-decoration:none;color:#3AAF85"><i class="fa fa-check" aria-hidden="true"> Domain Aman!</i></footer>';
}
elseif($response->error->message == "(#368) The action attempted has been deemed abusive or is otherwise disallowed"){
    $info = $checkdom . '<footer class="text-muted" style="text-decoration:none;color:#DA552F"><i class="fa fa-times" aria-hidden="true"> Domain Fraud!</i></footer>';
}
else{
    $info = $response->error->message;
}

     $_page[] = array(
        'id' => $n++,
        'info' => $info,
        'domain' => '<footer class="text-muted" style="text-decoration:none;">'.$row['domain'].'</footer>',
        // and so on for the rest
    );   
	}
header('Content-Type: application/json');
echo json_encode($_page);
}
?>