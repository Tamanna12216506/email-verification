<?php
require_once 'functions.php';

session_start();
$message = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['unsubscribe_email'])) {
        $email = trim($_POST['unsubscribe_email']);
        $code = generateVerificationCode();

        $_SESSION['unsubscribe_email'] = $email;
        $_SESSION['unsubscribe_code'] = $code;

        // Send unsubscribe verification email
        $subject = "Confirm Unsubscription";
        $body = "<p>To confirm unsubscription, use this code: <strong>$code</strong></p>";
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From: no-reply@example.com\r\n";

        mail($email, $subject, $body, $headers);
        $message = "Unsubscribe verification code sent to your email.";
    } elseif (isset($_POST['unsubscribe_verification_code'])) {
        $entered_code = trim($_POST['unsubscribe_verification_code']);
        if (isset($_SESSION['unsubscribe_code']) && $entered_code === $_SESSION['unsubscribe_code']) {
            unsubscribeEmail($_SESSION['unsubscribe_email']);
            $message = "You have been unsubscribed successfully.";
            unset($_SESSION['unsubscribe_code'], $_SESSION['unsubscribe_email']);
        } else {
            $message = "Invalid unsubscribe verification code.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Unsubscribe</title>
</head>
<body>
    <h2>Unsubscribe from GitHub Updates</h2>
    <form method="POST">
        <label for="unsubscribe_email">Enter your email to unsubscribe:</label><br>
        <input type="email" name="unsubscribe_email" required>
        <button id="submit-unsubscribe">Unsubscribe</button>
    </form>

    <br>

    <form method="POST">
        <label for="unsubscribe_verification_code">Enter the code sent to your email:</label><br>
        <input type="text" name="unsubscribe_verification_code">
        <button id="verify-unsubscribe">Verify</button>
    </form>

    <p><strong><?= htmlspecialchars($message) ?></strong></p>
</body>
</html>
