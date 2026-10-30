<?php
define('ROOT_PATH', dirname(__FILE__));
require ROOT_PATH . '/src/helpers.php';
require ROOT_PATH . '/src/Core/Database.php';

echo "Database connection: OK\n";

// Check roles table
try {
    $roles = Database::query('SELECT * FROM roles')->fetchAll();
    echo "Roles table:\n";
    if (!$roles) {
        echo "  (empty - no roles exist!)\n";
    } else {
        foreach ($roles as $r) {
            echo "  ID=" . $r['id'] . " | " . $r['name'] . "\n";
        }
    }
} catch (Throwable $e) {
    echo "Roles table error: " . $e->getMessage() . "\n";
}

// Check users table
try {
    $count = Database::query('SELECT COUNT(*) FROM users')->fetchColumn();
    echo "Users count: $count\n";
} catch (Throwable $e) {
    echo "Users table error: " . $e->getMessage() . "\n";
}

// Show config being used
$cfg = config('db');
echo "\nConfig DB: host={$cfg['host']} name={$cfg['name']} user={$cfg['user']}\n";