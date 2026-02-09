<?php

function sendEmail($to, $subject, $body, $fromName = 'TradeTechie') {
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: {$fromName} <noreply@tradetechie.com>" . "\r\n";
    
    try {
        $result = mail($to, $subject, $body, $headers);
        
        if (!$result) {
            error_log("Email failed to send to: {$to}");
        }
        
        return $result;
    } catch (Exception $e) {
        error_log("Email error: " . $e->getMessage());
        return false;
    }
}

function sendWelcomeEmail($email, $name) {
    $subject = "Welcome to TradeTechie - Registration Successful";
    
    $body = "
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(135deg, #1a2332 0%, #2d3748 100%); color: white; padding: 30px; text-align: center; border-radius: 8px 8px 0 0; }
            .content { background: #f8fafc; padding: 30px; border-radius: 0 0 8px 8px; }
            .button { display: inline-block; padding: 12px 30px; background: #10b981; color: white; text-decoration: none; border-radius: 6px; margin: 20px 0; }
            .footer { text-align: center; margin-top: 30px; color: #64748b; font-size: 14px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>Welcome to TradeTechie!</h1>
            </div>
            <div class='content'>
                <h2>Hello {$name},</h2>
                <p>Thank you for registering with TradeTechie. Your account has been successfully created!</p>
                <p>You can now access all features including:</p>
                <ul>
                    <li>Educational content on stock market trading</li>
                    <li>Personal watchlist to track your favorite stocks</li>
                    <li>Notes feature to document your trading insights</li>
                </ul>
                <p style='text-align: center;'>
                    <a href='" . SITE_URL . "dashboard.php' class='button'>Go to Dashboard</a>
                </p>
                <p>If you have any questions, feel free to reach out to our support team.</p>
                <p>Happy Trading!</p>
            </div>
            <div class='footer'>
                <p>&copy; " . date('Y') . " TradeTechie. All rights reserved.</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    return sendEmail($email, $subject, $body);
}
