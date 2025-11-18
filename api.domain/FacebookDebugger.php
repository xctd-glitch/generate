<?php
include_once('../connection.config.php');

if(isset($_GET['access_token'])) 
{
	die(ajax_api($_GET['access_token']));
}

//gets the data from a URL  
function ajax_api($access_token){
$n = 1;
$_page = array();
$result = mysqli_query($link, "SELECT * FROM addondomain WHERE sub_domain='$sub_domain'");
while($row = mysqli_fetch_array($result)) {
			$graph = 'https://graph.facebook.com/';
			$post = 'id='.urlencode('http://'.$row['domain']).'&scrape=true&access_token='.$access_token;

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
			    $info = $checkdom . ' : Domain Aman!';
			}
			elseif($response->error->message == "(#368) The action attempted has been deemed abusive or is otherwise disallowed"){
			    $info = $checkdom . ' : Domain Fraud!';
			}
			else{
			    $info = $response->error->message;
			}
			
     $_page[] = array(
        'id' => $n++,
        'sub_domain' => $info,
        'domain' => $row['domain'],
        // and so on for the rest
    );   
	}
	
header('Content-Type: application/json');
echo json_encode($_page);

}
?>
