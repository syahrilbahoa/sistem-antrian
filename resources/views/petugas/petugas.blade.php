<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Petugas - Panggil Antrian</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js untuk grafik laporan -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Custom styles */
        .dashboard-bg {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            min-height: 100vh;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .btn-call {
            transition: all 0.3s ease;
            animation: pulse-blue 2s infinite;
        }
        
        .btn-skip {
            transition: all 0.3s ease;
            animation: pulse-red 1.5s infinite;
        }
        
        .btn-report {
            transition: all 0.3s ease;
        }
        
        @keyframes pulse-blue {
            0%, 100% { box-shadow: 0 0 5px #3b82f6; }
            50% { box-shadow: 0 0 20px #3b82f6, 0 0 30px #2563eb; }
        }
        
        @keyframes pulse-red {
            0%, 100% { box-shadow: 0 0 5px #ef4444; }
            50% { box-shadow: 0 0 15px #ef4444, 0 0 25px #dc2626; }
        }
        
        .ticket-called {
            animation: slide-out 0.5s ease forwards;
        }
        
        @keyframes slide-out {
            0% { transform: translateX(0); opacity: 1; }
            100% { transform: translateX(100px); opacity: 0; }
        }
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        
        .modal-content {
            animation: modal-appear 0.3s ease;
        }
        
        @keyframes modal-appear {
            0% { transform: scale(0.8); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
        
        .tab-active {
            background-color: #3b82f6;
            color: white;
        }
        
        .dropdown-enter {
            animation: dropdown-fade 0.2s ease;
        }
        
        @keyframes dropdown-fade {
            0% { opacity: 0; transform: translateY(-10px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        
        /* Tambahan untuk dropdown yang lebih baik */
        .dropdown-container {
            position: relative;
            display: inline-block;
        }
        
        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            min-width: 200px;
            z-index: 100;
        }
        
        .dropdown-menu.show {
            display: block;
        }
    </style>
</head>
<body class="dashboard-bg">
    <div class="container mx-auto px-4 py-6">
        <!-- Header -->
        <header class="flex flex-col md:flex-row justify-between items-center mb-8">
            <div class="flex items-center mb-4 md:mb-0">
                <div class="bg-white p-3 rounded-full mr-4">
                    <i class="fas fa-user-md text-blue-600 text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-white">DASHBOARD PETUGAS</h1>
                    <p class="text-blue-200">Sistem Panggilan Antrian Pasien</p>
                </div>
            </div>
            
            <div class="flex items-center space-x-4">
                <div class="text-right">
                    <p class="text-white font-semibold">Petugas: <span class="text-blue-200">Syahril Bahoa</span></p>
                    <p class="text-blue-200 text-sm">Loket: <span class="font-bold">Pendaftaran 1</span></p>
                </div>
                <div class="dropdown-container">
                    <button id="userMenuBtn" class="bg-white/20 p-3 rounded-full cursor-pointer hover:bg-white/30 transition focus:outline-none">
                        <i class="fas fa-user-circle text-white text-2xl"></i>
                    </button>
                    <!-- Dropdown Logout -->
                    <div id="userDropdown" class="dropdown-menu mt-2 w-48 bg-white rounded-lg shadow-xl overflow-hidden z-50 dropdown-enter">
                        <div class="px-4 py-3 border-b">
                            <p class="text-sm font-medium text-gray-900">Syahril Bahoa</p>
                            <p class="text-xs text-gray-500">Petugas Pendaftaran</p>
                        </div>
                        <button id="btnLogout" class="w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50 flex items-center focus:outline-none">
                            <i class="fas fa-sign-out-alt mr-3"></i> Keluar dari Sistem
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Dashboard -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Kolom 1: Antrian saat ini & kontrol -->
            <div class="lg:col-span-2">
                <!-- Status antrian -->
                <div class="glass-card rounded-2xl p-6 mb-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-white">
                            <i class="fas fa-list-ol mr-3"></i>STATUS ANTRIAN
                        </h2>
                        <div class="text-right">
                            <p class="text-white text-lg">Tanggal: <span class="font-bold" id="currentDate"></span></p>
                            <p class="text-blue-200">Waktu: <span class="font-bold" id="currentTime"></span></p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                        <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-xl p-4 text-center">
                            <p class="text-blue-200 text-sm mb-1">SEDANG DIPANGGIL</p>
                            <p class="text-white text-4xl font-bold" id="currentCalled">A-025</p>
                            <div class="mt-2">
                                <span class="text-green-300 text-sm"><i class="fas fa-clock mr-1"></i>2 menit lalu</span>
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-r from-green-600 to-green-800 rounded-xl p-4 text-center">
                            <p class="text-green-200 text-sm mb-1">ANTRIAN BERIKUTNYA</p>
                            <p class="text-white text-4xl font-bold" id="nextInLine">A-026</p>
                            <p class="text-green-200 text-sm mt-2">Menunggu panggilan</p>
                        </div>
                        
                        <div class="bg-gradient-to-r from-purple-600 to-purple-800 rounded-xl p-4 text-center">
                            <p class="text-purple-200 text-sm mb-1">TOTAL ANTRIAN HARI INI</p>
                            <p class="text-white text-4xl font-bold" id="totalToday">142</p>
                            <p class="text-purple-200 text-sm mt-2">+5 dari kemarin</p>
                        </div>
                    </div>
                    
                    <!-- Tombol kontrol -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <button id="btnCall" class="btn-call bg-gradient-to-r from-green-500 to-green-700 hover:from-green-600 hover:to-green-800 text-white text-2xl font-bold py-6 px-4 rounded-xl flex flex-col items-center justify-center">
                            <i class="fas fa-bullhorn text-4xl mb-3"></i>
                            PANGGIL ANTRIAN BERIKUTNYA
                            <span class="text-green-100 text-lg mt-2" id="nextNumberDisplay">(A-026)</span>
                        </button>
                        
                        <button id="btnSkip" class="btn-skip bg-gradient-to-r from-red-500 to-red-700 hover:from-red-600 hover:to-red-800 text-white text-2xl font-bold py-6 px-4 rounded-xl flex flex-col items-center justify-center">
                            <i class="fas fa-forward text-4xl mb-3"></i>
                            LEWATI ANTRIAN INI
                            <span class="text-red-100 text-lg mt-2">(Tandai tidak hadir)</span>
                        </button>
                    </div>
                    
                    <div class="mt-6 text-center">
                        <p class="text-blue-200">
                            <i class="fas fa-info-circle mr-2"></i>
                            Tekan tombol untuk memanggil atau melewatkan antrian
                        </p>
                    </div>
                </div>
                
                <!-- Daftar antrian -->
                <div class="glass-card rounded-2xl p-6">
                    <h2 class="text-2xl font-bold text-white mb-6">
                        <i class="fas fa-users mr-3"></i>DAFTAR ANTRIAN MENUNGGU
                    </h2>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-white">
                            <thead>
                                <tr class="border-b border-white/20">
                                    <th class="py-3 px-4 text-left">No. Antrian</th>
                                    <th class="py-3 px-4 text-left">Waktu Ambil</th>
                                    <th class="py-3 px-4 text-left">Status</th>
                                    <th class="py-3 px-4 text-left">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="queueList">
                                <!-- Data antrian akan diisi oleh JavaScript -->
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-6 text-center">
                        <button id="btnRefresh" class="bg-blue-600 hover:bg-blue-700 text-white py-3 px-6 rounded-lg flex items-center justify-center mx-auto">
                            <i class="fas fa-sync-alt mr-3"></i> REFRESH DAFTAR ANTRIAN
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Kolom 2: Panel laporan & kontrol -->
            <div class="space-y-6">
                <!-- Panel laporan cepat -->
                <div class="glass-card rounded-2xl p-6">
                    <h2 class="text-2xl font-bold text-white mb-6">
                        <i class="fas fa-chart-bar mr-3"></i>LAPORAN CEPAT
                    </h2>
                    
                    <div class="space-y-4">
                        <div class="bg-blue-900/30 rounded-xl p-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-blue-200 text-sm">Terlayani Hari Ini</p>
                                    <p class="text-white text-3xl font-bold">24</p>
                                </div>
                                <div class="bg-blue-500 p-3 rounded-full">
                                    <i class="fas fa-user-check text-white text-xl"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-green-900/30 rounded-xl p-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-green-200 text-sm">Antrian Tertinggal</p>
                                    <p class="text-white text-3xl font-bold">3</p>
                                </div>
                                <div class="bg-green-500 p-3 rounded-full">
                                    <i class="fas fa-user-times text-white text-xl"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-red-900/30 rounded-xl p-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-red-200 text-sm">Waktu Tunggu Rata-rata</p>
                                    <p class="text-white text-3xl font-bold">15m</p>
                                </div>
                                <div class="bg-red-500 p-3 rounded-full">
                                    <i class="fas fa-clock text-white text-xl"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-amber-900/30 rounded-xl p-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-amber-200 text-sm">Waktu Layanan Rata-rata</p>
                                    <p class="text-white text-3xl font-bold">8m</p>
                                </div>
                                <div class="bg-amber-500 p-3 rounded-full">
                                    <i class="fas fa-stopwatch text-white text-xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8">
                        <button id="btnMonthlyReport" class="btn-report w-full bg-gradient-to-r from-amber-500 to-amber-700 hover:from-amber-600 hover:to-amber-800 text-white text-lg font-bold py-4 px-4 rounded-xl flex items-center justify-center">
                            <i class="fas fa-file-alt text-2xl mr-3"></i>
                            LAPORAN BULANAN
                        </button>
                        
                        <button id="btnSkipReport" class="btn-report w-full bg-gradient-to-r from-red-500 to-red-700 hover:from-red-600 hover:to-red-800 text-white text-lg font-bold py-4 px-4 rounded-xl flex items-center justify-center mt-4">
                            <i class="fas fa-exclamation-triangle text-2xl mr-3"></i>
                            LAPORAN ANTRIAN TERLEWAT
                        </button>
                    </div>
                </div>
                
                <!-- Riwayat panggilan terakhir -->
                <div class="glass-card rounded-2xl p-6">
                    <h2 class="text-2xl font-bold text-white mb-6">
                        <i class="fas fa-history mr-3"></i>RIWAYAT PANGGILAN
                    </h2>
                    
                    <div class="space-y-4 max-h-80 overflow-y-auto pr-2" id="callHistory">
                        <!-- Riwayat panggilan akan diisi oleh JavaScript -->
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <footer class="mt-8 text-center text-blue-200">
            <p class="text-sm">
                <i class="fas fa-hospital mr-2"></i>Klinik Anugrah Farma - Dashboard Petugas Antrian v1.0
            </p>
            <p class="text-xs mt-1 text-blue-300">© 2025 Klinik Anugrah Farma. Hak Cipta Dilindungi.</p>
        </footer>
    </div>

    <!-- Modal Laporan Bulanan -->
    <div id="monthlyReportModal" class="modal">
        <div class="modal-content bg-white rounded-2xl w-11/12 md:w-3/4 lg:w-2/3 p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-file-alt text-blue-600 mr-3"></i>LAPORAN BULANAN ANTRIAN
                </h3>
                <button id="closeMonthlyModal" class="text-gray-500 hover:text-gray-700 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="mb-6">
                <div class="flex flex-wrap gap-4 mb-6">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-gray-700 mb-2">Pilih Bulan</label>
                        <select id="selectMonth" class="w-full p-3 border border-gray-300 rounded-lg">
                            <option value="0">Januari 2025</option>
                            <option value="1">Februari 2025</option>
                            <option value="2">Maret 2025</option>
                            <option value="3" selected>April 2025</option>
                            <option value="4">Mei 2025</option>
                        </select>
                    </div>
                    
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-gray-700 mb-2">Loket</label>
                        <select id="selectCounter" class="w-full p-3 border border-gray-300 rounded-lg">
                            <option value="all">Semua Loket</option>
                            <option value="1" selected>Loket Pendaftaran 1</option>
                            <option value="2">Loket Pendaftaran 2</option>
                            <option value="3">Loket Farmasi</option>
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <p class="text-blue-700 text-sm">Total Antrian</p>
                        <p class="text-3xl font-bold text-blue-800">842</p>
                    </div>
                    
                    <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                        <p class="text-green-700 text-sm">Terlayani</p>
                        <p class="text-3xl font-bold text-green-800">798</p>
                    </div>
                    
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                        <p class="text-red-700 text-sm">Terlewatkan</p>
                        <p class="text-3xl font-bold text-red-800">44</p>
                    </div>
                    
                    <div class="bg-purple-50 border border-purple-200 rounded-xl p-4">
                        <p class="text-purple-700 text-sm">Waktu Rata-rata</p>
                        <p class="text-3xl font-bold text-purple-800">12m</p>
                    </div>
                </div>
                
                <!-- Grafik -->
                <div class="mb-8">
                    <h4 class="text-xl font-bold text-gray-800 mb-4">Statistik Harian (April 2025)</h4>
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <canvas id="monthlyChart" height="250"></canvas>
                    </div>
                </div>
                
                <!-- Tabel detail -->
                <div>
                    <h4 class="text-xl font-bold text-gray-800 mb-4">Detail Laporan</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="py-3 px-4 text-left border border-gray-300">Tanggal</th>
                                    <th class="py-3 px-4 text-left border border-gray-300">Total</th>
                                    <th class="py-3 px-4 text-left border border-gray-300">Terlayani</th>
                                    <th class="py-3 px-4 text-left border border-gray-300">Terlewat</th>
                                    <th class="py-3 px-4 text-left border border-gray-300">Rata-rata</th>
                                </tr>
                            </thead>
                            <tbody id="monthlyTableBody">
                                <!-- Data akan diisi oleh JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end space-x-4 mt-8">
                <button id="printMonthlyReport" class="bg-blue-600 hover:bg-blue-700 text-white py-3 px-6 rounded-lg flex items-center">
                    <i class="fas fa-print mr-2"></i> Cetak Laporan
                </button>
                <button id="exportMonthlyReport" class="bg-green-600 hover:bg-green-700 text-white py-3 px-6 rounded-lg flex items-center">
                    <i class="fas fa-file-export mr-2"></i> Export Data
                </button>
                <button id="closeMonthlyModal2" class="bg-gray-300 hover:bg-gray-400 text-gray-800 py-3 px-6 rounded-lg">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Laporan Antrian Terlewat -->
    <div id="skipReportModal" class="modal">
        <div class="modal-content bg-white rounded-2xl w-11/12 md:w-3/4 lg:w-1/2 p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-exclamation-triangle text-red-600 mr-3"></i>LAPORAN ANTRIAN TERLEWATI
                </h3>
                <button id="closeSkipModal" class="text-gray-500 hover:text-gray-700 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="mb-6">
                <div class="mb-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-red-100 p-3 rounded-full mr-4">
                            <i class="fas fa-user-times text-red-600 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-gray-800">Antrian yang Tidak Dihadiri</h4>
                            <p class="text-gray-600">Daftar antrian yang terlewat atau ditandai tidak hadir</p>
                        </div>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="py-3 px-4 text-left border border-gray-300">No. Antrian</th>
                                <th class="py-3 px-4 text-left border border-gray-300">Waktu Panggilan</th>
                                <th class="py-3 px-4 text-left border border-gray-300">Petugas</th>
                                <th class="py-3 px-4 text-left border border-gray-300">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody id="skipReportTable">
                            <!-- Data akan diisi oleh JavaScript -->
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-yellow-600 text-xl mr-3 mt-1"></i>
                        <div>
                            <p class="text-yellow-800 font-semibold">Informasi:</p>
                            <p class="text-yellow-700 text-sm">Antrian terlewat terjadi ketika pasien tidak merespons setelah dipanggil 3 kali atau petugas menandai tidak hadir setelah waktu tunggu tertentu.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end space-x-4 mt-8">
                <button id="printSkipReport" class="bg-red-600 hover:bg-red-700 text-white py-3 px-6 rounded-lg flex items-center">
                    <i class="fas fa-print mr-2"></i> Cetak Laporan
                </button>
                <button id="closeSkipModal2" class="bg-gray-300 hover:bg-gray-400 text-gray-800 py-3 px-6 rounded-lg">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Logout -->
    <div id="logoutModal" class="modal">
        <div class="modal-content bg-white rounded-2xl w-11/12 md:w-1/3 p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-sign-out-alt text-blue-600 mr-3"></i>Konfirmasi Logout
                </h3>
                <button id="closeLogoutModal" class="text-gray-500 hover:text-gray-700 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="mb-6">
                <div class="flex items-center mb-4">
                    <div class="bg-blue-100 p-3 rounded-full mr-4">
                        <i class="fas fa-user-circle text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-800 font-medium">Apakah Anda yakin ingin keluar dari sistem?</p>
                        <p class="text-gray-600 text-sm mt-1">Anda akan dialihkan ke halaman login.</p>
                    </div>
                </div>
                
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mt-4">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-yellow-600 text-xl mr-3 mt-1"></i>
                        <div>
                            <p class="text-yellow-800 font-semibold">Perhatian:</p>
                            <p class="text-yellow-700 text-sm">Pastikan semua antrian telah diproses sebelum logout.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end space-x-4">
                <button id="cancelLogout" class="bg-gray-300 hover:bg-gray-400 text-gray-800 py-2 px-6 rounded-lg">
                    Batal
                </button>
                <button id="confirmLogout" class="bg-red-600 hover:bg-red-700 text-white py-2 px-6 rounded-lg flex items-center">
                    <i class="fas fa-sign-out-alt mr-2"></i> Ya, Logout
                </button>
            </div>
        </div>
    </div>

    <script>
        // Data antrian
        let queueData = {
            current: 25,
            next: 26,
            totalToday: 142,
            servedToday: 24,
            skippedToday: 3,
            queueList: [
                { number: 'A-026', time: '08:45', status: 'menunggu' },
                { number: 'A-027', time: '08:47', status: 'menunggu' },
                { number: 'A-028', time: '08:50', status: 'menunggu' },
                { number: 'A-029', time: '08:52', status: 'menunggu' },
                { number: 'A-030', time: '08:55', status: 'menunggu' },
                { number: 'A-031', time: '08:57', status: 'menunggu' },
                { number: 'A-032', time: '09:00', status: 'menunggu' }
            ],
            callHistory: [
                { number: 'A-025', time: '09:15', status: 'called' },
                { number: 'A-024', time: '09:10', status: 'served' },
                { number: 'A-023', time: '09:05', status: 'served' },
                { number: 'A-022', time: '09:00', status: 'served' },
                { number: 'A-021', time: '08:55', status: 'served' },
                { number: 'A-020', time: '08:50', status: 'served' },
                { number: 'A-019', time: '08:45', status: 'served' },
                { number: 'A-018', time: '08:40', status: 'served' },
                { number: 'A-017', time: '08:35', status: 'served' },
                { number: 'A-016', time: '08:30', status: 'served' }
            ],
            skippedList: [
                { number: 'A-003', time: '08:35', officer: 'Syahril Bahoa', note: 'Tidak hadir setelah 3x panggilan' },
                { number: 'A-012', time: '08:55', officer: 'Syahril Bahoa', note: 'Pasien mengundurkan diri' },
                { number: 'A-015', time: '09:10', officer: 'Syahril Bahoa', note: 'Tidak hadir setelah panggilan' }
            ]
        };

        // Chart instance
        let monthlyChart = null;
        let dropdownVisible = false;

        // Inisialisasi dashboard
        document.addEventListener('DOMContentLoaded', function() {
            // Update waktu
            updateDateTime();
            setInterval(updateDateTime, 1000);
            
            // Inisialisasi data
            updateDashboard();
            
            // Setup dropdown user menu
            setupUserDropdown();
            
            // Setup event listeners
            setupEventListeners();
            
            // Setup modal
            setupModals();
        });

        // Fungsi untuk toggle dropdown user menu
        function setupUserDropdown() {
            const userMenuBtn = document.getElementById('userMenuBtn');
            const userDropdown = document.getElementById('userDropdown');
            
            // Sembunyikan dropdown awal
            userDropdown.classList.add('hidden');
            
            // Toggle dropdown saat tombol user diklik
            userMenuBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdownVisible = !dropdownVisible;
                if (dropdownVisible) {
                    userDropdown.classList.remove('hidden');
                    userDropdown.classList.add('show');
                } else {
                    userDropdown.classList.add('hidden');
                    userDropdown.classList.remove('show');
                }
            });
            
            // Tutup dropdown saat klik di luar
            document.addEventListener('click', function(e) {
                if (!userDropdown.contains(e.target) && !userMenuBtn.contains(e.target)) {
                    userDropdown.classList.add('hidden');
                    userDropdown.classList.remove('show');
                    dropdownVisible = false;
                }
            });
            
            // Tutup dropdown saat tombol logout diklik
            document.getElementById('btnLogout').addEventListener('click', function() {
                userDropdown.classList.add('hidden');
                userDropdown.classList.remove('show');
                dropdownVisible = false;
            });
        }

        // Update tanggal dan waktu
        function updateDateTime() {
            const now = new Date();
            const dateStr = now.toLocaleDateString('id-ID', { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
            const timeStr = now.toLocaleTimeString('id-ID', { 
                hour: '2-digit', 
                minute: '2-digit',
                second: '2-digit'
            });
            
            document.getElementById('currentDate').textContent = dateStr;
            document.getElementById('currentTime').textContent = timeStr;
        }

        // Update dashboard dengan data terbaru
        function updateDashboard() {
            // Update status antrian
            document.getElementById('currentCalled').textContent = `A-${queueData.current.toString().padStart(3, '0')}`;
            document.getElementById('nextInLine').textContent = `A-${queueData.next.toString().padStart(3, '0')}`;
            document.getElementById('totalToday').textContent = queueData.totalToday;
            document.getElementById('nextNumberDisplay').textContent = `(A-${queueData.next.toString().padStart(3, '0')})`;
            
            // Update daftar antrian
            updateQueueList();
            
            // Update riwayat panggilan
            updateCallHistory();
            
            // Update laporan cepat
            document.querySelector('.bg-blue-900\\/30 .text-3xl').textContent = queueData.servedToday;
            document.querySelector('.bg-green-900\\/30 .text-3xl').textContent = queueData.skippedToday;
        }

        // Update daftar antrian
        function updateQueueList() {
            const queueList = document.getElementById('queueList');
            queueList.innerHTML = '';
            
            queueData.queueList.forEach((item, index) => {
                const row = document.createElement('tr');
                row.className = 'border-b border-white/10 hover:bg-white/5';
                row.innerHTML = `
                    <td class="py-3 px-4">
                        <div class="flex items-center">
                            <span class="bg-blue-500 text-white text-sm font-bold py-1 px-3 rounded-full mr-3">${index + 1}</span>
                            <span class="text-xl font-bold">${item.number}</span>
                        </div>
                    </td>
                    <td class="py-3 px-4">${item.time}</td>
                    <td class="py-3 px-4">
                        <span class="bg-yellow-500/20 text-yellow-300 py-1 px-3 rounded-full text-sm">${item.status}</span>
                    </td>
                    <td class="py-3 px-4">
                        <button class="call-specific-btn bg-blue-600 hover:bg-blue-700 text-white py-1 px-3 rounded text-sm" data-number="${item.number}">
                            <i class="fas fa-bullhorn mr-1"></i> Panggil
                        </button>
                    </td>
                `;
                queueList.appendChild(row);
            });
            
            // Tambahkan event listener untuk tombol panggil spesifik
            document.querySelectorAll('.call-specific-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const number = this.getAttribute('data-number');
                    callSpecificNumber(number);
                });
            });
        }

        // Update riwayat panggilan
        function updateCallHistory() {
            const callHistory = document.getElementById('callHistory');
            callHistory.innerHTML = '';
            
            queueData.callHistory.forEach(item => {
                const statusIcon = item.status === 'called' ? 'fa-bullhorn text-blue-500' : 
                                 item.status === 'served' ? 'fa-check-circle text-green-500' : 
                                 'fa-times-circle text-red-500';
                
                const statusText = item.status === 'called' ? 'Dipanggil' : 
                                  item.status === 'served' ? 'Terlayani' : 
                                  'Terlewat';
                
                const itemDiv = document.createElement('div');
                itemDiv.className = 'bg-white/5 rounded-xl p-3';
                itemDiv.innerHTML = `
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <i class="fas ${statusIcon} text-xl mr-3"></i>
                            <div>
                                <p class="font-bold text-white">${item.number}</p>
                                <p class="text-blue-200 text-sm">${item.time}</p>
                            </div>
                        </div>
                        <span class="text-white text-sm font-semibold">${statusText}</span>
                    </div>
                `;
                callHistory.appendChild(itemDiv);
            });
        }

        // Panggil antrian berikutnya
        function callNext() {
            // Update data
            queueData.current = queueData.next;
            queueData.next++;
            queueData.servedToday++;
            queueData.totalToday++;
            
            // Pindahkan dari daftar antrian ke riwayat
            if (queueData.queueList.length > 0) {
                const calledItem = queueData.queueList.shift();
                calledItem.time = new Date().toLocaleTimeString('id-ID', { 
                    hour: '2-digit', 
                    minute: '2-digit' 
                });
                calledItem.status = 'called';
                queueData.callHistory.unshift(calledItem);
                
                // Batasi riwayat
                if (queueData.callHistory.length > 10) {
                    queueData.callHistory.pop();
                }
            }
            
            // Update tampilan
            updateDashboard();
            
            // Tampilkan notifikasi
            showNotification(`Antrian A-${queueData.current.toString().padStart(3, '0')} berhasil dipanggil!`, 'success');
            
            // Simulasi suara panggilan
            playCallSound();
        }

        // Panggil antrian spesifik
        function callSpecificNumber(number) {
            // Cari antrian dalam daftar
            const index = queueData.queueList.findIndex(item => item.number === number);
            
            if (index !== -1) {
                // Update data
                queueData.current = parseInt(number.split('-')[1]);
                queueData.next = queueData.current + 1;
                queueData.servedToday++;
                
                // Hapus dari daftar antrian
                const calledItem = queueData.queueList.splice(index, 1)[0];
                calledItem.time = new Date().toLocaleTimeString('id-ID', { 
                    hour: '2-digit', 
                    minute: '2-digit' 
                });
                calledItem.status = 'called';
                queueData.callHistory.unshift(calledItem);
                
                // Batasi riwayat
                if (queueData.callHistory.length > 10) {
                    queueData.callHistory.pop();
                }
                
                // Update tampilan
                updateDashboard();
                
                // Tampilkan notifikasi
                showNotification(`Antrian ${number} berhasil dipanggil!`, 'success');
                
                // Simulasi suara panggilan
                playCallSound();
            }
        }

        // Lewati antrian
        function skipCurrent() {
            // Update data
            queueData.skippedToday++;
            queueData.next++;
            
            // Pindahkan dari daftar antrian ke riwayat terlewat
            if (queueData.queueList.length > 0) {
                const skippedItem = queueData.queueList.shift();
                skippedItem.time = new Date().toLocaleTimeString('id-ID', { 
                    hour: '2-digit', 
                    minute: '2-digit' 
                });
                skippedItem.status = 'skipped';
                queueData.callHistory.unshift(skippedItem);
                
                // Tambahkan ke daftar terlewat
                queueData.skippedList.unshift({
                    number: skippedItem.number,
                    time: skippedItem.time,
                    officer: 'Syahril Bahoa',
                    note: 'Tidak hadir setelah panggilan'
                });
                
                // Batasi riwayat
                if (queueData.callHistory.length > 10) {
                    queueData.callHistory.pop();
                }
            }
            
            // Update tampilan
            updateDashboard();
            
            // Tampilkan notifikasi
            showNotification(`Antrian A-${(queueData.next-1).toString().padStart(3, '0')} ditandai sebagai terlewat!`, 'warning');
        }

        // Setup event listeners
        function setupEventListeners() {
            // Tombol panggil antrian berikutnya
            document.getElementById('btnCall').addEventListener('click', callNext);
            
            // Tombol lewati antrian
            document.getElementById('btnSkip').addEventListener('click', skipCurrent);
            
            // Tombol refresh
            document.getElementById('btnRefresh').addEventListener('click', function() {
                // Tambahkan antrian baru untuk simulasi
                const newNumber = queueData.next + queueData.queueList.length;
                const newTime = new Date().toLocaleTimeString('id-ID', { 
                    hour: '2-digit', 
                    minute: '2-digit' 
                });
                
                queueData.queueList.push({
                    number: `A-${newNumber.toString().padStart(3, '0')}`,
                    time: newTime,
                    status: 'menunggu'
                });
                
                queueData.totalToday++;
                
                showNotification('Daftar antrian diperbarui!', 'info');
                updateDashboard();
            });
            
            // Tombol logout
            document.getElementById('btnLogout').addEventListener('click', function() {
                showLogoutModal();
            });
            
            // Tombol laporan bulanan
            document.getElementById('btnMonthlyReport').addEventListener('click', function() {
                showMonthlyReport();
            });
            
            // Tombol laporan antrian terlewat
            document.getElementById('btnSkipReport').addEventListener('click', function() {
                showSkipReport();
            });
        }

        // Setup modal
        function setupModals() {
            // Modal laporan bulanan
            const monthlyModal = document.getElementById('monthlyReportModal');
            const closeMonthlyBtn = document.getElementById('closeMonthlyModal');
            const closeMonthlyBtn2 = document.getElementById('closeMonthlyModal2');
            
            closeMonthlyBtn.addEventListener('click', function() {
                monthlyModal.style.display = 'none';
            });
            
            closeMonthlyBtn2?.addEventListener('click', function() {
                monthlyModal.style.display = 'none';
            });
            
            // Modal laporan terlewat
            const skipModal = document.getElementById('skipReportModal');
            const closeSkipBtn = document.getElementById('closeSkipModal');
            const closeSkipBtn2 = document.getElementById('closeSkipModal2');
            
            closeSkipBtn.addEventListener('click', function() {
                skipModal.style.display = 'none';
            });
            
            closeSkipBtn2.addEventListener('click', function() {
                skipModal.style.display = 'none';
            });
            
            // Modal logout
            const logoutModal = document.getElementById('logoutModal');
            const closeLogoutBtn = document.getElementById('closeLogoutModal');
            const cancelLogoutBtn = document.getElementById('cancelLogout');
            const confirmLogoutBtn = document.getElementById('confirmLogout');
            
            closeLogoutBtn.addEventListener('click', function() {
                logoutModal.style.display = 'none';
            });
            
            cancelLogoutBtn.addEventListener('click', function() {
                logoutModal.style.display = 'none';
            });
            
            confirmLogoutBtn.addEventListener('click', function() {
                performLogout();
            });
            
            // Tombol cetak laporan
            document.getElementById('printMonthlyReport').addEventListener('click', function() {
                alert('Laporan bulanan berhasil dicetak!');
            });
            
            document.getElementById('printSkipReport').addEventListener('click', function() {
                alert('Laporan antrian terlewat berhasil dicetak!');
            });
            
            // Tombol export
            document.getElementById('exportMonthlyReport').addEventListener('click', function() {
                alert('Data laporan berhasil diexport ke Excel!');
            });
            
            // Tutup modal saat klik di luar konten
            window.addEventListener('click', function(event) {
                if (event.target === monthlyModal) {
                    monthlyModal.style.display = 'none';
                }
                if (event.target === skipModal) {
                    skipModal.style.display = 'none';
                }
                if (event.target === logoutModal) {
                    logoutModal.style.display = 'none';
                }
            });
        }

        // Tampilkan modal logout
        function showLogoutModal() {
            const modal = document.getElementById('logoutModal');
            modal.style.display = 'flex';
        }

        // Proses logout
        function performLogout() {
            // Tampilkan notifikasi logout
            showNotification('Sedang mengeluarkan Anda dari sistem...', 'info');
            
            // Tutup modal logout
            document.getElementById('logoutModal').style.display = 'none';
            
            // Simulasi proses logout
            setTimeout(() => {
                // Tampilkan notifikasi sukses
                showNotification('Logout berhasil! Mengalihkan ke halaman login...', 'success');
                
                // Simulasi redirect ke halaman login setelah 2 detik
                setTimeout(() => {
                    // Dalam implementasi nyata, ini akan mengarahkan ke halaman login
                    // window.location.href = 'login.html';
                    
                    // Untuk demo, kita reset data dan tampilkan pesan
                    resetDataForDemo();
                    alert('Logout berhasil!\n\nAnda telah keluar dari sistem.\n\nSekarang Anda akan diarahkan ke halaman login.');
                }, 2000);
            }, 1000);
        }

        // Reset data untuk demo logout
        function resetDataForDemo() {
            // Reset data antrian ke keadaan awal
            queueData = {
                current: 0,
                next: 1,
                totalToday: 142,
                servedToday: 24,
                skippedToday: 3,
                queueList: [
                    { number: 'A-001', time: '08:15', status: 'menunggu' },
                    { number: 'A-002', time: '08:20', status: 'menunggu' },
                    { number: 'A-003', time: '08:25', status: 'menunggu' },
                    { number: 'A-004', time: '08:30', status: 'menunggu' },
                    { number: 'A-005', time: '08:35', status: 'menunggu' },
                    { number: 'A-006', time: '08:40', status: 'menunggu' },
                    { number: 'A-007', time: '08:45', status: 'menunggu' }
                ],
                callHistory: [
                    { number: 'A-000', time: '08:10', status: 'called' },
                    { number: 'A-999', time: '08:05', status: 'served' },
                    { number: 'A-998', time: '08:00', status: 'served' }
                ],
                skippedList: [
                    { number: 'A-003', time: '08:35', officer: 'Syahril Bahoa', note: 'Tidak hadir setelah 3x panggilan' }
                ]
            };
            
            // Update dashboard dengan data reset
            updateDashboard();
            
            // Tampilkan pesan bahwa user telah logout
            const headerName = document.querySelector('.text-right .text-blue-200');
            if (headerName) {
                headerName.textContent = '[Telah Logout]';
            }
            
            // Tutup dropdown jika terbuka
            const userDropdown = document.getElementById('userDropdown');
            userDropdown.classList.add('hidden');
            userDropdown.classList.remove('show');
            dropdownVisible = false;
        }

        // Tampilkan laporan bulanan
        function showMonthlyReport() {
            const modal = document.getElementById('monthlyReportModal');
            modal.style.display = 'flex';
            
            // Setup grafik jika belum ada
            if (!monthlyChart) {
                setupMonthlyChart();
            }
            
            // Update tabel detail
            updateMonthlyTable();
        }

        // Setup grafik bulanan
        function setupMonthlyChart() {
            const ctx = document.getElementById('monthlyChart').getContext('2d');
            
            // Data contoh untuk grafik
            const days = Array.from({length: 30}, (_, i) => i + 1);
            const servedData = days.map(() => Math.floor(Math.random() * 40) + 20);
            const skippedData = days.map(() => Math.floor(Math.random() * 10) + 1);
            
            monthlyChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: days.map(d => d.toString()),
                    datasets: [
                        {
                            label: 'Terlayani',
                            data: servedData,
                            backgroundColor: 'rgba(59, 130, 246, 0.7)',
                            borderColor: 'rgb(59, 130, 246)',
                            borderWidth: 1
                        },
                        {
                            label: 'Terlewat',
                            data: skippedData,
                            backgroundColor: 'rgba(239, 68, 68, 0.7)',
                            borderColor: 'rgb(239, 68, 68)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Jumlah Antrian Per Hari'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Jumlah Antrian'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Hari'
                            }
                        }
                    }
                }
            });
        }

        // Update tabel laporan bulanan
        function updateMonthlyTable() {
            // Contoh data untuk tabel
            const tableBody = document.getElementById('monthlyTableBody');
            tableBody.innerHTML = '';
            
            for (let i = 1; i <= 5; i++) {
                const total = Math.floor(Math.random() * 40) + 20;
                const served = Math.floor(total * 0.9);
                const skipped = total - served;
                const avgTime = Math.floor(Math.random() * 10) + 15;
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="py-2 px-4 border border-gray-300">${i} April 2025</td>
                    <td class="py-2 px-4 border border-gray-300">${total}</td>
                    <td class="py-2 px-4 border border-gray-300">${served}</td>
                    <td class="py-2 px-4 border border-gray-300">${skipped}</td>
                    <td class="py-2 px-4 border border-gray-300">${avgTime} menit</td>
                `;
                tableBody.appendChild(row);
            }
        }

        // Tampilkan laporan antrian terlewat
        function showSkipReport() {
            const modal = document.getElementById('skipReportModal');
            modal.style.display = 'flex';
            
            // Update tabel
            updateSkipTable();
        }

        // Update tabel antrian terlewat
        function updateSkipTable() {
            const tableBody = document.getElementById('skipReportTable');
            tableBody.innerHTML = '';
            
            queueData.skippedList.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="py-2 px-4 border border-gray-300">
                        <span class="font-bold text-red-600">${item.number}</span>
                    </td>
                    <td class="py-2 px-4 border border-gray-300">${item.time}</td>
                    <td class="py-2 px-4 border border-gray-300">${item.officer}</td>
                    <td class="py-2 px-4 border border-gray-300">${item.note}</td>
                `;
                tableBody.appendChild(row);
            });
        }

        // Tampilkan notifikasi
        function showNotification(message, type) {
            // Buat elemen notifikasi
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 ${type === 'success' ? 'bg-green-500' : type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500'} text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-pulse`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle'} mr-3 text-xl"></i>
                    <span class="font-semibold">${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Hapus notifikasi setelah 3 detik
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }

        // Simulasi suara panggilan
        function playCallSound() {
            // Membuat elemen audio untuk suara panggilan
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();
            
            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);
            
            // Suara panggilan antrian (beep-beep)
            oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
            oscillator.frequency.setValueAtTime(600, audioContext.currentTime + 0.1);
            oscillator.frequency.setValueAtTime(800, audioContext.currentTime + 0.2);
            
            gainNode.gain.setValueAtTime(0.1, audioContext.currentTime);
            gainNode.gain.setValueAtTime(0.1, audioContext.currentTime + 0.1);
            gainNode.gain.setValueAtTime(0.1, audioContext.currentTime + 0.2);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.3);
            
            oscillator.start();
            oscillator.stop(audioContext.currentTime + 0.3);
        }
    </script>
</body>
</html>