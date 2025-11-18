<?php
// scan_netskope_cpanel.php
// Simple PHP script to scan for domains that contain the string "netskope" (case-insensitive).
// Modes supported:
//  - filesystem: requires root and scans /var/cpanel/userdata for occurrences (best for WHM/cPanel root)
//  - csv: scan a CSV/text file containing domains (one per line or in CSV columns)
// Usage (CLI): php scan_netskope_cpanel.php
// Edit the $config section below before running.

// ---------- CONFIG ----------
$config = [
    // mode: 'filesystem' or 'csv'
    'mode' => 'filesystem',

    // if mode == 'csv', set the path to the exported domain list
    'csv_path' => __DIR__ . '/domains.csv',

    // search term (default: netskope)
    'search' => 'netskope',

    // If filesystem mode, list of paths to search. Keep defaults for cPanel/WHM.
    'search_paths' => [
        '/var/cpanel/userdata',
        '/var/named',
        '/etc/valiases',
    ],

    // whether to show matched file snippets (true/false)
    'show_snippets' => true,
];
// ----------------------------

function out($s = "") { echo $s . PHP_EOL; }

$search = $config['search'];
$matches = [];

if ($config['mode'] === 'filesystem') {
    out("[INFO] Mode: filesystem. Scanning paths: " . implode(', ', $config['search_paths']));

    foreach ($config['search_paths'] as $path) {
        if (!is_dir($path)) {
            out("[WARN] Path not found or not accessible: $path");
            continue;
        }

        // Recursively iterate and grep files for search term
        $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS));
        foreach ($it as $file) {
            if (!$file->isFile()) continue;
            // skip large binary files by extension
            $ext = pathinfo($file->getFilename(), PATHINFO_EXTENSION);
            if (in_array(strtolower($ext), ['gz','zip','db','so','pem','key','crt'])) continue;

            $filepath = $file->getPathname();
            // Quick size check to avoid scanning huge files
            if ($file->getSize() > 5 * 1024 * 1024) continue; // skip >5MB

            $content = @file_get_contents($filepath);
            if ($content === false) continue;

            if (stripos($content, $search) !== false) {
                // try to extract domain-ish name from filename or file content
                $found = [];
                // look for domain-like tokens in file
                if (preg_match_all('/([a-z0-9][-a-z0-9]+\.)+[a-z]{2,}/i', $content, $m)) {
                    $found = array_unique($m[0]);
                }

                $matches[] = [
                    'file' => $filepath,
                    'domains' => $found,
                    'snippet' => $config['show_snippets'] ? get_snippet($content, $search) : '',
                ];
            }
        }
    }

    // Also try to detect files whose filename contains the search term (useful for userdata filenames)
    foreach ($config['search_paths'] as $path) {
        if (!is_dir($path)) continue;
        $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS));
        foreach ($rii as $f) {
            if (!$f->isFile()) continue;
            if (stripos($f->getFilename(), $search) !== false) {
                $matches[] = [
                    'file' => $f->getPathname(),
                    'domains' => [],
                    'snippet' => '',
                ];
            }
        }
    }

} else { // csv mode
    $csv = $config['csv_path'];
    out("[INFO] Mode: csv. Reading file: $csv");
    if (!file_exists($csv)) {
        out("[ERROR] CSV file not found: $csv");
        exit(1);
    }

    $fh = fopen($csv, 'r');
    if (!$fh) { out('[ERROR] Cannot open CSV file.'); exit(1); }

    while (($row = fgetcsv($fh)) !== false) {
        // join row columns and search
        $line = implode(' ', $row);
        if (stripos($line, $search) !== false) {
            // extract domains
            if (preg_match_all('/([a-z0-9][-a-z0-9]+\.)+[a-z]{2,}/i', $line, $m)) {
                $domains = array_unique($m[0]);
            } else {
                $domains = [trim($line)];
            }
            $matches[] = [ 'file' => $csv, 'domains' => $domains, 'snippet' => $line ];
        }
    }
    fclose($fh);
}

// Deduplicate results by file
$seen = [];
$unique = [];
foreach ($matches as $m) {
    if (in_array($m['file'], $seen)) continue;
    $seen[] = $m['file'];
    $unique[] = $m;
}

if (empty($unique)) {
    out("[RESULT] Tidak ketemu domain yang mengandung '{$search}'. Good news for once.");
    exit(0);
}

out("[RESULT] Ditemukan " . count($unique) . " file yang mengandung '{$search}':\n");
foreach ($unique as $u) {
    out('FILE: ' . $u['file']);
    if (!empty($u['domains'])) out('  Domains found: ' . implode(', ', $u['domains']));
    if (!empty($u['snippet'])) out('  Snippet: ' . trim(substr($u['snippet'], 0, 200)) . '...');
    out('');
}

function get_snippet($content, $term, $radius = 60) {
    $pos = stripos($content, $term);
    if ($pos === false) return '';
    $start = max(0, $pos - $radius);
    $len = strlen($term) + $radius * 2;
    $s = substr($content, $start, $len);
    return preg_replace('/\s+/', ' ', trim($s));
}

// EOF
