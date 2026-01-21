<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Antrian dengan Video</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #0f172a;
        }

        /* Animasi untuk nomor antrian yang dipanggil */
        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        .pulse-animation {
            animation: pulse 2s infinite;
        }

        /* Animasi untuk teks berjalan */
        @keyframes marquee {
            0% {
                transform: translateX(100%);
            }

            100% {
                transform: translateX(-100%);
            }
        }

        .marquee-container {
            overflow: hidden;
            white-space: nowrap;
            box-sizing: border-box;
        }

        .marquee-content {
            display: inline-block;
            animation: marquee 25s linear infinite;
            padding-left: 100%;
        }

        /* Custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #1e293b;
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #475569;
            border-radius: 4px;
        }

        /* Glow effect untuk card loket */
        .loket-glow {
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.3);
        }

        /* Styling untuk video container */
        .video-container {
            position: relative;
            padding-bottom: 56.25%;
            /* 16:9 aspect ratio */
            height: 0;
            overflow: hidden;
            border-radius: 12px;
        }

        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>
</head>

<body class="bg-slate-900 text-white">
    <!-- Header dengan running text -->
    <header class="bg-gradient-to-r from-blue-900 to-purple-900 py-3 px-4 shadow-lg">
        <div class="container mx-auto">
            <div class="marquee-container">
                <div class="marquee-content text-lg font-semibold">
                    <span class="mx-8"><i class="fas fa-bullhorn text-yellow-300 mr-2"></i> SELAMAT DATANG DI LAYANAN
                        KAMI - NOMOR ANTRIAN DAPAT DIAMBIL DI MESIN PENGAMBIL NOMOR -</span>
                    <span class="mx-8"><i class="fas fa-info-circle text-green-300 mr-2"></i> HARAP MEMPERHATIKAN NOMOR
                        ANTRIAN ANDA DAN LOKET YANG DIPANGGIL -</span>
                    <span class="mx-8"><i class="fas fa-clock text-red-300 mr-2"></i> WAKTU PELAYANAN: SENIN-JUMAT
                        08:00-16:00, SABTU 08:00-14:00 -</span>
                </div>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-4 py-6">
        <!-- Bagian atas: Video dan Antrian Saat Ini -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8">
            <!-- Video (kiri) - col-6 -->
            <div class="lg:col-span-6 bg-slate-800 rounded-2xl shadow-2xl p-4">
                <div class="flex items-center mb-4">
                    <i class="fas fa-video text-blue-400 text-2xl mr-3"></i>
                    <h2 class="text-2xl font-bold text-blue-300">VIDEO INFORMASI</h2>
                </div>

                <div class="video-container mb-4">
                    <!-- Video embed dari YouTube (contoh video informasi layanan publik) -->
                    <iframe width="560" height="315" src="https://www.youtube.com/embed/3jRO3kt47qs?si=82nSe_DiXeXFpzPY"
                        title="YouTube video player" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                </div>


            </div>

            <!-- Antrian Saat Ini (kanan) - col-6 -->
            <div class="lg:col-span-6 bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl shadow-2xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-3xl font-bold text-white">ANTRIAN SAAT INI</h2>
                        <p class="text-slate-300 mt-1">Nomor yang sedang dipanggil</p>
                    </div>
                    <div class="text-right">
                        <div class="text-xl font-semibold text-blue-300">
                            <i class="far fa-clock mr-2"></i>
                            <span id="current-time">--:--:--</span>
                        </div>
                        <div class="text-slate-300" id="current-date">-- --- ----</div>
                    </div>
                </div>

                <!-- Display besar untuk antrian yang sedang dipanggil -->
                <div class="bg-gradient-to-r from-blue-900 to-indigo-900 rounded-2xl p-8 text-center mb-6 loket-glow">
                    <div class="text-4xl font-bold text-blue-200 mb-12">NOMOR DIPANGGIL</div>
                    <div id="current-queue-number" class="text-9xl font-bold text-white mb-4 pulse-animation">A042</div>
                    <br>
                    <br>
                </div>
            </div>
        </div>

        <!-- Bagian bawah: 4 Loket (masing-masing col-3) -->
        <div class="mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Loket 1 -->
                <div
                    class="bg-gradient-to-br from-blue-900 to-blue-800 rounded-2xl shadow-xl p-5 border-l-8 border-blue-500">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-2xl font-bold text-blue-300">LOKET 1</h3>
                        <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-sm font-bold">AKTIF</span>
                    </div>
                    <div class="mb-4">
                        <div class="text-blue-200 mb-1">Sedang Melayani</div>
                        <div class="text-4xl font-bold text-white text-center py-3" id="loket1-number">A042</div>
                    </div>
                </div>

                <!-- Loket 2 -->
                <div
                    class="bg-gradient-to-br from-emerald-900 to-emerald-800 rounded-2xl shadow-xl p-5 border-l-8 border-emerald-500">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-2xl font-bold text-emerald-300">LOKET 2</h3>
                        <span class="bg-emerald-500 text-white px-3 py-1 rounded-full text-sm font-bold">AKTIF</span>
                    </div>

                    <div class="mb-4">
                        <div class="text-emerald-200 mb-1">Sedang Melayani</div>
                        <div class="text-4xl font-bold text-white text-center py-3" id="loket2-number">B014</div>
                    </div>

                </div>

                <!-- Loket 3 -->
                <div
                    class="bg-gradient-to-br from-amber-900 to-amber-800 rounded-2xl shadow-xl p-5 border-l-8 border-amber-500">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-2xl font-bold text-amber-300">LOKET 3</h3>
                        <span class="bg-amber-500 text-white px-3 py-1 rounded-full text-sm font-bold">AKTIF</span>
                    </div>
                    <div class="mb-4">
                        <div class="text-amber-200 mb-1">Sedang Melayani</div>
                        <div class="text-4xl font-bold text-white text-center py-3" id="loket3-number">C027</div>
                    </div>
                </div>

                <!-- Loket 4 -->
                <div
                    class="bg-gradient-to-br from-purple-900 to-purple-800 rounded-2xl shadow-xl p-5 border-l-8 border-purple-500">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-2xl font-bold text-purple-300">LOKET 4</h3>
                        <span class="bg-purple-500 text-white px-3 py-1 rounded-full text-sm font-bold">SIAP</span>
                    </div>
                    <div class="mb-4">
                        <div class="text-purple-200 mb-1">Sedang Melayani</div>
                        <div class="text-4xl font-bold text-white text-center py-3" id="loket4-number">D011</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-slate-800 rounded-xl p-5 text-center">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                    <div class="flex items-center justify-center md:justify-start">
                        <i class="fas fa-ticket-alt text-blue-400 text-2xl mr-3"></i>
                        <h3 class="text-xl font-bold text-white">SISTEM ANTRIAN DIGITAL</h3>
                    </div>
                    <p class="text-slate-300 mt-2">Melayani dengan cepat, tepat, dan ramah</p>
                </div>
                <div class="text-slate-300">
                    <p><i class="far fa-clock mr-2"></i> Jam Operasional: 08:00 - 16:00</p>
                    <p class="mt-1"><i class="fas fa-phone mr-2"></i> Call Center: (021) 1234-5678</p>
                </div>
            </div>
            <div class="border-t border-slate-700 mt-4 pt-4 text-slate-400 text-sm">
                <p>© 2026 Display Antrian Digital. Update real-time setiap detik.</p>
            </div>
        </footer>
    </div>

    <script>
        // Fungsi untuk update waktu secara real-time
        function updateDateTime() {
            const now = new Date();

            // Format tanggal
            const optionsDate = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const formattedDate = now.toLocaleDateString('id-ID', optionsDate);
            document.getElementById('current-date').textContent = formattedDate;

            // Format waktu
            const hours = now.getHours().toString().padStart(2, '0');
            const minutes = now.getMinutes().toString().padStart(2, '0');
            const seconds = now.getSeconds().toString().padStart(2, '0');
            document.getElementById('current-time').textContent = `${hours}:${minutes}:${seconds}`;
        }

        // Data simulasi antrian
        const queueData = {
            loket1: { number: 'A042', type: 'PEMBAYARAN', next: ['A043', 'A044', 'A045'] },
            loket2: { number: 'B014', type: 'INFORMASI', next: ['B015', 'B016', 'B017'] },
            loket3: { number: 'C027', type: 'PENDAFTARAN', next: ['C028', 'C029', 'C030'] },
            loket4: { number: 'D011', type: 'PENGADUAN', next: ['D012', 'D013', 'D014'] }
        };

        // Simulasi perubahan antrian
        function simulateQueueChange() {
            const lokets = ['loket1', 'loket2', 'loket3', 'loket4'];
            const prefixes = ['A', 'B', 'C', 'D'];

            // Setiap 15 detik, ubah nomor antrian secara acak
            setInterval(() => {
                const randomLoketIndex = Math.floor(Math.random() * 4);
                const loketKey = lokets[randomLoketIndex];
                const prefix = prefixes[randomLoketIndex];

                // Update nomor di loket
                const currentNumber = parseInt(queueData[loketKey].number.substring(1));
                const newNumber = (currentNumber + 1).toString().padStart(3, '0');
                queueData[loketKey].number = prefix + newNumber;

                // Update antrian berikutnya
                const nextNumber = (currentNumber + 2).toString().padStart(3, '0');
                queueData[loketKey].next.unshift(prefix + nextNumber);
                queueData[loketKey].next.pop();

                // Update tampilan
                updateDisplay();

                // Jika ini adalah antrian yang sedang dipanggil, update juga display utama
                if (randomLoketIndex === 0) { // Misal loket 1 adalah yang sedang dipanggil
                    updateCurrentQueueDisplay(prefix + newNumber, `LOKET 1 - ${queueData[loketKey].type}`);
                }

            }, 15000); // 15 detik
        }

        // Update tampilan semua loket
        function updateDisplay() {
            for (let i = 1; i <= 4; i++) {
                const loketKey = `loket${i}`;
                document.getElementById(`${loketKey}-number`).textContent = queueData[loketKey].number;
            }
        }

        // Update display antrian yang sedang dipanggil
        function updateCurrentQueueDisplay(number, loketInfo) {
            const currentQueueElement = document.getElementById('current-queue-number');
            const currentLoketElement = document.getElementById('current-loket-name');

            // Tambahkan animasi
            currentQueueElement.classList.remove('pulse-animation');
            void currentQueueElement.offsetWidth; // Trigger reflow
            currentQueueElement.classList.add('pulse-animation');

            // Update teks
            currentQueueElement.textContent = number;
            currentLoketElement.textContent = loketInfo;

            // Update juga antrian berikutnya
            const nextQueueElements = document.querySelectorAll('#current-queue-section .bg-slate-700 .text-2xl');
            nextQueueElements[0].textContent = queueData.loket1.next[0];
            nextQueueElements[1].textContent = queueData.loket2.next[0];
            nextQueueElements[2].textContent = queueData.loket3.next[0];
            nextQueueElements[3].textContent = queueData.loket4.next[0];
        }

        // Rotasi antrian yang sedang dipanggil setiap 20 detik
        function rotateCurrentQueue() {
            const lokets = [
                { number: queueData.loket1.number, name: 'LOKET 1 - PEMBAYARAN' },
                { number: queueData.loket2.number, name: 'LOKET 2 - INFORMASI' },
                { number: queueData.loket3.number, name: 'LOKET 3 - PENDAFTARAN' },
                { number: queueData.loket4.number, name: 'LOKET 4 - PENGADUAN' }
            ];

            let currentIndex = 0;

            setInterval(() => {
                currentIndex = (currentIndex + 1) % 4;
                updateCurrentQueueDisplay(lokets[currentIndex].number, lokets[currentIndex].name);
            }, 20000); // 20 detik
        }

        // Inisialisasi
        document.addEventListener('DOMContentLoaded', function () {
            updateDateTime();
            setInterval(updateDateTime, 1000); // Update waktu setiap detik

            // Inisialisasi data awal
            updateDisplay();
            updateCurrentQueueDisplay(queueData.loket1.number, 'LOKET 1 - PEMBAYARAN');

            // Mulai simulasi
            simulateQueueChange();
            rotateCurrentQueue();
        });
    </script>
</body>

</html>