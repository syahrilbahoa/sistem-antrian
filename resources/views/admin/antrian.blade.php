<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{$title}}</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
    </style>
</head>

<body class="admin-bg text-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        @include('admin.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1">
            <!-- Header -->
            <header class="bg-gray-900/50 p-4 border-b border-gray-800">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="mb-4 md:mb-0">
                        <h1 class="text-2xl font-bold">
                            <i class="fas fa-shield-alt text-blue-500 mr-3"></i>
                            Data Antrian
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
                                <div class="max-h-64 overflow-y-auto" id="notificationsContainer">
                                    <!-- Notifikasi akan diisi secara dinamis dari backend -->
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

            <!-- Konten Antrian -->
            <div class="p-6">
                <div class="glass-card rounded-xl p-6 mb-8">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold">
                            <i class="fas fa-list-ol text-blue-400 mr-3"></i>
                            Daftar Antrian Hari Ini
                        </h2>
                        <div class="flex space-x-4">
                            <button class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg">
                                <i class="fas fa-sync-alt mr-2"></i> Refresh
                            </button>
                            <button class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg">
                                <i class="fas fa-print mr-2"></i> Cetak Laporan
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-700">
                                    <th class="py-3 px-4 text-left">No</th>
                                    <th class="py-3 px-4 text-left">No. Antrian</th>
                                    <th class="py-3 px-4 text-left">Nama Dokter</th>
                                    <th class="py-3 px-4 text-left">Waktu Ambil</th>
                                    <th class="py-3 px-4 text-left">Loket Tujuan</th>
                                    <th class="py-3 px-4 text-left">Status</th>
                                    <th class="py-3 px-4 text-left">Waktu Layanan</th>
                                    <th class="py-3 px-4 text-left">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="queueTable">
                                @forelse ($data as $key => $item)
                                <tr class="border-b border-gray-800 table-row-hover">
                                    <td class="py-3 px-4 font-medium">{{$key + 1}}</td>
                                    <td class="py-3 px-4">{{$item->nomor_antrian}}</td>
                                    <td class="py-3 px-4">{{$item->nama_dokter}}</td>
                                    <td class="py-3 px-4">{{$item->waktu_ambil}}</td>
                                    <td class="py-3 px-4">{{$item->nama_loket ?? 'belum dilayani'}}</td>
                                    <td class="py-3 px-4">
                                        @if($item->status == 'menunggu')
                                        <span class="bg-yellow-500/20 text-yellow-400 px-3 py-1 rounded-full text-sm">
                                            {{ $item->status }}
                                        </span>
                                        @elseif($item->status == 'dipanggil')
                                        <span class="bg-blue-500/20 text-blue-400 px-3 py-1 rounded-full text-sm">
                                            {{ $item->status }}
                                        </span>
                                        @elseif($item->status == 'selesai')
                                        <span class="bg-green-500/20 text-green-400 px-3 py-1 rounded-full text-sm">
                                            {{ $item->status }}
                                        </span>
                                        @else
                                        <span class="bg-gray-500/20 text-gray-400 px-3 py-1 rounded-full text-sm">
                                            {{ $item->status }}
                                        </span>
                                        @endif
                                    </td>

                                    <td class="py-3 px-4">{{$item->waktu_panggil}}</td>
                                    <td class="py-3 px-4">
                                        <div class="flex space-x-2">
                                            <button
                                                class="bg-blue-500/20 text-blue-400 p-2 rounded-lg hover:bg-blue-500/30">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button
                                                class="bg-green-500/20 text-green-400 p-2 rounded-lg hover:bg-green-500/30">
                                                <i class="fas fa-print"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty

                                @endforelse
                                <!-- <tr class="border-b border-gray-800 table-row-hover">
                                    <td class="py-3 px-4 font-medium">A-001</td>
                                    <td class="py-3 px-4">08:00</td>
                                    <td class="py-3 px-4">Pendaftaran 1</td>
                                    <td class="py-3 px-4">
                                        <span
                                            class="bg-green-500/20 text-green-400 px-3 py-1 rounded-full text-sm">Selesai</span>
                                    </td>
                                    <td class="py-3 px-4">08:15</td>
                                    <td class="py-3 px-4">
                                        <div class="flex space-x-2">
                                            <button
                                                class="bg-blue-500/20 text-blue-400 p-2 rounded-lg hover:bg-blue-500/30">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button
                                                class="bg-green-500/20 text-green-400 p-2 rounded-lg hover:bg-green-500/30">
                                                <i class="fas fa-print"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="border-b border-gray-800 table-row-hover">
                                    <td class="py-3 px-4 font-medium">A-002</td>
                                    <td class="py-3 px-4">08:05</td>
                                    <td class="py-3 px-4">Pendaftaran 1</td>
                                    <td class="py-3 px-4">
                                        <span
                                            class="bg-green-500/20 text-green-400 px-3 py-1 rounded-full text-sm">Selesai</span>
                                    </td>
                                    <td class="py-3 px-4">08:20</td>
                                    <td class="py-3 px-4">
                                        <div class="flex space-x-2">
                                            <button
                                                class="bg-blue-500/20 text-blue-400 p-2 rounded-lg hover:bg-blue-500/30">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button
                                                class="bg-green-500/20 text-green-400 p-2 rounded-lg hover:bg-green-500/30">
                                                <i class="fas fa-print"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="border-b border-gray-800 table-row-hover">
                                    <td class="py-3 px-4 font-medium">A-003</td>
                                    <td class="py-3 px-4">08:10</td>
                                    <td class="py-3 px-4">Pendaftaran 2</td>
                                    <td class="py-3 px-4">
                                        <span
                                            class="bg-yellow-500/20 text-yellow-400 px-3 py-1 rounded-full text-sm">Diproses</span>
                                    </td>
                                    <td class="py-3 px-4">-</td>
                                    <td class="py-3 px-4">
                                        <div class="flex space-x-2">
                                            <button
                                                class="bg-blue-500/20 text-blue-400 p-2 rounded-lg hover:bg-blue-500/30">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button
                                                class="bg-green-500/20 text-green-400 p-2 rounded-lg hover:bg-green-500/30">
                                                <i class="fas fa-print"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="border-b border-gray-800 table-row-hover">
                                    <td class="py-3 px-4 font-medium">A-004</td>
                                    <td class="py-3 px-4">08:15</td>
                                    <td class="py-3 px-4">Farmasi</td>
                                    <td class="py-3 px-4">
                                        <span
                                            class="bg-blue-500/20 text-blue-400 px-3 py-1 rounded-full text-sm">Menunggu</span>
                                    </td>
                                    <td class="py-3 px-4">-</td>
                                    <td class="py-3 px-4">
                                        <div class="flex space-x-2">
                                            <button
                                                class="bg-blue-500/20 text-blue-400 p-2 rounded-lg hover:bg-blue-500/30">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button
                                                class="bg-green-500/20 text-green-400 p-2 rounded-lg hover:bg-green-500/30">
                                                <i class="fas fa-print"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="border-b border-gray-800 table-row-hover">
                                    <td class="py-3 px-4 font-medium">A-005</td>
                                    <td class="py-3 px-4">08:20</td>
                                    <td class="py-3 px-4">Pendaftaran 1</td>
                                    <td class="py-3 px-4">
                                        <span
                                            class="bg-blue-500/20 text-blue-400 px-3 py-1 rounded-full text-sm">Menunggu</span>
                                    </td>
                                    <td class="py-3 px-4">-</td>
                                    <td class="py-3 px-4">
                                        <div class="flex space-x-2">
                                            <button
                                                class="bg-blue-500/20 text-blue-400 p-2 rounded-lg hover:bg-blue-500/30">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button
                                                class="bg-green-500/20 text-green-400 p-2 rounded-lg hover:bg-green-500/30">
                                                <i class="fas fa-print"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr> -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Statistik Antrian -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="glass-card rounded-xl p-6">
                        <div class="flex items-center">
                            <div class="bg-blue-500/20 p-3 rounded-full mr-4">
                                <i class="fas fa-list-ol text-blue-400 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-gray-400 text-sm">Total Antrian Hari Ini</p>
                                <p class="text-2xl font-bold mt-1" id="totalQueueToday">0</p>
                            </div>
                        </div>
                    </div>

                    <div class="glass-card rounded-xl p-6">
                        <div class="flex items-center">
                            <div class="bg-green-500/20 p-3 rounded-full mr-4">
                                <i class="fas fa-check-circle text-green-400 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-gray-400 text-sm">Sudah Dilayani</p>
                                <p class="text-2xl font-bold mt-1" id="servedToday">0</p>
                            </div>
                        </div>
                    </div>

                    <div class="glass-card rounded-xl p-6">
                        <div class="flex items-center">
                            <div class="bg-yellow-500/20 p-3 rounded-full mr-4">
                                <i class="fas fa-clock text-yellow-400 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-gray-400 text-sm">Belum Dilayani</p>
                                <p class="text-2xl font-bold mt-1" id="waitingToday">0</p>
                            </div>
                        </div>
                    </div>

                    <div class="glass-card rounded-xl p-6">
                        <div class="flex items-center">
                            <div class="bg-red-500/20 p-3 rounded-full mr-4">
                                <i class="fas fa-times-circle text-red-400 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-gray-400 text-sm">Terlewatkan</p>
                                <p class="text-2xl font-bold mt-1" id="missedToday">0</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
        // Data antrian akan diambil dari backend
        let queueData = [];

        let dropdownVisible = false;
        let notificationVisible = false;

        // Inisialisasi dashboard
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi data
            updateStatistics();

            // Setup dropdown menu
            setupDropdowns();

            // Setup event listeners
            setupEventListeners();

            // Setup modals
            setupModals();
        });

        // Update statistik antrian
        function updateStatistics() {
            // Update jumlah antrian
            document.getElementById('totalQueueToday').textContent = '42';
            document.getElementById('servedToday').textContent = '28';
            document.getElementById('waitingToday').textContent = '12';
            document.getElementById('missedToday').textContent = '2';
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

        // Setup event listeners
        function setupEventListeners() {
            // Tombol logout
            document.getElementById('btnLogout').addEventListener('click', function() {
                showLogoutModal();
            });


        }

        // Setup modals
        function setupModals() {
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
                if (event.target === logoutModal) {
                    logoutModal.style.display = 'none';
                }
            });
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
                }, 2000);
            }, 1000);
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
                            window.location.href = "/";
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