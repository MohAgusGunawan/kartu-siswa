// Untuk menampilkan nama file yang dipilih
$('.custom-file-input').on('change', function () {
    var fileName = $(this).val().split('\\').pop();
    $(this).next('.custom-file-label').html(fileName);
});

// Untuk menampilkan gambar yang dipilih
function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function () {
        var output = document.getElementById('img-preview');
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}

document.getElementById('nis').addEventListener('input', function () {
    var maxDigits = 5;
    var val = this.value;

    if (val.length > maxDigits) {
        this.value = val.slice(0, maxDigits); // Membatasi jumlah digit menjadi 5
    }
});
document.getElementById('nama').addEventListener('keypress', function (e) {
    // Mengecek apakah karakter yang diketik adalah angka (0-9)
    if (e.key >= '0' && e.key <= '9') {
        e.preventDefault(); // Mencegah karakter angka dituliskan
    }
});