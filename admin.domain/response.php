<?php
// response.php
// include connection file
include_once('../connection.php');

$db = new dbObj();
$conn = $db->getConnstring(); // mysqli connection

$params = $_REQUEST;
$action = isset($params['action']) ? $params['action'] : '';

$empCls = new addondomain($conn);

switch($action) {
 case 'add':
    $empCls->insertaddondomain($params);
 break;
 case 'edit':
    $empCls->updateaddondomain($params);
 break;
 case 'delete':
    $empCls->deleteaddondomain($params);
 break;
 default:
    $empCls->getaddondomains($params);
    return;
}

class addondomain {
    protected $conn;
    protected $data = array();

    function __construct($conn) {
        $this->conn = $conn;
    }

    public function getaddondomains($params) {
        $this->data = $this->getRecords($params);
        header('Content-Type: application/json');
        echo json_encode($this->data);
    }

    function insertaddondomain($params) {
        header('Content-Type: application/json');
        // expecting single domain per request: domain and sub_domain
        $sub = isset($params['sub_domain']) ? strtoupper(trim($params['sub_domain'])) : 'GLOBAL';
        $domain = isset($params['domain']) ? trim($params['domain']) : '';

        if ($domain === '') {
            echo json_encode(['ok' => false, 'message' => 'No domain provided']);
            return;
        }

        // basic sanitization
        $domain_safe = htmlspecialchars($domain, ENT_QUOTES, 'UTF-8');

        // prepared statement to avoid injection
        $stmt = $this->conn->prepare("INSERT INTO `addondomain` (sub_domain, domain) VALUES (?, ?)");
        if (!$stmt) {
            echo json_encode(['ok' => false, 'message' => 'Prepare failed: ' . $this->conn->error]);
            return;
        }
        $stmt->bind_param('ss', $sub, $domain_safe);
        $res = $stmt->execute();
        if ($res) {
            echo json_encode(['ok' => true, 'message' => 'Inserted', 'domain' => $domain_safe, 'id' => $stmt->insert_id]);
        } else {
            // duplicate or other error
            echo json_encode(['ok' => false, 'message' => 'DB error: ' . $stmt->error]);
        }
        $stmt->close();
    }

    function getRecords($params) {
        $rp = isset($params['rowCount']) ? intval($params['rowCount']) : 100;
        $page = isset($params['current']) ? intval($params['current']) : 1;
        $start_from = ($page-1) * $rp;

        $where = '';
        $data = [];

        if (!empty($params['searchPhrase'])) {
            $sp = $this->conn->real_escape_string($params['searchPhrase']);
            $where = " WHERE (sub_domain LIKE '{$sp}%' OR domain LIKE '{$sp}%') ";
        }

        $order = '';
        if (!empty($params['sort']) && is_array($params['sort'])) {
            $col = key($params['sort']);
            $dir = current($params['sort']) === 'asc' ? 'ASC' : 'DESC';
            // whitelisted columns
            $allowed = ['id','sub_domain','domain'];
            if (in_array($col, $allowed)) $order = " ORDER BY {$col} {$dir} ";
        }

        $sqlTot = "SELECT COUNT(*) as cnt FROM `addondomain` " . $where;
        $totRes = $this->conn->query($sqlTot);
        $total = 0;
        if ($totRes) {
            $r = $totRes->fetch_assoc();
            $total = intval($r['cnt']);
        }

        $sql = "SELECT * FROM `addondomain` " . $where . $order;
        if ($rp != -1) $sql .= " LIMIT {$start_from}, {$rp}";

        $query = $this->conn->query($sql) or die(json_encode(['ok' => false, 'message' => 'error to fetch addondomains data']));
        while ($row = $query->fetch_assoc()) { $data[] = $row; }

        $json_data = array(
            "current" => intval($page),
            "rowCount" => intval($rp),
            "total" => intval($total),
            "rows" => $data
        );
        return $json_data;
    }

    function updateaddondomain($params) {
        header('Content-Type: application/json');
        $id = isset($params['edit_id']) ? intval($params['edit_id']) : 0;
        $sub = isset($params['edit_sub_domain']) ? strtoupper(trim($params['edit_sub_domain'])) : '';
        $domain = isset($params['edit_domain']) ? trim($params['edit_domain']) : '';

        if ($id <= 0 || $domain === '') {
            echo json_encode(['ok' => false, 'message' => 'Invalid parameters']);
            return;
        }

        $domain_safe = htmlspecialchars($domain, ENT_QUOTES, 'UTF-8');

        $stmt = $this->conn->prepare("UPDATE `addondomain` SET sub_domain = ?, domain = ? WHERE id = ?");
        if (!$stmt) {
            echo json_encode(['ok' => false, 'message' => 'Prepare failed: ' . $this->conn->error]);
            return;
        }
        $stmt->bind_param('ssi', $sub, $domain_safe, $id);
        $res = $stmt->execute();
        if ($res) {
            echo json_encode(['ok' => true, 'message' => 'Updated']);
        } else {
            echo json_encode(['ok' => false, 'message' => 'DB error: ' . $stmt->error]);
        }
        $stmt->close();
    }

    function deleteaddondomain($params) {
        header('Content-Type: application/json');
        $id = isset($params['id']) ? intval($params['id']) : 0;
        if ($id <= 0) {
            echo json_encode(['ok' => false, 'message' => 'Invalid id']);
            return;
        }

        $stmt = $this->conn->prepare("DELETE FROM `addondomain` WHERE id = ?");
        if (!$stmt) {
            echo json_encode(['ok' => false, 'message' => 'Prepare failed: ' . $this->conn->error]);
            return;
        }
        $stmt->bind_param('i', $id);
        $res = $stmt->execute();
        if ($res) {
            echo json_encode(['ok' => true, 'message' => 'Deleted']);
        } else {
            echo json_encode(['ok' => false, 'message' => 'DB error: ' . $stmt->error]);
        }
        $stmt->close();
    }
}
?>
