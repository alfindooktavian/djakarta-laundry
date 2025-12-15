<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Djakarta Laundry</title>
  <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      scroll-behavior: smooth;
    }

    html {
      scroll-padding-top: 80px;
    }

    main {
      background: linear-gradient(to bottom, #ffffff 0%, #ebf4ff 100%);
    }
  </style>
</head>

<body class="text-gray-800">

  <!-- Navbar -->
  <header class="fixed top-0 left-0 w-full bg-white shadow-sm z-50">
    <div class="max-w-7xl mx-auto flex justify-between items-center px-6 py-7">
      <div class="text-blue-500 font-bold text-xl">Djakarta Laundry</div>
      <nav class="hidden md:flex space-x-8 font-medium">
        <a href="#home" class=" hover:text-blue-700">Beranda</a>
        <a href="#services" class="hover:text-blue-700">Layanan</a>
        <a href="#locations" class="hover:text-blue-700">Lokasi</a>
        <a href="#contact" class="hover:text-blue-700">Kontak</a>
      </nav>
    </div>
  </header>

  <!-- 🌤️ Semua konten utama dalam gradasi -->
  <main class="pt-20">

    <!-- Hero Section -->
    <section id="home" class="py-12">
      <div class="max-w-7xl mx-auto px-6 md:flex md:items-center md:justify-between">
        <div class="max-w-lg">
          <h1 class="text-4xl font-bold mt-4 mb-3 leading-tight">
            Laundry Jadi Mudah,<br> Pakaian Jadi Bersih
          </h1>
          <p class="text-gray-600 mb-6">
            Djakarta Laundry siap mencuci, mengeringkan, dan melipat pakaian Anda dengan harga terjangkau.
          </p>
          <a href="#contact"
            class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700">
            Hubungi Kami
          </a>
          <!-- <div class="flex items-center gap-6 mt-8 text-gray-600">
            <div>
              <span class="text-2xl font-bold text-blue-600">18M+</span><br> Cucian telah diselesaikan
            </div>
          </div> -->
        </div>
        <div class="mt-10 md:mt-0 relative">
          <div class="absolute -top-6 -right-6 w-40 h-40 bg-blue-100 rounded-full -z-10"></div>
          <img src='images/favicon.png' alt="Mesin Cuci" class="w-72 mx-auto">
        </div>
      </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-12 relative overflow-hidden">
      <div class="max-w-7xl mx-auto px-6 text-center relative">
        <h2 class="text-3xl font-bold text-gray-800 mb-10">Layanan Kami</h2>

        <div class="relative overflow-hidden pb-4">
          <!-- Fade kiri -->
          <div
            class="absolute left-0 top-0 w-24 h-full bg-gradient-to-r from-gray-50 via-gray-50/80 to-transparent z-10 pointer-events-none">
          </div>
          <!-- Fade kanan -->
          <div
            class="absolute right-0 top-0 w-24 h-full bg-gradient-to-l from-gray-50 via-gray-50/80 to-transparent z-10 pointer-events-none">
          </div>

          <!-- Container scroll -->
          <div class="scroll-wrapper">
            <div class="scroll-content flex gap-6 w-max mx-auto py-2">
              <!-- Set pertama -->
              @foreach($services as $service)
                <div
                  class="inline-block min-w-[280px] bg-white p-6 rounded-2xl shadow-md transform transition-all duration-500 hover:scale-105">
                  <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $service->name }}</h3>
                  <p class="text-blue-600 font-bold mb-2">
                    Rp {{ number_format($service->price, 0, ',', '.') }}
                    <span class="text-gray-500 text-sm">/ {{ $service->type }}</span>
                  </p>
                  <p class="text-gray-500 text-sm">Layanan cepat & terpercaya.</p>
                </div>
              @endforeach

              <!-- Set kedua (duplikat agar loop tanpa jeda) -->
              @foreach($services as $service)
                <div
                  class="inline-block min-w-[280px] bg-white p-6 rounded-2xl shadow-md transform transition-all duration-500 hover:scale-105">
                  <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $service->name }}</h3>
                  <p class="text-blue-600 font-bold mb-2">
                    Rp {{ number_format($service->price, 0, ',', '.') }}
                    <span class="text-gray-500 text-sm">/ {{ $service->type }}</span>
                  </p>
                  <p class="text-gray-500 text-sm">Layanan cepat & terpercaya.</p>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>

      <style>
        /* Loop tanpa jeda */
        @keyframes scrollLoop {
          0% {
            transform: translateX(0);
          }

          100% {
            transform: translateX(-50%);
          }
        }

        .scroll-wrapper {
          display: flex;
          width: max-content;
          animation: scrollLoop 40s linear infinite;
        }

        .scroll-wrapper:hover {
          animation-play-state: paused;
        }
      </style>
    </section>

    <!-- Locations Section -->
    <section id="locations" class="py-12">
      <div class="max-w-7xl mx-auto px-6 text-center">
        <h2 class="text-blue-600 text-sm font-semibold mb-3 tracking-wide uppercase">Lokasi Kami</h2>
        <h3 class="text-3xl font-bold text-gray-800 mb-4">Temukan Djakarta Laundry</h3>
        <p class="text-gray-600 max-w-2xl mx-auto mb-12">
          Kunjungi cabang kami dan nikmati layanan laundry cepat, bersih, dan terpercaya setiap harinya.
        </p>

        <div
          class="relative max-w-5xl mx-auto bg-white shadow-lg rounded-2xl overflow-hidden transform transition-all duration-500">
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d252947.05851784348!2d111.11358459453125!3d-7.863873000000003!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e799fb155c55a63%3A0xed53935bc3a26cdc!2sDjakarta%20Laundry!5e0!3m2!1sid!2sid!4v1762741652512!5m2!1sid!2sid"
            width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade">
          </iframe>
          <div
            class="absolute bottom-4 left-1/2 -translate-x-1/2 bg-white/90 backdrop-blur-sm px-6 py-3 rounded-full shadow-md flex items-center gap-2 text-gray-700">
            <span class="text-blue-600 text-xl">📍</span>
            <span class="font-medium">Djakarta Laundry — Ponorogo, Jawa Timur</span>
          </div>
        </div>
      </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-12">
      <div class="max-w-5xl mx-auto px-6 text-center">
        <h3 class="text-3xl md:text-4xl font-bold mb-6 text-gray-800">Hubungi Kami</h3>
        <p class="text-gray-600 mb-10 max-w-2xl mx-auto leading-relaxed">
          Punya pertanyaan atau butuh bantuan?
          Tim kami siap membantu dengan cepat melalui WhatsApp!
        </p>
        <a href="https://wa.me/6281235536861?text=Halo%20Djakarta%20Laundry!%20Saya%20ingin%20bertanya%20tentang%20layanan%20Anda."
          target="_blank"
          class="inline-flex items-center gap-3 bg-green-500 hover:bg-green-600 text-white px-8 py-3 rounded-full font-semibold text-lg transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
          <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-6 h-6">
            <path
              d="M20.52 3.48A11.8 11.8 0 0 0 12 0C5.37 0 0 5.37 0 12c0 2.1.54 4.16 1.57 5.97L0 24l6.2-1.63A11.94 11.94 0 0 0 12 24c6.63 0 12-5.37 12-12 0-3.2-1.25-6.22-3.48-8.52ZM12 22a10 10 0 0 1-5.1-1.4l-.36-.2-3.68.96.98-3.59-.23-.37A9.93 9.93 0 0 1 2 12c0-5.5 4.5-10 10-10a9.9 9.9 0 0 1 7.07 2.93A9.9 9.9 0 0 1 22 12c0 5.5-4.5 10-10 10Z" />
          </svg>
          Chat via WhatsApp
        </a>
      </div>
    </section>

  </main>

  <!-- Footer -->
  <footer class="bg-blue-600 text-white mt-0 py-12">
    <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-3 gap-6 justify-items">
      <div>
        <h4 class="font-bold text-lg mb-2">Djakarta Laundry</h4>
        <p class="text-sm text-blue-100">Laundry Jadi Mudah, Pakaian Jadi Bersih</p>
      </div>

      <div>
        <h5 class="font-semibold mb-2">Tentang Kami</h5>
        <ul class="space-y-1 text-blue-100 text-sm">
          <li><a href="#home" class="hover:text-white">Beranda</a></li>
          <li><a href="#services" class="hover:text-white">Layanan</a></li>
          <li><a href="#services" class="hover:text-white">Harga</a></li>
        </ul>
      </div>

      <div>
        <h5 class="font-semibold mb-2">Dukung</h5>
        <ul class="space-y-1 text-blue-100 text-sm">
          <li><a href="#contact" class="hover:text-white">Kontak</a></li>
        </ul>
      </div>
    </div>

    <div class="text-center text-blue-200 text-sm mt-8 border-t border-blue-500/30 pt-4">
      © 2025 Djakarta Laundry. All rights reserved.
    </div>
  </footer>

  <script>
    // Script untuk scroll halus di semua anchor link (#id)
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        // Cegah perilaku default
        e.preventDefault();

        // Ambil target ID dari href
        const targetID = this.getAttribute('href');
        const targetElement = document.querySelector(targetID);

        // Jika target ada, lakukan scroll dengan animasi
        if (targetElement) {
          window.scrollTo({
            top: targetElement.offsetTop - 80, // offset navbar
            behavior: 'smooth'
          });
        }
      });
    });
  </script>

</body>

</html>