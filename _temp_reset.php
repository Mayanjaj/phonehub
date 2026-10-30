<?php
// Temporary password reset script - delete after use
define('ROOT_PATH', dirname(__FILE__));
require ROOT_PATH . '/src/helpers.php';
require ROOT_PATH . '/src/Core/Database.php';

$email = 'jomayanja2255@gmail.com';
$newPass = 'PhoneHubAdmin2026!'; // 18 characters, meets the 10-char minimum

// Check if the account exists
$user = Database::query(
    'SELECT u.id, u.full_name, u.email, r.name AS role, u.is_active
     FROM users u
     JOIN roles r ON r.id = u.role_id
     WHERE u.email = ?',
    [$email]
)->fetch();

if (!$user) {
    echo "ERROR: No account found with email $email\n";
    echo "Existing accounts:\n";
    $all = Database::query('SELECT u.email, r.name AS role, u.is_active FROM users u JOIN roles r ON r.id = u.role_id ORDER BY u.id')->fetchAll();
    foreach ($all as $u) {
        echo "  " . $u['email'] . ' (' . $u['role'] . ')' . ($u['is_active'] ? '' : ' INACTIVE') . "\n";
    }
    exit(1);
}

echo "Found account:\n";
echo "  ID:      " . $user['id'] . "\n";
echo "  Name:    " . $user['full_name'] . "\n";
echo "  Email:   " . $user['email'] . "\n";
echo "  Role:    " . $user['role'] . "\n";
echo "  Active:  " . ($user['is_active'] ? 'yes' : 'no') . "\n";

// Reset password, re-activate, clear login attempts
Database::query(
    'UPDATE users SET password_hash = ?, is_active = 1 WHERE id = ?',
    [password_hash($newPass, PASSWORD_DEFAULT), $user['id']]
);
Database::query('DELETE FROM login_attempts WHERE email = ?', [$email]);

echo "\nSUCCESS: Password has been reset.\n";
echo "  Email:    $email\n";
echo "  New password: $newPass\n";
echo "  Role:     " . $user['role'] . "\n";
echo "\nYou can now log in at http://localhost/phonehub/login.php\n";