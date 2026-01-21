<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anjungan Cetak Antrian Pasien</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Custom styles untuk kiosk */
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 5px #3b82f6; }
            50% { box-shadow: 0 0 20px #3b82f6, 0 0 30px #2563eb; }
        }
        
        .kiosk-screen {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            min-height: 100vh;
        }
        
        .btn-cetak {
            transition: all 0.3s ease;
            animation: pulse-glow 2s infinite;
        }
        
        .btn-cetak:hover {
            transform: scale(1.05);
            animation: none;
        }
        
        .ticket-animation {
            transition: all 0.5s ease;
        }
        
        .ticket-printed {
            transform: translateY(-20px);
            opacity: 0.9;
        }
        
        /* Disable text selection untuk kiosk */
        .kiosk-screen * {
            user-select: none;
        }
        
        /* Efek cetak karcis */
        @keyframes printTicket {
            0% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-10px) scale(1.02); }
            100% { transform: translateY(0) scale(1); }
        }
    </style>
</head>
<body class="kiosk-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <header class="text-center mb-12">
            <div class="flex justify-center items-center mb-4">
                <div class="bg-white p-3 rounded-full mr-4">
                    <i class="fas fa-hospital text-blue-600 text-3xl"></i>
                </div>
                <h1 class="text-5xl font-bold text-white">ANJUNGAN CETAK KARCIS</h1>
            </div>
        </header>

        <div class="flex flex-col lg:flex-row justify-center items-center gap-8">
            <!-- Card 1: Tombol cetak antrian -->
            <div class="w-full lg:w-1/2">
                <div class="bg-white/10 backdrop-blur-sm rounded-3xl p-8 border border-white/20 shadow-2xl h-full">
                    <div class="text-center mb-10">
                        <h3 class="text-4xl font-bold text-white mb-4">CETAK ANTRIAN</h3>
                        <p class="text-blue-100 text-xl">Tekan tombol di bawah untuk mengambil nomor antrian pasien</p>
                    </div>
                    
                    <div class="flex flex-col items-center justify-center h-2/3">
                        <div class="mb-8">
                            <i class="fas fa-user-md text-white text-8xl"></i>
                        </div>
                        <div class="text-center mb-10">
                            <h4 class="text-3xl font-bold text-white mb-2">PENDAFTARAN PASIEN</h4>
                            <p class="text-blue-100 text-xl mb-4">Untuk pasien baru maupun lama</p>
                        
                        </div>
                        <!-- Tombol cetak karcis -->
                        <button id="btnCetakAntrian" class="btn-cetak w-4/5 bg-gradient-to-r from-blue-500 to-blue-700 hover:from-blue-600 hover:to-blue-800 text-white text-3xl font-bold py-8 px-6 rounded-2xl flex items-center justify-center shadow-2xl">
                            <i class="fas fa-ticket-alt mr-6 text-4xl"></i>
                            CETAK NOMOR ANTRIAN
                        </button>
                        
                        <div class="mt-10 text-center">
                            <div class="flex items-center justify-center text-white text-lg">
                                <i class="fas fa-info-circle mr-3 text-2xl"></i>
                                <p>Harap simpan karcis antrian Anda dengan baik</p>
                                <br><br>
                                <br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Card 2: Tampilan karcis -->
            <div class="w-full lg:w-1/2">
                <div class="bg-white/10 backdrop-blur-sm rounded-3xl p-8 border border-white/20 shadow-2xl h-full">
                    <div class="text-center mb-8">
                        <h3 class="text-3xl font-bold text-white">KARCIS ANDA</h3>
                    </div>
                    
                    <!-- Area karcis -->
                    <div id="ticketArea" class="bg-gradient-to-b from-white to-gray-100 rounded-2xl p-8 mb-8 min-h-[500px] flex flex-col items-center justify-center transition-all duration-500">
                        <div class="text-center">
                            <i class="fas fa-ticket-alt text-gray-300 text-9xl mb-6"></i>
                            <p class="text-gray-500 text-2xl">Karcis antrian akan muncul di sini</p>
                            <p class="text-gray-400 text-lg mt-2">Tekan tombol "CETAK NOMOR ANTRIAN" untuk mulai</p>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    <script>
        // Data nomor antrian
        let nomorAntrian = 25;
        let totalAntrianHariIni = 142;
        
        // Fungsi untuk menghasilkan nomor antrian
        function cetakKarcis() {
            // Tambah nomor antrian
            nomorAntrian++;
            totalAntrianHariIni++;
            
            // Format nomor antrian (contoh: A-026)
            const nomorAntrianFormatted = `A-${nomorAntrian.toString().padStart(3, '0')}`;
            
            // Buat elemen karcis
            const ticketArea = document.getElementById('ticketArea');
            
            // Tambahkan efek animasi
            ticketArea.style.animation = 'printTicket 0.5s ease';
            
            // Set timeout untuk reset animasi
            setTimeout(() => {
                ticketArea.style.animation = '';
            }, 500);
            
            // Update tampilan karcis
            ticketArea.innerHTML = `
                <div class="text-center w-full">
                    <!-- Header karcis -->
                    <div class="mb-8 border-b-2 border-blue-200 pb-6">
                        <div class="flex justify-center items-center mb-4">
                            <i class="fas fa-hospital text-blue-600 text-5xl mr-4"></i>
                            <div>
                                <h2 class="text-4xl font-bold text-gray-800">RUMAH SAKIT SEHAT MANDIRI</h2>
                                <p class="text-gray-600 text-lg">Anjungan Pendaftaran Mandiri</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Informasi layanan -->
                    <div class="my-8">
                        <p class="text-gray-600 text-xl mb-2">LAYANAN</p>
                        <h3 class="text-5xl font-bold text-blue-700 mb-4">PENDAFTARAN PASIEN</h3>
                        <div class="inline-block bg-blue-100 text-blue-800 px-8 py-3 rounded-full font-semibold text-xl">
                            <i class="fas fa-clock mr-3"></i>Estimasi Tunggu: 15-30 menit
                        </div>
                    </div>
                    
                    <!-- Nomor antrian -->
                    <div class="my-10">
                        <p class="text-gray-600 text-xl mb-2">NOMOR ANTRIAN ANDA</p>
                        <div class="text-8xl font-bold text-blue-800 mb-4">${nomorAntrianFormatted}</div>
                        <p class="text-gray-500 text-lg">Harap menunggu di ruang tunggu hingga nomor Anda dipanggil</p>
                    </div>
                    
                    <!-- Informasi waktu -->
                    <div class="mt-12 pt-8 border-t-2 border-gray-300 w-full">
                        <div class="flex justify-between items-center text-gray-700 px-4">
                            <div class="text-left">
                                <p class="font-semibold text-lg">Tanggal</p>
                                <p class="text-2xl">${new Date().toLocaleDateString('id-ID')}</p>
                            </div>
                            <div class="text-center">
                                <div class="w-16 h-16 mx-auto mb-2">
                                    <div class="w-full h-full rounded-full border-4 border-blue-500 flex items-center justify-center">
                                        <i class="fas fa-ticket-alt text-blue-500 text-2xl"></i>
                                    </div>
                                </div>
                                <p class="text-gray-500">Karcis Antrian</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-lg">Waktu</p>
                                <p class="text-2xl">${new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'})}</p>
                            </div>
                        </div>
                        
                        <div class="mt-8 bg-blue-50 rounded-xl p-4">
                            <div class="flex items-start">
                                <i class="fas fa-info-circle text-blue-600 text-xl mr-3 mt-1"></i>
                                <div>
                                    <p class="text-blue-800 font-semibold">Simpan karcis ini dengan baik!</p>
                                    <p class="text-blue-600 text-sm">Tunjukkan karcis ini ke petugas saat nomor Anda dipanggil. Nomor antrian akan dipanggil melalui pengeras suara di ruang tunggu.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Update informasi antrian
            document.getElementById('antrianSekarang').textContent = nomorAntrianFormatted;
            document.getElementById('antrianTerakhir').textContent = `A-${(nomorAntrian-1).toString().padStart(3, '0')}`;
            document.getElementById('totalAntrian').textContent = totalAntrianHariIni;
            
            // Simulasikan suara printer
            playPrinterSound();
            
            // Tampilkan pesan sukses
            showMessage(`Karcis ${nomorAntrianFormatted} berhasil dicetak!`);
        }
        
        // Fungsi untuk memainkan suara printer (simulasi)
        function playPrinterSound() {
            // Membuat elemen audio untuk suara printer
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();
            
            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);
            
            oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
            oscillator.frequency.exponentialRampToValueAtTime(200, audioContext.currentTime + 0.5);
            
            gainNode.gain.setValueAtTime(0.1, audioContext.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);
            
            oscillator.start();
            oscillator.stop(audioContext.currentTime + 0.5);
        }
        
        // Fungsi untuk menampilkan pesan
        function showMessage(message) {
            // Buat elemen pesan
            const messageElement = document.createElement('div');
            messageElement.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-pulse';
            messageElement.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-3 text-xl"></i>
                    <span class="font-semibold text-lg">${message}</span>
                </div>
            `;
            
            document.body.appendChild(messageElement);
            
            // Hapus pesan setelah 3 detik
            setTimeout(() => {
                messageElement.remove();
            }, 3000);
        }
        
        // Event listener untuk tombol cetak karcis
        document.getElementById('btnCetakAntrian').addEventListener('click', cetakKarcis);
        
        // Event listener untuk touch (optimasi tablet/kiosk)
        document.getElementById('btnCetakAntrian').addEventListener('touchstart', function(e) {
            this.classList.add('scale-95');
            e.preventDefault(); // Mencegah zoom pada perangkat touch
        });
        
        document.getElementById('btnCetakAntrian').addEventListener('touchend', function() {
            this.classList.remove('scale-95');
        });
        
        // Update waktu secara real-time
        function updateWaktu() {
            const now = new Date();
            const timeStr = now.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
            
            // Update waktu di karcis jika sudah dicetak
            const waktuElements = document.querySelectorAll('.text-2xl');
            waktuElements.forEach(el => {
                if (el.textContent.includes(':') && el.previousElementSibling && 
                    el.previousElementSibling.textContent === 'Waktu') {
                    el.textContent = timeStr;
                }
            });
        }
        
        // Simulasi panggilan antrian (berubah setiap 2 menit)
        function simulasiPanggilanAntrian() {
            const sekarang = document.getElementById('antrianSekarang');
            const terakhir = document.getElementById('antrianTerakhir');
            
            // Ambil nomor dari text content
            const nomorSekarang = parseInt(sekarang.textContent.split('-')[1]);
            const nomorTerakhir = parseInt(terakhir.textContent.split('-')[1]);
            
            // Jika nomor sekarang lebih besar dari nomor terakhir, panggil antrian berikutnya
            if (nomorSekarang > nomorTerakhir) {
                // Update antrian terakhir
                terakhir.textContent = sekarang.textContent;
                
                // Panggil antrian berikutnya (jika ada)
                if (nomorSekarang < nomorAntrian) {
                    const nextNumber = nomorSekarang + 1;
                    sekarang.textContent = `A-${nextNumber.toString().padStart(3, '0')}`;
                }
            }
        }
        
        // Jalankan update waktu setiap detik
        setInterval(updateWaktu, 1000);
        
        // Jalankan simulasi panggilan antrian setiap 2 menit
        setInterval(simulasiPanggilanAntrian, 120000);
        
        // Inisialisasi
        updateWaktu();
    </script>
</body>
</html>