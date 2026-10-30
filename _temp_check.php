<?php
define('ROOT_PATH', dirname(__FILE__));
require ROOT_PATH . '/src/helpers.php';
require ROOT_PATH . '/src/Core/Database.php';

echo "All users in database:\n";
$all = Database::query('SELECT u.id, u.email, r.name AS role, u.is_active FROM users u JOIN roles r ON r.id = u.role_id ORDER BY u.id')->fetchAll();
if (!$all) {
    echo "  (none)\n";
} else {
    foreach ($all as $u) {
        echo "  ID=" . $u['id'] . " | " . $u['email'] . " | " . $u['role'] . " | " . ($u['is_active'] ? 'active' : 'INACTIVE') . "\n";
    }
}

echo "\nRoles table:\n";
$roles = Database::query('SELECT * FROM roles')->fetchAll();
foreach ($roles as $r) {
    echo "  ID=" . $r['id'] . " | " . $r['name'] . "\n";
}