<?php
declare(strict_types=1);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$host = '127.0.0.1';
$user = 'gassstea_base';
$pass = 'gassstea_basegassstea_basegassstea_base';
$db   = 'gassstea_base';
$link = new mysqli($host, $user, $pass, $db);
$link->set_charset('utf8mb4');
