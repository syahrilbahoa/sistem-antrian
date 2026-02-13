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
        color: white;
    }

    .input-group input:focus {
        background: rgba(255, 255, 255, 0.15);
        border-color: rgba(255, 255, 255, 0.5);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
    }

    .input-group label {
        transition: all 0.3s ease;
        pointer-events: none;
        color: #bfdbfe;
    }

    .input-group input:focus+label,
    .input-group input:not(:placeholder-shown)+label {
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

        0%,
        100% {
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

    .toggle-checkbox:checked+.toggle-label {
        background: #3b82f6;
    }
    </style>
</head>

<body class="login-bg">
    <!-- Alert untuk login gagal (hidden secara default) -->
    @if(session('error'))
    <div id="loginAlert" class="fixed top-4 right-4 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg z-50">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle text-xl mr-3"></i>
            <div>
                <p class="font-bold">Login Gagal</p>
                <p class="text-sm">{{ session('error') }}</p>
            </div>
            <button id="closeAlert" class="ml-6 text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endif

    @if(session('success'))
    <div id="loginAlert" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg z-50">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-xl mr-3"></i>
            <div>
                <p class="font-bold">Login Berhasil</p>
                <p class="text-sm">{{ session('success') }}</p>
            </div>
            <button id="closeAlert" class="ml-6 text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endif

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
                    <form method="POST" action="{{ route('login.process') }}">
                        @csrf

                        <div class="input-group">
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required class="w-full p-4 text-white rounded-xl" placeholder=" ">
                            <label for="email">Email</label>
                        </div>

                        <div class="input-group">
                            <input type="password" name="password" id="password" required class="w-full p-4 text-white rounded-xl" placeholder=" ">
                            <label for="password">Password</label>
                        </div>

                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center">
                                <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="remember" class="ml-2 block text-sm text-white">Ingat Saya</label>
                            </div>
                            
                            <a href="#" class="text-sm text-blue-200 hover:text-white">Lupa Password?</a>
                        </div>

                        @error('email')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror
                        
                        @error('password')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror

                        <button type="submit" class="btn-login w-full text-white py-4 rounded-xl">
                            MASUK
                        </button>
                    </form>

                    <!-- Alert untuk error validasi -->
                    @if ($errors->any())
                    <div class="mt-4 p-3 bg-red-500 text-white rounded-lg">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <script>
    // Fungsi untuk menyembunyikan alert
    document.addEventListener('DOMContentLoaded', function() {
        const closeAlertBtn = document.getElementById('closeAlert');
        if (closeAlertBtn) {
            closeAlertBtn.addEventListener('click', function() {
                const alertDiv = this.closest('#loginAlert');
                if (alertDiv) {
                    alertDiv.classList.add('hidden');
                }
            });
            
            // Sembunyikan alert setelah 5 detik
            setTimeout(function() {
                const alertDiv = document.getElementById('loginAlert');
                if (alertDiv) {
                    alertDiv.classList.add('hidden');
                }
            }, 5000);
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
        
        // Trigger label animation if input has value on load
        if (input.value) {
            input.parentElement.querySelector('label').style.transform = 'translateY(-30px)';
            input.parentElement.querySelector('label').style.fontSize = '0.875rem';
            input.parentElement.querySelector('label').style.color = '#93c5fd';
        }
    });
    </script>
</body>

</html>