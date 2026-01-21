<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Antrian Rumah Sakit</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Animate.css untuk animasi -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        /* Custom styles */
        .login-bg {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }
        
        .input-group {
            position: relative;
            margin-bottom: 2rem;
        }
        
        .input-group input {
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }
        
        .input-group input:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.5);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }
        
        .input-group label {
            transition: all 0.3s ease;
            pointer-events: none;
        }
        
        .input-group input:focus + label,
        .input-group input:not(:placeholder-shown) + label {
            transform: translateY(-30px);
            font-size: 0.875rem;
            color: #93c5fd;
        }
        
        .btn-login {
            background: linear-gradient(to right, #3b82f6, #1d4ed8);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .btn-login::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 5px;
            height: 5px;
            background: rgba(255, 255, 255, 0.5);
            opacity: 0;
            border-radius: 100%;
            transform: scale(1, 1) translate(-50%);
            transform-origin: 50% 50%;
        }
        
        .btn-login:focus:not(:active)::after {
            animation: ripple 1s ease-out;
        }
        
        @keyframes ripple {
            0% {
                transform: scale(0, 0);
                opacity: 0.5;
            }
            100% {
                transform: scale(20, 20);
                opacity: 0;
            }
        }
        
        .role-card {
            background: rgba(255, 255, 255, 0.08);
            border: 2px solid transparent;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .role-card:hover {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(59, 130, 246, 0.5);
            transform: translateY(-5px);
        }
        
        .role-card.selected {
            background: rgba(59, 130, 246, 0.2);
            border-color: #3b82f6;
            transform: translateY(-5px);
        }
        
        .alert {
            animation: slideInDown 0.5s ease;
        }
        
        @keyframes slideInDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        .floating-icon {
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }
        
        /* Dark mode toggle */
        .toggle-checkbox:checked {
            right: 0;
            border-color: #3b82f6;
        }
        
        .toggle-checkbox:checked + .toggle-label {
            background: #3b82f6;
        }
    </style>
</head>
<body class="login-bg">
    <!-- Alert untuk login gagal (hidden secara default) -->
    <div id="loginAlert" class="fixed top-4 right-4 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 hidden">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle text-xl mr-3"></i>
            <div>
                <p class="font-bold">Login Gagal</p>
                <p class="text-sm">Username atau password salah. Silakan coba lagi.</p>
            </div>
            <button id="closeAlert" class="ml-6 text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row items-center justify-center gap-12">
            <!-- Logo dan informasi -->
            <div class="w-full lg:w-1/2 text-center lg:text-left animate__animated animate__fadeInLeft">
                <div class="flex justify-center lg:justify-start items-center mb-8">
                    <div class="bg-white p-4 rounded-2xl mr-4 floating-icon">
                        <i class="fas fa-hospital text-blue-600 text-4xl"></i>
                    </div>
                    <div>
                        <h1 class="text-4xl lg:text-5xl font-bold text-white">KLINIK</h1>
                        <h2 class="text-2xl lg:text-3xl text-blue-200">Anugrah Farma</h2>
                    </div>
                </div>
                
                <h3 class="text-3xl font-bold text-white mb-6">SISTEM ANTRIAN DIGITAL</h3>
                
                <div class="space-y-6 mb-10">
                    <div class="flex items-center text-blue-100">
                        <div class="bg-blue-500 p-2 rounded-full mr-4">
                            <i class="fas fa-ticket-alt text-white"></i>
                        </div>
                        <p class="text-lg">Cetak antrian secara mandiri</p>
                    </div>
                    
                    <div class="flex items-center text-blue-100">
                        <div class="bg-green-500 p-2 rounded-full mr-4">
                            <i class="fas fa-bullhorn text-white"></i>
                        </div>
                        <p class="text-lg">Panggilan antrian terdigitalisasi</p>
                    </div>
                    
                    <div class="flex items-center text-blue-100">
                        <div class="bg-purple-500 p-2 rounded-full mr-4">
                            <i class="fas fa-chart-line text-white"></i>
                        </div>
                        <p class="text-lg">Laporan dan monitoring real-time</p>
                    </div>
                </div>
                
                            </div>
            
            <!-- Form login -->
            <div class="w-full lg:w-1/2 max-w-lg animate__animated animate__fadeInRight">
                <div class="login-container rounded-3xl p-8 lg:p-10">
                    <div class="text-center mb-10">
                        <h2 class="text-4xl font-bold text-white mb-4">MASUK KE SISTEM</h2>
                    </div>
                    
                    <!-- Form input -->
                    <form id="loginForm">
                        <div class="input-group">
                            <input type="text" id="username" 
                                   class="w-full p-4 text-white rounded-xl focus:outline-none placeholder-transparent"
                                   placeholder="Username" 
                                   required
                                   autocomplete="username">
                            <label for="username" class="absolute left-4 top-4 text-blue-200">Username</label>
                            <div class="absolute right-4 top-4">
                                <i class="fas fa-user text-blue-300"></i>
                            </div>
                        </div>
                        
                        <div class="input-group">
                            <input type="password" id="password" 
                                   class="w-full p-4 text-white rounded-xl focus:outline-none placeholder-transparent"
                                   placeholder="Password" 
                                   required
                                   autocomplete="current-password">
                            <label for="password" class="absolute left-4 top-4 text-blue-200">Password</label>
                            <div class="absolute right-4 top-4">
                                <i class="fas fa-lock text-blue-300"></i>
                            </div>
                            <button type="button" id="togglePassword" class="absolute right-12 top-4 text-blue-300">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        
                        <!-- Tombol login -->
                        <button type="submit" class="btn-login w-full text-white text-xl font-bold py-4 rounded-xl mb-6">
                            <i class="fas fa-sign-in-alt mr-3"></i> MASUK
                        </button>
                  
                    </form>
                    
                    
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Lupa Password -->
    <div id="forgotPasswordModal" class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl p-8 max-w-md w-11/12 animate__animated animate__zoomIn">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-key text-blue-600 mr-3"></i>Reset Password
                </h3>
                <button id="closeForgotModal" class="text-gray-500 hover:text-gray-700 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <p class="text-gray-600 mb-6">
                Masukkan username atau email Anda. Kami akan mengirimkan tautan untuk mereset password.
            </p>
            
            <div class="mb-6">
                <label for="resetUsername" class="block text-gray-700 mb-2">Username atau Email</label>
                <input type="text" id="resetUsername" 
                       class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
            </div>
            
            <div class="flex justify-end space-x-4">
                <button id="cancelReset" class="px-6 py-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                    Batal
                </button>
                <button id="submitReset" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                    Kirim Tautan
                </button>
            </div>
        </div>
    </div>

    <script>
        // Data user untuk simulasi login
        const users = {
            petugas: {
                username: "petugas",
                password: "petugas123",
                role: "petugas",
                name: "Dr. Andi Pratama"
            },
            admin: {
                username: "admin",
                password: "admin123",
                role: "admin",
                name: "Admin Sistem"
            },
            operator: {
                username: "operator",
                password: "operator123",
                role: "petugas",
                name: "Budi Santoso"
            }
        };

        // State aplikasi
        let selectedRole = "petugas";
        let isPasswordVisible = false;
        let darkMode = false;

        // Inisialisasi
        document.addEventListener('DOMContentLoaded', function() {
            // Setup pilihan role
            setupRoleSelection();
            
            // Setup form login
            setupLoginForm();
            
            // Setup toggle password visibility
            setupPasswordToggle();
            
            // Setup lupa password
            setupForgotPassword();
            
            // Setup dark mode toggle
            setupDarkMode();
            
            // Auto focus ke username field
            document.getElementById('username').focus();
        });

        // Setup pilihan role
        function setupRoleSelection() {
            const rolePetugas = document.getElementById('rolePetugas');
            const roleAdmin = document.getElementById('roleAdmin');
            
            rolePetugas.addEventListener('click', function() {
                selectRole('petugas');
            });
            
            roleAdmin.addEventListener('click', function() {
                selectRole('admin');
            });
        }

        // Fungsi untuk memilih role
        function selectRole(role) {
            selectedRole = role;
            
            // Update tampilan kartu role
            document.getElementById('rolePetugas').classList.remove('selected');
            document.getElementById('roleAdmin').classList.remove('selected');
            
            if (role === 'petugas') {
                document.getElementById('rolePetugas').classList.add('selected');
                // Auto-fill untuk demo
                document.getElementById('username').value = 'petugas';
                document.getElementById('password').value = 'petugas123';
            } else {
                document.getElementById('roleAdmin').classList.add('selected');
                // Auto-fill untuk demo
                document.getElementById('username').value = 'admin';
                document.getElementById('password').value = 'admin123';
            }
        }

        // Setup form login
        function setupLoginForm() {
            const loginForm = document.getElementById('loginForm');
            
            loginForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const username = document.getElementById('username').value.trim();
                const password = document.getElementById('password').value;
                const remember = document.getElementById('remember').checked;
                
                // Validasi input
                if (!username || !password) {
                    showAlert('warning', 'Harap lengkapi semua field');
                    return;
                }
                
                // Simulasi proses login
                simulateLogin(username, password, remember);
            });
        }

        // Simulasi proses login
        function simulateLogin(username, password, remember) {
            // Tampilkan loading state
            const submitBtn = document.querySelector('#loginForm button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-3"></i> MEMPROSES...';
            submitBtn.disabled = true;
            
            // Simulasi delay request
            setTimeout(() => {
                // Cek kredensial
                const user = Object.values(users).find(u => 
                    u.username === username && u.password === password
                );
                
                if (user) {
                    // Simpan ke localStorage jika remember dicentang
                    if (remember) {
                        localStorage.setItem('rememberedUser', JSON.stringify({
                            username: user.username,
                            role: user.role
                        }));
                    } else {
                        localStorage.removeItem('rememberedUser');
                    }
                    
                    // Simpan session
                    sessionStorage.setItem('currentUser', JSON.stringify(user));
                    
                    // Tampilkan pesan sukses
                    showAlert('success', `Login berhasil! Selamat datang ${user.name}`);
                    
                    // Redirect berdasarkan role
                    setTimeout(() => {
                        if (user.role === 'petugas') {
                            window.location.href = 'dashboard-petugas.html';
                        } else {
                            window.location.href = 'dashboard-admin.html';
                        }
                    }, 1500);
                    
                } else {
                    // Tampilkan pesan error
                    showAlert('error', 'Username atau password salah');
                    
                    // Reset tombol
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    
                    // Tambahkan efek shake pada form
                    document.getElementById('loginForm').classList.add('animate__animated', 'animate__shakeX');
                    setTimeout(() => {
                        document.getElementById('loginForm').classList.remove('animate__animated', 'animate__shakeX');
                    }, 1000);
                }
            }, 1500);
        }

        // Setup toggle password visibility
        function setupPasswordToggle() {
            const toggleBtn = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            
            toggleBtn.addEventListener('click', function() {
                isPasswordVisible = !isPasswordVisible;
                
                if (isPasswordVisible) {
                    passwordInput.type = 'text';
                    toggleBtn.innerHTML = '<i class="fas fa-eye-slash"></i>';
                } else {
                    passwordInput.type = 'password';
                    toggleBtn.innerHTML = '<i class="fas fa-eye"></i>';
                }
            });
        }

        // Setup lupa password
        function setupForgotPassword() {
            const forgotLink = document.getElementById('forgotPassword');
            const forgotModal = document.getElementById('forgotPasswordModal');
            const closeForgotBtn = document.getElementById('closeForgotModal');
            const cancelResetBtn = document.getElementById('cancelReset');
            const submitResetBtn = document.getElementById('submitReset');
            
            // Buka modal
            forgotLink.addEventListener('click', function(e) {
                e.preventDefault();
                forgotModal.classList.remove('hidden');
            });
            
            // Tutup modal
            closeForgotBtn.addEventListener('click', function() {
                forgotModal.classList.add('hidden');
            });
            
            cancelResetBtn.addEventListener('click', function() {
                forgotModal.classList.add('hidden');
            });
            
            // Submit reset password
            submitResetBtn.addEventListener('click', function() {
                const resetUsername = document.getElementById('resetUsername').value.trim();
                
                if (!resetUsername) {
                    alert('Harap masukkan username atau email');
                    return;
                }
                
                // Simulasi pengiriman email reset
                submitResetBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mengirim...';
                submitResetBtn.disabled = true;
                
                setTimeout(() => {
                    forgotModal.classList.add('hidden');
                    showAlert('success', 'Tautan reset password telah dikirim ke email Anda');
                    
                    // Reset form
                    document.getElementById('resetUsername').value = '';
                    submitResetBtn.innerHTML = 'Kirim Tautan';
                    submitResetBtn.disabled = false;
                }, 2000);
            });
            
            // Tutup modal saat klik di luar
            window.addEventListener('click', function(e) {
                if (e.target === forgotModal) {
                    forgotModal.classList.add('hidden');
                }
            });
        }

        // Setup dark mode
        function setupDarkMode() {
            const darkModeToggle = document.getElementById('darkModeToggle');
            
            // Cek preferensi sebelumnya
            const savedDarkMode = localStorage.getItem('darkMode') === 'true';
            if (savedDarkMode) {
                enableDarkMode();
                darkModeToggle.checked = true;
            }
            
            // Toggle dark mode
            darkModeToggle.addEventListener('change', function() {
                if (this.checked) {
                    enableDarkMode();
                    localStorage.setItem('darkMode', 'true');
                } else {
                    disableDarkMode();
                    localStorage.setItem('darkMode', 'false');
                }
            });
        }

        // Enable dark mode
        function enableDarkMode() {
            darkMode = true;
            document.body.classList.add('bg-gray-900');
            document.querySelector('.login-bg').style.background = 'linear-gradient(135deg, #0f172a 0%, #1e293b 100%)';
        }

        // Disable dark mode
        function disableDarkMode() {
            darkMode = false;
            document.body.classList.remove('bg-gray-900');
            document.querySelector('.login-bg').style.background = 'linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%)';
        }

        // Fungsi untuk menampilkan alert
        function showAlert(type, message) {
            const alertDiv = document.getElementById('loginAlert');
            
            // Set jenis alert
            if (type === 'success') {
                alertDiv.className = alertDiv.className.replace('bg-red-500', 'bg-green-500');
                alertDiv.querySelector('.fa-exclamation-circle').className = 'fas fa-check-circle text-xl mr-3';
                alertDiv.querySelector('p.font-bold').textContent = 'Login Berhasil';
            } else if (type === 'warning') {
                alertDiv.className = alertDiv.className.replace('bg-red-500', 'bg-yellow-500');
                alertDiv.querySelector('.fa-exclamation-circle').className = 'fas fa-exclamation-triangle text-xl mr-3';
                alertDiv.querySelector('p.font-bold').textContent = 'Peringatan';
            } else {
                alertDiv.className = alertDiv.className.replace('bg-green-500', 'bg-red-500').replace('bg-yellow-500', 'bg-red-500');
                alertDiv.querySelector('.fa-check-circle, .fa-exclamation-triangle').className = 'fas fa-exclamation-circle text-xl mr-3';
                alertDiv.querySelector('p.font-bold').textContent = 'Login Gagal';
            }
            
            // Set pesan
            alertDiv.querySelector('p.text-sm').textContent = message;
            
            // Tampilkan alert
            alertDiv.classList.remove('hidden');
            alertDiv.classList.add('animate__animated', 'animate__slideInDown');
            
            // Sembunyikan otomatis setelah 5 detik
            setTimeout(() => {
                hideAlert();
            }, 5000);
            
            // Tombol close
            document.getElementById('closeAlert').addEventListener('click', hideAlert);
        }

        // Fungsi untuk menyembunyikan alert
        function hideAlert() {
            const alertDiv = document.getElementById('loginAlert');
            alertDiv.classList.add('hidden');
            alertDiv.classList.remove('animate__animated', 'animate__slideInDown');
        }

        // Auto-fill jika ada remembered user
        window.addEventListener('load', function() {
            const rememberedUser = localStorage.getItem('rememberedUser');
            
            if (rememberedUser) {
                try {
                    const user = JSON.parse(rememberedUser);
                    document.getElementById('username').value = user.username;
                    document.getElementById('remember').checked = true;
                    
                    // Pilih role sesuai user
                    selectRole(user.role);
                } catch (e) {
                    console.error('Error parsing remembered user:', e);
                }
            }
        });

        // Animasi untuk input label
        document.querySelectorAll('.input-group input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.querySelector('label').style.color = '#93c5fd';
            });
            
            input.addEventListener('blur', function() {
                if (!this.value) {
                    this.parentElement.querySelector('label').style.color = '#bfdbfe';
                }
            });
        });

        // Efek keyboard shortcut
        document.addEventListener('keydown', function(e) {
            // Ctrl+Enter untuk submit form
            if (e.ctrlKey && e.key === 'Enter') {
                document.getElementById('loginForm').dispatchEvent(new Event('submit'));
            }
            
            // Esc untuk close modal
            if (e.key === 'Escape') {
                document.getElementById('forgotPasswordModal').classList.add('hidden');
                hideAlert();
            }
        });
    </script>
</body>
</html>