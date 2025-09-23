<?php
// Set response header to JSON
header('Content-Type: application/json');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get the email from the POST data
$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

if (!$email) {
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit;
}

// PHPMailer setup
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

try {
    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);
    
    // ✅ Hostinger SMTP settings
    $mail->isSMTP();
    $mail->Host = 'smtp.hostinger.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'info@tanzania-safari-adventure.com'; 
    $mail->Password = 'TanzaniaSafari@2025'; 
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    
    // Recipients
    $mail->setFrom('info@tanzania-safari-adventure.com', 'Tanzania Safari Adventure');
    $mail->addAddress('info@tanzania-safari-adventure.com'); // send to your inbox
    
    // Content
    $mail->isHTML(true);
    $mail->Subject = 'New Early Access Signup';
    $mail->Body = "
        <h2>New Early Access Signup</h2>
        <p><strong>Email:</strong> $email</p>
        <p><strong>Signup Date:</strong> " . date('Y-m-d H:i:s') . "</p>
    ";
    $mail->AltBody = "New early access signup from: $email";
    
    // Send email
    $mail->send();
    
    // Log the submission (optional)
    file_put_contents('subscriptions.log', "$email," . date('Y-m-d H:i:s') . "\n", FILE_APPEND | LOCK_EX);
    
    echo json_encode(['success' => true, 'message' => 'Email sent successfully']);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to send email']);
}
?>