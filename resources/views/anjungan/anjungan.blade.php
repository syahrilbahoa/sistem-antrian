<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Anjungan Cetak Antrian Pasien</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Custom styles untuk kiosk */
        @keyframes pulse-glow {

            0%,
            100% {
                box-shadow: 0 0 5px #3b82f6;
            }

            50% {
                box-shadow: 0 0 20px #3b82f6, 0 0 30px #2563eb;
            }
        }

        .kiosk-screen {
            background: linear-gradient(135deg, #0B2F66 0%, #1a4a8a 100%);
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
            0% {
                transform: translateY(0) scale(1);
            }

            50% {
                transform: translateY(-10px) scale(1.02);
            }

            100% {
                transform: translateY(0) scale(1);
            }
        }

        /* Modern Modal Styles */
        .modal-modern {
            backdrop-filter: blur(8px);
            animation: modalFadeIn 0.3s ease;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .modal-content {
            animation: slideUp 0.4s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .doctor-card {
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .doctor-card:hover {
            border-color: #3b82f6;
            background: linear-gradient(to right, #f8fafc, #f1f5f9);
        }

        .doctor-card.selected {
            border-color: #3b82f6;
            background: linear-gradient(to right, #eff6ff, #dbeafe);
        }

        .ripple {
            position: relative;
            overflow: hidden;
        }

        .ripple:after {
            content: "";
            display: block;
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
            background-image: radial-gradient(circle, #fff 10%, transparent 10.01%);
            background-repeat: no-repeat;
            background-position: 50%;
            transform: scale(10, 10);
            opacity: 0;
            transition: transform 0.3s, opacity 0.5s;
        }

        .ripple:active:after {
            transform: scale(0, 0);
            opacity: 0.3;
            transition: 0s;
        }
    </style>
</head>

<body class="kiosk-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <header class="text-center mb-12">
            <div class="flex justify-center items-center mb-4">
                <div class="bg-white p-4 rounded-full mr-4 shadow-lg">
                    <i class="fas fa-hospital text-blue-600 text-4xl"></i>
                </div>
                <h1 class="text-5xl font-bold text-white tracking-wide">ANJUNGAN CETAK KARCIS</h1>
            </div>
            <div class="w-32 h-1 bg-white/30 mx-auto rounded-full"></div>
        </header>

        <div class="flex flex-col lg:flex-row justify-center items-center gap-8">
            <!-- Card 1: Tombol cetak antrian -->
            <div class="w-full lg:w-1/2">
                <div class="bg-white/10 backdrop-blur-md rounded-3xl p-8 border border-white/20 shadow-2xl h-full">
                    <div class="text-center mb-10">
                        <h3 class="text-4xl font-bold text-white mb-4">CETAK ANTRIAN</h3>
                        <p class="text-blue-100 text-xl">Tekan tombol di bawah untuk mengambil nomor antrian pasien</p>
                    </div>

                    <div class="flex flex-col items-center justify-center h-2/3">
                        <div class="mb-8 relative">
                            <div class="absolute inset-0 bg-white/20 rounded-full blur-xl"></div>
                            <i class="fas fa-user-md text-white text-8xl relative z-10"></i>
                        </div>
                        <div class="text-center mb-10">
                            <h4 class="text-3xl font-bold text-white mb-2">PENDAFTARAN PASIEN</h4>
                            <p class="text-blue-100 text-xl mb-4">Untuk pasien baru maupun lama</p>
                        </div>
                        <!-- Tombol cetak karcis -->
                        <button id="btnCetakAntrian"
                            class="btn-cetak w-4/5 bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:from-blue-600 hover:via-blue-700 hover:to-blue-800 text-white text-3xl font-bold py-8 px-6 rounded-2xl flex items-center justify-center shadow-2xl transform transition-all duration-300 hover:shadow-blue-500/50">
                            <i class="fas fa-ticket-alt mr-6 text-4xl"></i>
                            CETAK NOMOR ANTRIAN
                        </button>

                        <div class="mt-10 text-center">
                            <div class="flex items-center justify-center text-white/80 text-lg">
                                <i class="fas fa-info-circle mr-3 text-2xl"></i>
                                <p>Harap simpan karcis antrian Anda dengan baik</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Tampilan karcis -->
            <div class="w-full lg:w-1/2">
                <div class="bg-white/10 backdrop-blur-md rounded-3xl p-8 border border-white/20 shadow-2xl h-full">
                    <div class="text-center mb-8">
                        <h3 class="text-3xl font-bold text-white">KARCIS ANDA</h3>
                        <div class="w-16 h-1 bg-white/30 mx-auto rounded-full mt-2"></div>
                    </div>

                    <!-- Area karcis -->
                    <div id="ticketArea"
                        class="bg-gradient-to-b from-white to-gray-100 rounded-2xl p-8 mb-8 min-h-[500px] flex flex-col items-center justify-center transition-all duration-500 shadow-inner">
                        <div class="text-center">
                            <div class="relative">
                                <div class="absolute inset-0 bg-blue-100/30 rounded-full blur-2xl"></div>
                                <i class="fas fa-ticket-alt text-gray-300 text-9xl mb-6 relative z-10"></i>
                            </div>
                            <p class="text-gray-500 text-2xl font-medium">Karcis antrian akan muncul di sini</p>
                            <p class="text-gray-400 text-lg mt-2">Tekan tombol "CETAK NOMOR ANTRIAN" untuk mulai</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODERN MODAL PILIH DOKTER -->
    <div id="modalDokter" class="modal-modern fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
        <div class="modal-content bg-white rounded-3xl w-full max-w-lg overflow-hidden shadow-2xl">
            <!-- Header Modal dengan gradient -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="bg-white/20 p-3 rounded-2xl backdrop-blur-sm">
                            <i class="fas fa-user-md text-white text-3xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-white ml-4">Pilih Dokter</h3>
                    </div>
                    <button id="btnCloseModal" class="text-white/80 hover:text-white transition-colors">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>
                <p class="text-blue-100 mt-2 ml-16">Silakan pilih dokter yang akan dituju</p>
            </div>

            <!-- Body Modal -->
            <div class="px-8 py-6">
                <!-- Pencarian Dokter (Fitur tambahan) -->
                <div class="relative mb-6">
                    <i
                        class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-xl"></i>
                    <input type="text" id="searchDokter" placeholder="Cari nama dokter..."
                        class="w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-xl text-lg focus:border-blue-500 focus:ring-4 focus:ring-blue-200 transition-all outline-none">
                </div>

                <!-- Daftar Dokter dengan Card Style -->
                <div class="space-y-4 max-h-[320px] overflow-y-auto pr-2 custom-scrollbar">
                    <label
                        class="doctor-card block p-4 rounded-xl cursor-pointer transition-all duration-300 hover:shadow-md bg-gray-50">
                        <input type="radio" name="dokter" value="Dr.Nelyan Mokoginta,Sp.PD" class="hidden doctor-radio">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-16 h-16 bg-gradient-to-br from-blue-100 to-blue-200 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user-circle text-blue-600 text-3xl"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <h4 class="text-xl font-bold text-gray-800">Dr. Nelyan Mokoginta, Sp.PD</h4>
                                <p class="text-blue-600 font-medium">Dokter Umum</p>
                                <div class="flex items-center mt-2 text-sm text-gray-500">
                                    <i class="fas fa-clock mr-2"></i>
                                    <span>Tersedia 10.00 - 16.00</span>
                                </div>
                            </div>
                            <div class="flex-shrink-0 ml-4">
                                <div
                                    class="w-8 h-8 rounded-full border-2 border-gray-300 flex items-center justify-center doctor-check">
                                    <i class="fas fa-check text-transparent"></i>
                                </div>
                            </div>
                        </div>
                    </label>

                    <label
                        class="doctor-card block p-4 rounded-xl cursor-pointer transition-all duration-300 hover:shadow-md bg-gray-50">
                        <input type="radio" name="dokter" value="Dr.Akbar Patiti,Sp.BS" class="hidden doctor-radio">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-16 h-16 bg-gradient-to-br from-blue-100 to-blue-200 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user-circle text-blue-600 text-3xl"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <h4 class="text-xl font-bold text-gray-800">Dr. Akbar Patiti, Sp.BS</h4>
                                <p class="text-blue-600 font-medium">Dokter Umum</p>
                                <div class="flex items-center mt-2 text-sm text-gray-500">
                                    <i class="fas fa-clock mr-2"></i>
                                    <span>Tersedia 10.00 - 16.00</span>
                                </div>
                            </div>
                            <div class="flex-shrink-0 ml-4">
                                <div
                                    class="w-8 h-8 rounded-full border-2 border-gray-300 flex items-center justify-center doctor-check">
                                    <i class="fas fa-check text-transparent"></i>
                                </div>
                            </div>
                        </div>
                    </label>
                </div>

                <!-- Informasi Tambahan -->
                <div class="mt-6 p-4 bg-blue-50 rounded-xl flex items-start">
                    <i class="fas fa-info-circle text-blue-600 text-xl mt-1 mr-3"></i>
                    <p class="text-sm text-gray-600">Pastikan Anda memilih dokter yang sesuai dengan keluhan Anda. Nomor
                        antrian akan tercetak setelah konfirmasi.</p>
                </div>
            </div>

            <!-- Footer Modal dengan tombol aksi -->
            <div class="bg-gray-50 px-8 py-5 flex gap-4">
                <button id="btnBatal"
                    class="ripple flex-1 bg-white border-2 border-gray-300 text-gray-700 py-4 px-6 rounded-xl text-lg font-semibold hover:bg-gray-100 transition-all duration-300 flex items-center justify-center">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </button>
                <button id="btnLanjutCetak"
                    class="ripple flex-1 bg-gradient-to-r from-blue-600 to-blue-700 text-white py-4 px-6 rounded-xl text-lg font-bold hover:from-blue-700 hover:to-blue-800 transition-all duration-300 flex items-center justify-center shadow-lg hover:shadow-xl">
                    <i class="fas fa-print mr-2"></i>
                    Cetak Antrian
                </button>
            </div>
        </div>
    </div>

    <!-- Custom Scrollbar Style -->
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</body>

<script>
    // =====================
    // ELEMENT
    // =====================
    const btnCetak = document.getElementById('btnCetakAntrian');
    const modalDokter = document.getElementById('modalDokter');
    const btnBatal = document.getElementById('btnBatal');
    const btnCloseModal = document.getElementById('btnCloseModal');
    const btnLanjut = document.getElementById('btnLanjutCetak');
    const ticketArea = document.getElementById('ticketArea');
    const searchInput = document.getElementById('searchDokter');
    const doctorRadios = document.querySelectorAll('.doctor-radio');
    const doctorCards = document.querySelectorAll('.doctor-card');

    // =====================
    // SEARCH FUNCTIONALITY
    // =====================
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            doctorCards.forEach(card => {
                const doctorName = card.querySelector('h4').textContent.toLowerCase();
                const specialty = card.querySelector('p.text-blue-600').textContent.toLowerCase();

                if (doctorName.includes(searchTerm) || specialty.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }

    // =====================
    // DOCTOR SELECTION HANDLER
    // =====================
    doctorCards.forEach((card, index) => {
        card.addEventListener('click', function() {
            // Remove selected class from all cards
            doctorCards.forEach(c => {
                c.classList.remove('selected');
                c.classList.remove('bg-blue-50');
                const checkIcon = c.querySelector('.doctor-check i');
                checkIcon.classList.remove('text-white');
                checkIcon.classList.add('text-transparent');
                const checkDiv = c.querySelector('.doctor-check');
                checkDiv.classList.remove('border-blue-600', 'bg-blue-600');
                checkDiv.classList.add('border-gray-300');
            });

            // Add selected class to clicked card
            this.classList.add('selected', 'bg-blue-50');
            const checkIcon = this.querySelector('.doctor-check i');
            checkIcon.classList.remove('text-transparent');
            checkIcon.classList.add('text-white');
            const checkDiv = this.querySelector('.doctor-check');
            checkDiv.classList.remove('border-gray-300');
            checkDiv.classList.add('border-blue-600', 'bg-blue-600');

            // Check the radio button
            const radio = this.querySelector('.doctor-radio');
            if (radio) {
                radio.checked = true;
            }
        });
    });

    // =====================
    // 1. KLIK CETAK → TAMPIL MODAL
    // =====================
    btnCetak.addEventListener('click', function() {
        modalDokter.classList.remove('hidden');
        modalDokter.classList.add('flex');

        // Reset search input
        if (searchInput) {
            searchInput.value = '';
        }

        // Reset selection
        doctorCards.forEach(card => {
            card.style.display = 'block';
            card.classList.remove('selected', 'bg-blue-50');
            const checkIcon = card.querySelector('.doctor-check i');
            checkIcon.classList.remove('text-white');
            checkIcon.classList.add('text-transparent');
            const checkDiv = card.querySelector('.doctor-check');
            checkDiv.classList.remove('border-blue-600', 'bg-blue-600');
            checkDiv.classList.add('border-gray-300');
        });

        // Uncheck all radios
        doctorRadios.forEach(radio => {
            radio.checked = false;
        });
    });

    // =====================
    // CLOSE MODAL FUNCTIONS
    // =====================
    function closeModal() {
        modalDokter.classList.add('hidden');
        modalDokter.classList.remove('flex');

        // Reset search
        if (searchInput) {
            searchInput.value = '';
        }

        // Reset selection
        doctorCards.forEach(card => {
            card.style.display = 'block';
            card.classList.remove('selected', 'bg-blue-50');
            const checkIcon = card.querySelector('.doctor-check i');
            checkIcon.classList.remove('text-white');
            checkIcon.classList.add('text-transparent');
            const checkDiv = card.querySelector('.doctor-check');
            checkDiv.classList.remove('border-blue-600', 'bg-blue-600');
            checkDiv.classList.add('border-gray-300');
        });

        doctorRadios.forEach(radio => {
            radio.checked = false;
        });
    }

    btnBatal.addEventListener('click', closeModal);
    if (btnCloseModal) {
        btnCloseModal.addEventListener('click', closeModal);
    }

    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target === modalDokter) {
            closeModal();
        }
    });

    // =====================
    // 3. LANJUT CETAK → FETCH KE SERVER
    // =====================
    btnLanjut.addEventListener('click', function() {
        // Get selected doctor from radio buttons
        let selectedDoctor = null;
        doctorRadios.forEach(radio => {
            if (radio.checked) {
                selectedDoctor = radio.value;
            }
        });

        if (!selectedDoctor) {
            // Show error with sweet alert style
            showNotification('⚠️ Silakan pilih dokter terlebih dahulu', 'error');
            return;
        }

        // Show loading state
        btnLanjut.disabled = true;
        btnLanjut.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';

        fetch("{{ route('antrian.cetak') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content")
                },
                body: JSON.stringify({
                    nama_dokter: selectedDoctor
                })
            })
            .then(res => {
                if (!res.ok) throw new Error('Response error');
                return res.json();
            })
            .then(data => {
                if (data.success) {
                    // Close modal
                    closeModal();

                    // Show success notification
                    showNotification('✅ Karcis berhasil dicetak!', 'success');

                    // Display ticket
                    tampilkanKarcis(
                        data.nomor_antrian,
                        data.tanggal,
                        data.waktu,
                        data.nama_dokter
                    );

                    // Print ticket
                    cetakKarcis(
                        data.nomor_antrian,
                        data.tanggal,
                        data.waktu,
                        data.nama_dokter
                    );
                } else {
                    showNotification('❌ Gagal cetak antrian', 'error');
                }
            })
            .catch(error => {
                console.error('ERROR:', error);
                showNotification('❌ Terjadi kesalahan server', 'error');
            })
            .finally(() => {
                // Reset button state
                btnLanjut.disabled = false;
                btnLanjut.innerHTML = '<i class="fas fa-print mr-2"></i>Cetak Antrian';
            });
    });

    // =====================
    // 4. TAMPILKAN KARCIS
    // =====================
    function tampilkanKarcis(nomor, tanggal, waktu, nama_dokter) {
        ticketArea.innerHTML = `
        <div class="text-center transform transition-all duration-500 scale-95 hover:scale-100">
            <div class="relative mb-4">
                <div class="absolute inset-0 bg-blue-500/20 rounded-full blur-2xl"></div>
                <i class="fas fa-qrcode text-gray-700 text-4xl mb-2 relative z-10"></i>
            </div>
            <div class="border-b-2 border-dashed border-gray-300 pb-4 mb-4">
                <h3 class="text-2xl font-bold text-gray-700 mb-2">NOMOR ANTRIAN</h3>
                <h1 class="text-8xl font-extrabold text-blue-700 mb-2 animate-pulse">${nomor}</h1>
            </div>
            <div class="space-y-2">
                <p class="text-xl text-gray-800">
                    <i class="fas fa-user-md text-blue-600 mr-2"></i>
                    <span class="font-semibold">${nama_dokter}</span>
                </p>
                <div class="flex justify-center items-center space-x-4 text-gray-600">
                    <p class="text-lg"><i class="fas fa-calendar-alt mr-2"></i>${tanggal}</p>
                    <p class="text-lg"><i class="fas fa-clock mr-2"></i>${waktu}</p>
                </div>
            </div>
            <div class="mt-6 pt-4 border-t border-gray-200">
                <div class="flex justify-center space-x-2">
                    <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                    <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                    <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                </div>
                <p class="text-sm text-gray-500 mt-2">Harap simpan karcis ini untuk pemeriksaan</p>
            </div>
        </div>
    `;
    }

    // =====================
    // 5. CETAK KARCIS
    // =====================
    function cetakKarcis(nomor, tanggal, waktu, nama_dokter) {
        let win = window.open('', '', 'width=350,height=500');

        win.document.write(`
        <html>
        <head>
            <title>Karcis Antrian - Rumah Sakit</title>
            <style>
                body {
                    font-family: 'Arial', sans-serif;
                    text-align: center;
                    padding: 30px 20px;
                    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
                    margin: 0;
                    min-height: 100vh;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }
                .ticket {
                    background: white;
                    border-radius: 16px;
                    padding: 30px 20px;
                    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
                    max-width: 300px;
                    width: 100%;
                }
                .hospital-name {
                    color: #1e3a8a;
                    font-size: 14px;
                    font-weight: bold;
                    margin-bottom: 20px;
                }
                h1 {
                    font-size: 56px;
                    margin: 15px 0;
                    color: #2563eb;
                    font-weight: 900;
                }
                .doctor {
                    font-size: 16px;
                    font-weight: bold;
                    color: #1e293b;
                    margin: 10px 0;
                }
                .datetime {
                    font-size: 14px;
                    color: #64748b;
                    margin: 5px 0;
                }
                .footer {
                    margin-top: 25px;
                    padding-top: 20px;
                    border-top: 2px dashed #cbd5e1;
                    font-size: 12px;
                    color: #94a3b8;
                }
            </style>
        </head>
        <body>
            <div class="ticket">
                <div class="hospital-name">KLINIK ANUGRAH FARMA</div>
                <div style="font-size: 12px; color: #475569;">NOMOR ANTRIAN</div>
                <h1>${nomor}</h1>
                <div class="doctor">${nama_dokter}</div>
                <div class="datetime">${tanggal}</div>
                <div class="datetime">${waktu}</div>
                <div class="footer">
                    Harap simpan karcis ini<br>
                    untuk pemeriksaan
                </div>
            </div>
        </body>
        </html>
    `);

        win.document.close();
        win.focus();

        // Small delay to ensure content is loaded
        setTimeout(() => {
            win.print();
            win.close();
        }, 250);
    }

    // =====================
    // NOTIFICATION SYSTEM
    // =====================
    function showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className =
            `fixed top-5 right-5 px-6 py-4 rounded-xl shadow-2xl z-50 transform transition-all duration-500 translate-x-0`;

        // Set style based on type
        if (type === 'error') {
            notification.classList.add('bg-red-500', 'text-white');
        } else if (type === 'success') {
            notification.classList.add('bg-green-500', 'text-white');
        } else {
            notification.classList.add('bg-blue-500', 'text-white');
        }

        notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'error' ? 'exclamation-circle' : type === 'success' ? 'check-circle' : 'info-circle'} mr-3 text-xl"></i>
            <span class="text-lg font-medium">${message}</span>
        </div>
    `;

        document.body.appendChild(notification);

        // Remove notification after 3 seconds
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                notification.remove();
            }, 500);
        }, 3000);
    }
</script>

</html>