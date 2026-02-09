<?php
/**
 * Authentication Process Handler
 * Handles login, registration, and logout operations
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/email.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . SITE_URL);
    exit();
}

// Verify CSRF token
if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
    setFlashMessage('error', 'Invalid request. Please try again.');
    header('Location: ' . SITE_URL);
    exit();
}

$action = $_POST['action'] ?? '';

// ============================================
// REGISTRATION HANDLER
// ============================================
if ($action === 'register') {
    // Sanitize and validate inputs
    $full_name = sanitize($_POST['full_name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone'] ?? '');
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validation
    $errors = [];
    
    if (strlen($full_name) < 3) {
        $errors[] = 'Full name must be at least 3 characters long.';
    }
    
    if (!validateEmail($email)) {
        $errors[] = 'Invalid email address.';
    }
    
    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters long.';
    }
    
    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match.';
    }
    
    // Check if email already exists
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $errors[] = 'Email address already registered.';
    }
    $stmt->close();
    
    // If validation fails
    if (!empty($errors)) {
        setFlashMessage('error', implode('<br>', $errors));
        header('Location: register.php');
        exit();
    }
    
    // Hash password securely
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert user into database
    $stmt = $conn->prepare("INSERT INTO users (full_name, email, phone, password, user_type, status) VALUES (?, ?, ?, ?, 'user', 'active')");
    $stmt->bind_param("ssss", $full_name, $email, $phone, $hashed_password);
    
    if ($stmt->execute()) {
        $user_id = $stmt->insert_id;
        
        // Log activity
        logActivity($user_id, 'User Registration', 'New user registered: ' . $email);
        
        // Send welcome email
        sendWelcomeEmail($email, $full_name);
        
        // Auto-login after registration
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_name'] = $full_name;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_type'] = 'user';
        
        setFlashMessage('success', 'Registration successful! Welcome to TradeTechie.');
        header('Location: ' . SITE_URL . 'dashboard.php');
    } else {
        setFlashMessage('error', 'Registration failed. Please try again.');
        header('Location: register.php');
    }
    
    $stmt->close();
    exit();
}

// ============================================
// LOGIN HANDLER
// ============================================
if ($action === 'login') {
    // Sanitize inputs
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);
    
    // Validation
    if (!validateEmail($email)) {
        setFlashMessage('error', 'Invalid email address.');
        header('Location: login.php');
        exit();
    }
    
    // Fetch user from database
    $stmt = $conn->prepare("SELECT user_id, full_name, email, password, user_type, status FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        setFlashMessage('error', 'Invalid email or password.');
        header('Location: login.php');
        $stmt->close();
        exit();
    }
    
    $user = $result->fetch_assoc();
    $stmt->close();
    
    // Check if account is active
    if ($user['status'] !== 'active') {
        setFlashMessage('error', 'Your account has been deactivated. Please contact support.');
        header('Location: login.php');
        exit();
    }
    
    // Verify password
    if (!password_verify($password, $user['password'])) {
        setFlashMessage('error', 'Invalid email or password.');
        header('Location: login.php');
        exit();
    }
    
    // Set session variables
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['user_name'] = $user['full_name'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_type'] = $user['user_type'];
    
    // Log activity
    logActivity($user['user_id'], 'User Login', 'User logged in: ' . $email);
    
    // Set remember me cookie (optional enhancement)
    if ($remember) {
        // Implementation for remember me functionality
        // setcookie('remember_token', $token, time() + (86400 * 30), "/");
    }
    
    setFlashMessage('success', 'Login successful! Welcome back, ' . $user['full_name'] . '.');
    
    // Redirect based on user type
    if ($user['user_type'] === 'admin') {
        header('Location: ' . SITE_URL . 'admin/');
    } else {
        header('Location: ' . SITE_URL . 'dashboard.php');
    }
    
    exit();
}

// Invalid action
setFlashMessage('error', 'Invalid action.');
header('Location: ' . SITE_URL);
exit();
?>
