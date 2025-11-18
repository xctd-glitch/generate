
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
		
		$sub_id = strtoupper($params["sub_id"]);
		$gen_url = htmlspecialchars_decode($params["gen_url"]);
		$sm_url = strtoupper($params["sm_url"]);
		$password = htmlspecialchars_decode($params["password"]);

		$sql = "INSERT INTO `generate` (sub_id, password, gen_url, sm_url) VALUES('" . $sub_id . "','" . $password . "','" . $gen_url . "','" . $sm_url . "') ";
		
		echo $result = mysqli_query($this->conn, $sql) or die("error to insert generate data");
		
	}
	
	
	function getRecords($params) {
		$rp = isset($params['rowCount']) ? $params['rowCount'] : 100;
		
		if (isset($params['current'])) { $page  = $params['current']; } else { $page=1; };  
        $start_from = ($page-1) * $rp;
		
		$sql = $sqlRec = $sqlTot = $where = '';
		
		if( !empty($params['searchPhrase']) ) {   
			$where .=" WHERE ";
			$where .=" ( sub_id LIKE '".$params['searchPhrase']."%' ";    
			$where .=" OR password LIKE '".$params['searchPhrase']."%'";
			$where .=" OR gen_url LIKE '".$params['searchPhrase']."%'";
			$where .=" OR sm_url LIKE '".$params['searchPhrase']."%' )";
			}
	   if( !empty($params['sort']) ) {  
			$where .=" ORDER By ".key($params['sort']) .' '.current($params['sort'])." ";
		}
	   // getting total number records without any search
		$sql = "SELECT * FROM `generate` ";
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
		$sql = "Update `generate` set sub_id='" . strtoupper($params["edit_sub_id"])."', password='" . htmlspecialchars_decode($params["edit_password"]) . "', gen_url='" . htmlspecialchars_decode($params["edit_gen_url"]) . "', sm_url='" . htmlspecialchars_decode($params["edit_sm_url"]) . "' WHERE id='".$_POST["edit_id"]."'";
		
		echo $result = mysqli_query($this->conn, $sql) or die("error to update generate data");
	}
	
	function deletegenerate($params) {
		$data = array();
		//print_R($_POST);die;
		$sql = "delete from `generate` WHERE id='".$params["id"]."'";
		
		echo $result = mysqli_query($this->conn, $sql) or die("error to delete generate data");
	}
}
?>
	