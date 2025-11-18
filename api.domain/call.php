<?php
error_reporting(0);

if(isset($_GET['domain'])) 
{
	die(ajax_api($_GET['domain']));
}

//gets the data from a URL  
function ajax_api($domain)  
{
    $userid = $_GET['sub_domain'];
    $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . '://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
    $url = $actual_link.'/api.php';
    $curl = curl_init();          
    $post['domain'] = $domain; // our data todo in received

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt ($curl, CURLOPT_POST, TRUE);
    curl_setopt ($curl, CURLOPT_POSTFIELDS, $post); 
    curl_setopt($curl, CURLOPT_TIMEOUT, 1); 
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_FORBID_REUSE, true);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 1);
    curl_setopt($curl, CURLOPT_DNS_CACHE_TIMEOUT, 10); 
    curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
    $result = curl_exec($curl);
    
    $_page = array();
     $_page[] = array(
        'domain' => $domain,
        'userid' => $userid
        // and so on for the rest
    );   
    
header ("Content-type: application/json");
print json_encode($_page);    
curl_close($curl);
}
?>