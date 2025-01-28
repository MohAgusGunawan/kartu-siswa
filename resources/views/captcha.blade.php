<?php
// Proses validasi Turnstile di backend
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
    } else {
        $statusMessage = "Verifikasi gagal. Silakan coba lagi.";
    }
}
?>

<style>
    /* Gunakan Flexbox untuk memusatkan elemen */
    body {
      margin: 0;
      height: 100vh; /* Tinggi penuh viewport */
      display: flex;
      justify-content: center; /* Pusatkan horizontal */
      align-items: center; /* Pusatkan vertikal */
      background-color: #f0f0f0; /* Warna latar belakang */
    }

    /* Styling tambahan untuk widget Turnstile */
    .cf-turnstile {
      background-color: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
  </style>

<body>
<!-- Widget Turnstile -->
<div id="turnstile-widget" class="cf-turnstile" data-sitekey="0x4AAAAAAA6j75MpRvhSaHTH"></div>

<!-- Pesan status -->
<p id="status-message"><?php echo $statusMessage ?? ''; ?></p>

<script>
  // Fungsi untuk menangani respons Turnstile
  function handleTurnstileCallback(token) {
    // Kirim token ke backend untuk validasi
    fetch(window.location.href, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: `token=${encodeURIComponent(token)}`,
    })
    .then(response => response.text())
    .then(() => {
      // Refresh halaman untuk menampilkan pesan status
      window.location.href = "{{ route('form.index') }}";
    })
    .catch(error => {
      console.error('Error:', error);
      document.getElementById('status-message').textContent = "Terjadi kesalahan. Silakan refresh halaman.";
    });
  }

  // Tambahkan event listener untuk menerima token dari Turnstile
  window.onload = function() {
    turnstile.render('#turnstile-widget', {
      sitekey: '0x4AAAAAAA6j75MpRvhSaHTH', // Ganti dengan Site Key Anda
      callback: handleTurnstileCallback,
    });
  };
</script>

<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
</body>