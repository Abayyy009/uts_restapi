<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>SIG Pemetaan Sekolah</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
    
    body {
      font-family: 'Poppins', sans-serif;
      scroll-behavior: smooth;
    }
    
    .school-card {
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      transform-origin: center;
      background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    }
    
    .school-card:hover {
      transform: translateY(-8px) scale(1.02);
      box-shadow: 0 15px 30px rgba(0,0,0,0.12);
      background: linear-gradient(135deg, #ffffff 0%, #f0f4ff 100%);
    }
    
    .feature-card {
      transition: all 0.4s ease;
      background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    }
    
    .feature-card:hover {
      transform: translateY(-10px) scale(1.03);
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    .section {
      scroll-margin-top: 80px;
    }
    
    .navbar {
      position: sticky;
      top: 0;
      z-index: 1000;
      transition: all 0.3s ease;
      background: rgba(5, 150, 105, 0.95);
      backdrop-filter: blur(10px);
    }
    
    .navbar.scrolled {
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      background: rgba(5, 150, 105, 0.98);
    }
    
    .banner {
      background: linear-gradient(135deg, #059669 0%, #0ea5e9 100%);
      position: relative;
      overflow: hidden;
    }
    
    .banner::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
      animation: pulse 15s infinite linear;
    }
    
    @keyframes pulse {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    
    .floating {
      animation: floating 3s ease-in-out infinite;
    }
    
    @keyframes floating {
      0% { transform: translateY(0px); }
      50% { transform: translateY(-15px); }
      100% { transform: translateY(0px); }
    }
    
    .wave-shape {
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      overflow: hidden;
      line-height: 0;
    }
    
    .wave-shape svg {
      position: relative;
      display: block;
      width: calc(100% + 1.3px);
      height: 100px;
    }
    
    .wave-shape .shape-fill {
      fill: #FFFFFF;
    }
    
    .map-container {
      transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    
    .school-marker {
      transition: all 0.3s ease;
    }
    
    .school-marker:hover {
      transform: scale(1.3);
      z-index: 1000;
    }
    
    .btn-primary {
      background: linear-gradient(135deg, #059669 0%, #0ea5e9 100%);
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(5, 150, 105, 0.3);
    }
    
    .btn-primary:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(5, 150, 105, 0.4);
    }
    
    .btn-secondary {
      transition: all 0.3s ease;
    }
    
    .btn-secondary:hover {
      transform: translateY(-3px);
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    
    .loading-spinner {
      animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    
    .fade-in {
      animation: fadeIn 0.8s ease-in;
    }
    
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
    
    .slide-up {
      animation: slideUp 0.8s ease-out;
    }
    
    @keyframes slideUp {
      from { 
        transform: translateY(30px);
        opacity: 0;
      }
      to { 
        transform: translateY(0);
        opacity: 1;
      }
    }
    
    .pulse {
      animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
      0% { box-shadow: 0 0 0 0 rgba(5, 150, 105, 0.7); }
      70% { box-shadow: 0 0 0 10px rgba(5, 150, 105, 0); }
      100% { box-shadow: 0 0 0 0 rgba(5, 150, 105, 0); }
    }
  </style>
</head>
<body class="bg-gray-100">
  <!-- Navbar -->
  <nav id="mainNav" class="navbar bg-green-600 p-4 text-white shadow-lg">
    <div class="container mx-auto flex justify-between items-center">
      <div class="flex items-center space-x-4">
        <div class="flex items-center animate__animated animate__fadeInLeft">
          <i class="fas fa-school text-2xl mr-2"></i>
          <h1 class="text-xl font-bold">SIG Pemetaan Sekolah</h1>
        </div>
      </div>
      <div class="hidden md:flex space-x-6">
        <a href="#home" class="hover:text-green-200 transition transform hover:scale-110">Home</a>
        <a href="#about" class="hover:text-green-200 transition transform hover:scale-110">About</a>
        <a href="#list-sekolah" class="hover:text-green-200 transition transform hover:scale-110">List Sekolah</a>
        <a href="#fitur" class="hover:text-green-200 transition transform hover:scale-110">Fitur</a>
        <a href="#maps" class="hover:text-green-200 transition transform hover:scale-110">Maps</a>
      </div>
      <button id="mobileMenuButton" class="md:hidden text-2xl focus:outline-none">
        <i class="fas fa-bars"></i>
      </button>
    </div>
    
    <!-- Mobile Menu -->
    <div id="mobileMenu" class="md:hidden hidden bg-green-700 p-4 animate__animated animate__fadeInDown">
      <div class="flex flex-col space-y-3">
        <a href="#home" class="hover:text-green-200 transition py-2 border-b border-green-600">Home</a>
        <a href="#about" class="hover:text-green-200 transition py-2 border-b border-green-600">About</a>
        <a href="#list-sekolah" class="hover:text-green-200 transition py-2 border-b border-green-600">List Sekolah</a>
        <a href="#fitur" class="hover:text-green-200 transition py-2 border-b border-green-600">Fitur</a>
        <a href="#maps" class="hover:text-green-200 transition py-2">Maps</a>
      </div>
    </div>
  </nav>

  <!-- Banner/Home Section -->
  <section id="home" class="section banner text-white py-20 text-center relative overflow-hidden">
    <div class="container mx-auto px-4 relative z-10">
      <h2 class="text-4xl md:text-5xl font-bold mb-6 animate__animated animate__fadeInDown">Sistem Informasi Geografis Pemetaan Sekolah</h2>
      <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto animate__animated animate__fadeIn animate__delay-1s">Temukan informasi lengkap tentang sekolah-sekolah di wilayah Kabupaten/Kota dengan mudah dan cepat</p>
      <div class="animate__animated animate__fadeInUp animate__delay-1s">
        <a href="#maps" class="btn-primary px-8 py-4 rounded-full font-medium inline-block">Lihat Peta Sekolah <i class="fas fa-arrow-down ml-2"></i></a>
      </div>
    </div>
    <div class="wave-shape">
      <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
        <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25" class="shape-fill"></path>
        <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5" class="shape-fill"></path>
        <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" class="shape-fill"></path>
      </svg>
    </div>
  </section>

  <!-- About Section -->
  <section id="about" class="section bg-white py-16">
    <div class="container mx-auto px-4">
      <h2 class="text-3xl font-bold text-center mb-12 slide-up">Tentang Aplikasi Ini</h2>
      
      <div class="flex flex-col md:flex-row items-center gap-8">
        <div class="md:w-1/2 slide-up">
          <div class="relative">
            <img src="https://images.unsplash.com/photo-1588072432836-e10032774350?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1472&q=80" 
                 alt="Sekolah" class="rounded-xl shadow-xl w-full h-auto transform rotate-1 hover:rotate-0 transition duration-500">
            <div class="absolute -bottom-4 -right-4 bg-green-500 p-4 rounded-lg shadow-lg text-white animate__animated animate__pulse animate__infinite">
              <i class="fas fa-map-marked-alt text-3xl"></i>
            </div>
          </div>
        </div>
        <div class="md:w-1/2 slide-up" style="animation-delay: 0.2s;">
          <h3 class="text-2xl font-semibold mb-4">Apa itu SIG Pemetaan Sekolah?</h3>
          <p class="text-gray-700 mb-4">
            Sistem Informasi Geografis (SIG) Pemetaan Sekolah adalah platform digital inovatif yang menyediakan informasi spasial 
            lengkap tentang lokasi dan data sekolah-sekolah di seluruh wilayah Kabupaten/Kota. Aplikasi ini dirancang untuk memudahkan 
            berbagai pihak dalam mengakses data pendidikan secara visual dan interaktif.
          </p>
          <p class="text-gray-700 mb-6">
            Dengan teknologi pemetaan modern, pengguna dapat menjelajahi distribusi sekolah, menganalisis pola penyebaran, 
            dan mendapatkan insight berharga untuk pengambilan keputusan terkait pendidikan.
          </p>
          <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg transform hover:translate-x-2 transition duration-300">
            <p class="text-green-700 flex items-start">
              <i class="fas fa-info-circle mr-3 mt-1 text-green-500"></i> 
              <span>Data sekolah kami diperbarui secara real-time dan diverifikasi langsung oleh Dinas Pendidikan setempat untuk memastikan akurasi informasi.</span>
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- List Sekolah Section -->
  <section id="list-sekolah" class="section bg-gray-50 py-16">
    <div class="container mx-auto px-4">
      <h2 class="text-3xl font-bold text-center mb-6 slide-up">Daftar Sekolah</h2>
      <p class="text-center text-gray-600 max-w-2xl mx-auto mb-12 slide-up" style="animation-delay: 0.1s;">Temukan dan filter sekolah berdasarkan kriteria yang Anda butuhkan</p>
      
      <div class="bg-white rounded-xl shadow-xl p-6 slide-up" style="animation-delay: 0.2s;">
        <div class="flex flex-col md:flex-row gap-6 mb-8">
          <div class="w-full md:w-1/3">
            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Sekolah</label>
            <select id="jenisFilter" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
              <option value="semua">Semua Jenis</option>
              <option value="SD">SD</option>
              <option value="SMP">SMP</option>
              <option value="SMA">SMA</option>
              <option value="SMK">SMK</option>
            </select>
          </div>
          <div class="w-full md:w-1/3">
            <label class="block text-sm font-medium text-gray-700 mb-2">Status Sekolah</label>
            <select id="statusFilter" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
              <option value="semua">Semua Status</option>
              <option value="Negeri">Negeri</option>
              <option value="Swasta">Swasta</option>
            </select>
          </div>
          <div class="w-full md:w-1/3">
            <label class="block text-sm font-medium text-gray-700 mb-2">Akreditasi</label>
            <select id="akreditasiFilter" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
              <option value="semua">Semua Akreditasi</option>
              <option value="A">A</option>
              <option value="B">B</option>
              <option value="C">C</option>
              <option value="Tidak Terakreditasi">Tidak Terakreditasi</option>
            </select>
          </div>
        </div>

        <div class="flex flex-col md:flex-row gap-4 mb-6">
          <div class="relative w-full md:flex-1">
            <input type="text" id="searchSchool" placeholder="Cari nama sekolah..." 
                   class="w-full p-3 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
            <i class="fas fa-search absolute left-3 top-4 text-gray-400"></i>
          </div>
          
        </div>

        <div id="schoolList" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <!-- Loading placeholder -->
          <div class="col-span-full text-center py-10">
            <div class="inline-block loading-spinner text-green-500 text-4xl mb-4">
              <i class="fas fa-circle-notch"></i>
            </div>
            <p class="text-gray-500">Memuat data sekolah...</p>
          </div>
        </div>

        <div class="mt-8 flex justify-center">
          <button id="loadMore" class="btn-primary px-8 py-3 rounded-lg hover:bg-green-700 transition hidden">
            Muat Lebih Banyak <i class="fas fa-chevron-down ml-2"></i>
          </button>
        </div>
      </div>
    </div>
  </section>

  <!-- Fitur Section -->
  <section id="fitur" class="section bg-white py-16">
    <div class="container mx-auto px-4">
      <h2 class="text-3xl font-bold text-center mb-6 slide-up">Fitur Unggulan</h2>
      <p class="text-center text-gray-600 max-w-2xl mx-auto mb-12 slide-up" style="animation-delay: 0.1s;">Berbagai kemudahan yang kami tawarkan untuk eksplorasi data sekolah</p>
      
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="feature-card bg-white p-8 rounded-xl shadow-md transition duration-300 slide-up" style="animation-delay: 0.2s;">
          <div class="text-green-500 text-5xl mb-6 flex justify-center">
            <div class="floating">
              <i class="fas fa-map-marked-alt"></i>
            </div>
          </div>
          <h3 class="text-xl font-semibold mb-3 text-center">Pemetaan Interaktif</h3>
          <p class="text-gray-700 text-center">
            Visualisasi lokasi sekolah pada peta digital dengan berbagai layer informasi untuk analisis geospasial yang mendalam.
          </p>
        </div>
        
        <div class="feature-card bg-white p-8 rounded-xl shadow-md transition duration-300 slide-up" style="animation-delay: 0.3s;">
          <div class="text-green-500 text-5xl mb-6 flex justify-center">
            <div class="floating" style="animation-delay: 0.2s;">
              <i class="fas fa-filter"></i>
            </div>
          </div>
          <h3 class="text-xl font-semibold mb-3 text-center">Filter Canggih</h3>
          <p class="text-gray-700 text-center">
            Temukan sekolah dengan cepat menggunakan filter multi-kriteria untuk jenis, status, akreditasi, dan fasilitas.
          </p>
        </div>
        
        <div class="feature-card bg-white p-8 rounded-xl shadow-md transition duration-300 slide-up" style="animation-delay: 0.4s;">
          <div class="text-green-500 text-5xl mb-6 flex justify-center">
            <div class="floating" style="animation-delay: 0.4s;">
              <i class="fas fa-chart-line"></i>
            </div>
          </div>
          <h3 class="text-xl font-semibold mb-3 text-center">Analisis Data</h3>
          <p class="text-gray-700 text-center">
            Dashboard analitik untuk memahami distribusi dan statistik sekolah di wilayah Anda.
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- Maps Section -->
  <section id="maps" class="section bg-gray-50 py-16">
    <div class="container mx-auto px-4">
      <h2 class="text-3xl font-bold text-center mb-6 slide-up">Peta Sekolah</h2>
      <p class="text-center text-gray-600 max-w-2xl mx-auto mb-12 slide-up" style="animation-delay: 0.1s;">Jelajahi lokasi sekolah secara visual dengan peta interaktif kami</p>
      
      <div class="bg-white rounded-xl shadow-xl overflow-hidden slide-up" style="animation-delay: 0.2s;">
        <div class="flex flex-col md:flex-row">
          <!-- Sidebar Filter + List Sekolah -->
          <div class="w-full md:w-1/4 p-4 border-r border-gray-200 overflow-y-auto bg-gray-50">
            <h3 class="text-xl font-semibold mb-4 flex items-center">
              <i class="fas fa-filter mr-2 text-green-500"></i> Filter Peta
            </h3>

            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Sekolah</label>
              <select id="mapJenisFilter" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                <option value="semua">Semua Jenis</option>
                <option value="SD">SD</option>
                <option value="SMP">SMP</option>
                <option value="SMA">SMA</option>
                <option value="SMK">SMK</option>
              </select>
            </div>

            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-2">Status Sekolah</label>
              <select id="mapStatusFilter" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                <option value="semua">Semua Status</option>
                <option value="Negeri">Negeri</option>
                <option value="Swasta">Swasta</option>
              </select>
            </div>

            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700 mb-2">Akreditasi</label>
              <select id="mapAkreditasiFilter" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                <option value="semua">Semua Akreditasi</option>
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C">C</option>
                <option value="Tidak Terakreditasi">Tidak Terakreditasi</option>
              </select>
            </div>

            <button id="applyMapFilter" class="w-full btn-primary py-3 rounded-lg transition mb-6 flex items-center justify-center">
              <i class="fas fa-check-circle mr-2"></i> Terapkan Filter
            </button>

            <button id="resetMap" class="w-full btn-secondary bg-gray-100 text-gray-700 py-3 rounded-lg hover:bg-gray-200 transition mb-6 flex items-center justify-center">
              <i class="fas fa-redo mr-2"></i> Reset Peta
            </button>

            <h4 class="text-lg font-semibold mb-3 flex items-center">
              <i class="fas fa-list-ul mr-2 text-green-500"></i> Daftar Sekolah
            </h4>
            <div id="mapSchoolList" class="space-y-3 overflow-y-auto max-h-[400px] pr-2">
              <!-- Loading placeholder -->
              <div class="text-center py-10">
                <div class="inline-block loading-spinner text-green-500 text-xl mb-3">
                  <i class="fas fa-circle-notch"></i>
                </div>
                <p class="text-gray-500 text-sm">Memuat daftar sekolah...</p>
              </div>
            </div>
          </div>

          <!-- Peta -->
          <div class="w-full md:w-3/4 h-96 md:h-[600px] relative">
            <div id="map" class="w-full h-full"></div>
            
            <!-- Legenda -->
            <div class="absolute bottom-4 right-4 bg-white p-3 rounded-lg shadow-md z-[1000]">
              <h5 class="font-semibold mb-2 text-sm">Legenda:</h5>
              <div class="flex items-center mb-1">
                <div class="w-4 h-4 rounded-full bg-blue-500 mr-2"></div>
                <span class="text-xs">SD</span>
              </div>
              <div class="flex items-center mb-1">
                <div class="w-4 h-4 rounded-full bg-green-500 mr-2"></div>
                <span class="text-xs">SMP</span>
              </div>
              <div class="flex items-center mb-1">
                <div class="w-4 h-4 rounded-full bg-red-500 mr-2"></div>
                <span class="text-xs">SMA</span>
              </div>
              <div class="flex items-center">
                <div class="w-4 h-4 rounded-full bg-purple-500 mr-2"></div>
                <span class="text-xs">SMK</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-gray-800 text-white py-12 relative">
    <div class="container mx-auto px-4">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <div class="animate__animated animate__fadeIn">
          <h3 class="text-xl font-bold mb-4 flex items-center">
            <i class="fas fa-school mr-2 text-green-400"></i> SIG Pemetaan Sekolah
          </h3>
          <p class="text-gray-400">
            Platform informasi geografis terdepan untuk pemetaan sekolah di seluruh Indonesia.
          </p>
          <div class="mt-4">
            <button class="btn-primary px-6 py-2 rounded-full text-sm flex items-center">
              <i class="fas fa-download mr-2"></i> Unduh Aplikasi
            </button>
          </div>
        </div>
        
        <div class="animate__animated animate__fadeIn" style="animation-delay: 0.1s;">
          <h4 class="font-semibold mb-4 text-lg">Tautan Cepat</h4>
          <ul class="space-y-3">
            <li><a href="#home" class="text-gray-400 hover:text-white transition flex items-center"><i class="fas fa-chevron-right mr-2 text-xs text-green-400"></i> Home</a></li>
            <li><a href="#about" class="text-gray-400 hover:text-white transition flex items-center"><i class="fas fa-chevron-right mr-2 text-xs text-green-400"></i> About</a></li>
            <li><a href="#list-sekolah" class="text-gray-400 hover:text-white transition flex items-center"><i class="fas fa-chevron-right mr-2 text-xs text-green-400"></i> List Sekolah</a></li>
            <li><a href="#maps" class="text-gray-400 hover:text-white transition flex items-center"><i class="fas fa-chevron-right mr-2 text-xs text-green-400"></i> Peta Sekolah</a></li>
          </ul>
        </div>
        
        <div class="animate__animated animate__fadeIn" style="animation-delay: 0.2s;">
          <h4 class="font-semibold mb-4 text-lg">Kontak Kami</h4>
          <ul class="space-y-3 text-gray-400">
            <li class="flex items-start">
              <i class="fas fa-envelope mr-3 mt-1 text-green-400"></i>
              <span>info@sigsekolah.id</span>
            </li>
            <li class="flex items-start">
              <i class="fas fa-phone mr-3 mt-1 text-green-400"></i>
              <span>(021) 1234 5678</span>
            </li>
            <li class="flex items-start">
              <i class="fas fa-map-marker-alt mr-3 mt-1 text-green-400"></i>
              <span>Gedung Pendidikan Lt.5<br>Jl. Pendidikan No.1, Jakarta</span>
            </li>
          </ul>
        </div>
        
        <div class="animate__animated animate__fadeIn" style="animation-delay: 0.3s;">
          <h4 class="font-semibold mb-4 text-lg">Sosial Media</h4>
          <div class="flex space-x-4 mb-6">
            <a href="#" class="text-gray-400 hover:text-white text-2xl transition transform hover:scale-125">
              <i class="fab fa-facebook"></i>
            </a>
            <a href="#" class="text-gray-400 hover:text-white text-2xl transition transform hover:scale-125">
              <i class="fab fa-twitter"></i>
            </a>
            <a href="#" class="text-gray-400 hover:text-white text-2xl transition transform hover:scale-125">
              <i class="fab fa-instagram"></i>
            </a>
            <a href="#" class="text-gray-400 hover:text-white text-2xl transition transform hover:scale-125">
              <i class="fab fa-youtube"></i>
            </a>
          </div>
          <h4 class="font-semibold mb-3 text-lg">Newsletter</h4>
          <div class="flex">
            <input type="email" placeholder="Email Anda" class="p-2 rounded-l-lg focus:outline-none text-gray-800 w-full">
            <button class="bg-green-500 hover:bg-green-600 px-4 rounded-r-lg transition">
              <i class="fas fa-paper-plane"></i>
            </button>
          </div>
        </div>
      </div>
      
      <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400 animate__animated animate__fadeIn">
        <p>&copy; 2023 SIG Pemetaan Sekolah. All rights reserved.</p>
      </div>
    </div>
  </footer>

  <script>
  let map;
  let schoolMarkers = [];
  let allSchools = [];
  let displayedSchools = 10;

  function getMarkerColor(jenisSekolah) {
    switch (jenisSekolah) {
      case 'SD': return 'blue';
      case 'SMP': return 'green';
      case 'SMA': return 'red';
      case 'SMK': return 'purple';
      default: return 'orange';
    }
  }

  function createSchoolMarker(school) {
    const marker = L.marker([school.latitude, school.longitude], {
      icon: L.divIcon({
        className: 'school-marker',
        html: `<div style="background-color: ${getMarkerColor(school.jenis_sekolah)}" class="w-6 h-6 rounded-full flex items-center justify-center text-white font-bold">${school.jenis_sekolah.charAt(0)}</div>`,
        iconSize: [24, 24]
      }),
      schoolData: school
    });

    marker.bindPopup(`
      <div class="w-64">
        <h3 class="font-bold text-lg mb-1">${school.nama}</h3>
        <div class="flex items-center mb-1">
          <i class="fas fa-tag mr-2 text-gray-500"></i>
          <span>${school.jenis_sekolah} ${school.status_sekolah}</span>
        </div>
        <div class="flex items-center mb-1">
          <i class="fas fa-star mr-2 text-yellow-500"></i>
          <span>Akreditasi: ${school.akreditasi}</span>
        </div>
        <div class="flex items-start mb-1">
          <i class="fas fa-map-marker-alt mr-2 mt-1 text-gray-500"></i>
          <span>${school.alamat}</span>
        </div>
      </div>
    `);
    return marker;
  }

  async function loadSchools() {
    try {
      const response = await fetch('http://127.0.0.1:8000/api/sekolah');
      const json = await response.json();
      allSchools = json.data.data;

      displaySchools(allSchools);
      updateMapWithFilteredSchools(allSchools);
      displayMapSidebarSchools(allSchools);

    } catch (error) {
      console.error(error);
    }
  }

  function displaySchools(schools) {
  const list = document.getElementById('schoolList');
  const filtered = schools.filter(s => s.latitude && s.longitude);
  if (filtered.length === 0) {
    list.innerHTML = `<p class="text-center py-8 text-gray-500">Tidak ada sekolah ditemukan.</p>`;
    return;
  }

  const toShow = filtered.slice(0, displayedSchools);
  list.innerHTML = toShow.map(s =>
    `<div class="school-card bg-white p-4 rounded-lg shadow mb-4 cursor-pointer">
      <h4 class="font-bold">${s.nama}</h4>
      <p class="text-sm text-gray-600">${s.jenis_sekolah} - ${s.status_sekolah} - Akreditasi ${s.akreditasi}</p>
      <button class="show-on-map mt-2 text-green-600 text-sm hover:underline" data-lat="${s.latitude}" data-lng="${s.longitude}">Tampilkan di Peta</button>
    </div>`
  ).join('');

  document.querySelectorAll('.show-on-map').forEach(btn => {
    btn.addEventListener('click', () => {
      const lat = parseFloat(btn.dataset.lat);
      const lng = parseFloat(btn.dataset.lng);
      map.flyTo([lat, lng], 16);
      const marker = schoolMarkers.find(m => m.getLatLng().lat === lat && m.getLatLng().lng === lng);
      if (marker) marker.openPopup();

      // 👇 Scroll otomatis ke section #maps
      document.getElementById('maps').scrollIntoView({ behavior: 'smooth' });
    });
  });
}


  function displayMapSidebarSchools(schools) {
    const container = document.getElementById('mapSchoolList');
    const filtered = schools.filter(s => s.latitude && s.longitude);
    if (filtered.length === 0) {
      container.innerHTML = `<p class="text-gray-500 py-4">Tidak ada sekolah ditemukan.</p>`;
      return;
    }

    container.innerHTML = filtered.map(s =>
      `<div class="school-card bg-white p-3 mb-2 rounded shadow cursor-pointer">
        <h4 class="font-bold text-sm">${s.nama}</h4>
        <p class="text-xs text-gray-500">${s.jenis_sekolah} - ${s.status_sekolah} - Akreditasi ${s.akreditasi}</p>
      </div>`
    ).join('');

    container.querySelectorAll('.school-card').forEach((card, i) => {
      card.addEventListener('click', () => {
        const s = filtered[i];
        map.flyTo([s.latitude, s.longitude], 16);
        const marker = schoolMarkers.find(m => m.getLatLng().lat === s.latitude && m.getLatLng().lng === s.longitude);
        if (marker) marker.openPopup();
      });
    });
  }

  function updateMapWithFilteredSchools(schools) {
    const filtered = schools.filter(s => s.latitude && s.longitude);
    schoolMarkers.forEach(m => map.removeLayer(m));
    schoolMarkers = [];
    filtered.forEach(s => {
      const marker = createSchoolMarker(s);
      marker.addTo(map);
      schoolMarkers.push(marker);
    });
    if (filtered.length > 0) {
      const bounds = L.latLngBounds(filtered.map(s => [s.latitude, s.longitude]));
      map.fitBounds(bounds);
    }
  }

  function filterMapSchools() {
    const jenis = document.getElementById('mapJenisFilter').value;
    const status = document.getElementById('mapStatusFilter').value;
    const akreditasi = document.getElementById('mapAkreditasiFilter').value;

    let filtered = allSchools;
    if (jenis !== 'semua') filtered = filtered.filter(s => s.jenis_sekolah === jenis);
    if (status !== 'semua') filtered = filtered.filter(s => s.status_sekolah === status);
    if (akreditasi !== 'semua') filtered = filtered.filter(s => s.akreditasi === akreditasi);

    updateMapWithFilteredSchools(filtered);
    displayMapSidebarSchools(filtered);
  }

  document.addEventListener('DOMContentLoaded', () => {
  map = L.map('map').setView([-6.5, 106.8], 12);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap'
  }).addTo(map);


  loadSchools();

    document.getElementById('applyMapFilter').addEventListener('click', filterMapSchools);
    document.getElementById('resetMap').addEventListener('click', () => {
      updateMapWithFilteredSchools(allSchools);
      displayMapSidebarSchools(allSchools);
    });

  // Event untuk Live Filter & Search
  document.getElementById('jenisFilter').addEventListener('change', filterAndDisplaySchools);
  document.getElementById('statusFilter').addEventListener('change', filterAndDisplaySchools);
  document.getElementById('akreditasiFilter').addEventListener('change', filterAndDisplaySchools);
  document.getElementById('searchSchool').addEventListener('input', filterAndDisplaySchools);

  // Tombol Reset (kembali ke semua sekolah)
  document.getElementById('resetFilterButton').addEventListener('click', () => {
    document.getElementById('jenisFilter').value = 'semua';
    document.getElementById('statusFilter').value = 'semua';
    document.getElementById('akreditasiFilter').value = 'semua';
    document.getElementById('searchSchool').value = '';
    displaySchools(allSchools);
  });
});

  function filterAndDisplaySchools() {
  const jenis = document.getElementById('jenisFilter').value;
  const status = document.getElementById('statusFilter').value;
  const akreditasi = document.getElementById('akreditasiFilter').value;
  const searchTerm = document.getElementById('searchSchool').value.toLowerCase();

  let filtered = allSchools;

  if (jenis !== 'semua') {
    filtered = filtered.filter(s => s.jenis_sekolah === jenis);
  }
  if (status !== 'semua') {
    filtered = filtered.filter(s => s.status_sekolah === status);
  }
  if (akreditasi !== 'semua') {
    filtered = filtered.filter(s => s.akreditasi === akreditasi);
  }
  if (searchTerm) {
    filtered = filtered.filter(s => 
      s.nama.toLowerCase().includes(searchTerm) ||
      (s.alamat && s.alamat.toLowerCase().includes(searchTerm))
    );
  }

  displaySchools(filtered);
}



</script>

</body>
</html>