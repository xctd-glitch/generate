<?php
include_once('../../xmlapi.php');

ob_end_clean(); //if our framework have turn on ob_start() we don't need bufering respond up to this script will be finish 
header("Connection: close\r\n"); //send information to curl is close
header("Content-Encoding: none\r\n"); //extra information 
header("Content-Length: 1"); //extra information
ignore_user_abort(true); //script will be exisits up to finish below query even web browser will be close before sender get respond

error_reporting(0);
if(isset($_POST['domain'])) 
{
	die(ajax_api($_POST['domain']));
}

//gets the data from a URL  
function ajax_api($domain)  
{

    $cpanelUser = 'gassstea';
    $cpanelPass = 'ksw30089ksw30089ksw30089';

    $XmlApi = new xmlapi('localhost');
    $XmlApi->set_port(2083);
    $XmlApi->set_output('json');
    $XmlApi->password_auth($cpanelUser, $cpanelPass);
    $XmlApi->set_debug(0);

    $result = $XmlApi->api2_query($cpanelUser, 'Park', 'park', [
        'domain'      => $domain
    ]);

header ("Content-type: application/json");
if($result !== null){
        $result = $XmlApi->api2_query($cpanelUser, 'SubDomain', 'addsubdomain', [
       'domain'      => '*',
       'rootdomain'  => $domain,
       'dir'         => '/gasss-team'
    ]);
}

}
?>
