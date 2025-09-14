<?php
use PHPMailer-master/src/PHPMailer.php;
use PHPMailer-master/src/Exception.php;

require 'vendor/autoload.php'; // Make sure to install PHPMailer via Composer

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email address']);
        exit;
    }
    
    try {
        $mail = new PHPMailer(true);
        
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.hostinger.com'; // Hostinger SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'info@tanzania-safari-adventure.com'; // Full email address
        $mail->Password = 'TanzaniaSafari@2025'; // Hostinger email password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Use TLS (587)
        $mail->Port = 587; // Port for TLS (or use 465 with SMTPS)
        
        // Recipients
        $mail->setFrom('info@tanzania-adventure-safari.com', 'Tanzania Safari Adventure');
        $mail->addAddress('info@tanzania-safari-adventure.com');
        $mail->addReplyTo($email);
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = 'New Newsletter Subscription';
        $mail->Body = "
            <h2>New Newsletter Subscription</h2>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Subscription Date:</strong> " . date('Y-m-d H:i:s') . "</p>
        ";
        
        $mail->send();
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        error_log("PHPMailer Error: " . $mail->ErrorInfo);
        echo json_encode(['success' => false, 'message' => 'Mailer Error: ' . $mail->ErrorInfo]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>