<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recaptchaResponse = $_POST['g-recaptcha-response'];  // Mendapatkan respons reCAPTCHA dari form
    $secretKey = 'your-secret-key';  // Ganti dengan secret key Anda

    // URL untuk verifikasi ke server Google
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = [
        'secret' => $secretKey,
        'response' => $recaptchaResponse,
    ];

    // Melakukan permintaan POST untuk verifikasi
    $options = [
        'http' => [
            'method' => 'POST',
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'content' => http_build_query($data),
        ],
    ];
    $context = stream_context_create($options);
    $verifyResponse = file_get_contents($url, false, $context);
    $responseData = json_decode($verifyResponse);

    if ($responseData->success) {
        // Verifikasi berhasil, lanjutkan dengan proses penyimpanan form
        echo "Captcha Verified!";
    } else {
        // Gagal verifikasi captcha
        echo "Captcha verification failed.";
    }
}
?>
