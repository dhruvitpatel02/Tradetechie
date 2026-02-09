<?php
require_once 'config/database.php';

echo "<!DOCTYPE html><html><head><title>Upgrade TradeTechie</title><style>
body{font-family:Arial;max-width:800px;margin:50px auto;padding:20px;}
.success{color:green;padding:10px;background:#d4edda;border:1px solid green;margin:10px 0;}
.error{color:red;padding:10px;background:#f8d7da;border:1px solid red;margin:10px 0;}
h1{color:#333;}
</style></head><body><h1>\ud83d\ude80 TradeTechie Upgrade</h1>";

try {
    // Add tags column to stock_notes
    $conn->query("ALTER TABLE stock_notes ADD COLUMN IF NOT EXISTS tags VARCHAR(255) DEFAULT ''");
    echo "<div class='success'>\u2713 Added tags column to stock_notes</div>";
    
    // Create user_progress table
    $sql = "CREATE TABLE IF NOT EXISTS user_progress (
        progress_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        content_id INT NOT NULL,
        completed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
        FOREIGN KEY (content_id) REFERENCES educational_content(content_id) ON DELETE CASCADE,
        UNIQUE KEY unique_user_content (user_id, content_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    $conn->query($sql);
    echo "<div class='success'>\u2713 Created user_progress table</div>";
    
    // Create user_preferences table
    $sql = "CREATE TABLE IF NOT EXISTS user_preferences (
        pref_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL UNIQUE,
        theme VARCHAR(10) DEFAULT 'light',
        notifications_enabled BOOLEAN DEFAULT TRUE,
        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    $conn->query($sql);
    echo "<div class='success'>\u2713 Created user_preferences table</div>";
    
    echo "<div class='success'><h2>\ud83c\udf89 Upgrade Complete!</h2></div>";
    echo "<div class='success'>
        <strong>New Features Activated:</strong><br>
        \u2713 Dark Mode Toggle<br>
        \u2713 Progress Tracking for Learning<br>
        \u2713 Tags for Notes<br>
        \u2713 Auto-save for Notes<br>
        \u2713 Live Price Updates<br>
        \u2713 Modern Premium UI
    </div>";
    echo "<a href='index.php' style='display:inline-block;padding:10px 20px;background:#007bff;color:white;text-decoration:none;border-radius:5px;margin-top:20px;'>Go to Homepage</a>";
    echo "<p style='margin-top:20px;'><small>You can delete upgrade.php after this.</small></p>";
    
} catch (Exception $e) {
    echo "<div class='error'>\u274c Error: " . $e->getMessage() . "</div>";
}

echo "</body></html>";
?>
