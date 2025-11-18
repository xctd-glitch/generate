<?php
declare(strict_types=1);
session_start();

/* ===== Simple Authentication ===== */
$ADMIN_PASSWORD = 'admin123'; // CHANGE THIS!

if (!isset($_SESSION['admin_logged_in'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
        if ($_POST['password'] === $ADMIN_PASSWORD) {
            $_SESSION['admin_logged_in'] = true;
        } else {
            $error = 'Invalid password';
        }
    }
    
    if (!isset($_SESSION['admin_logged_in'])) {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Admin Login - Domain Manager</title>
            <style>
                body { font-family: Arial, sans-serif; max-width: 400px; margin: 100px auto; padding: 20px; }
                input { width: 100%; padding: 10px; margin: 10px 0; box-sizing: border-box; }
                button { width: 100%; padding: 10px; background: #007bff; color: white; border: none; cursor: pointer; }
                button:hover { background: #0056b3; }
                .error { color: red; padding: 10px; background: #ffe6e6; margin: 10px 0; }
            </style>
        </head>
        <body>
            <h2>Admin Login</h2>
            <?php if (isset($error)): ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="POST">
                <input type="password" name="password" placeholder="Admin Password" required>
                <button type="submit">Login</button>
            </form>
        </body>
        </html>
        <?php
        exit;
    }
}

/* ===== Load dependencies ===== */
require_once __DIR__ . '/connection.config.php';
require_once __DIR__ . '/../url_validator.php';

if (!isset($link) || !$link) {
    die('Database connection failed');
}

$urlValidator = new URLValidator($link);

/* ===== Handle Actions ===== */
$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'add_whitelist':
            $domain = trim($_POST['domain'] ?? '');
            $notes = trim($_POST['notes'] ?? '');
            if ($domain) {
                if ($urlValidator->addToWhitelist($domain, 'admin', $notes)) {
                    $message = "Domain '$domain' added to whitelist";
                    $message_type = 'success';
                } else {
                    $message = "Failed to add domain to whitelist";
                    $message_type = 'error';
                }
            }
            break;
            
        case 'add_blacklist':
            $domain = trim($_POST['domain'] ?? '');
            $reason = trim($_POST['reason'] ?? 'Manually blocked');
            if ($domain) {
                if ($urlValidator->addToBlacklist($domain, $reason, 'admin')) {
                    $message = "Domain '$domain' added to blacklist";
                    $message_type = 'success';
                } else {
                    $message = "Failed to add domain to blacklist";
                    $message_type = 'error';
                }
            }
            break;
            
        case 'remove_whitelist':
            $domain = trim($_POST['domain'] ?? '');
            if ($domain) {
                if ($urlValidator->removeFromWhitelist($domain)) {
                    $message = "Domain '$domain' removed from whitelist";
                    $message_type = 'success';
                } else {
                    $message = "Failed to remove domain from whitelist";
                    $message_type = 'error';
                }
            }
            break;
            
        case 'remove_blacklist':
            $domain = trim($_POST['domain'] ?? '');
            if ($domain) {
                if ($urlValidator->removeFromBlacklist($domain)) {
                    $message = "Domain '$domain' removed from blacklist";
                    $message_type = 'success';
                } else {
                    $message = "Failed to remove domain from blacklist";
                    $message_type = 'error';
                }
            }
            break;
            
        case 'logout':
            session_destroy();
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
    }
}

/* ===== Get Lists ===== */
$whitelist = [];
$result = $link->query("SELECT * FROM domain_whitelist ORDER BY added_at DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $whitelist[] = $row;
    }
}

$blacklist = [];
$result = $link->query("SELECT * FROM domain_blacklist ORDER BY added_at DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $blacklist[] = $row;
    }
}

/* ===== Get Offering URLs for analysis ===== */
$offering_urls = [];
$result = $link->query("SELECT DISTINCT offer FROM offering WHERE active = 1 LIMIT 100");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $url = $row['offer'];
        $parsed = parse_url($url);
        if (isset($parsed['host'])) {
            $domain = strtolower($parsed['host']);
            if (strpos($domain, 'www.') === 0) {
                $domain = substr($domain, 4);
            }
            $validation = $urlValidator->validateURL($url);
            $offering_urls[] = [
                'url' => $url,
                'domain' => $domain,
                'valid' => $validation['valid'],
                'reason' => $validation['reason']
            ];
        }
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Domain Manager - Admin Panel</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; margin-bottom: 10px; }
        .subtitle { color: #666; margin-bottom: 30px; }
        .section { margin-bottom: 40px; }
        h2 { color: #444; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #007bff; }
        .message { padding: 15px; margin-bottom: 20px; border-radius: 4px; }
        .message.success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .message.error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #555; }
        input[type="text"], textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
        textarea { resize: vertical; min-height: 60px; }
        button { padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; }
        button:hover { background: #0056b3; }
        button.danger { background: #dc3545; }
        button.danger:hover { background: #c82333; }
        button.secondary { background: #6c757d; }
        button.secondary:hover { background: #5a6268; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f8f9fa; font-weight: bold; color: #333; }
        tr:hover { background: #f8f9fa; }
        .status { padding: 4px 8px; border-radius: 3px; font-size: 12px; font-weight: bold; }
        .status.valid { background: #d4edda; color: #155724; }
        .status.invalid { background: #f8d7da; color: #721c24; }
        .empty { text-align: center; padding: 30px; color: #999; font-style: italic; }
        .info-box { background: #e7f3ff; border-left: 4px solid #007bff; padding: 15px; margin-bottom: 20px; }
        .warning-box { background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin-bottom: 20px; }
        .logout { float: right; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        @media (max-width: 768px) { .grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <div class="container">
        <h1>Domain Manager <button class="secondary logout" onclick="document.getElementById('logoutForm').submit()">Logout</button></h1>
        <div class="subtitle">Manage allowed and blocked domains for redirect safety</div>
        
        <form id="logoutForm" method="POST" style="display:none;">
            <input type="hidden" name="action" value="logout">
        </form>
        
        <?php if ($message): ?>
            <div class="message <?= $message_type ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
        
        <div class="info-box">
            <strong>How it works:</strong>
            <ul style="margin-left: 20px; margin-top: 10px;">
                <li><strong>Whitelist:</strong> Domains explicitly allowed for redirects. If whitelist is empty, all non-blacklisted domains are allowed.</li>
                <li><strong>Blacklist:</strong> Domains explicitly blocked. Takes priority over whitelist.</li>
                <li>The system automatically blocks suspicious patterns (IP addresses, data URIs, suspicious TLDs, etc.)</li>
            </ul>
        </div>
        
        <div class="section">
            <h2>Current Offering URLs Status</h2>
            <?php if (empty($offering_urls)): ?>
                <div class="empty">No offering URLs found</div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Domain</th>
                            <th>Status</th>
                            <th>Reason</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($offering_urls as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['domain']) ?></td>
                                <td><span class="status <?= $item['valid'] ? 'valid' : 'invalid' ?>"><?= $item['valid'] ? 'VALID' : 'BLOCKED' ?></span></td>
                                <td><?= htmlspecialchars($item['reason']) ?></td>
                                <td>
                                    <?php if (!$item['valid']): ?>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="action" value="add_whitelist">
                                            <input type="hidden" name="domain" value="<?= htmlspecialchars($item['domain']) ?>">
                                            <input type="hidden" name="notes" value="Manually approved">
                                            <button type="submit" style="padding: 5px 10px; font-size: 12px;">Whitelist</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        
        <div class="grid">
            <div class="section">
                <h2>Whitelist (<?= count($whitelist) ?> domains)</h2>
                
                <form method="POST">
                    <input type="hidden" name="action" value="add_whitelist">
                    <div class="form-group">
                        <label>Domain:</label>
                        <input type="text" name="domain" placeholder="example.com" required>
                    </div>
                    <div class="form-group">
                        <label>Notes (optional):</label>
                        <textarea name="notes" placeholder="Why is this domain trusted?"></textarea>
                    </div>
                    <button type="submit">Add to Whitelist</button>
                </form>
                
                <?php if (empty($whitelist)): ?>
                    <div class="empty">No whitelisted domains</div>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Domain</th>
                                <th>Added</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($whitelist as $item): ?>
                                <tr>
                                    <td>
                                        <?= htmlspecialchars($item['domain']) ?>
                                        <?php if ($item['notes']): ?>
                                            <br><small style="color: #666;"><?= htmlspecialchars($item['notes']) ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($item['added_at']) ?></td>
                                    <td>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="action" value="remove_whitelist">
                                            <input type="hidden" name="domain" value="<?= htmlspecialchars($item['domain']) ?>">
                                            <button type="submit" class="danger" style="padding: 5px 10px; font-size: 12px;">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
            
            <div class="section">
                <h2>Blacklist (<?= count($blacklist) ?> domains)</h2>
                
                <form method="POST">
                    <input type="hidden" name="action" value="add_blacklist">
                    <div class="form-group">
                        <label>Domain:</label>
                        <input type="text" name="domain" placeholder="malicious-site.com" required>
                    </div>
                    <div class="form-group">
                        <label>Reason:</label>
                        <input type="text" name="reason" placeholder="Why is this domain blocked?" value="Manually blocked">
                    </div>
                    <button type="submit" class="danger">Add to Blacklist</button>
                </form>
                
                <?php if (empty($blacklist)): ?>
                    <div class="empty">No blacklisted domains</div>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Domain</th>
                                <th>Reason</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($blacklist as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['domain']) ?></td>
                                    <td><?= htmlspecialchars($item['reason']) ?></td>
                                    <td>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="action" value="remove_blacklist">
                                            <input type="hidden" name="domain" value="<?= htmlspecialchars($item['domain']) ?>">
                                            <button type="submit" style="padding: 5px 10px; font-size: 12px;">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
