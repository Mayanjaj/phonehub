<?php
// Temporary fix: seed roles and create admin account
define('ROOT_PATH', dirname(__FILE__));
require ROOT_PATH . '/src/helpers.php';
require ROOT_PATH . '/src/Core/Database.php';

$pdo = Database::pdo();

// 1. Insert roles if missing
foreach (['customer', 'admin', 'staff'] as $roleName) {
    $exists = $pdo->prepare('SELECT id FROM roles WHERE name = ?');
    $exists->execute([$roleName]);
    if (!$exists->fetch()) {
        $pdo->prepare('INSERT INTO roles (name) VALUES (?)')->execute([$roleName]);
        echo "Inserted role: $roleName\n";
    } else {
        echo "Role already exists: $roleName\n";
    }
}

// 2. Check if user already exists
$email = 'jomayanja2255@gmail.com';
$user = Database::query('SELECT u.id, u.email, r.name AS role FROM users u JOIN roles r ON r.id = u.role_id WHERE u.email = ?', [$email])->fetch();

if ($user) {
    echo "\nUser already exists: {$user['email']} ({$user['role']})\n";
    // Reset password and activate
    $newPass = 'PhoneHubAdmin2026!';
    Database::query('UPDATE users SET password_hash = ?, is_active = 1 WHERE id = ?', [password_hash($newPass, PASSWORD_DEFAULT), $user['id']]);
    Database::query('DELETE FROM login_attempts WHERE email = ?', [$email]);
    echo "Password reset. New password: $newPass\n";
} else {
    // 3. Create the admin account
    $adminRoleId = Database::query("SELECT id FROM roles WHERE name = 'admin'")->fetchColumn();
    $name = 'Admin User';
    $newPass = 'PhoneHubAdmin2026!';

    Database::query(
        "INSERT INTO users (role_id, full_name, email, password_hash) VALUES (?, ?, ?, ?)",
        [$adminRoleId, $name, $email, password_hash($newPass, PASSWORD_DEFAULT)]
    );

    echo "\nAdmin account created:\n";
    echo "  Name:     $name\n";
    echo "  Email:    $email\n";
    echo "  Password: $newPass\n";
    echo "  Role:     admin\n";
}

// 4. Show all users for verification
echo "\nAll users:\n";
$all = Database::query('SELECT u.id, u.email, r.name AS role, u.is_active FROM users u JOIN roles r ON r.id = u.role_id ORDER BY u.id')->fetchAll();
foreach ($all as $u) {
    echo "  ID=" . $u['id'] . " | " . $u['email'] . " | " . $u['role'] . " | " . ($u['is_active'] ? 'active' : 'INACTIVE') . "\n";
}

echo "\nYou can now log in at http://localhost/phonehub/login.php\n";
echo "Email: jomayanja2255@gmail.com\n";
echo "Password: PhoneHubAdmin2026!\n";