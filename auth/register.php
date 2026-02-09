<?php
$page_title = 'Register';
require_once __DIR__ . '/../includes/header.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: ' . SITE_URL . 'dashboard.php');
    exit();
}
?>

<div class="auth-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card auth-card">
                    <div class="auth-header">
                        <h3 class="mb-0"><i class="bi bi-person-plus"></i> Create Account</h3>
                        <p class="mb-0 mt-2">Join TradeTechie and start your trading journey</p>
                    </div>
                    <div class="auth-body">
                        <form action="process.php" method="POST" class="needs-validation" novalidate>
                            <input type="hidden" name="action" value="register">
                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                            
                            <div class="mb-3">
                                <label for="full_name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" required minlength="3">
                                <div class="invalid-feedback">Please enter your full name (minimum 3 characters).</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                                <div class="invalid-feedback">Please enter a valid email address.</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number (Optional)</label>
                                <input type="tel" class="form-control" id="phone" name="phone" pattern="[0-9]{10}">
                                <div class="invalid-feedback">Please enter a valid 10-digit phone number.</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required minlength="8">
                                <div id="passwordStrength"></div>
                                <div class="invalid-feedback">Password must be at least 8 characters long.</div>
                                <small class="text-muted">Use at least 8 characters with letters, numbers, and symbols.</small>
                            </div>
                            
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                <div id="passwordMatch"></div>
                                <div class="invalid-feedback">Please confirm your password.</div>
                            </div>
                            
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    I agree to the Terms & Conditions
                                </label>
                                <div class="invalid-feedback">You must agree before submitting.</div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-person-check"></i> Register
                            </button>
                        </form>
                        
                        <hr class="my-4">
                        
                        <p class="text-center mb-0">
                            Already have an account? <a href="login.php" class="text-decoration-none">Login here</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Initialize password strength checker
displayPasswordStrength('password', 'passwordStrength');
checkPasswordMatch('password', 'confirm_password', 'passwordMatch');
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
