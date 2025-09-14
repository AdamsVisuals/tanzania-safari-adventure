<?php
// Load PHPMailer classes manually
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

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
        
        // Recipients - Notification to admin
        $mail->setFrom('info@tanzania-safari-adventure.com', 'Tanzania Safari Adventure');
        $mail->addAddress('info@tanzania-safari-adventure.com'); // Recipient email
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
        
        // Now send confirmation email to the client
        $confirmationMail = new PHPMailer(true);
        
        // Server settings (same as above)
        $confirmationMail->isSMTP();
        $confirmationMail->Host = 'smtp.hostinger.com';
        $confirmationMail->SMTPAuth = true;
        $confirmationMail->Username = 'info@tanzania-safari-adventure.com';
        $confirmationMail->Password = 'TanzaniaSafari@2025';
        $confirmationMail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $confirmationMail->Port = 587;
        
        // Recipients - Confirmation to subscriber
        $confirmationMail->setFrom('info@tanzania-safari-adventure.com', 'Tanzania Safari Adventure');
        $confirmationMail->addAddress($email); // Send to the subscriber
        
        // Content
        $confirmationMail->isHTML(true);
        $confirmationMail->Subject = 'Welcome to Our Newsletter!';
        $confirmationMail->Body = "
            <h2>Thank You for Subscribing!</h2>
            <p>Dear Subscriber,</p>
            <p>Thank you for subscribing to our newsletter. You'll now receive updates about our Tanzania safari adventures, special offers, and travel tips.</p>
            <p>If you did not request this subscription, please ignore this email.</p>
            <br>
            <p>Best regards,<br>Tanzania Safari Adventure Team</p>
        ";
        
        $confirmationMail->send();
        
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        error_log("PHPMailer Error: " . $mail->ErrorInfo);
        echo json_encode(['success' => false, 'message' => 'Mailer Error: ' . $mail->ErrorInfo]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>