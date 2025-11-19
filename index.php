<?php
// =================================================================
// KONFIGURASI KONEKSI MySQL BARU (GANTI DENGAN KREDENSIAL ASLI ANDA!)
// =================================================================
define('DB_SERVER', 'sql307.infinityfree.com');
define('DB_USERNAME', 'if0_40041281'); // <-- GANTI INI
define('DB_PASSWORD', 'hzpPrbU6LLB27xv'); // <-- GANTI INI
define('DB_NAME', 'if0_40041281_terbaru'); // <-- GANTI INI
define('TABLE_GREETINGS', 'greetings'); // Nama tabel untuk ucapan
// Fungsi koneksi database
function connectDB() {
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        // Dalam lingkungan produksi, sebaiknya log error dan tampilkan pesan umum.
        // echo "Koneksi gagal: " . $conn->connect_error;
        return null;
    }
    return $conn;
}

// =================================================================
// LOGIKA TAMBAHAN: MENYIMPAN UCAPAN (CRUD MySQL)
// =================================================================
$greetings_success = false;
$greetings_error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_greeting'])) {
    $conn = connectDB();
    if ($conn) {
        $name = trim($_POST['guestName']);
        $message = trim($_POST['guestMessage']);

        if (!empty($name) && !empty($message)) {
            // Bersihkan input
            $safe_name = $conn->real_escape_string(htmlentities($name));
            $safe_message = $conn->real_escape_string(htmlentities($message));
            $timestamp = date('Y-m-d H:i:s');
            
            // Query Insert
            $sql = "INSERT INTO " . TABLE_GREETINGS . " (name, message, timestamp) VALUES ('$safe_name', '$safe_message', '$timestamp')";

            if ($conn->query($sql) === TRUE) {
                $greetings_success = true;
            } else {
                $greetings_error = "Gagal menyimpan ucapan: " . $conn->error;
            }
        } else {
            $greetings_error = "Nama dan Ucapan tidak boleh kosong.";
        }
        $conn->close();
    } else {
        $greetings_error = "Server database tidak dapat dihubungkan.";
    }
}

// =================================================================
// LOGIKA UNTUK MENGAMBIL DAFTAR UCAPAN
// =================================================================
$greetings_list = [];
$conn = connectDB();
if ($conn) {
    // Query SELECT - ambil 50 ucapan terbaru
    $sql_select = "SELECT name, message, timestamp FROM " . TABLE_GREETINGS . " ORDER BY timestamp DESC LIMIT 50";
    $result = $conn->query($sql_select);

    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $greetings_list[] = $row;
        }
    }
    $conn->close();
}


// =================================================================
// LOGIKA UNTUK MENGAMBIL NAMA TAMU DARI PARAMETER URL (?to=...)
// =================================================================

// Cek apakah parameter 'to' ada di URL
$guest_name = 'Tamu Undangan'; // Nilai default

if (isset($_GET['to']) && !empty($_GET['to'])) {
    // Ambil nilai mentah
    $raw_name = $_GET['to'];
    
    // 1. Ganti underscore atau hyphen dengan spasi
    $cleaned_name = str_replace(array('_', '-'), ' ', $raw_name);
    
    // 2. Bersihkan dari potensi XSS dan kapitalisasi setiap kata
    $guest_name_formatted = ucwords(htmlentities($cleaned_name));

    // 3. Jika nama yang diformat adalah "Tamu" (dari ?to=tamu), 
    // kita tetap tampilkan default "Tamu Undangan" (opsional, tergantung preferensi)
    if (strtolower($guest_name_formatted) === 'tamu') {
        $guest_name = 'Tamu Undangan';
    } else {
        $guest_name = $guest_name_formatted;
    }
}
// Variabel $guest_name sekarang siap digunakan di HTML
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Undangan Pernikahan Adelia & Fani</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap">
    

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <script type="module">
        // Semua inisiasi Firebase dihapus karena menggunakan MySQL
        
        // Menghapus semua fungsi Firebase (setupGreetingListener, sendGreeting, handleGreetingSubmit)
        // Karena ucapan ditangani sepenuhnya oleh PHP/MySQL
    </script>

    <style>
         @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Playfair+Display:ital,wght@0,400..900;1s,400..900&display=swap');
        
        .font-playfair {
            font-family: 'Playfair Display', serif;
        }

        .font-inter {
            font-family: 'Inter', sans-serif;
        }

        /* Warna khusus untuk tema emas/cokelat tua */
        .text-gold-dark {
            color: #7b5b3a; /* Cokelat tua keemasan */
        }
        
        .text-gold-medium {
            color: #9c805a; /* Warna teks yang lebih lembut */
        }
        /* Definisi Font Kustom */
        @font-face {
            font-family: 'ElegantScript';
            src: url('https://fonts.gstatic.com/s/greatvibes/v14/RWmMoKWR9v4vgtybtntVnmFbbw.woff2') format('woff2');
            font-weight: 400;
            font-style: normal;
        }

        @font-face {
            font-family: 'EleganceSerif';
            src: url('https://fonts.gstatic.com/s/tenorsans/v23/bx6CNwJ3_b_Muf4MWYp8Ew.woff2') format('woff2');
            font-weight: 400;
            font-style: normal;
        }

        /* Font Arabic Formal */
        @import url('https://fonts.googleapis.com/css2?family=Scheherazade+New:wght@400;700&display=swap');

        /* Konfigurasi Warna Tailwind Kustom */
        :root {
            /* Warna Emas Primer (b5936e) */
            --color-primary-gold: #b5936e;
            /* Warna Emas Sekunder (96826d) - Warna Baru yang Diminta */
            --color-secondary-gold: #96826d;
            /* Warna Teks Gelap Baru (didasarkan pada 96826d) */
            --color-secondary-dark: #4a3e35; 
            /* Warna Teks yang Hampir Hitam/Abu */
            --color-text-dark: #6a6a6a; 
        }

        /* Konfigurasi Tailwind CSS di Style Tag */
        
        /* Tambahkan class kustom untuk warna */
        .text-primary-gold { color: var(--color-primary-gold); }
        .bg-primary-gold { background-color: var(--color-primary-gold); }
        .hover\:bg-primary-gold:hover { background-color: var(--color-primary-gold); }
        
        .text-secondary-gold { color: var(--color-secondary-gold); }
        .bg-secondary-gold { background-color: var(--color-secondary-gold); }
        .hover\:bg-secondary-gold:hover { background-color: var(--color-secondary-gold); }

        .text-secondary-dark { color: var(--color-secondary-dark); }
        
        body {
            /* Latar belakang utama yang diminta */
            background-image: url('https://undanganonline.42web.io/Img/custom-Maylia-Ibam-UTAMA-1.jpg');
            background-size: cover;
            background-attachment: fixed;
            background-position: center center;
            background-repeat: no-repeat;
            min-height: 100vh;
            overflow-y: hidden; /* Sembunyikan scrollbar awal */
            font-family: 'Inter', sans-serif;
        }
        
        .main-card {
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.06);
        }

        .couple-card-container {
            background-color: rgba(255, 255, 255, 0.95);
        }
        
        .couple-image-border {
            border: 8px solid rgba(181, 147, 110, 0.5);
            box-shadow: 0 0 0 4px #fff, 0 0 0 6px var(--color-primary-gold);
        }

        .footer-image {
            width: 100%;
            height: auto;
            max-width: 600px;
            margin: 0 auto;
            display: block;
        }

        /* Styling khusus untuk Cover Page */
        #cover-section {
            background-image: url('https://undanganonline.42web.io/Img/custom-Maylia-Ibam-cb2-fix.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: var(--color-secondary-dark); /* Menggunakan warna baru */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            z-index: 50;
            transition: opacity 1s ease-in-out;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .cover-hidden {
            opacity: 0;
            pointer-events: none;
        }
        
        /* Konten cover langsung di dalam #cover-section */
        #cover-section > p, #cover-section > button {
            position: relative; 
            z-index: 55;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1); 
        }

        .cover-initials {
            font-family: 'ElegantScript', cursive;
            font-size: 5rem;
            color: var(--color-secondary-gold); /* Menggunakan warna baru */
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        .cover-wedding-text {
            font-family: 'EleganceSerif', serif;
            font-size: 0.8rem;
            letter-spacing: 0.15em;
            color: var(--color-secondary-dark); /* Menggunakan warna baru */
            margin-bottom: 0.5rem;
        }

        .cover-names {
            font-family: 'ElegantScript', cursive;
            font-size: 3rem;
            color: var(--color-primary-gold); /* Tetap emas primer */
            line-height: 1.2;
            margin-bottom: 0.25rem;
        }
        .cover-date {
            font-family: 'EleganceSerif', serif;
            font-size: 0.9rem;
            letter-spacing: 0.1em;
            color: var(--color-secondary-dark); /* Menggunakan warna baru */
            margin-bottom: 1.5rem;
        }
        .cover-dear {
            font-family: 'EleganceSerif', serif;
            font-size: 0.8rem;
            color: var(--color-text-dark); /* Tetap abu-abu yang lebih lembut */
            margin-bottom: 0.25rem;
        }
        .cover-guest {
            font-family: 'EleganceSerif', serif;
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--color-secondary-dark); /* Menggunakan warna baru */
            margin-bottom: 2rem;
        }

        /* Styling Video Hero */
        #hero-video-section {
            width: 100%;
            height: 100vh; 
            position: relative;
            overflow: hidden;
            background-color: black; 
            display: flex; /* Untuk menempatkan overlay di tengah */
            justify-content: center; /* Untuk menempatkan overlay di tengah */
            align-items: center; /* Untuk menempatkan overlay di tengah */
        }
        #hero-video-section video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover; 
            z-index: 10;
        }
        /* Overlay agar teks tetap terbaca di atas video */
        #hero-video-section::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.2); 
            z-index: 15;
        }

        /* Styling untuk teks di atas video hero */
        #hero-text-overlay {
            position: relative; /* Penting agar z-index bekerja relatif terhadap video section */
            z-index: 20; /* Pastikan di atas video dan overlay */
            text-align: center;
            color: white; /* Warna teks default, bisa diubah per elemen */
            text-shadow: 1px 1px 5px rgba(0,0,0,0.5); /* Shadow agar lebih terbaca */
        }
        #hero-text-overlay .hero-wedding-text {
            font-family: 'EleganceSerif', serif;
            font-size: 0.8rem;
            letter-spacing: 0.15em;
            color: #f0f0f0; /* Warna lebih terang untuk kontras */
            margin-bottom: 0.5rem;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.6);
        }
        #hero-text-overlay .hero-names {
            font-family: 'ElegantScript', cursive;
            font-size: 3rem;
            color: var(--color-primary-gold); 
            line-height: 1.2;
            margin-bottom: 0.25rem;
            text-shadow: 1px 1px 4px rgba(0,0,0,0.8);
        }
        #hero-text-overlay .hero-date {
            font-family: 'EleganceSerif', serif;
            font-size: 0.9rem;
            letter-spacing: 0.1em;
            color: #f0f0f0;
            margin-bottom: 1.5rem;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.6);
        }


             /* Kelas untuk ayat Al-Qur'an agar terlihat besar dan jelas */
        .quran-ayat {
            font-size: 1.8rem; /* Lebih kecil dari sebelumnya (mungkin 2rem) */
            line-height: 2.5;
        }

        /* Kelas untuk terjemahan */
        .quran-translation {
            font-size: 0.875rem; /* Ukuran font lebih kecil (setara sm:text-sm) */
            line-height: 1.6;
        }
        
        /* Kelas untuk bingkai ayat (sesuai gambar yang diunggah) */
        .ayat-frame {
            background-image: url('https://undanganonline.42web.io/Img/BG-AYAT-MAYLIA-FIX.png');
            background-size: cover;
            background-position: center;
            padding: 40px 25px; /* Sesuaikan padding */
            margin: 0 auto;
            width: 100%;
            height: 100vh;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        /* Overwrite font-size for mobile to ensure it stays inside */
        @media (max-width: 640px) {
            .quran-ayat {
                font-size: 1.5rem; /* Dikecilkan untuk perangkat mobile */
                line-height: 2.2;
            }
            .quran-translation {
                font-size: 0.8rem; /* Dikecilkan lagi untuk perangkat mobile */
            }
            .ayat-frame {
                padding: 30px 20px;
            }

        /* Styling untuk Countdown Section */
        #countdown-section {
            background-image: url('https://undanganonline.42web.io/Img/BG-GEBRYOU-COUNT-maylia.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            padding: 5rem 1rem;
            width : 100%;
            height: 100vh; 
        }

        .countdown-box {
            background-color: rgba(255, 255, 255, 0.85); 
            border: 1px solid rgba(181, 147, 110, 0.5);
            color: var(--color-secondary-dark); /* Menggunakan warna baru */
            padding: 1rem 0.5rem;
        }

        /* Styling BARU untuk setiap event card */
        .event-card {
            background-image: url('https://undanganonline.42web.io/Img/BG-DATES-MAYLIA-2.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            padding: 3rem 1.5rem; 
            border-radius: 0.5rem;
            margin-bottom: 2rem; 
            position: relative;
            z-index: 1; 
        }

        /* MENGHAPUS SEMUA BORDER, SHADOW, DAN BACKGROUND DARI KONTEN EVENT */
        .event-card-content {
            background-color: transparent; 
            padding: 0; 
            border-radius: 0;
            box-shadow: none; 
            width: 100%;
            margin: 0 auto; 
            color: var(--color-secondary-dark); /* Menggunakan warna baru */
        }
        
        /* Penyesuaian warna teks agar tetap terbaca di atas background ornamen */
        .event-title-script {
            font-family: 'ElegantScript', cursive;
            font-size: 2.5rem; 
            color: var(--color-secondary-dark); /* Menggunakan warna baru */
            text-shadow: 1px 1px 1px rgba(255, 255, 255, 0.7); 
            margin-bottom: 0.5rem;
        }
        .event-date-text, .event-time-details, .event-address {
            color: var(--color-secondary-dark); /* Menggunakan warna baru */
            text-shadow: 1px 1px 1px rgba(255, 255, 255, 0.7);
        }
        .event-location-name {
            color: var(--color-primary-gold); 
            text-shadow: 1px 1px 1px rgba(255, 255, 255, 0.7);
        }

        .event-date-text {
            font-family: 'EleganceSerif', serif;
            font-size: 1.1rem;
            font-weight: 600;
            letter-spacing: 0.05em;
        }
        .event-time-details {
            font-family: 'EleganceSerif', serif;
            font-size: 0.9rem;
            margin-top: 1rem;
        }
        .event-location-name {
            font-family: 'EleganceSerif', serif;
            font-size: 1.5rem;
            font-weight: 700;
            margin-top: 1.5rem;
        }
        .event-address {
            font-family: 'Inter', sans-serif;
            font-size: 0.85rem;
            line-height: 1.5;
            margin-bottom: 1.5rem;
        }
        .location-icon-style {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }
        .time-divider-icon {
            font-size: 1.2rem;
            margin-top: 1rem;
            margin-bottom: 1rem;
        }
        .google-maps-btn {
            background-color: var(--color-primary-gold); 
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 9999px; 
            font-weight: 600;
            transition: background-color 0.3s ease;
        }
        .google-maps-btn:hover {
            background-color: var(--color-secondary-gold);
        }

        /* Styling untuk Gallery Section */
        #gallery-section {
            background-image: url('https://undanganonline.42web.io/Img/paper-plos-p-1.jpg'); 
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            padding: 3rem 1rem;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.5rem; 
        }
        .gallery-item {
            aspect-ratio: 1/1.2; 
            overflow: hidden;
            border-radius: 0.5rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: transform 0.2s ease;
        }
        .gallery-item:hover {
            transform: scale(1.02);
        }
        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Styling Modal (Lightbox/Gift/Umum) */
        .modal {
            display: none;
            position: fixed;
            z-index: 100;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.9);
            animation: fadeIn 0.3s;
        }
        .modal-gift {
            background-color: rgba(0,0,0,0.8);
            z-index: 200; 
        }
        @keyframes fadeIn {
            from {opacity: 0;} 
            to {opacity: 1;}
        }

        .modal-content-container {
            margin: auto;
            display: block;
            width: 90%;
            max-width: 700px;
            padding-top: 3rem; 
        }

        .modal-image-content {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 0.5rem;
        }
        
        .modal-gift-content {
            background-color: #fff;
            margin: 15% auto; 
            padding: 20px;
            border-radius: 1rem;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .modal-actions {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 1rem;
        }
        .modal-action-btn {
            background-color: var(--color-primary-gold);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.9rem;
            font-weight: 600;
            transition: background-color 0.3s;
        }
        .modal-action-btn:hover {
            background-color: var(--color-secondary-gold);
        }

        .close-btn {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
            cursor: pointer;
            z-index: 101;
        }
        .close-btn:hover,
        .close-btn:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }

        .close-btn-gift {
            position: absolute;
            top: 10px;
            right: 20px;
            color: var(--color-secondary-dark); /* Menggunakan warna baru */
            font-size: 30px;
            font-weight: bold;
            transition: 0.3s;
            cursor: pointer;
            z-index: 201;
        }
        .close-btn-gift:hover {
            color: var(--color-primary-gold);
        }

        .bank-card {
            border: 1px solid #e0e0e0;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1rem;
            background-color: #fcfaf7;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            text-align: left;
        }
        .bank-card button {
            transition: background-color 0.3s;
        }

        /* Menghilangkan ornamen di setiap section */
        #ayat-section .h-20,
        #couple-section .h-24,
        #gift-rsvp-section .h-10,
        .thank-you-ornament,
        #couple-section::before,
        #couple-section::after {
            display: none !important;
        }
        /* Style untuk inisial MK di footer */
        .footer-initials {
            font-family: 'ElegantScript', cursive;
            font-size: 5rem;
            color: var(--color-secondary-gold); /* Menggunakan warna baru */
            line-height: 1;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.2);
            position: relative;
            z-index: 10; 
            margin-bottom: -1.5rem; 
        }

        /* Style untuk daftar ucapan */
        #greetings-list {
            max-height: 400px;
            overflow-y: auto;
            scrollbar-width: thin; 
            scrollbar-color: var(--color-primary-gold) #fcfaf7; 
        }
        #greetings-list::-webkit-scrollbar {
            width: 8px;
        }
        #greetings-list::-webkit-scrollbar-track {
            background: #fcfaf7;
            border-radius: 4px;
        }
        #greetings-list::-webkit-scrollbar-thumb {
            background-color: var(--color-primary-gold);
            border-radius: 4px;
            border: 2px solid #fcfaf7; 
        }
        .animate-fadeIn {
            animation: fadeIn 0.5s ease-out;
        }

        /* Style Baru untuk Tombol Musik */
        #music-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: var(--color-primary-gold);
            color: white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.1s;
        }
        #music-btn:hover {
            background-color: var(--color-secondary-gold);
        }
        .music-playing {
            animation: spin 2s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>

    <audio id="background-music" loop>
        <source src="https://undanganonline.42web.io/Lagu/Kabagyan-Sadewok.mp3" type="audio/mp3">
        Your browser does not support the audio element.
    </audio>

    <section id="cover-section" class="text-center">
        

<p class="cover-initials">AF</p>
        <p class="cover-wedding-text">WEDDING INVITATION</p>
        <p class="cover-names">Adelia & Fani</p>
        <p class="cover-date">14 . 12 . 2025</p>
        
        <p class="cover-dear">Kepada Yth:</p>
        <p class="cover-guest"><?php echo $guest_name; ?></p>

        <button id="open-btn" class="bg-primary-gold hover:bg-secondary-gold text-white font-bold py-3 px-8 rounded-full shadow-lg transition duration-300 text-sm mt-4">
            <i class="fas fa-envelope-open mr-2"></i> OPEN INVITATION
        </button>
    </section>

    

<div id="main-content" class="max-w-xl mx-auto relative min-h-screen">
        
        

<section id="hero-video-section">
            <video id="hero-video" autoplay muted playsinline>
                <source src="https://undanganonline.42web.io/Img/Custom-Maylia-Ibam-Rev-Fix-brake.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            
            

<div id="hero-text-overlay">
                <p class="hero-wedding-text">THE WEDDING OF</p>
                <p class="hero-names">Adelia & Fani</p>
                <p class="hero-date">14 . 12 . 2025</p>
            </div>
        </section>

            <!-- Section Ayat Al-Qur'an (Bingkai Bunga) -->
    <section class="py-10">
        <div class="ayat-frame rounded-xl text-center flex flex-col items-center justify-center">

            <!-- Bismillah - Font size: 1.8rem (Mobile: 1.5rem), Color: text-gold-dark -->
            <p lang="ar" dir="rtl" class="font-serif text-gold-dark quran-ayat">
                بِسْمِ اللّٰهِ الرَّحْمٰنِ الرَّحِيْمِ
            </p>

            <!-- Ayat Ar-Rum: 21 - Font size: 1.8rem (Mobile: 1.5rem), Color: text-gold-dark -->
            <p lang="ar" dir="rtl" class="font-serif text-gold-dark quran-ayat mt-4">
                وَمِنْ اٰيٰتِهٖٓ اَنْ خَلَقَ لَكُمْ مِّنْ اَنْفُسِكُمْ اَزْوَاجًا لِّتَسْكُنُوْٓا اِلَيْهَا وَجَعَلَ بَيْنَكُمْ مَّوَدَّةً وَّرَحْمَةً ۗاِنَّ فِيْ ذٰلِكَ لَاٰيٰتٍ لِّقَوْمٍ يَّتَفَكَّرُوْنَ
            </p>

            <!-- Terjemahan - Font size: 0.875rem (Mobile: 0.8rem), Color: text-gold-medium -->
            <p class="mt-8 px-4 text-gold-medium quran-translation italic max-w-lg mx-auto">
                “Dan di antara tanda-tanda (kebesaran)-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari jenismu sendiri, agar kamu cenderung dan merasa tenteram kepadanya, dan Dia menjadikan di antaramu rasa kasih dan sayang. Sungguh, pada yang demikian itu benar-benar terdapat tanda-tanda (kebesaran Allah) bagi kaum yang berpikir.”
            </p>
            
            <!-- Sumber - Font size: 0.875rem (Mobile: 0.8rem), Color: text-gold-medium -->
            <p class="mt-4 text-gold-medium quran-translation font-semibold">
                (QS Ar-Rūm : 21)
            </p>

        </div>
    </section>
        
        

<section id="countdown-section" class="py-16 px-8 text-center">
            <div class="main-card p-6 rounded-lg bg-transparent shadow-none">
                <h2 class="text-xl font-serif-elegant font-semibold text-secondary-dark tracking-widest mb-2">
                    COUNTING
                </h2>
                <h1 class="text-5xl font-script text-primary-gold mb-10">
                    The Days
                </h1>

                

<div class="grid grid-cols-4 gap-2 sm:gap-4 max-w-xs mx-auto text-center font-inter font-bold">
                    
                    <div class="countdown-box rounded-xl shadow-lg">
                        <div id="days" class="text-3xl sm:text-4xl text-primary-gold">00</div>
                        <div class="text-xs sm:text-sm mt-1 text-secondary-dark opacity-80">DAYS</div>
                    </div>

                    <div class="countdown-box rounded-xl shadow-lg">
                        <div id="hours" class="text-3xl sm:text-4xl text-primary-gold">00</div>
                        <div class="text-xs sm:text-sm mt-1 text-secondary-dark opacity-80">HOURS</div>
                    </div>

                    <div class="countdown-box rounded-xl shadow-lg">
                        <div id="minutes" class="text-3xl sm:text-4xl text-primary-gold">00</div>
                        <div class="text-xs sm:text-sm mt-1 text-secondary-dark opacity-80">MIN</div>
                    </div>

                    <div class="countdown-box rounded-xl shadow-lg">
                        <div id="seconds" class="text-3xl sm:text-4xl text-primary-gold">00</div>
                        <div class="text-xs sm:text-sm mt-1 text-secondary-dark opacity-80">SEC</div>
                    </div>
                </div>

                <div class="mt-10">
                    <button class="bg-primary-gold hover:bg-secondary-gold text-white font-bold py-3 px-8 rounded-full shadow-xl transition duration-300 text-sm">
                        <i class="fas fa-calendar-alt mr-2"></i> SAVE THE DATE
                    </button>
                </div>
            </div>
        </section>
        

<section id="couple-section" class="py-16 px-8 text-center relative">
            
            
            <div class="couple-card-container p-6 rounded-lg max-w-md mx-auto relative z-10">
                <p class="text-sm text-secondary-dark mb-8 leading-relaxed">
                    Assalamualaikum Wr. Wb. Dengan memohon
                    Rahmat & Ridho Allah SWT, kami bermaksud
                    mengundang Bapak/Ibu/Saudara/i untuk menghadiri
                    acara pernikahan putra-putri kami:
                </p>

                

<div class="mb-10">
                    

<img src="https://placehold.co/300x400/b5936e/ffffff?text=" alt="foto" class="w-40 h-40 object-cover mx-auto rounded-full mb-4 couple-image-border">
                    <h3 class="text-3xl font-script text-secondary-gold mb-1">Adelia</h3>
                    <p class="text-base font-semibold text-secondary-dark mb-1">Adelia Mei Fadilla</p>
                    <p class="text-sm text-text-dark">Putri kedua dari Bpk. Sutomo & Ibu. Suhartini</p>
    <p class="text-sm text-text-dark">(Bluru Permai Fk-24 RT 13 RW 11 Sidoarjo)</p>
                </div>

                <h2 class="text-4xl font-script text-primary-gold my-6">&</h2>

                

<div class="mb-10">
                    

<img src="https://placehold.co/300x400/b5936e/ffffff?text=" alt="Fani" class="w-40 h-40 object-cover mx-auto rounded-full mb-4 couple-image-border">
                    <h3 class="text-3xl font-script text-secondary-gold mb-1">Fani</h3>
                    <p class="text-base font-semibold text-secondary-dark mb-1">Pratu Fani Risaldi</p>
                    <p class="text-sm text-text-dark">Putra Pertama dari Bpk. Rosul & Ibu. Titik Suryani</p>
    <p class="text-sm text-text-dark">(JL Menur 2 no 1 RT 01 RW 01 Surabaya)</p>
                </div>
            </div>

            
        </section>


        

<section id="events-main-section" class="py-8 px-4 text-center">
            <h2 class="text-4xl font-script text-primary-gold mb-10">Akad & Resepsi</h2>

            

<div class="event-card mx-auto max-w-sm">
                <div class="event-card-content">
                    <p class="cover-initials text-4xl mb-6">AF</p>
                    <h3 class="event-title-script">Akad Nikah</h3>
                    <p class="event-date-text">MINGGU</p>
                    <p class="event-date-text">14 DESEMBER 2025</p>
                    <hr class="time-divider-line">
                    <i class="fas fa-dove time-divider-icon"></i>
                    <hr class="time-divider-line mb-4">
                    <p class="event-time-details">Pukul 07.00 WIB – Selesai</p>
                    
                    <i class="fas fa-map-marker-alt location-icon-style"></i>
                    <h4 class="event-location-name">Gedung Soerokromo Sidoarjo</h4>
                    <p class="event-address">
                        Semambung, Gedangan, Sidoarjo Regency, East Java 61254
                    </p>
                    <a href="https://maps.app.goo.gl/3HcYk1Cuym1YhB9k7" target="_blank" class="inline-flex items-center google-maps-btn">
                        <i class="fas fa-map mr-2"></i> GOOGLE MAPS
                    </a>
                </div>
            </div>

            

<div class="event-card mx-auto max-w-sm mt-8">
                <div class="event-card-content">
                    <p class="cover-initials text-4xl mb-6">AF</p>
                    <h3 class="event-title-script">Resepsi</h3>
                    <p class="event-date-text">MINGGU</p>
                    <p class="event-date-text">14 DESEMBER 2025</p>
                    <hr class="time-divider-line">
                    <i class="fas fa-dove time-divider-icon"></i>
                    <hr class="time-divider-line mb-4">
                    <p class="event-time-details">Pukul 10.00 WIB – Selesai</p>
                    
                    <i class="fas fa-map-marker-alt location-icon-style"></i>
                    <h4 class="event-location-name">Gedung Soerokromo Sidoarjo</h4>
                    <p class="event-address">
                        Semambung, Gedangan, Sidoarjo Regency, East Java 61254
                    </p>
                    <a href="https://maps.app.goo.gl/3HcYk1Cuym1YhB9k7" target="_blank" class="inline-flex items-center google-maps-btn">
                        <i class="fas fa-map mr-2"></i> GOOGLE MAPS
                    </a>
                </div>
            </div>
        </section>


        

<section id="gallery-section" class="py-16 px-4 text-center">
            <h2 class="text-4xl font-script text-primary-gold mb-10">Our Gallery</h2>

            

<div class="gallery-grid max-w-lg mx-auto">
                

<div class="gallery-item" onclick="openImageModal('https://placehold.co/400x500/b5936e/ffffff?text=Foto+1')">
                    <img src="https://placehold.co/400x500/b5936e/ffffff?text=Foto+1" alt="Foto Prewedding 1" loading="lazy">
                </div>
                
                

<div class="gallery-item" onclick="openImageModal('https://placehold.co/400x500/96826d/ffffff?text=Foto+2')">
                    <img src="https://placehold.co/400x500/96826d/ffffff?text=Foto+2" alt="Foto Prewedding 2" loading="lazy">
                </div>

                

<div class="gallery-item" onclick="openImageModal('https://placehold.co/400x500/8B4513/ffffff?text=Foto+3')">
                    <img src="https://placehold.co/400x500/8B4513/ffffff?text=Foto+3" alt="Foto Prewedding 3" loading="lazy">
                </div>

                

<div class="gallery-item" onclick="openImageModal('https://placehold.co/400x500/b5936e/ffffff?text=Foto+4')">
                    <img src="https://placehold.co/400x500/b5936e/ffffff?text=Foto+4" alt="Foto Prewedding 4" loading="lazy">
                </div>
                
                

<div class="gallery-item" onclick="openImageModal('https://placehold.co/400x500/96826d/ffffff?text=Foto+5')">
                    <img src="https://placehold.co/400x500/96826d/ffffff?text=Foto+5" alt="Foto Prewedding 5" loading="lazy">
                </div>
                
                

<div class="gallery-item" onclick="openImageModal('https://placehold.co/400x500/8B4513/ffffff?text=Foto+6')">
                    <img src="https://placehold.co/400x500/8B4513/ffffff?text=Foto+6" alt="Foto Prewedding 6" loading="lazy">
                </div>
            </div>
            
            <p class="text-sm text-text-dark mt-6">Klik pada foto untuk memperbesar dan opsi download/share.</p>
        </section>


        

<section id="gift-greetings-section" class="py-16 px-8 text-center relative">
            
            

<div class="main-card p-6 rounded-xl mb-12 relative z-10 bg-white shadow-xl">
                

                
                <h2 class="text-4xl font-script text-primary-gold mb-6">Wedding Gift</h2>
                <p class="text-sm text-secondary-dark mb-8 leading-relaxed px-4">
                    Doa restu Anda merupakan karunia yang sangat berarti bagi
                    kami. Dan jika memberi adalah ungkapan tanda kasih Anda,
                    Anda dapat memberi kado secara cashless.
                </p>
                
                <button onclick="openGiftModal()" class="bg-secondary-gold hover:bg-primary-gold text-white font-bold py-3 px-8 rounded-full shadow-lg transition duration-300 text-sm">
                    <i class="fas fa-gift mr-2"></i> CLICK HERE
                </button>
            </div>

            

<div class="main-card p-6 rounded-xl relative z-10 bg-white shadow-xl">
                

                
                <h2 class="text-4xl font-script text-primary-gold mb-6">Ucapan & Doa</h2>
                <p class="text-sm text-secondary-dark mb-8 leading-relaxed px-4">
                    Bagikan doa terbaik Anda untuk kebahagiaan kami
                </p>

                

<form class="space-y-4 text-left" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . (isset($_GET['to']) ? '?to=' . urlencode($_GET['to']) : ''); ?>">
                    <?php if ($greetings_success): ?>
                        <div class="p-3 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                            Ucapan dan doa Anda berhasil terkirim!
                        </div>
                    <?php endif; ?>
                    <?php if ($greetings_error): ?>
                        <div class="p-3 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                            Gagal mengirim ucapan: <?php echo $greetings_error; ?>
                        </div>
                    <?php endif; ?>
                    
                    <input type="hidden" name="send_greeting" value="1">
                    
                    <div class="font-semibold text-sm text-secondary-dark">Nama Anda</div>
                    <input type="text" name="guestName" placeholder="Contoh: Bpk. Fulan" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary-gold" required>
                    
                    <div class="font-semibold text-sm text-secondary-dark">Ucapan & Doa</div>
                    <textarea name="guestMessage" placeholder="Contoh: Selamat menempuh hidup baru, semoga sakinah mawaddah warahmah!" rows="4" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:focus:border-primary-gold" required></textarea>
                    
                    <button type="submit" class="w-full bg-primary-gold hover:bg-secondary-gold text-white font-bold py-3 rounded-lg shadow-md transition duration-300 mt-6">
                        KIRIM UCAPAN
                    </button>
                </form>

                <h3 class="text-3xl font-script text-secondary-gold mt-12 mb-6">Daftar Ucapan</h3>
                

<div id="greetings-list" class="mt-4 p-2">
                    
                    <?php if (!empty($greetings_list)): ?>
                        <?php foreach ($greetings_list as $greeting): ?>
                            <div class="bg-white p-4 rounded-xl shadow-md mb-4 border border-primary-gold/30 animate-fadeIn text-left">
                                <p class="font-serif-elegant text-lg text-secondary-gold font-bold mb-1"><?php echo htmlspecialchars($greeting['name']); ?></p>
                                <p class="font-inter text-sm text-secondary-dark leading-relaxed mb-3"><?php echo nl2br(htmlspecialchars($greeting['message'])); ?></p>
                                <p class="text-xs text-gray-400 font-inter"><?php echo date('d M Y H:i', strtotime($greeting['timestamp'])); ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-center text-text-dark">Belum ada ucapan. Jadilah yang pertama!</p>
                    <?php endif; ?>

                </div>
            </div>

        </section>

        

<section class="bg-white px-8 py-16 text-center">
            
            

            
            

<h2 class="text-5xl font-script text-primary-gold mb-8 tracking-wider">
                Thank You
            </h2>
            
            

<p class="text-base text-secondary-dark mb-6 leading-relaxed px-4 md:px-8">
                Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila
                Anda berkenan hadir dan memberikan do'a restunya untuk
                pernikahan kami.
            </p>
            
            

<p class="text-sm text-text-dark mb-10 leading-relaxed px-4 md:px-8">
                Atas do'a dan restunya,
                kami ucapkan terima kasih
            </p>

            

<div class="text-center mb-10">
                <h3 class="text-2xl font-serif-elegant font-bold text-secondary-gold tracking-widest mb-1">
                    Adelia & Fani
                </h3>
                <p class="text-sm font-inter text-secondary-gold font-semibold">
                    #IBELONGTOMAY
                </p>
            </div>

            
        </section>
        
        

<footer class="py-0 text-center relative flex flex-col items-center justify-center">
            
            

<p class="footer-initials">AF</p> 
            <img 
                src="https://undanganonline.42web.io/Img/custom-Maylia-Ibam-close.jpg" 
                alt="Foto Close-up Maylia Ibam"
                onerror="this.onerror=null;this.src='https://placehold.co/800x600/b5936e/ffffff?text=Footer+Image+Error';"
                class="footer-image mt-[-1.5rem] relative z-[5]" 
            >
        </footer>

    </div>
    
    <div id="music-btn">
        <i id="music-icon" class="fas fa-volume-up"></i>
    </div>
    

<div id="imageModal" class="modal">
        <span class="close-btn" onclick="closeImageModal()">×</span>
        <div class="modal-content-container">
            <img class="modal-image-content" id="modalImage" src="" alt="Foto Diperbesar">
            <div class="modal-actions">
                

<a id="downloadBtn" href="#" class="modal-action-btn" download>
                    <i class="fas fa-download mr-1"></i> Download
                </a>
                

<button id="shareBtn" class="modal-action-btn">
                    <i class="fas fa-share-alt mr-1"></i> Share
                </button>
            </div>
        </div>
    </div>

    

<div id="giftModal" class="modal modal-gift">
        <div class="modal-gift-content relative">
            <span class="close-btn-gift" onclick="closeGiftModal()">×</span>
            <h3 class="text-3xl font-script text-primary-gold mb-6">Kirim Kado Online</h3>
            
            <p class="text-sm text-secondary-dark mb-6">
                Silakan pilih rekening tujuan di bawah ini.
            </p>

            

<div class="bank-card">
                <p class="text-lg font-bold text-primary-gold mb-1">Bank BNI</p>
                <p class="text-xs text-text-dark mb-2">A/N Fani Risaldi</p>
                <div class="flex items-center justify-between">
                    <span id="bni-acc" class="text-base font-mono text-secondary-dark font-semibold">1858637752</span>
                    <button 
                        onclick="copyToClipboard('1858637752', 'Bank BNI')" 
                        class="bg-secondary-gold hover:bg-primary-gold text-white text-xs px-3 py-1 rounded-full flex items-center">
                        <i class="fas fa-copy mr-1"></i> Salin
                    </button>
                </div>
            </div>

<div class="bank-card">
                <p class="text-lg font-bold text-primary-gold mb-1">Bank BCA</p>
                <p class="text-xs text-text-dark mb-2">A/N Adelia Mei Fadilla</p>
                <div class="flex items-center justify-between">
                    <span id="bca-acc" class="text-base font-mono text-secondary-dark font-semibold">0181990235</span>
                    <button 
                        onclick="copyToClipboard('0181990235', 'Bank BCA')" 
                        class="bg-secondary-gold hover:bg-primary-gold text-white text-xs px-3 py-1 rounded-full flex items-center">
                        <i class="fas fa-copy mr-1"></i> Salin
                    </button>
                </div>
            </div>         

<div class="bank-card">
                <p class="text-lg font-bold text-primary-gold mb-1">Bank Mandiri</p>
                <p class="text-xs text-text-dark mb-2">A/N Adelia Mei Fadilla</p>
                <div class="flex items-center justify-between">
                    <span id="mandiri-acc" class="text-base font-mono text-secondary-dark font-semibold">1410024356958</span>
                    <button 
                        onclick="copyToClipboard('1410024356958', 'Bank Mandiri')" 
                        class="bg-secondary-gold hover:bg-primary-gold text-white text-xs px-3 py-1 rounded-full flex items-center">
                        <i class="fas fa-copy mr-1"></i> Salin
                    </button>
                </div>
            </div>
            
        </div>
    </div>

    <script>
        // --- LOGIKA UTAMA (TIDAK BERUBAH) ---
        // Target date for the wedding (14 December 2025, 07:00 WIB)
        const weddingDate = new Date("2025-12-14T07:00:00+07:00").getTime(); // +07:00 for WIB

        // Update the countdown every 1 second
        const countdownInterval = setInterval(function() {

            // Get current date and time
            const now = new Date().getTime();

            // Find the distance between now and the count down date
            const distance = weddingDate - now;

            // Time calculations for days, hours, minutes and seconds
            const days = Math.floor((distance / (1000 * 60 * 60 * 24)));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Function to pad single digits with a leading zero
            const pad = (num) => num < 10 ? '0' + num : num;

            // Output the result in elements with id="days", "hours", "minutes", and "seconds"
            const daysEl = document.getElementById("days");
            const hoursEl = document.getElementById("hours");
            const minutesEl = document.getElementById("minutes");
            const secondsEl = document.getElementById("seconds");

            if (daysEl) daysEl.innerHTML = pad(days);
            if (hoursEl) hoursEl.innerHTML = pad(hours);
            if (minutesEl) minutesEl.innerHTML = pad(minutes);
            if (secondsEl) secondsEl.innerHTML = pad(seconds);

            // If the count down is finished, write some text
            if (distance < 0) {
                clearInterval(countdownInterval);
                if (daysEl) daysEl.innerHTML = "00";
                if (hoursEl) hoursEl.innerHTML = "00";
                if (minutesEl) minutesEl.innerHTML = "00";
                if (secondsEl) secondsEl.innerHTML = "00";
                // Optional: display a message instead of the timer
                const countdownSection = document.getElementById("countdown-section");
                if (countdownSection) {
                    countdownSection.querySelector('.main-card').innerHTML = 
                        `<h1 class="text-4xl font-script text-primary-gold">We Are Married!</h1>
                         <p class="text-sm text-secondary-dark mt-4">Thank you for your love and prayers.</p>`;
                }
            }
        }, 1000);


        document.getElementById('open-btn').addEventListener('click', function() {
            const cover = document.getElementById('cover-section');
            const heroVideo = document.getElementById('hero-video'); 
            
            // --- LOGIKA BARU UNTUK UPDATE URL (REVISI) ---
            const currentUrl = window.location.href;
            
            // Cek apakah URL sudah memiliki query string (termasuk '?to=...')
            if (!currentUrl.includes('?')) {
                // Jika belum ada query string, tambahkan default '?to=tamu'
                const newUrl = currentUrl + '?to=tamu';
                // Menggunakan history.replaceState untuk mengubah URL tanpa memuat ulang halaman
                window.history.replaceState({}, document.title, newUrl);
            }
            // Jika sudah ada query string (misal: ?to=Bapak_Budi), biarkan URL seperti adanya
            // --- AKHIR LOGIKA REVISI ---

            // Sembunyikan cover dengan transisi
            cover.classList.add('cover-hidden');

            // Aktifkan scroll pada body
            document.body.style.overflowY = 'auto';

            // Hapus cover dari DOM setelah transisi selesai
            setTimeout(() => {
                cover.style.display = 'none';
            }, 1000); // Sesuaikan dengan durasi transisi CSS (1s)

            // Pastikan video dimulai saat cover terbuka
            if (heroVideo) {
                // Gunakan promise untuk memastikan play sukses, tangani error jika tidak bisa autoplay
                heroVideo.play().catch(error => {
                    console.error("Autoplay failed:", error);
                    // Tampilkan pesan atau tombol play jika autoplay gagal
                });
                // Pastikan video berhenti saat durasi habis
                heroVideo.onended = () => {
                    heroVideo.pause(); 
                };
            }

            // Otomatis pindah ke bagian paling atas dari konten utama
            window.scrollTo(0, 0);

            // PUTAR MUSIK
            playMusic();
        });

        // --- FUNGSI UTILITY GLOBAL ---

        // Fungsi alert kustom (menggantikan alert() bawaan)
        function alert(message) {
            const tempModal = document.createElement('div');
            tempModal.classList.add('fixed', 'inset-0', 'bg-black/50', 'z-[1000]', 'flex', 'items-center', 'justify-center');
            tempModal.innerHTML = `
                <div class="bg-white p-6 rounded-lg shadow-xl max-w-xs mx-4 text-center">
                    <p class="text-gray-700 mb-4">${message}</p>
                    <button onclick="this.parentNode.parentNode.remove()" class="bg-primary-gold text-white px-4 py-2 rounded-full text-sm">OK</button>
                </div>
            `;
            document.body.appendChild(tempModal);
        }

        // Fungsi Salin ke Clipboard
        function copyToClipboard(text, bankName) {
            try {
                // Gunakan Clipboard API modern
                navigator.clipboard.writeText(text).then(() => {
                    alert(`Nomor rekening ${bankName} berhasil disalin!`);
                }, (err) => {
                    console.error('Async: Could not copy text: ', err);
                    // Fallback copy
                    const el = document.createElement('textarea');
                    el.value = text;
                    document.body.appendChild(el);
                    el.select();
                    document.execCommand('copy');
                    document.body.removeChild(el);
                    alert(`Nomor rekening ${bankName} berhasil disalin! (Fallback)`);
                });
            } catch (err) {
                console.error('Could not copy text (Old Browser Fallback): ', err);
                // Fallback copy
                const el = document.createElement('textarea');
                el.value = text;
                document.body.appendChild(el);
                el.select();
                document.execCommand('copy');
                document.body.removeChild(el);
                alert(`Nomor rekening ${bankName} berhasil disalin!`);
            }
        }


        // --- LOGIKA MODAL GALLERY (IMAGE) ---
        const imageModal = document.getElementById("imageModal");
        const modalImage = document.getElementById("modalImage");
        const downloadBtn = document.getElementById("downloadBtn");
        const shareBtn = document.getElementById("shareBtn");
        let currentImageSrc = '';

        function openImageModal(imageSrc) {
            currentImageSrc = imageSrc;
            imageModal.style.display = "block";
            modalImage.src = imageSrc;
            
            // Set link download
            downloadBtn.href = imageSrc;
        }

        function closeImageModal() {
            imageModal.style.display = "none";
            currentImageSrc = '';
        }

        // Logika Share
        shareBtn.addEventListener('click', async () => {
            if (navigator.share) {
                try {
                    // Membuat data share
                    const shareData = {
                        title: 'Foto Pernikahan Adelia & Fani',
                        text: 'Lihat koleksi foto prewedding Adelia dan Fani!',
                        url: currentImageSrc, // Share URL fotonya
                    };

                    // Coba share file/URL
                    await navigator.share(shareData);
                    
                } catch (error) {
                    // Jika share gagal (dibatalkan pengguna atau gagal teknis)
                    if (error.name !== 'AbortError') {
                        console.error('Error sharing:', error);
                        // Fallback ke copy link
                        copyToClipboard(currentImageSrc, 'Foto');
                    }
                }
            } else {
                // Fallback untuk browser yang tidak mendukung Web Share API
                copyToClipboard(currentImageSrc, 'Foto');
            }
        });
        
        // --- LOGIKA MODAL GIFT BARU ---
        const giftModal = document.getElementById("giftModal");

        function openGiftModal() {
            giftModal.style.display = "flex"; // Menggunakan flex agar mudah di tengah
        }

        function closeGiftModal() {
            giftModal.style.display = "none";
        }

        // Tutup modal ketika mengklik di luar konten modal
        window.onclick = function(event) {
            if (event.target == imageModal) {
                closeImageModal();
            }
            if (event.target == giftModal) {
                closeGiftModal();
            }
        }
        
        // --- LOGIKA MUSIC PLAYER BARU ---
        const music = document.getElementById('background-music');
        const musicBtn = document.getElementById('music-btn');
        const musicIcon = document.getElementById('music-icon');
        let isPlaying = false;

        function playMusic() {
            if (music) {
                music.play().then(() => {
                    isPlaying = true;
                    musicIcon.classList.remove('fa-volume-mute');
                    musicIcon.classList.add('fa-volume-up');
                    musicBtn.classList.add('music-playing');
                }).catch(error => {
                    // Tangani jika browser memblokir autoplay
                    console.log("Autoplay musik gagal, membutuhkan interaksi pengguna.", error);
                    isPlaying = false;
                    musicIcon.classList.remove('fa-volume-up');
                    musicIcon.classList.add('fa-volume-mute');
                    musicBtn.classList.remove('music-playing');
                });
            }
        }

        function toggleMusic() {
            if (music) {
                if (isPlaying) {
                    music.pause();
                    isPlaying = false;
                    musicIcon.classList.remove('fa-volume-up');
                    musicIcon.classList.add('fa-volume-mute');
                    musicBtn.classList.remove('music-playing');
                } else {
                    playMusic();
                }
            }
        }

        if (musicBtn) {
            musicBtn.addEventListener('click', toggleMusic);
            // Default icon sebelum cover dibuka adalah mute (agar saat diputar, ikon langsung berubah)
            musicIcon.classList.add('fa-volume-mute');
        }

        // Opsional: Jika Anda ingin musik otomatis diputar segera setelah halaman dimuat (sering diblokir browser)
        // playMusic(); 
        
    </script>
</body>
</html>
