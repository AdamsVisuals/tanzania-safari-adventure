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
    
    // Email styling and branding
    $fontImport = '<link href="https://fonts.googleapis.com/css2?family=Finger+Paint&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">';
    
    $emailStyle = '
        <style>
            * { 
                font-family: "Poppins", Arial, sans-serif; 
            }
            .brand-font {
                font-family: "Finger Paint", cursive;
            }
            .container {
                max-width: 600px;
                margin: 0 auto;
                background-color: #ffffff;
                padding: 20px;
            }
            .header {
                text-align: center;
                padding: 20px 0;
                border-bottom: 2px solid #f0f0f0;
            }
            .logo {
                max-width: 180px;
            }
            .content {
                padding: 20px;
                line-height: 1.6;
                color: #333333;
            }
            .footer {
                background-color: #f5f5f5;
                padding: 15px;
                text-align: center;
                font-size: 14px;
                color: #666;
                border-top: 2px solid #f0f0f0;
            }
            .social-links {
                margin: 15px 0;
            }
            .social-links a {
                margin: 0 10px;
                text-decoration: none;
                color: #E67E22;
                font-weight: 500;
            }
            .highlight {
                color: #E67E22;
                font-weight: 600;
            }
            .details-table {
                width: 100%;
                border-collapse: collapse;
                margin: 20px 0;
            }
            .details-table td {
                padding: 8px;
                border-bottom: 1px solid #f0f0f0;
            }
            .details-table td:first-child {
                font-weight: 500;
                width: 40%;
            }
        </style>
    ';
    
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
        
        // Important settings to improve deliverability
        $mailCompany->DKIM_selector = 'default'; // Add DKIM selector if you have DKIM set up
        $mailCompany->Priority = 1; // High priority but not urgent (1 = High, 3 = Normal, 5 = Low)
        
        // Recipients
        $mailCompany->setFrom('info@tanzania-safari-adventure.com', 'Tanzania Safari Adventure');
        $mailCompany->addAddress('bookings@tanzania-safari-adventure.com', 'Booking Department');
        $mailCompany->addReplyTo($email, $firstName . ' ' . $lastName);
        
        // Add headers to improve deliverability
        $mailCompany->addCustomHeader('List-Unsubscribe: <mailto:info@tanzania-safari-adventure.com?subject=Unsubscribe>');
        $mailCompany->addCustomHeader('X-Mailer: Tanzania Safari Adventure Webform');
        $mailCompany->addCustomHeader('X-Priority: 1 (Highest)');
        $mailCompany->addCustomHeader('X-MSMail-Priority: High');
        $mailCompany->addCustomHeader('Importance: High');
        
        // Content
        $mailCompany->isHTML(true);
        $mailCompany->Subject = 'New Travel Proposal Request: ' . $firstName . ' ' . $lastName;
        
        // Add plain text version for better spam scoring
        $companyTextBody = "New Travel Proposal Request\n\n";
        $companyTextBody .= "Client Information:\n";
        $companyTextBody .= "Name: $firstName $lastName\n";
        $companyTextBody .= "Email: $email\n";
        $companyTextBody .= "Phone: $phone\n";
        $companyTextBody .= "Country: $country\n\n";
        $companyTextBody .= "Travel Details:\n";
        $companyTextBody .= "Travel Types: $travelTypes\n";
        $companyTextBody .= "Duration: $days days\n";
        $companyTextBody .= "Travel Companion: $travelCompanion\n";
        $companyTextBody .= "Travel Date: $travelDate\n";
        $companyTextBody .= "Budget per person: $$budget\n";
        $companyTextBody .= "Number of travelers: $adults adults, $children children\n";
        $companyTextBody .= "Adults ages: $adultAges\n";
        $companyTextBody .= "Children ages: $childAges\n";
        $companyTextBody .= "Additional Notes: $notes\n\n";
        $companyTextBody .= "Preferences:\n";
        $companyTextBody .= "Permission to contact: $permission\n";
        $companyTextBody .= "Signed up for discount: $discount\n\n";
        $companyTextBody .= "Tanzania Safari Adventure © " . date('Y') . "\n";
        $companyTextBody .= "Arusha, Tanzania | +255 767 243 848 | info@tanzania-safari-adventure.com";
        
        $companyEmailBody = "
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset='UTF-8'>
                <title>New Travel Proposal Request</title>
                $fontImport
                $emailStyle
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <img class='logo' src='https://tanzania-safari-adventure.com/images/Tanzania%20adventure%20transparent%20logo.png' alt='Tanzania Safari Adventure'>
                        <h1 class='brand-font'>New Travel Proposal Request</h1>
                    </div>
                    
                    <div class='content'>
                        <h2>Client Information</h2>
                        <table class='details-table'>
                            <tr><td>Name:</td><td>$firstName $lastName</td></tr>
                            <tr><td>Email:</td><td><a href='mailto:$email'>$email</a></td></tr>
                            <tr><td>Phone:</td><td><a href='tel:$phone'>$phone</a></td></tr>
                            <tr><td>Country:</td><td>$country</td></tr>
                        </table>
                        
                        <h2>Travel Details</h2>
                        <table class='details-table'>
                            <tr><td>Travel Types:</td><td>$travelTypes</td></tr>
                            <tr><td>Duration:</td><td>$days days</td></tr>
                            <tr><td>Travel Companion:</td><td>$travelCompanion</td></tr>
                            <tr><td>Travel Date:</td><td>$travelDate</td></tr>
                            <tr><td>Budget per person:</td><td>$$budget</td></tr>
                            <tr><td>Number of travelers:</td><td>$adults adults, $children children</td></tr>
                            <tr><td>Adults ages:</td><td>$adultAges</td></tr>
                            <tr><td>Children ages:</td><td>$childAges</td></tr>
                            <tr><td>Additional Notes:</td><td>$notes</td></tr>
                        </table>
                        
                        <h2>Preferences</h2>
                        <table class='details-table'>
                            <tr><td>Permission to contact:</td><td>$permission</td></tr>
                            <tr><td>Signed up for discount:</td><td>$discount</td></tr>
                        </table>
                    </div>
                    
                    <div class='footer'>
                        <p>Tanzania Safari Adventure © " . date('Y') . "</p>
                        <div class='social-links'>
                            <a href='https://www.instagram.com/tanzania_safari_adventure'>Instagram</a> | 
                            <a href='https://www.facebook.com/tanzaniasafariadventure'>Facebook</a>
                        </div>
                        <p>Arusha, Tanzania | +255 767 243 848 | info@tanzania-safari-adventure.com</p>
                    </div>
                </div>
            </body>
            </html>
        ";
        
        $mailCompany->Body = $companyEmailBody;
        $mailCompany->AltBody = $companyTextBody;
        
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
        
        // Important settings to improve deliverability
        $mailClient->DKIM_selector = 'default';
        $mailClient->Priority = 1;
        
        // Recipients
        $mailClient->setFrom('info@tanzania-safari-adventure.com', 'Tanzania Safari Adventure');
        $mailClient->addAddress($email, $firstName . ' ' . $lastName);
        
        // Add headers to improve deliverability
        $mailClient->addCustomHeader('List-Unsubscribe: <mailto:info@tanzania-safari-adventure.com?subject=Unsubscribe>');
        $mailClient->addCustomHeader('X-Mailer: Tanzania Safari Adventure Webform');
        $mailClient->addCustomHeader('X-Priority: 1 (Highest)');
        $mailClient->addCustomHeader('X-MSMail-Priority: High');
        $mailClient->addCustomHeader('Importance: High');
        
        // Content
        $mailClient->isHTML(true);
        $mailClient->Subject = 'Thank you for your Tanzania Safari Adventure Request';
        
        // Add plain text version for better spam scoring
        $clientTextBody = "Thank you for your request!\n\n";
        $clientTextBody .= "Dear $firstName,\n\n";
        $clientTextBody .= "Thank you for requesting a travel proposal from Tanzania Safari Adventure. We're excited to help you plan your dream safari experience!\n\n";
        $clientTextBody .= "What happens next?\n";
        $clientTextBody .= "- Our safari experts will review your request\n";
        $clientTextBody .= "- We'll create a personalized itinerary just for you\n";
        $clientTextBody .= "- You'll receive our proposal within 24 hours\n\n";
        $clientTextBody .= "We'll be in touch soon with your personalized travel proposal. If you have any immediate questions, feel free to reply to this email or call us at +255 767 243 848.\n\n";
        $clientTextBody .= "Karibu Tanzania! (Welcome to Tanzania!)\n\n";
        $clientTextBody .= "Warm regards,\n";
        $clientTextBody .= "The Tanzania Safari Adventure Team\n\n";
        $clientTextBody .= "Tanzania Safari Adventure © " . date('Y') . "\n";
        $clientTextBody .= "Arusha, Tanzania | +255 767 243 848 | info@tanzania-safari-adventure.com";
        
        $clientEmailBody = "
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset='UTF-8'>
                <title>Thank you for your Tanzania Safari Adventure Request</title>
                $fontImport
                $emailStyle
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <img class='logo' src='https://tanzania-safari-adventure.com/images/Tanzania%20adventure%20transparent%20logo.png' alt='Tanzania Safari Adventure'>
                        <h1 class='brand-font'>Thank you for your request!</h1>
                    </div>
                    
                    <div class='content'>
                        <p>Dear $firstName,</p>
                        <p>Thank you for requesting a travel proposal from <span class='highlight'>Tanzania Safari Adventure</span>. We're excited to help you plan your dream safari experience!</p>
                        
                        <h3 class='highlight'>What happens next?</h3>
                        <ul>
                            <li>Our safari experts will review your request</li>
                            <li>We'll create a personalized itinerary just for you</li>
                            <li>You'll receive our proposal within 24 hours</li>
                        </ul>
                        
                        <p>We'll be in touch soon with your personalized travel proposal. If you have any immediate questions, feel free to reply to this email or call us at +255 767 243 848.</p>
                        
                        <p>Karibu Tanzania! (Welcome to Tanzania!)</p>
                        
                        <p>Warm regards,<br>
                        The Tanzania Safari Adventure Team</p>
                    </div>
                    
                    <div class='footer'>
                        <p>Tanzania Safari Adventure © " . date('Y') . "</p>
                        <div class='social-links'>
                            Follow us on 
                            <a href='https://www.instagram.com/tanzania_safari_adventure'>Instagram</a> | 
                            <a href='https://www.facebook.com/tanzaniasafariadventure'>Facebook</a>
                        </div>
                        <p>Arusha, Tanzania | +255 767 243 848 | info@tanzania-safari-adventure.com</p>
                    </div>
                </div>
            </body>
            </html>
        ";
        
        $mailClient->Body = $clientEmailBody;
        $mailClient->AltBody = $clientTextBody;
        
        $mailClient->send();
        
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $mailCompany->ErrorInfo]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>