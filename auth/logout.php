<?php
/**
 * Logout Handler
 * Destroys session and logs out user
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';

// Log activity before destroying session
if (isLoggedIn()) {
    logActivity($_SESSION['user_id'], 'User Logout', 'User logged out: ' . $_SESSION['user_email']);
}

// Destroy all session data
session_unset();
session_destroy();

// Redirect to home page
setFlashMessage('success', 'You have been logged out successfully.');
header('Location: ' . SITE_URL);
exit();
?>
