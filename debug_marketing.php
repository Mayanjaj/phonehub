<?php
require __DIR__ . '/src/bootstrap.php';

echo "=== Checking tables ===\n";
try {
    $tables = Database::query('SHOW TABLES FROM phonehub_uganda')->fetchAll();
    foreach ($tables as $t) {
        echo "  Table: " . reset($t) . "\n";
    }
} catch (Throwable $e) {
    echo "  ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== Checking campaign_visits table ===\n";
try {
    $cols = Database::query('DESCRIBE campaign_visits')->fetchAll();
    foreach ($cols as $c) {
        echo "  " . $c['Field'] . " " . $c['Type'] . "\n";
    }
} catch (Throwable $e) {
    echo "  ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== Checking orders table for utm_campaign ===\n";
try {
    $cols = Database::query('DESCRIBE orders')->fetchAll();
    foreach ($cols as $c) {
        if ($c['Field'] === 'utm_campaign') {
            echo "  Found: " . $c['Field'] . " " . $c['Type'] . "\n";
        }
    }
} catch (Throwable $e) {
    echo "  ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== Checking payments table ===\n";
try {
    $cols = Database::query('DESCRIBE payments')->fetchAll();
    foreach ($cols as $c) {
        echo "  " . $c['Field'] . " " . $c['Type'] . "\n";
    }
} catch (Throwable $e) {
    echo "  ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== Running Campaign::listWithStats() ===\n";
try {
    $rows = \App\Services\Campaign::listWithStats();
    echo "  Success: " . count($rows) . " campaigns\n";
} catch (Throwable $e) {
    echo "  ERROR: " . $e->getMessage() . "\n";
    echo "  Code: " . $e->getCode() . "\n";
}

echo "\n=== Running campaign_visits query ===\n";
try {
    $rows = Database::query('SELECT visit_date, utm_campaign, utm_source, visits FROM campaign_visits ORDER BY visit_date DESC, utm_campaign LIMIT 30')->fetchAll();
    echo "  Success: " . count($rows) . " rows\n";
} catch (Throwable $e) {
    echo "  ERROR: " . $e->getMessage() . "\n";
    echo "  Code: " . $e->getCode() . "\n";
}