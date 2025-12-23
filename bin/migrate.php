#!/usr/bin/env php
<?php
// Simple migration runner for the invoice project
$dir = __DIR__ . '/../migrations';
if (!is_dir($dir)) {
    echo "No migrations directory found.\n";
    exit(1);
}
$conf = parse_ini_file(__DIR__ . '/../ppa/database_conf.ini');
$mysqli = mysqli_connect($conf['Host'], $conf['User'], $conf['Password'], $conf['Schema']);
if (!$mysqli) {
    echo "DB connection failed: " . mysqli_connect_error() . "\n";
    exit(1);
}
// Ensure schema_migrations table exists
$create = file_get_contents(__DIR__ . '/../migrations/001_create_schema_migrations.sql');
$mysqli->multi_query($create);
while ($mysqli->more_results() && $mysqli->next_result()) { /* flush */ }

// Get applied migrations
$result = $mysqli->query("SELECT version FROM schema_migrations");
$applied = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $applied[$row['version']] = true;
    }
}

$mfiles = glob($dir . '/*.sql');
sort($mfiles);
foreach ($mfiles as $file) {
    $version = basename($file);
    if (isset($applied[$version])) {
        echo "Skipping $version\n";
        continue;
    }
    echo "Applying $version...\n";
    $sql = file_get_contents($file);
    if ($mysqli->multi_query($sql)) {
        // wait for completion
        while ($mysqli->more_results() && $mysqli->next_result()) { /* flush */ }
        $stmt = $mysqli->prepare('INSERT INTO schema_migrations (version) VALUES (?)');
        $stmt->bind_param('s', $version);
        $stmt->execute();
        echo "Applied $version\n";
    } else {
        echo "Failed to apply $version: " . $mysqli->error . "\n";
        exit(1);
    }
}

echo "Migrations complete.\n";
