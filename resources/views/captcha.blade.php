<?php
// Proses validasi Turnstile di backend
$statusMessage = "";
$hideCaptcha = false;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['token'])) {
    $secretKey = "0x4AAAAAAA6j7_uuC4IiGCFHKgjuWg7g6ZQ"; // Ganti dengan Secret Key Anda
    $token = $_POST['token'];

    $url = "https://challenges.cloudflare.com/turnstile/v0/siteverify";
    $data = [
        'secret' => $secretKey,
        'response' => $token,
    ];

    $options = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data),
        ],
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    $result = json_decode($result);

    if ($result->success) {
        $statusMessage = "Anda adalah manusia!";
        $hideCaptcha = true; // Set untuk menyembunyikan CAPTCHA
    } else {
        $statusMessage = "Verifikasi gagal. Silakan coba lagi.";
    }
}
?>