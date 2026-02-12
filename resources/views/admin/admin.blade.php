<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Admin - Sistem Antrian Klinik</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js untuk grafik -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Custom styles */
        .admin-bg {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-bg {
            background: rgba(15, 23, 42, 0.8);
        }

        .nav-link {
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            background: rgba(59, 130, 246, 0.2);
            border-left: 4px solid #3b82f6;
        }

        .nav-link.active {
            background: rgba(59, 130, 246, 0.3);
            border-left: 4px solid #3b82f6;
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
            0% {
                transform: scale(0.8);
                opacity: 0;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .dropdown-enter {
            animation: dropdown-fade 0.2s ease;
        }

        @keyframes dropdown-fade {
            0% {
                opacity: 0;
                transform: translateY(-10px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stat-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .table-row-hover:hover {
            background: rgba(59, 130, 246, 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(37, 99, 235, 0.3);
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3);
        }

        .btn-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            transition: all 0.3s ease;
        }

        .btn-warning:hover {
            background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(245, 158, 11, 0.3);
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            transition: all 0.3s ease;
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(239, 68, 68, 0.3);
        }

        .notification-badge {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }

        .loader {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #3b82f6;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body class="admin-bg text-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        <div class="sidebar-bg w-64 min-h-screen p-6 hidden md:block">
            <div class="flex items-center mb-10">
                <div class="bg-blue-500 p-3 rounded-full mr-4">
                    <i class="fas fa-shield-alt text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold">Admin Panel</h1>
                    <p class="text-blue-300 text-sm">Klinik Anugrah Farma</p>
                </div>
            </div>

            <nav class="space-y-2">
                <a href="#" class="nav-link active flex items-center p-3 rounded-lg">
                    <i class="fas fa-tachometer-alt mr-3 text-blue-400"></i>
                    Dashboard
                </a>
                <a href="#" class="nav-link flex items-center p-3 rounded-lg">
                    <i class="fas fa-users mr-3 text-green-400"></i>
                    Manajemen Petugas
                </a>
                <a href="#" class="nav-link flex items-center p-3 rounded-lg">
                    <i class="fas fa-list-ol mr-3 text-purple-400"></i>
                    Antrian
                    <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">12</span>
                </a>
                <a href="#" class="nav-link flex items-center p-3 rounded-lg">
                    <i class="fas fa-chart-bar mr-3 text-yellow-400"></i>
                    Laporan
                </a>
                <a href="#" class="nav-link flex items-center p-3 rounded-lg">
                    <i class="fas fa-cogs mr-3 text-gray-400"></i>
                    Pengaturan
                </a>

            </nav>

            <div class="mt-20">
                <div class="bg-blue-900/30 rounded-xl p-4">
                    <p class="text-sm text-blue-300">Total Pengguna Aktif</p>
                    <p class="text-2xl font-bold">24</p>
                    <div class="flex items-center mt-2">
                        <i class="fas fa-user-check text-green-400 mr-2"></i>
                        <span class="text-green-400 text-sm">+3 dari kemarin</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1">
            <!-- Header -->
            <header class="bg-gray-900/50 p-4 border-b border-gray-800">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="mb-4 md:mb-0">
                        <h1 class="text-2xl font-bold">
                            <i class="fas fa-shield-alt text-blue-500 mr-3"></i>
                            Dashboard Admin
                        </h1>
                        <p class="text-gray-400">Sistem Manajemen Antrian Klinik</p>
                    </div>

                    <div class="flex items-center space-x-4">
                        <!-- Notifikasi -->
                        <div class="relative">
                            <button id="notificationBtn" class="bg-gray-800 p-2 rounded-full hover:bg-gray-700">
                                <i class="fas fa-bell text-yellow-400"></i>
                                <span
                                    class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center notification-badge">3</span>
                            </button>
                            <!-- Dropdown Notifikasi -->
                            <div id="notificationDropdown"
                                class="absolute right-0 mt-2 w-80 bg-gray-800 rounded-lg shadow-xl z-50 hidden dropdown-enter">
                                <div class="p-4 border-b border-gray-700">
                                    <h3 class="font-bold">Notifikasi</h3>
                                </div>
                                <div class="max-h-64 overflow-y-auto">
                                    <div class="p-3 border-b border-gray-700 hover:bg-gray-700">
                                        <div class="flex items-start">
                                            <div class="bg-blue-500 p-2 rounded-full mr-3">
                                                <i class="fas fa-user-md text-white"></i>
                                            </div>
                                            <div class="flex-1">
                                                <p class="font-medium">Petugas baru ditambahkan</p>
                                                <p class="text-gray-400 text-sm">Andi Setiawan bergabung sebagai petugas
                                                    pendaftaran</p>
                                                <p class="text-gray-500 text-xs mt-1">2 jam yang lalu</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-3 border-b border-gray-700 hover:bg-gray-700">
                                        <div class="flex items-start">
                                            <div class="bg-red-500 p-2 rounded-full mr-3">
                                                <i class="fas fa-exclamation-triangle text-white"></i>
                                            </div>
                                            <div class="flex-1">
                                                <p class="font-medium">Antrian menumpuk</p>
                                                <p class="text-gray-400 text-sm">15 antrian menunggu di Loket 1</p>
                                                <p class="text-gray-500 text-xs mt-1">1 jam yang lalu</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-3 hover:bg-gray-700">
                                        <div class="flex items-start">
                                            <div class="bg-green-500 p-2 rounded-full mr-3">
                                                <i class="fas fa-chart-line text-white"></i>
                                            </div>
                                            <div class="flex-1">
                                                <p class="font-medium">Laporan bulanan siap</p>
                                                <p class="text-gray-400 text-sm">Laporan April 2025 telah siap diunduh
                                                </p>
                                                <p class="text-gray-500 text-xs mt-1">3 jam yang lalu</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-3 border-t border-gray-700">
                                    <a href="#" class="text-blue-400 text-sm hover:text-blue-300">Lihat semua
                                        notifikasi</a>
                                </div>
                            </div>
                        </div>

                        <!-- User Menu -->
                        <div class="relative">
                            <button id="userMenuBtn"
                                class="flex items-center space-x-2 bg-gray-800 hover:bg-gray-700 p-2 rounded-lg">
                                <div class="bg-blue-500 p-2 rounded-full">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                                <div class="text-left hidden md:block">
                                    <p class="font-medium">Bahoa</p>
                                    <p class="text-gray-400 text-sm">Super Admin</p>
                                </div>
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </button>
                            <!-- Dropdown Menu -->
                            <div id="userDropdown"
                                class="absolute right-0 mt-2 w-48 bg-gray-800 rounded-lg shadow-xl z-50 hidden dropdown-enter">
                                <div class="p-4 border-b border-gray-700">
                                    <p class="font-medium">dr. Anugrah Pratama</p>
                                    <p class="text-gray-400 text-sm">admin@klinikanugrah.com</p>
                                </div>
                                <a href="#" class="flex items-center p-3 hover:bg-gray-700">
                                    <i class="fas fa-user-cog text-blue-400 mr-3"></i>
                                    Profil Saya
                                </a>
                                <a href="#" class="flex items-center p-3 hover:bg-gray-700">
                                    <i class="fas fa-cog text-gray-400 mr-3"></i>
                                    Pengaturan Akun
                                </a>
                                <div class="border-t border-gray-700">
                                    <button id="btnLogout"
                                        class="w-full text-left p-3 text-red-400 hover:bg-red-900/20 flex items-center">
                                        <i class="fas fa-sign-out-alt mr-3"></i>
                                        Keluar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Dashboard Content -->
            <div class="p-6">
                <!-- Statistik Utama -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="glass-card rounded-xl p-6 stat-card">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-400 text-sm">Total Antrian Hari Ini</p>
                                <p class="text-3xl font-bold mt-2">142</p>
                                <div class="flex items-center mt-2">
                                    <i class="fas fa-arrow-up text-green-400 mr-2"></i>
                                    <span class="text-green-400 text-sm">+12% dari kemarin</span>
                                </div>
                            </div>
                            <div class="bg-blue-500/20 p-3 rounded-full">
                                <i class="fas fa-list-ol text-blue-400 text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="glass-card rounded-xl p-6 stat-card">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-400 text-sm">Petugas Aktif</p>
                                <p class="text-3xl font-bold mt-2">8</p>
                                <div class="flex items-center mt-2">
                                    <i class="fas fa-user-check text-green-400 mr-2"></i>
                                    <span class="text-green-400 text-sm">Semua aktif</span>
                                </div>
                            </div>
                            <div class="bg-green-500/20 p-3 rounded-full">
                                <i class="fas fa-user-md text-green-400 text-2xl"></i>
                            </div>
                        </div>
                    </div>



                </div>

                <!-- Chart dan Tabel -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <!-- Grafik Statistik -->
                    <div class="glass-card rounded-xl p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-bold">
                                <i class="fas fa-chart-line text-blue-400 mr-3"></i>
                                Statistik Harian
                            </h2>
                            <select class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-sm">
                                <option>7 Hari Terakhir</option>
                                <option>30 Hari Terakhir</option>
                                <option>Bulan Ini</option>
                            </select>
                        </div>
                        <div class="h-64">
                            <canvas id="dailyChart"></canvas>
                        </div>
                    </div>

                    <!-- Aktivitas Terbaru -->
                    <div class="glass-card rounded-xl p-6">
                        <h2 class="text-xl font-bold mb-6">
                            <i class="fas fa-history text-green-400 mr-3"></i>
                            Aktivitas Terbaru
                        </h2>
                        <div class="space-y-4 max-h-64 overflow-y-auto pr-2">
                            <div class="flex items-start p-3 bg-gray-800/30 rounded-lg">
                                <div class="bg-blue-500 p-2 rounded-full mr-3">
                                    <i class="fas fa-user-plus text-white"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium">Petugas baru ditambahkan</p>
                                    <p class="text-gray-400 text-sm">Syahril Bahoa ditambahkan sebagai petugas Loket 1
                                    </p>
                                    <p class="text-gray-500 text-xs mt-1">10:30 AM • Hari ini</p>
                                </div>
                            </div>

                            <div class="flex items-start p-3 bg-gray-800/30 rounded-lg">
                                <div class="bg-green-500 p-2 rounded-full mr-3">
                                    <i class="fas fa-check-circle text-white"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium">Sistem backup berhasil</p>
                                    <p class="text-gray-400 text-sm">Backup data harian berhasil dilakukan</p>
                                    <p class="text-gray-500 text-xs mt-1">08:00 AM • Hari ini</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-start p-3 bg-gray-800/30 rounded-lg">
                            <div class="bg-purple-500 p-2 rounded-full mr-3">
                                <i class="fas fa-chart-bar text-white"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium">Laporan bulanan dibuat</p>
                                <p class="text-gray-400 text-sm">Laporan performa Maret 2025 telah dihasilkan</p>
                                <p class="text-gray-500 text-xs mt-1">2 hari lalu • 04:15 PM</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Petugas -->
            <div class="glass-card rounded-xl p-6 mb-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold">
                        <i class="fas fa-user-md text-green-400 mr-3"></i>
                        Daftar Petugas
                    </h2>
                    <button id="btnAddOfficer" class="btn-primary px-4 py-2 rounded-lg flex items-center">
                        <i class="fas fa-user-plus mr-2"></i> Tambah Petugas
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-700">
                                <th class="py-3 px-4 text-left">ID</th>
                                <th class="py-3 px-4 text-left">Nama</th>
                                <th class="py-3 px-4 text-left">Loket</th>
                                <th class="py-3 px-4 text-left">Status</th>
                                <th class="py-3 px-4 text-left">Antrian Hari Ini</th>
                                <th class="py-3 px-4 text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="officerTable">
                            <!-- Data akan diisi oleh JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Informasi Sistem -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Status Sistem -->
                <div class="glass-card rounded-xl p-6">
                    <h2 class="text-xl font-bold mb-6">
                        <i class="fas fa-server text-blue-400 mr-3"></i>
                        Status Sistem
                    </h2>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center p-3 bg-gray-800/30 rounded-lg">
                            <div class="flex items-center">
                                <div class="bg-green-500 p-2 rounded-full mr-3">
                                    <i class="fas fa-check text-white"></i>
                                </div>
                                <div>
                                    <p class="font-medium">Server Database</p>
                                    <p class="text-gray-400 text-sm">Online • Response: 45ms</p>
                                </div>
                            </div>
                            <span class="bg-green-500/20 text-green-400 px-3 py-1 rounded-full text-sm">Aktif</span>
                        </div>

                        <div class="flex justify-between items-center p-3 bg-gray-800/30 rounded-lg">
                            <div class="flex items-center">
                                <div class="bg-green-500 p-2 rounded-full mr-3">
                                    <i class="fas fa-check text-white"></i>
                                </div>
                                <div>
                                    <p class="font-medium">Server Antrian</p>
                                    <p class="text-gray-400 text-sm">Online • 24/7</p>
                                </div>
                            </div>
                            <span class="bg-green-500/20 text-green-400 px-3 py-1 rounded-full text-sm">Aktif</span>
                        </div>

                        <div class="flex justify-between items-center p-3 bg-gray-800/30 rounded-lg">
                            <div class="flex items-center">
                                <div class="bg-yellow-500 p-2 rounded-full mr-3">
                                    <i class="fas fa-exclamation-triangle text-white"></i>
                                </div>
                                <div>
                                    <p class="font-medium">Backup Harian</p>
                                    <p class="text-gray-400 text-sm">Berjalan • 85%</p>
                                </div>
                            </div>
                            <span
                                class="bg-yellow-500/20 text-yellow-400 px-3 py-1 rounded-full text-sm">Berjalan</span>
                        </div>

                        <div class="flex justify-between items-center p-3 bg-gray-800/30 rounded-lg">
                            <div class="flex items-center">
                                <div class="bg-blue-500 p-2 rounded-full mr-3">
                                    <i class="fas fa-shield-alt text-white"></i>
                                </div>
                                <div>
                                    <p class="font-medium">Keamanan Sistem</p>
                                    <p class="text-gray-400 text-sm">Tinggi • 98%</p>
                                </div>
                            </div>
                            <span class="bg-blue-500/20 text-blue-400 px-3 py-1 rounded-full text-sm">Aman</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="glass-card rounded-xl p-6">
                    <h2 class="text-xl font-bold mb-6">
                        <i class="fas fa-bolt text-yellow-400 mr-3"></i>
                        Aksi Cepat
                    </h2>
                    <div class="grid grid-cols-2 gap-4">
                        <button
                            class="bg-blue-900/30 hover:bg-blue-800/40 p-4 rounded-lg flex flex-col items-center justify-center transition">
                            <i class="fas fa-file-export text-blue-400 text-2xl mb-2"></i>
                            <span class="text-sm font-medium">Export Data</span>
                        </button>

                        <button
                            class="bg-green-900/30 hover:bg-green-800/40 p-4 rounded-lg flex flex-col items-center justify-center transition">
                            <i class="fas fa-print text-green-400 text-2xl mb-2"></i>
                            <span class="text-sm font-medium">Cetak Laporan</span>
                        </button>

                        <button
                            class="bg-purple-900/30 hover:bg-purple-800/40 p-4 rounded-lg flex flex-col items-center justify-center transition">
                            <i class="fas fa-cog text-purple-400 text-2xl mb-2"></i>
                            <span class="text-sm font-medium">Pengaturan</span>
                        </button>

                        <button
                            class="bg-red-900/30 hover:bg-red-800/40 p-4 rounded-lg flex flex-col items-center justify-center transition">
                            <i class="fas fa-history text-red-400 text-2xl mb-2"></i>
                            <span class="text-sm font-medium">Riwayat Log</span>
                        </button>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-700">
                        <h3 class="font-bold mb-3">Backup Terakhir</h3>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-400">15 April 2025 • 02:00 AM</p>
                                <p class="text-xs text-gray-500">Ukuran: 2.4 GB</p>
                            </div>
                            <button class="bg-gray-800 hover:bg-gray-700 px-3 py-1 rounded text-sm">
                                <i class="fas fa-redo mr-1"></i> Backup Sekarang
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Update Sistem -->
                <div class="glass-card rounded-xl p-6">
                    <h2 class="text-xl font-bold mb-6">
                        <i class="fas fa-sync-alt text-green-400 mr-3"></i>
                        Update Sistem
                    </h2>
                    <div class="space-y-4">
                        <div class="bg-blue-900/20 border border-blue-800 rounded-lg p-4">
                            <div class="flex items-start">
                                <i class="fas fa-info-circle text-blue-400 text-xl mr-3 mt-1"></i>
                                <div>
                                    <p class="font-medium">Versi v1.2.5</p>
                                    <p class="text-gray-400 text-sm mt-1">Saat ini menggunakan versi terbaru</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-800/30 rounded-lg p-4">
                            <p class="font-medium mb-2">Changelog Terbaru</p>
                            <ul class="text-gray-400 text-sm space-y-1">
                                <li class="flex items-start">
                                    <i class="fas fa-check text-green-400 mr-2 mt-1"></i>
                                    <span>Perbaikan bug pada sistem antrian</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check text-green-400 mr-2 mt-1"></i>
                                    <span>Optimasi performa database</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check text-green-400 mr-2 mt-1"></i>
                                    <span>Penambahan fitur export Excel</span>
                                </li>
                            </ul>
                        </div>

                        <button
                            class="w-full bg-gray-800 hover:bg-gray-700 py-3 rounded-lg flex items-center justify-center">
                            <i class="fas fa-download mr-2"></i> Cek Update
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Modal Tambah Petugas -->
    <div id="addOfficerModal" class="modal">
        <div class="modal-content bg-gray-800 rounded-xl w-11/12 md:w-1/2 p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold">
                    <i class="fas fa-user-plus text-blue-400 mr-3"></i>
                    Tambah Petugas Baru
                </h3>
                <button id="closeAddOfficerModal" class="text-gray-400 hover:text-white text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="addOfficerForm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-gray-300 mb-2">Nama Lengkap</label>
                        <input type="text"
                            class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 focus:outline-none focus:border-blue-500"
                            placeholder="Masukkan nama petugas" required>
                    </div>

                    <div>
                        <label class="block text-gray-300 mb-2">Email</label>
                        <input type="email"
                            class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 focus:outline-none focus:border-blue-500"
                            placeholder="email@klinikanugrah.com" required>
                    </div>

                    <div>
                        <label class="block text-gray-300 mb-2">Nomor Telepon</label>
                        <input type="tel"
                            class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 focus:outline-none focus:border-blue-500"
                            placeholder="0812-3456-7890" required>
                    </div>

                    <div>
                        <label class="block text-gray-300 mb-2">Loket</label>
                        <select
                            class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 focus:outline-none focus:border-blue-500"
                            required>
                            <option value="">Pilih Loket</option>
                            <option value="1">Loket Pendaftaran 1</option>
                            <option value="2">Loket Pendaftaran 2</option>
                            <option value="3">Loket Farmasi</option>
                            <option value="4">Loket Pembayaran</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-300 mb-2">Role</label>
                        <select
                            class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 focus:outline-none focus:border-blue-500"
                            required>
                            <option value="">Pilih Role</option>
                            <option value="petugas">Petugas Antrian</option>
                            <option value="supervisor">Supervisor</option>
                            <option value="admin">Admin Loket</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-300 mb-2">Shift Kerja</label>
                        <select
                            class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 focus:outline-none focus:border-blue-500"
                            required>
                            <option value="">Pilih Shift</option>
                            <option value="pagi">Pagi (07:00 - 15:00)</option>
                            <option value="siang">Siang (15:00 - 23:00)</option>
                            <option value="malam">Malam (23:00 - 07:00)</option>
                        </select>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-300 mb-2">Catatan</label>
                    <textarea
                        class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 focus:outline-none focus:border-blue-500"
                        rows="3" placeholder="Tambahkan catatan jika perlu..."></textarea>
                </div>

                <div class="flex justify-end space-x-4">
                    <button type="button" id="cancelAddOfficer"
                        class="bg-gray-700 hover:bg-gray-600 px-6 py-3 rounded-lg">
                        Batal
                    </button>
                    <button type="submit" class="btn-primary px-6 py-3 rounded-lg flex items-center">
                        <i class="fas fa-save mr-2"></i> Simpan Petugas
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Logout Confirmation -->
    <div id="logoutModal" class="modal">
        <div class="modal-content bg-gray-800 rounded-xl w-11/12 md:w-1/3 p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold">
                    <i class="fas fa-sign-out-alt text-red-400 mr-3"></i>
                    Konfirmasi Logout
                </h3>
                <button id="closeLogoutModal" class="text-gray-400 hover:text-white text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="mb-6">
                <div class="flex items-center mb-4">
                    <div class="bg-red-500/20 p-3 rounded-full mr-4">
                        <i class="fas fa-user-circle text-red-400 text-xl"></i>
                    </div>
                    <div>
                        <p class="font-medium">Apakah Anda yakin ingin keluar?</p>
                        <p class="text-gray-400 text-sm mt-1">Anda akan dialihkan ke halaman login.</p>
                    </div>
                </div>

                <div class="bg-yellow-900/20 border border-yellow-800 rounded-lg p-4 mt-4">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-yellow-400 text-xl mr-3 mt-1"></i>
                        <div>
                            <p class="text-yellow-400 font-medium">Perhatian:</p>
                            <p class="text-yellow-300 text-sm">Pastikan semua tugas administratif telah disimpan.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4">
                <button id="cancelLogout" class="bg-gray-700 hover:bg-gray-600 px-6 py-3 rounded-lg">
                    Batal
                </button>
                <button id="confirmLogout" class="btn-danger px-6 py-3 rounded-lg flex items-center">
                    <i class="fas fa-sign-out-alt mr-2"></i> Ya, Logout
                </button>
            </div>
        </div>
    </div>

    <script>
        // Data petugas
        let officersData = [{
                id: 1,
                name: 'Syahril Bahoa',
                counter: 'Pendaftaran 1',
                status: 'aktif',
                queueToday: 24
            },
            {
                id: 2,
                name: 'Rehan Blongkod',
                counter: 'Pendaftaran 2',
                status: 'aktif',
                queueToday: 18
            },
            {
                id: 3,
                name: 'Hendrik Hatibae',
                counter: 'Farmasi',
                status: 'aktif',
                queueToday: 32
            },

        ];

        // Chart instance
        let dailyChart = null;
        let dropdownVisible = false;
        let notificationVisible = false;

        // Inisialisasi dashboard
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi data
            updateDashboard();

            // Setup dropdown menu
            setupDropdowns();

            // Setup chart
            setupCharts();

            // Setup event listeners
            setupEventListeners();

            // Setup modals
            setupModals();
        });

        // Update dashboard dengan data terbaru
        function updateDashboard() {
            updateOfficerTable();
        }

        // Update tabel petugas
        function updateOfficerTable() {
            const tableBody = document.getElementById('officerTable');
            tableBody.innerHTML = '';

            officersData.forEach(officer => {
                const statusColor = officer.status === 'aktif' ? 'bg-green-500/20 text-green-400' :
                    officer.status === 'istirahat' ? 'bg-yellow-500/20 text-yellow-400' :
                    'bg-red-500/20 text-red-400';

                const statusText = officer.status === 'aktif' ? 'Aktif' :
                    officer.status === 'istirahat' ? 'Istirahat' :
                    'Off';

                const row = document.createElement('tr');
                row.className = 'border-b border-gray-800 table-row-hover';
                row.innerHTML = `
                    <td class="py-4 px-4">
                        <div class="flex items-center">
                            <div class="bg-blue-500/20 p-2 rounded-lg mr-3">
                                <i class="fas fa-user text-blue-400"></i>
                            </div>
                            <span class="font-medium">#${officer.id.toString().padStart(3, '0')}</span>
                        </div>
                    </td>
                    <td class="py-4 px-4 font-medium">${officer.name}</td>
                    <td class="py-4 px-4">
                        <span class="bg-blue-900/30 text-blue-300 px-3 py-1 rounded-full text-sm">${officer.counter}</span>
                    </td>
                    <td class="py-4 px-4">
                        <span class="${statusColor} px-3 py-1 rounded-full text-sm">${statusText}</span>
                    </td>
                    <td class="py-4 px-4">
                        <div class="flex items-center">
                            <span class="font-bold mr-2">${officer.queueToday}</span>
                            <span class="text-gray-400 text-sm">antrian</span>
                        </div>
                    </td>
                    <td class="py-4 px-4">
                        <div class="flex space-x-2">
                            <button class="edit-officer-btn bg-blue-500/20 text-blue-400 p-2 rounded-lg hover:bg-blue-500/30" data-id="${officer.id}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="delete-officer-btn bg-red-500/20 text-red-400 p-2 rounded-lg hover:bg-red-500/30" data-id="${officer.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                `;
                tableBody.appendChild(row);
            });

            // Tambahkan event listener untuk tombol edit dan delete
            document.querySelectorAll('.edit-officer-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = parseInt(this.getAttribute('data-id'));
                    editOfficer(id);
                });
            });

            document.querySelectorAll('.delete-officer-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = parseInt(this.getAttribute('data-id'));
                    confirmDeleteOfficer(id);
                });
            });
        }

        // Setup dropdown menu
        function setupDropdowns() {
            const userMenuBtn = document.getElementById('userMenuBtn');
            const userDropdown = document.getElementById('userDropdown');
            const notificationBtn = document.getElementById('notificationBtn');
            const notificationDropdown = document.getElementById('notificationDropdown');

            // User dropdown
            userMenuBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdownVisible = !dropdownVisible;
                if (dropdownVisible) {
                    userDropdown.classList.remove('hidden');
                } else {
                    userDropdown.classList.add('hidden');
                }

                // Tutup notification dropdown jika terbuka
                if (notificationVisible) {
                    notificationDropdown.classList.add('hidden');
                    notificationVisible = false;
                }
            });

            // Notification dropdown
            notificationBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                notificationVisible = !notificationVisible;
                if (notificationVisible) {
                    notificationDropdown.classList.remove('hidden');
                } else {
                    notificationDropdown.classList.add('hidden');
                }

                // Tutup user dropdown jika terbuka
                if (dropdownVisible) {
                    userDropdown.classList.add('hidden');
                    dropdownVisible = false;
                }
            });

            // Tutup dropdown saat klik di luar
            document.addEventListener('click', function(e) {
                if (!userDropdown.contains(e.target) && !userMenuBtn.contains(e.target)) {
                    userDropdown.classList.add('hidden');
                    dropdownVisible = false;
                }

                if (!notificationDropdown.contains(e.target) && !notificationBtn.contains(e.target)) {
                    notificationDropdown.classList.add('hidden');
                    notificationVisible = false;
                }
            });
        }

        // Setup charts
        function setupCharts() {
            const ctx = document.getElementById('dailyChart').getContext('2d');

            // Data contoh untuk grafik
            const days = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
            const queueData = [120, 135, 142, 128, 155, 98, 75];
            const servedData = [115, 130, 138, 125, 150, 95, 72];

            dailyChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: days,
                    datasets: [{
                            label: 'Total Antrian',
                            data: queueData,
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Terlayani',
                            data: servedData,
                            borderColor: 'rgb(16, 185, 129)',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                color: '#9ca3af'
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(75, 85, 99, 0.3)'
                            },
                            ticks: {
                                color: '#9ca3af'
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(75, 85, 99, 0.3)'
                            },
                            ticks: {
                                color: '#9ca3af'
                            }
                        }
                    }
                }
            });
        }

        // Setup event listeners
        function setupEventListeners() {
            // Tombol tambah petugas
            document.getElementById('btnAddOfficer').addEventListener('click', function() {
                showAddOfficerModal();
            });

            // Tombol logout
            document.getElementById('btnLogout').addEventListener('click', function() {
                showLogoutModal();
            });

            // Navigation links
            document.querySelectorAll('.nav-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    // Remove active class from all links
                    document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
                    // Add active class to clicked link
                    this.classList.add('active');

                    // Show notification jika klik antrian
                    if (this.textContent.includes('Antrian')) {
                        showNotification('Fitur antrian sedang dikembangkan', 'info');
                    }
                });
            });

            // Quick actions
            document.querySelectorAll('.bg-blue-900\\/30, .bg-green-900\\/30, .bg-purple-900\\/30, .bg-red-900\\/30')
                .forEach(btn => {
                    btn.addEventListener('click', function() {
                        const text = this.querySelector('span').textContent;
                        showNotification(`Membuka ${text.toLowerCase()}...`, 'info');
                    });
                });
        }

        // Setup modals
        function setupModals() {
            // Modal tambah petugas
            const addOfficerModal = document.getElementById('addOfficerModal');
            const closeAddOfficerBtn = document.getElementById('closeAddOfficerModal');
            const cancelAddOfficerBtn = document.getElementById('cancelAddOfficer');

            closeAddOfficerBtn.addEventListener('click', function() {
                addOfficerModal.style.display = 'none';
            });

            cancelAddOfficerBtn.addEventListener('click', function() {
                addOfficerModal.style.display = 'none';
            });

            // Form tambah petugas
            document.getElementById('addOfficerForm').addEventListener('submit', function(e) {
                e.preventDefault();
                addNewOfficer();
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

            // Tutup modal saat klik di luar konten
            window.addEventListener('click', function(event) {
                if (event.target === addOfficerModal) {
                    addOfficerModal.style.display = 'none';
                }
                if (event.target === logoutModal) {
                    logoutModal.style.display = 'none';
                }
            });
        }

        // Tampilkan modal tambah petugas
        function showAddOfficerModal() {
            const modal = document.getElementById('addOfficerModal');
            modal.style.display = 'flex';

            // Reset form
            document.getElementById('addOfficerForm').reset();
        }

        // Tambah petugas baru
        function addNewOfficer() {
            const form = document.getElementById('addOfficerForm');
            const formData = new FormData(form);

            // Simulasi pengiriman data
            showNotification('Menambahkan petugas baru...', 'info');

            setTimeout(() => {
                // Tambahkan data baru
                const newOfficer = {
                    id: officersData.length + 1,
                    name: formData.get('nama') || 'Petugas Baru',
                    counter: 'Pendaftaran 1',
                    status: 'aktif',
                    queueToday: 0
                };

                officersData.push(newOfficer);
                updateDashboard();

                // Tutup modal
                document.getElementById('addOfficerModal').style.display = 'none';

                // Tampilkan notifikasi sukses
                showNotification('Petugas baru berhasil ditambahkan!', 'success');
            }, 1500);
        }

        // Edit petugas
        function editOfficer(id) {
            const officer = officersData.find(o => o.id === id);
            if (officer) {
                showNotification(`Mengedit data ${officer.name}...`, 'info');

                // Simulasi membuka modal edit
                setTimeout(() => {
                    alert(`Fitur edit untuk ${officer.name} sedang dikembangkan`);
                }, 500);
            }
        }

        // Konfirmasi hapus petugas
        function confirmDeleteOfficer(id) {
            const officer = officersData.find(o => o.id === id);
            if (officer) {
                if (confirm(`Apakah Anda yakin ingin menghapus ${officer.name} dari daftar petugas?`)) {
                    deleteOfficer(id);
                }
            }
        }

        // Hapus petugas
        function deleteOfficer(id) {
            showNotification('Menghapus petugas...', 'info');

            setTimeout(() => {
                officersData = officersData.filter(o => o.id !== id);
                updateDashboard();
                showNotification('Petugas berhasil dihapus!', 'success');
            }, 1000);
        }

        // Tampilkan modal logout
        function showLogoutModal() {
            const modal = document.getElementById('logoutModal');
            modal.style.display = 'flex';

            // Tutup dropdown jika terbuka
            const userDropdown = document.getElementById('userDropdown');
            userDropdown.classList.add('hidden');
            dropdownVisible = false;
        }

        // Proses logout
        function performLogout() {
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
                    alert(
                        'Logout berhasil!\n\nAnda telah keluar dari sistem admin.\n\nSekarang Anda akan diarahkan ke halaman login.'
                    );

                    // Reset UI untuk demo
                    resetAdminUI();
                }, 2000);
            }, 1000);
        }

        // Reset UI untuk demo logout
        function resetAdminUI() {
            // Reset data petugas
            officersData = [{
                    id: 1,
                    name: 'Syahril Bahoa',
                    counter: 'Pendaftaran 1',
                    status: 'aktif',
                    queueToday: 24
                },
                {
                    id: 2,
                    name: 'Andi Setiawan',
                    counter: 'Pendaftaran 2',
                    status: 'aktif',
                    queueToday: 18
                },
                {
                    id: 3,
                    name: 'Budi Santoso',
                    counter: 'Farmasi',
                    status: 'aktif',
                    queueToday: 32
                }
            ];

            // Update dashboard dengan data reset
            updateDashboard();

            // Update nama user di header
            const userName = document.querySelector('.text-left .font-medium');
            if (userName) {
                userName.textContent = '[Telah Logout]';
            }

            // Update notifikasi
            const notificationBadge = document.querySelector('.notification-badge');
            if (notificationBadge) {
                notificationBadge.textContent = '0';
            }
        }

        // Tampilkan notifikasi
        function showNotification(message, type) {
            // Buat elemen notifikasi
            const notification = document.createElement('div');
            notification.className =
                `fixed top-4 right-4 ${type === 'success' ? 'bg-green-500' : type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500'} text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-pulse`;
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
    </script>
    <script>
        function performLogout() {
            showNotification('Sedang mengeluarkan Anda dari sistem...', 'info');

            fetch("{{ route('logout') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .content,
                        "Accept": "application/json"
                    }
                })
                .then(response => {
                    if (response.ok) {
                        showNotification('Logout berhasil! Mengalihkan ke halaman login...', 'success');

                        setTimeout(() => {
                            window.location.href = "/login";
                        }, 1000);
                    } else {
                        throw new Error('Logout gagal');
                    }
                })
                .catch(error => {
                    console.error(error);
                    showNotification('Terjadi kesalahan saat logout', 'warning');
                });
        }
    </script>

</body>

</html>