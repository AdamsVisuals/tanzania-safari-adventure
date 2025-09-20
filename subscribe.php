<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

header('Content-Type: application/json');

// Validate email input
if (!isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["success" => false, "message" => "Please enter a valid email address."]);
    exit;
}

$email = $_POST['email'];
$subscriber_ip = $_SERVER['REMOTE_ADDR'];
$subscribed_date = date('Y-m-d H:i:s');

// Function to create professional branded email content
function createBrandedEmail($type, $email) {
    $website_url = "https://tanzania-safari-adventure.com";
    $logo_url = "https://tanzania-safari-adventure.com/images/Tanzania%20adventure%20transparent%20logo.png";
    $social_links = '
        <a href="https://facebook.com/tanzaniasafari" style="color: #3b5998; text-decoration: none; margin: 0 10px;">Facebook</a> • 
        <a href="https://instagram.com/tanzania_safari_adveture" style="color: #e4405f; text-decoration: none; margin: 0 10px;">Instagram</a> • 
        <a href="https://twitter.com/tanzaniasafari" style="color: #55acee; text-decoration: none; margin: 0 10px;">Twitter</a>
    ';
    
    if ($type === 'admin') {
        return '
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <title>New Newsletter Subscription</title>
            </head>
            <body style="font-family: poppins, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto;">
                <div style="background: linear-gradient(to bottom, #fafdff, #fafdff); padding: 20px; text-align: center;">
                    <img src="' . $logo_url . '" alt="Tanzania Safari Adventure" style="max-width: 180px;">
                </div>
                <div style="padding: 20px; border: 1px solid #ddd; border-top: none;">
                    <h2 style="color: #1a6c3c; margin-top: 0;">New Newsletter Subscription</h2>
                    <p>You have a new subscriber to your Tanzania Safari Adventure newsletter.</p>
                    <div style="background-color: #f9f9f9; padding: 15px; border-radius: 5px; margin: 20px 0;">
                        <p style="margin: 0;"><strong>Email:</strong> ' . $email . '</p>
                        <p style="margin: 10px 0 0 0;"><strong>Subscribed on:</strong> ' . date('F j, Y \a\t g:i a') . '</p>
                    </div>
                    <p>This subscriber has been added to your mailing list.</p>
                </div>
                <div style="background-color: #f5f5f5; padding: 15px; text-align: center; font-size: 14px; color: #666;">
                    <p>© ' . date('Y') . ' Tanzania Safari Adventure. All rights reserved.</p>
                    <p>' . $social_links . '</p>
                    <p><a href="' . $website_url . '" style="color: #1a6c3c; text-decoration: none;">' . $website_url . '</a></p>
                </div>
            </body>
            </html>
        ';
    } else {
        return '
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <title>Welcome to Tanzania Safari Adventure</title>
            </head>
            <body style="font-family: poppins, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto;">
                <div style="background: linear-gradient(to bottom, #fafdff, #fafdff); padding: 20px; text-align: center;">
                    <img src="' . $logo_url . '" alt="Tanzania Safari Adventure" style="max-width: 180px;">
                </div>
                <div style="padding: 20px; border: 1px solid #ddd; border-top: none;">
                    <h2 style="color: #1a6c3c; margin-top: 0;">Welcome to Tanzania Safari Adventure!</h2>
                    <p>Thank you for subscribing to our newsletter. We\'re excited to share the wonders of Tanzania with you.</p>
                    <p>As a subscriber, you\'ll be the first to receive:</p>
                    <ul>
                        <li>Exclusive safari offers and promotions</li>
                        <li>Wildlife spotting updates and migration reports</li>
                        <li>Travel tips and cultural insights</li>
                        <li>Photography tips from our expert guides</li>
                    </ul>
                    <div style="text-align: center; margin: 25px 0;">
                        <a href="' . $website_url . '/safari-packages" style="background-color: #e99b2d; color: white; padding: 12px 25px; text-decoration: none; border-radius: 4px; display: inline-block; font-weight: bold;">Explore Our Safari Packages</a>
                    </div>
                    <p>We respect your privacy and will never share your information with third parties. You can unsubscribe at any time using the link in our emails.</p>
                </div>
                <div style="background-color: #f5f5f5; padding: 15px; text-align: center; font-size: 14px; color: #666;">
                    <p>© ' . date('Y') . ' Tanzania Safari Adventure. All rights reserved.</p>
                    <p>' . $social_links . '</p>
                    <p><a href="' . $website_url . '" style="color: #1a6c3c; text-decoration: none;">' . $website_url . '</a></p>
                </div>
            </body>
            </html>
        ';
    }
}

try {
    // Send email to admin
    $adminMail = new PHPMailer(true);
    
    // Server settings
    $adminMail->isSMTP();
    $adminMail->Host       = 'smtp.hostinger.com';
    $adminMail->SMTPAuth   = true;
    $adminMail->Username   = 'info@tanzania-safari-adventure.com'; 
    $adminMail->Password   = 'TanzaniaSafari@2025'; 
    $adminMail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
    $adminMail->Port       = 587;

    // Recipients
    $adminMail->setFrom('info@tanzania-safari-adventure.com', 'Tanzania Safari Adventure');
    $adminMail->addAddress('info@tanzania-safari-adventure.com');
    $adminMail->addReplyTo($email);

    // Content
    $adminMail->isHTML(true);
    $adminMail->Subject = "New Newsletter Subscription: " . $email;
    $adminMail->Body    = createBrandedEmail('admin', $email);
    $adminMail->AltBody = "New newsletter subscriber: $email\nSubscribed on: $subscribed_date\nIP: $subscriber_ip";
    
    $adminMail->send();
    
    // Send confirmation email to subscriber
    $subscriberMail = new PHPMailer(true);
    
    // Server settings (same as admin email)
    $subscriberMail->isSMTP();
    $subscriberMail->Host       = 'smtp.hostinger.com';
    $subscriberMail->SMTPAuth   = true;
    $subscriberMail->Username   = 'info@tanzania-safari-adventure.com'; 
    $subscriberMail->Password   = 'TanzaniaSafari@2025'; 
    $subscriberMail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
    $subscriberMail->Port       = 587;

    // Recipients
    $subscriberMail->setFrom('info@tanzania-safari-adventure.com', 'Tanzania Safari Adventure');
    $subscriberMail->addAddress($email);
    $subscriberMail->addReplyTo('info@tanzania-safari-adventure.com', 'Tanzania Safari Adventure');

    // Content
    $subscriberMail->isHTML(true);
    $subscriberMail->Subject = "Welcome to Tanzania Safari Adventure!";
    $subscriberMail->Body    = createBrandedEmail('subscriber', $email);
    $subscriberMail->AltBody = "Thank you for subscribing to Tanzania Safari Adventure newsletter.\n\nWe're excited to share safari offers, wildlife updates, and travel tips with you.\n\nVisit us at: https://tanzania-safari-adventure.com";
    
    $subscriberMail->send();
    
    echo json_encode(["success" => true, "message" => "Thank you for subscribing! Please check your email for confirmation."]);
    
} catch (Exception $e) {
    error_log("Subscription failed for $email: " . $adminMail->ErrorInfo);
    echo json_encode(["success" => false, "message" => "Sorry, we couldn't complete your subscription at this time. Please try again later."]);
}