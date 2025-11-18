
<?php
	//include connection file 
include_once('../connection.php');
	
	$db = new dbObj();
	$connString =  $db->getConnstring();

	$params = $_REQUEST;
	
	$action = isset($params['action']) != '' ? $params['action'] : '';
	$empCls = new generate($connString);

	switch($action) {
	 case 'add':
		$empCls->insertgenerate($params);
	 break;
	 case 'edit':
		$empCls->updategenerate($params);
	 break;
	 case 'delete':
		$empCls->deletegenerate($params);
	 break;
	 default:
	 $empCls->getgenerates($params);
	 return;
	}
	
	class generate {
	protected $conn;
	protected $data = array();
	function __construct($connString) {
		$this->conn = $connString;
	}
	
	public function getgenerates($params) {
		
		$this->data = $this->getRecords($params);
		
		echo json_encode($this->data);
	}
	function insertgenerate($params) {
		$data = array();
		
		$country_code = strtoupper($params["country_code"]);
		$ua = strtoupper($params["ua"]);
		$offer = htmlspecialchars_decode($params["offer"]);
		$network = htmlspecialchars_decode($params["network"]);

		$sql = "INSERT INTO `offering` (country_code, offer, ua, network) VALUES('" . $country_code . "', '" . $offer . "', '" . $ua . "', '" . $network . "') ";
		
		echo $result = mysqli_query($this->conn, $sql) or die("error to insert generate data");
		
	}
	
	
	function getRecords($params) {
		$rp = isset($params['rowCount']) ? $params['rowCount'] : 100;
		
		if (isset($params['current'])) { $page  = $params['current']; } else { $page=1; };  
        $start_from = ($page-1) * $rp;
		
		$sql = $sqlRec = $sqlTot = $where = '';
		
		if( !empty($params['searchPhrase']) ) {   
			$where .=" WHERE ";
			$where .=" ( country_code LIKE '".$params['searchPhrase']."%' ";    
			$where .=" ( ua LIKE '".$params['searchPhrase']."%' ";    
			$where .=" ( offer LIKE '".$params['searchPhrase']."%' ";    
			$where .=" OR network LIKE '".$params['searchPhrase']."%' )";
	   }
	   if( !empty($params['sort']) ) {  
			$where .=" ORDER By ".key($params['sort']) .' '.current($params['sort'])." ";
		}
	   // getting total number records without any search
		$sql = "SELECT * FROM `offering` ";
		$sqlTot .= $sql;
		$sqlRec .= $sql;
		
		//concatenate search sql if value exist
		if(isset($where) && $where != '') {

			$sqlTot .= $where;
			$sqlRec .= $where;
		}
		if ($rp!=-1)
		$sqlRec .= " LIMIT ". $start_from .",".$rp;
		
		
		$qtot = mysqli_query($this->conn, $sqlTot) or die("error to fetch tot generates data");
		$queryRecords = mysqli_query($this->conn, $sqlRec) or die("error to fetch generates data");
		
		while( $row = mysqli_fetch_assoc($queryRecords) ) { 
			$data[] = $row;
		}

		$json_data = array(
			"current"            => intval($params['current']), 
			"rowCount"            => 100, 			
			"total"    => intval($qtot->num_rows),
			"rows"            => $data   // total data array
			);
		
		return $json_data;
	}
	function updategenerate($params) {
		$data = array();
		//print_R($_POST);die;
		$sql = "Update `offering` set country_code = '" . $params["edit_country_code"] . "', ua='" . $params["edit_ua"]. "', offer='" . htmlspecialchars_decode($params["edit_offer"]). "' , network='" . htmlspecialchars_decode($params["edit_network"]). "' WHERE id='".$_POST["edit_id"]."'";
		
		echo $result = mysqli_query($this->conn, $sql) or die("error to update generate data");
	}
	
	function deletegenerate($params) {
		$data = array();
		//print_R($_POST);die;
		$sql = "delete from `offering` WHERE id='".$params["id"]."'";
		
		echo $result = mysqli_query($this->conn, $sql) or die("error to delete generate data");
	}
}
?>
	