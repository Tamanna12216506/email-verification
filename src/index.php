<?php
require_once 'functions.php';

session_start();
$message = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['email'])) {
        $email = trim($_POST['email']);
        $code = generateVerificationCode();

        $_SESSION['email'] = $email;
        $_SESSION['verification_code'] = $code;

        sendVerificationEmail($email, $code);
        $message = "Verification code sent to your email.";
    } elseif (isset($_POST['verification_code'])) {
        $entered_code = trim($_POST['verification_code']);
        if (isset($_SESSION['verification_code']) && $entered_code === $_SESSION['verification_code']) {
            registerEmail($_SESSION['email']);
            $message = "Email successfully verified and registered!";
            unset($_SESSION['verification_code'], $_SESSION['email']);
        } else {
            $message = "Invalid verification code.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Email Verification</title>
</head>
<body>
    <h2>Email Registration</h2>
    <form method="POST">
        <label for="email">Enter your email:</label><br>
        <input type="email" name="email" required>
        <button id="submit-email">Submit</button>
    </form>

    <br>

    <form method="POST">
        <label for="verification_code">Enter verification code:</label><br>
        <input type="text" name="verification_code" maxlength="6" required>
        <button id="submit-verification">Verify</button>
    </form>

    <p><strong><?= htmlspecialchars($message) ?></strong></p>
</body>
</html>
