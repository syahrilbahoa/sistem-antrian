<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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

            0%,
            100% {
                box-shadow: 0 0 5px #3b82f6;
            }

            50% {
                box-shadow: 0 0 20px #3b82f6, 0 0 30px #2563eb;
            }
        }

        @keyframes pulse-red {

            0%,
            100% {
                box-shadow: 0 0 5px #ef4444;
            }

            50% {
                box-shadow: 0 0 15px #ef4444, 0 0 25px #dc2626;
            }
        }

        .ticket-called {
            animation: slide-out 0.5s ease forwards;
        }

        @keyframes slide-out {
            0% {
                transform: translateX(0);
                opacity: 1;
            }

            100% {
                transform: translateX(100px);
                opacity: 0;
            }
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

        .tab-active {
            background-color: #3b82f6;
            color: white;
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
                    <button id="userMenuBtn"
                        class="bg-white/20 p-3 rounded-full cursor-pointer hover:bg-white/30 transition focus:outline-none">
                        <i class="fas fa-user-circle text-white text-2xl"></i>
                    </button>
                    <!-- Dropdown Logout -->
                    <div id="userDropdown"
                        class="dropdown-menu mt-2 w-48 bg-white rounded-lg shadow-xl overflow-hidden z-50 dropdown-enter">
                        <div class="px-4 py-3 border-b">
                            <p class="text-sm font-medium text-gray-900">Syahril Bahoa</p>
                            <p class="text-xs text-gray-500">Petugas Pendaftaran</p>
                        </div>
                        <button id="btnLogout"
                            class="w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50 flex items-center focus:outline-none">
                            <i class="fas fa-sign-out-alt mr-3"></i> Keluar dari Sistem
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Dashboard -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Status antrian -->
            <div class="lg:col-span-3">
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
                            <p class="text-white text-4xl font-bold" id="currentCalled">--</p>
                            <div class="mt-2">
                                <span class="text-green-300 text-sm" id="calledTimeInfo"><i
                                        class="fas fa-clock mr-1"></i>--</span>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-green-600 to-green-800 rounded-xl p-4 text-center">
                            <p class="text-green-200 text-sm mb-1">ANTRIAN BERIKUTNYA</p>
                            <p class="text-white text-4xl font-bold" id="nextInLine">--</p>
                            <p class="text-green-200 text-sm mt-2" id="nextStatus">Menunggu panggilan</p>
                        </div>

                        <div class="bg-gradient-to-r from-purple-600 to-purple-800 rounded-xl p-4 text-center">
                            <p class="text-purple-200 text-sm mb-1">TOTAL ANTRIAN HARI INI</p>
                            <p class="text-white text-4xl font-bold" id="totalToday">0</p>
                            <p class="text-purple-200 text-sm mt-2" id="servedTodayInfo">Terlayani: 0</p>
                        </div>
                    </div>

                    <!-- Tombol kontrol -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <button id="btnCall"
                            class="btn-call bg-gradient-to-r from-green-500 to-green-700 hover:from-green-600 hover:to-green-800 text-white text-2xl font-bold py-6 px-4 rounded-xl flex flex-col items-center justify-center">
                            <i class="fas fa-bullhorn text-4xl mb-3"></i>
                            PANGGIL ANTRIAN BERIKUTNYA
                            <span class="text-green-100 text-lg mt-2" id="nextNumberDisplay">(--)</span>
                        </button>

                        <button id="btnSkip"
                            class="btn-skip bg-gradient-to-r from-red-500 to-red-700 hover:from-red-600 hover:to-red-800 text-white text-2xl font-bold py-6 px-4 rounded-xl flex flex-col items-center justify-center">
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
                        <button id="btnRefresh"
                            class="bg-blue-600 hover:bg-blue-700 text-white py-3 px-6 rounded-lg flex items-center justify-center mx-auto">
                            <i class="fas fa-sync-alt mr-3"></i> REFRESH DAFTAR ANTRIAN
                        </button>
                    </div>
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

        <!-- Footer -->
        <footer class="mt-8 text-center text-blue-200">
            <p class="text-sm">
                <i class="fas fa-hospital mr-2"></i>Klinik Anugrah Farma - Dashboard Petugas Antrian v1.0
            </p>
            <p class="text-xs mt-1 text-blue-300">© 2025 Klinik Anugrah Farma. Hak Cipta Dilindungi.</p>
        </footer>
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
                            <p class="text-yellow-700 text-sm">Pastikan semua antrian telah diproses sebelum logout.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4">
                <button id="cancelLogout" class="bg-gray-300 hover:bg-gray-400 text-gray-800 py-2 px-6 rounded-lg">
                    Batal
                </button>
                <button id="confirmLogout"
                    class="bg-red-600 hover:bg-red-700 text-white py-2 px-6 rounded-lg flex items-center">
                    <i class="fas fa-sign-out-alt mr-2"></i> Ya, Logout
                </button>
            </div>
        </div>
    </div>

    <script>
        // Data antrian
        let queueData = {
            current: null,
            next: null,
            totalToday: 0,
            servedToday: 0,
            skippedToday: 0,
            queueList: [],
            callHistory: [],
            skippedList: []
        };

        // Chart instance
        let monthlyChart = null;
        let dropdownVisible = false;

        // Auto refresh interval (dalam milidetik)
        const REFRESH_INTERVAL = 3000; // 3 detik
        let refreshTimer = null;

        // Inisialisasi dashboard
        document.addEventListener('DOMContentLoaded', function() {
            // Update waktu
            updateDateTime();
            setInterval(updateDateTime, 1000);

            // Load data dari server
            loadQueueData();

            // Setup polling untuk realtime update
            startRealtimeRefresh();

            // Setup dropdown user menu
            setupUserDropdown();

            // Setup event listeners
            setupEventListeners();

            // Setup modal
            setupModals();
        });

        // Fungsi untuk toggle dropdown user menu

        // Load data antrian dari server
        async function loadQueueData() {
            try {
                const response = await fetch('/api/antrian-data');
                if (response.ok) {
                    const data = await response.json();

                    // Update data lokal
                    queueData.current = data.current;
                    queueData.totalToday = data.totalToday;
                    queueData.servedToday = data.servedToday;
                    queueData.skippedToday = data.skippedToday;
                    queueData.queueList = data.queueList || [];
                    queueData.callHistory = data.callHistory || [];
                    queueData.skippedList = data.skippedList || [];

                    // Update tampilan
                    updateDashboard();
                }
            } catch (error) {
                console.error('Error loading queue data:', error);
            }
        }

        // Start realtime refresh dengan polling
        function startRealtimeRefresh() {
            if (refreshTimer) {
                clearInterval(refreshTimer);
            }

            refreshTimer = setInterval(async function() {
                await loadQueueData();
            }, REFRESH_INTERVAL);
        }

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
            // Update status antrian yang sedang dipanggil
            if (queueData.current) {
                document.getElementById('currentCalled').textContent = queueData.current.nomor_antrian;
                document.getElementById('calledTimeInfo').innerHTML =
                    `<i class="fas fa-clock mr-1"></i>${queueData.current.waktu_panggil || '--'}`;
            } else {
                document.getElementById('currentCalled').textContent = '--';
                document.getElementById('calledTimeInfo').innerHTML = '<i class="fas fa-clock mr-1"></i>--';
            }

            // Update antrian berikutnya
            if (queueData.queueList.length > 0) {
                document.getElementById('nextInLine').textContent = queueData.queueList[0].number;
                document.getElementById('nextNumberDisplay').textContent = `(${queueData.queueList[0].number})`;
                document.getElementById('nextStatus').textContent = 'Menunggu panggilan';
            } else {
                document.getElementById('nextInLine').textContent = '--';
                document.getElementById('nextNumberDisplay').textContent = '(Tidak ada antrian)';
                document.getElementById('nextStatus').textContent = 'Tidak ada antrian';
            }

            // Update total
            document.getElementById('totalToday').textContent = queueData.totalToday;
            document.getElementById('servedTodayInfo').textContent =
                `Terlayani: ${queueData.servedToday} | Terlewat: ${queueData.skippedToday}`;

            // Update daftar antrian
            updateQueueList();

            // Update riwayat panggilan
            updateCallHistory();
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
                        <button class="call-specific-btn bg-blue-600 hover:bg-blue-700 text-white py-1 px-3 rounded text-sm" data-number="${item.id}">
                            <i class="fas fa-bullhorn mr-1"></i> Panggil
                        </button>
                    </td>
                `;
                queueList.appendChild(row);
            });

            // Tambahkan event listener untuk tombol panggil spesifik
            document.querySelectorAll('.call-specific-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const number = this.getAttribute(
                        'data-number'); // Ini sekarang berisi nomor antrian
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
        async function callNext() {
            // Ambil nomor antrian pertama dari daftar antrian
            if (queueData.queueList.length > 0) {
                const nextQueue = queueData.queueList[0]; // Ambil antrian pertama

                try {
                    // Kirim permintaan ke server untuk memanggil antrian
                    const response = await fetch(`/panggil/${encodeURIComponent(nextQueue.number)}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    if (response.ok) {
                        // Refresh data dari server setelah panggilan berhasil
                        await loadQueueData();

                        // Tampilkan notifikasi
                        showNotification(`Antrian ${nextQueue.number} berhasil dipanggil!`, 'success');
                    } else {
                        throw new Error('Gagal memanggil antrian');
                    }
                } catch (error) {
                    console.error('Error calling queue:', error);
                    showNotification('Gagal memanggil antrian. Silakan coba lagi.', 'warning');
                }
            } else {
                showNotification('Tidak ada antrian untuk dipanggil.', 'warning');
            }
        }

        // Panggil antrian spesifik
        async function callSpecificNumber(number) {
            // Cari antrian dalam daftar
            const queueItem = queueData.queueList.find(item => item.number === number);

            if (queueItem) {
                try {
                    // Kirim permintaan ke server untuk memanggil antrian
                    const response = await fetch(`/panggil/${encodeURIComponent(queueItem.number)}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    if (response.ok) {
                        // Refresh data dari server setelah panggilan berhasil
                        await loadQueueData();

                        // Tampilkan notifikasi
                        showNotification(`Antrian ${number} berhasil dipanggil!`, 'success');
                    } else {
                        throw new Error('Gagal memanggil antrian');
                    }
                } catch (error) {
                    console.error('Error calling specific queue:', error);
                    showNotification('Gagal memanggil antrian. Silakan coba lagi.', 'warning');
                }
            } else {
                showNotification(`Antrian ${number} tidak ditemukan.`, 'warning');
            }
        }

        // Lewati antrian
        async function skipCurrent() {
            if (queueData.queueList.length > 0) {
                const skipQueue = queueData.queueList[0];

                try {
                    const response = await fetch(`/skip/${encodeURIComponent(skipQueue.number)}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    if (response.ok) {
                        // Refresh data dari server setelah skip berhasil
                        await loadQueueData();

                        showNotification(`Antrian ${skipQueue.number} ditandai sebagai terlewat!`, 'warning');
                    } else {
                        throw new Error('Gagal menandai antrian terlewat');
                    }
                } catch (error) {
                    console.error('Error skipping queue:', error);
                    showNotification('Gagal menandai antrian terlewat. Silakan coba lagi.', 'warning');
                }
            } else {
                showNotification('Tidak ada antrian untuk dilewati.', 'warning');
            }
        }

        // Setup event listeners
        function setupEventListeners() {
            // Tombol panggil antrian berikutnya
            document.getElementById('btnCall').addEventListener('click', callNext);

            // Tombol lewati antrian
            document.getElementById('btnSkip').addEventListener('click', skipCurrent);

            // Tombol refresh
            document.getElementById('btnRefresh').addEventListener('click', async function() {
                showNotification('Memperbarui daftar antrian...', 'info');
                await loadQueueData();
                showNotification('Daftar antrian diperbarui!', 'success');
            });

            // Tombol logout
            document.getElementById('btnLogout').addEventListener('click', function() {
                showLogoutModal();
            });
        }

        // Setup modal
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
        }

        // Tampilkan notifikasi
        function showNotification(message, type) {
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

            setTimeout(() => {
                notification.remove();
            }, 3000);
        }

        // Simulasi suara panggilan
        function playCallSound() {
            const audioContext = new(window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();

            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);

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

        // Proses logout
        function performLogout() {
            showNotification('Sedang mengeluarkan Anda dari sistem...', 'info');
            document.getElementById('logoutModal').style.display = 'none';

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
                    showNotification('Terjadi kesalahan saat logout', 'warning');
                    console.error(error);
                });
        }
    </script>

</body>

</html>
