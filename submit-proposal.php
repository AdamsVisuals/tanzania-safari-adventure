<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $firstName = $_POST['first-name'] ?? '';
    $lastName = $_POST['last-name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $country = $_POST['country'] ?? '';
    $days = $_POST['days'] ?? '';
    $budget = $_POST['budget'] ?? '';
    $travelDate = $_POST['travel-date'] ?? '';
    $travelTypes = $_POST['travel-types'] ?? '';
    $travelCompanion = $_POST['travel-companion'] ?? '';
    $adults = $_POST['adults'] ?? '';
    $children = $_POST['children'] ?? '';
    $adultAges = $_POST['adult-ages'] ?? '';
    $childAges = $_POST['child-ages'] ?? '';
    $notes = $_POST['notes'] ?? '';
    $permission = $_POST['permission'] ?? '';
    $discount = $_POST['discount'] ?? '';
    
    // Email to company
    $mailCompany = new PHPMailer(true);
    
    try {
        // ✅ Hostinger SMTP settings
        $mailCompany->isSMTP();
        $mailCompany->Host = 'smtp.hostinger.com';
        $mailCompany->SMTPAuth = true;
        $mailCompany->Username = 'info@tanzania-safari-adventure.com'; // your full email
        $mailCompany->Password = 'TanzaniaSafari@2025'; // your email password
        $mailCompany->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mailCompany->Port = 587;
        
        // Recipients
        $mailCompany->setFrom('info@tanzania-safari-adventure.com', 'Tanzania Safari Adventure');
        $mailCompany->addAddress('bookings@tanzania-safari-adventure.com', 'Booking Department');
        $mailCompany->addReplyTo($email, $firstName . ' ' . $lastName);
        
        // Content
        $mailCompany->isHTML(true);
        $mailCompany->Subject = 'New Travel Proposal Request: ' . $firstName . ' ' . $lastName;
        
        $companyEmailBody = "
            <h2>New Travel Proposal Request</h2>
            <p><strong>Client:</strong> $firstName $lastName</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Phone:</strong> $phone</p>
            <p><strong>Country:</strong> $country</p>
            <hr>
            <h3>Travel Details</h3>
            <p><strong>Travel Types:</strong> $travelTypes</p>
            <p><strong>Duration:</strong> $days days</p>
            <p><strong>Travel Companion:</strong> $travelCompanion</p>
            <p><strong>Travel Date:</strong> $travelDate</p>
            <p><strong>Budget per person:</strong> $$budget</p>
            <p><strong>Number of travelers:</strong> $adults adults, $children children</p>
            <p><strong>Adults ages:</strong> $adultAges</p>
            <p><strong>Children ages:</strong> $childAges</p>
            <p><strong>Additional Notes:</strong> $notes</p>
            <hr>
            <p><strong>Permission to contact:</strong> $permission</p>
            <p><strong>Signed up for discount:</strong> $discount</p>
        ";
        
        $mailCompany->Body = $companyEmailBody;
        $mailCompany->AltBody = strip_tags($companyEmailBody);
        
        $mailCompany->send();
        
        // Email to client
        $mailClient = new PHPMailer(true);
        
        // ✅ Same Hostinger SMTP settings
        $mailClient->isSMTP();
        $mailClient->Host = 'smtp.hostinger.com';
        $mailClient->SMTPAuth = true;
        $mailClient->Username = 'info@tanzania-safari-adventure.com';
        $mailClient->Password = 'TanzaniaSafari@2025';
        $mailClient->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mailClient->Port = 587;
        
        // Recipients
        $mailClient->setFrom('info@tanzania-safari-adventure.com', 'Tanzania Safari Adventure');
        $mailClient->addAddress($email, $firstName . ' ' . $lastName);
        
        // Content
        $mailClient->isHTML(true);
        $mailClient->Subject = 'Thank you for your Tanzania Safari Adventure Request';
        
        $clientEmailBody = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                <div style='background: #fafdff; padding: 20px; text-align: center;'>
                    <img src='https://tanzania-safari-adventure.com/images/Tanzania%20adventure%20transparent%20logo.png' alt='Tanzania Safari Adventure' style='max-width: 180px;'>
                    <h1 style='color: white; margin: 10px 0;'>Thank you for your request!</h1>
                </div>
                
                <div style='padding: 20px;'>
                    <p>Dear $firstName,</p>
                    <p>Thank you for requesting a travel proposal from Tanzania Safari Adventure. We're excited to help you plan your dream safari experience!</p>
                    
                    <h3 style='color: #E67E22;'>What happens next?</h3>
                    <ul>
                        <li>Our safari experts will review your request</li>
                        <li>We'll create a personalized itinerary just for you</li>
                        <li>You'll receive our proposal within 24 hours</li>
                    </ul>
                    
                    <p>We'll be in touch soon with your personalized travel proposal. If you have any immediate questions, feel free to reply to this email or call us at +255 123 456 789.</p>
                    
                    <p>Karibu Tanzania! (Welcome to Tanzania!)</p>
                    
                    <p>Warm regards,<br>
                    The Tanzania Safari Adventure Team</p>
                </div>
                
                <div style='background-color: #f5f5f5; padding: 15px; text-align: center; font-size: 14px; color: #666;'>
                    <p>Tanzania Safari Adventure © " . date('Y') . "</p>
                    <p>Arusha, Tanzania | +255 767 243 848 | info@tanzania-safari-adventure.com</p>
                </div>
            </div>
        ";
        
        $mailClient->Body = $clientEmailBody;
        $mailClient->AltBody = strip_tags($clientEmailBody);
        
        $mailClient->send();
        
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $mailCompany->ErrorInfo]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>