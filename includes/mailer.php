<?php
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class mailer
{
    public function mailSend($subject, $message, $address)
    {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'sadha8122@gmail.com';
        $mail->Password = 'sbdarleildyzavjo';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->addAddress($address);
        $mail->send();
    }
    public function registerMail($userID, $password)
    {
        $subject = "Your Account Information";
        $message = "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Your Account Information</title>
        </head>
        <body>
            <p>Hello,</p>
            <p>Your User ID: <strong>$userID</strong></p>
            <p>Your Password: <strong>$password</strong></p>
            <p>Please keep this information secure.</p>
            <p>Thank you!</p>
        </body>
        </html>
        ";
        $this->mailSend($subject, $message, $userID);
    }
    public function linkSend($resetLink, $token_expire,$address)
    {
        $subject = "Password Reset";
        $message = "
        <!DOCTYPE html>
        <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Password Reset</title>
            </head>
<body>
    <p>Hello,</p>
    <p>We received a request to reset the password associated with this email address. If you did not make this request, you can ignore this email.</p>
    <p>To reset your password, click the following link:</p>
    <p><a href='$resetLink'>$resetLink</a></p>
    <p>This link will expire on $token_expire. If you don't reset your password before then, you will need to request another reset.</p>
    <p>Thank you!</p>
</body>
</html>
";
        $this->mailSend($subject, $message, $address);
    }
}