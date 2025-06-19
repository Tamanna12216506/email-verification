<?php

function generateVerificationCode() {
    return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
}

function registerEmail($email) {
    $file = __DIR__ . '/registered_emails.txt';
    $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (!in_array($email, $emails)) {
        file_put_contents($file, $email . PHP_EOL, FILE_APPEND);
    }
}

function unsubscribeEmail($email) {
    $file = __DIR__ . '/registered_emails.txt';
    $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $updated = array_filter($emails, fn($e) => trim($e) !== trim($email));
    file_put_contents($file, implode(PHP_EOL, $updated) . PHP_EOL);
}

function sendVerificationEmail($email, $code) {
    $subject = "Your Verification Code";
    $message = "<p>Your verification code is: <strong>$code</strong></p>";
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: no-reply@example.com\r\n";
    mail($email, $subject, $message, $headers);
}

function fetchGitHubTimeline() {
    // Simulate GitHub timeline data (replace with real fetch if needed)
    $dummyData = [
        ["event" => "Push", "user" => "testuser"],
        ["event" => "Fork", "user" => "anotheruser"]
    ];
    return json_encode($dummyData);
}

function formatGitHubData($data) {
    $events = json_decode($data, true);
    $html = "<h2>GitHub Timeline Updates</h2>";
    $html .= "<table border='1'><tr><th>Event</th><th>User</th></tr>";
    foreach ($events as $event) {
        $html .= "<tr><td>{$event['event']}</td><td>{$event['user']}</td></tr>";
    }
    $html .= "</table>";
    return $html;
}

function sendGitHubUpdatesToSubscribers() {
    $file = __DIR__ . '/registered_emails.txt';
    if (!file_exists($file)) return;

    $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $data = fetchGitHubTimeline();
    $html = formatGitHubData($data);
    $subject = "Latest GitHub Updates";

    foreach ($emails as $email) {
        $unsubscribeLink = "http://localhost/email-project/src/unsubscribe.php";
        $message = $html . "<p><a href=\"$unsubscribeLink\" id=\"unsubscribe-button\">Unsubscribe</a></p>";

        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From: no-reply@example.com\r\n";

        mail($email, $subject, $message, $headers);
    }
}
