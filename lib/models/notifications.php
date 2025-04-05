<?php
// lib/models/notifications.php

require_once __DIR__ . '/../../vendor/autoload.php'; // Adjust the path as needed

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendApplicationNotification($employerEmail, $jobTitle, $applicantName) {
    $mail = new PHPMailer(true);

    try {
        // Load environment variables
        // (Assuming you've already loaded them in your main entry file. If not, load them here.)

        // Server settings
        $mail->isSMTP();
        $mail->Host       = getenv('SMTP_HOST'); // e.g., smtp.gmail.com
        $mail->SMTPAuth   = true;
        $mail->Username   = getenv('SMTP_USER');
        $mail->Password   = getenv('SMTP_PASS');
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = getenv('SMTP_PORT');

        // Recipients
        $mail->setFrom(getenv('FROM_EMAIL'), getenv('FROM_NAME'));
        $mail->addAddress($employerEmail);

        // Content
        $mail->isHTML(true);
        $mail->Subject = "New Application for: $jobTitle";
        $body = "
            <p>Dear Employer,</p>
            <p>A new application has been received for your job post: <strong>$jobTitle</strong>.</p>
            <p>Applicant: <strong>$applicantName</strong></p>
            <p>Please log in to your dashboard to view further details.</p>
            <p>Best regards,<br>Your Company</p>
        ";
        $mail->Body    = $body;
        $mail->AltBody = "Dear Employer, A new application has been received for your job post: $jobTitle. Applicant: $applicantName. Please log in to your dashboard for more details.";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}
?>
