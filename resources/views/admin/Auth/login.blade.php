<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Global Intermedia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-red: #E30613;
            --secondary-red: #FF5A65;
            --light-red: #FFF0F1;
            --dark-gray: #1A1A1A;
            --medium-gray: #666666;
            --light-gray: #F5F5F7;
            --white: #FFFFFF;
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.12);
        }
        
        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #eef0f3 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', 'Segoe UI', sans-serif;
            padding: 20px;
            margin: 0;
            position: relative;
            overflow-x: hidden;
        }
        
        /* Background subtle pattern */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 15% 50%, rgba(227, 6, 19, 0.03) 0%, transparent 20%),
                radial-gradient(circle at 85% 30%, rgba(227, 6, 19, 0.02) 0%, transparent 20%),
                radial-gradient(circle at 50% 80%, rgba(227, 6, 19, 0.02) 0%, transparent 20%);
            z-index: -1;
        }
        
        .login-container {
            width: 100%;
            max-width: 900px;
            position: relative;
        }
        
        .split-login-card {
            display: flex;
            height: 540px;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            background: var(--white);
            border: 1px solid rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .split-login-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.15);
        }
        
        /* Left Side - Image Section */
        .login-left {
            flex: 0.9;
            background: linear-gradient(135deg, 
                rgba(227, 6, 19, 0.92) 0%, 
                rgba(227, 6, 19, 0.85) 50%, 
                rgba(255, 90, 101, 0.8) 100%),
                url('/images/gedung_gi.jpg') center/cover no-repeat;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 35px 30px;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        /* Enhanced glass effect overlay */
        .login-left::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, 
                rgba(255, 255, 255, 0.05) 0%,
                rgba(255, 255, 255, 0.02) 50%,
                transparent 100%);
            z-index: 1;
        }
        
        /* Subtle pattern overlay */
        .login-left::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 20% 80%, rgba(255, 255, 255, 0.1) 2px, transparent 2px),
                radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
            background-size: 40px 40px;
            z-index: 0;
            opacity: 0.3;
        }
        
        .logo-section {
            position: relative;
            z-index: 2;
            text-align: center;
            margin-top: 10px;
        }
        
        .logo-image {
            max-width: 160px;
            height: auto;
            margin-bottom: 15px;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.2));
            transition: transform 0.3s ease;
        }
        
        .logo-image:hover {
            transform: scale(1.03);
        }
        
        .company-name {
            font-size: 1.8rem;
            font-weight: 800;
            letter-spacing: -0.3px;
            margin-bottom: 5px;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }
        
        .company-tagline {
            font-size: 0.9rem;
            font-weight: 500;
            opacity: 0.9;
            letter-spacing: 1.5px;
            margin-bottom: 25px;
            text-transform: uppercase;
        }
        
        .company-info {
            position: relative;
            z-index: 2;
            margin-top: auto;
            padding: 20px 0;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            padding: 20px;
            backdrop-filter: blur(5px);
        }
        
        .company-info h4 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .company-info h4 i {
            font-size: 1rem;
        }
        
        .company-info p {
            font-size: 0.85rem;
            opacity: 0.9;
            line-height: 1.5;
            margin-bottom: 0;
        }
        
        /* Right Side - Login Form */
        .login-right {
            flex: 1.1;
            padding: 40px 45px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: var(--white);
            position: relative;
        }
        
        /* Decorative corner accent */
        .login-right::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 60px;
            height: 60px;
            border-radius: 0 0 0 24px;
            background: linear-gradient(135deg, transparent 50%, var(--light-red) 50%);
            z-index: 1;
        }
        
        .login-form-header {
            text-align: center;
            margin-bottom: 35px;
            position: relative;
            z-index: 2;
        }
        
        .login-form-header h2 {
            font-weight: 700;
            font-size: 1.8rem;
            color: var(--dark-gray);
            margin-bottom: 8px;
            letter-spacing: -0.2px;
        }
        
        .login-form-header p {
            color: var(--medium-gray);
            font-size: 0.95rem;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 8px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
        }
        
        .form-label i {
            color: var(--primary-red);
            width: 18px;
            margin-right: 8px;
            font-size: 0.85rem;
        }
        
        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 13px 16px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: var(--light-gray);
            color: var(--dark-gray);
            box-shadow: var(--shadow-sm) inset;
        }
        
        .form-control:focus {
            border-color: var(--primary-red);
            box-shadow: 0 0 0 4px rgba(227, 6, 19, 0.08);
            background: var(--white);
        }
        
        .input-group {
            box-shadow: var(--shadow-sm);
            border-radius: 10px;
            overflow: hidden;
        }
        
        .input-group-text {
            background: var(--white);
            border: 2px solid #e9ecef;
            border-left: none;
            padding: 0 16px;
            cursor: pointer;
            color: var(--medium-gray);
            transition: all 0.3s ease;
        }
        
        .input-group-text:hover {
            background: var(--light-red);
            color: var(--primary-red);
        }
        
        .btn-login {
            background: linear-gradient(135deg, var(--primary-red) 0%, var(--secondary-red) 100%);
            border: none;
            color: white;
            padding: 14px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            width: 100%;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(227, 6, 19, 0.15);
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
        
        .btn-login:hover {
            background: linear-gradient(135deg, #d10512 0%, #ff4a56 100%);
            box-shadow: 0 6px 16px rgba(227, 6, 19, 0.25);
            transform: translateY(-2px);
        }
        
        .btn-login:active::after {
            animation: ripple 1s ease-out;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
            padding: 14px 18px;
            font-size: 0.9rem;
            margin-bottom: 20px;
            box-shadow: var(--shadow-sm);
        }
        
        .alert-danger {
            background-color: #fdf2f3;
            color: #c53030;
            border-left: 4px solid #e53e3e;
        }
        
        .alert-success {
            background-color: #f0fff4;
            color: #276749;
            border-left: 4px solid #38a169;
        }
        
        .footer-info {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }
        
        .footer-info p {
            color: var(--medium-gray);
            font-size: 0.8rem;
            margin-bottom: 5px;
        }
        
        .secure-badge {
            display: inline-flex;
            align-items: center;
            background: var(--light-gray);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            color: var(--medium-gray);
            margin-top: 8px;
            gap: 6px;
            box-shadow: var(--shadow-sm);
        }
        
        .secure-badge i {
            color: #38a169;
        }
        
        /* Responsive Design */
        @media (max-width: 992px) {
            .split-login-card {
                flex-direction: column;
                height: auto;
                max-width: 480px;
                margin: 0 auto;
            }
            
            .login-left {
                min-height: 220px;
                padding: 25px;
            }
            
            .logo-image {
                max-width: 140px;
            }
            
            .company-name {
                font-size: 1.6rem;
            }
            
            .login-right {
                padding: 35px 30px;
            }
        }
        
        @media (max-width: 576px) {
            body {
                padding: 15px;
            }
            
            .split-login-card {
                border-radius: 20px;
            }
            
            .login-left {
                padding: 20px;
                min-height: 180px;
            }
            
            .login-right {
                padding: 30px 25px;
            }
            
            .login-form-header h2 {
                font-size: 1.5rem;
            }
            
            .company-name {
                font-size: 1.4rem;
            }
        }
        
        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes ripple {
            0% {
                transform: scale(0, 0);
                opacity: 0.5;
            }
            100% {
                transform: scale(40, 40);
                opacity: 0;
            }
        }
        
        .login-right {
            animation: fadeInUp 0.6s ease-out 0.2s both;
        }
        
        .login-form-header {
            animation: fadeInUp 0.6s ease-out 0.3s both;
        }
        
        .form-group {
            animation: fadeInUp 0.6s ease-out 0.4s both;
        }
        
        /* Loading spinner */
        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Custom focus styles */
        .form-control:focus {
            outline: none;
            border-color: var(--primary-red);
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: var(--light-gray);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--primary-red);
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="split-login-card">
            <!-- Left Side - Gedung GI -->
            <div class="login-left">
                <div class="logo-section">
                    <!-- Logo Global Intermedia -->
                    <img src="/images/logo_gi.png" 
                         alt="Global Intermedia" 
                         class="logo-image"
                         onerror="this.onerror=null; this.style.display='none'; 
                                  document.getElementById('fallback-logo').style.display='block';">
                    
                    <!-- Fallback jika logo tidak ditemukan -->
                    <div id="fallback-logo" style="display: none;">
                        <div class="company-name">Global Intermedia</div>
                        <div class="company-tagline">Digital Solutions</div>
                    </div>
                </div>
                
                <div class="company-info">
                    <h4><i class="fas fa-lock"></i> Dashboard Admin </h4>
                    <p>Sistem manajemen yang di khususkan untuk admin dan tim teknisi Global Intermedia.</p>
                </div>
            </div>
            
            <div class="login-right">
                <div class="login-form-header">
                    <h2>Admin Login</h2>
                    <p>Masukkan kredensial Anda untuk mengakses dashboard</p>
                </div>
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('admin.login.submit') }}" method="POST" id="loginForm">
                    @csrf
                    
                    <!-- Username -->
                    <div class="mb-3">
                        <label for="username" class="form-label">
                            <i class="fas fa-user-circle"></i> Username
                        </label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror" 
                               id="username" name="username" 
                               value="{{ old('username') }}" 
                               placeholder="Masukkan username" required autofocus>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label for="password" class="form-label">
                            <i class="fas fa-key"></i> Password
                        </label>
                        <div class="input-group">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" 
                                   placeholder="Masukkan password" required>
                            <span class="input-group-text" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </span>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid mb-4">
                        <button type="submit" class="btn btn-login" id="loginButton">
                            <i class="fas fa-sign-in-alt me-2"></i>Masuk ke Dashboard
                        </button>
                    </div>
                </form>

                <!-- Footer -->
                <div class="footer-info">
                    <p>© 2024 Global Intermedia. All rights reserved.</p>
                    <div class="secure-badge">
                        <i class="fas fa-shield-alt"></i>
                        <span>SSL Terenkripsi • Sistem Aman</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Cek apakah logo gambar berhasil dimuat
            function checkLogoLoaded() {
                const logoImage = $('.logo-image');
                const fallbackLogo = $('#fallback-logo');
                
                logoImage.on('load', function() {
                    console.log('Logo image loaded successfully');
                    $(this).addClass('loaded');
                }).on('error', function() {
                    console.log('Logo image failed to load, showing fallback');
                    $(this).hide();
                    fallbackLogo.show();
                });
            }
            
            // Panggil fungsi cek logo
            checkLogoLoaded();
            
            // Toggle password visibility dengan efek
            $('#togglePassword').click(function() {
                const passwordInput = $('#password');
                const icon = $(this).find('i');
                
                $(this).addClass('active');
                setTimeout(() => {
                    $(this).removeClass('active');
                }, 300);
                
                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordInput.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });

            // Auto focus logic
            @if(old('username'))
                $('#password').focus();
            @else
                $('#username').focus();
            @endif

            // Form submission dengan efek loading yang lebih smooth
            $('#loginForm').on('submit', function(e) {
                const btn = $('#loginButton');
                const originalText = btn.html();
                
                btn.prop('disabled', true);
                btn.css('transform', 'scale(0.98)');
                btn.html('<span class="spinner me-2"></span>Memproses...');
                
                // Efek ripple pada card
                $('.split-login-card').css({
                    'transform': 'translateY(-2px) scale(0.995)',
                    'transition': 'all 0.3s ease'
                });
                
                return true;
            });

            // Alert auto dismiss dengan animasi
            setTimeout(function() {
                $('.alert').each(function() {
                    $(this).animate({
                        opacity: 0,
                        marginTop: '-10px'
                    }, 500, function() {
                        $(this).slideUp(300, function() {
                            $(this).remove();
                        });
                    });
                });
            }, 4500);

            // Hover effect yang lebih smooth
            $('.btn-login').hover(
                function() {
                    $(this).stop().animate({
                        boxShadow: '0 6px 20px rgba(227, 6, 19, 0.25)'
                    }, 200);
                },
                function() {
                    $(this).stop().animate({
                        boxShadow: '0 4px 12px rgba(227, 6, 19, 0.15)'
                    }, 200);
                }
            );

            // Efek saat mengetik di input
            $('.form-control').on('focus', function() {
                $(this).parent().addClass('focused');
            }).on('blur', function() {
                if ($(this).val() === '') {
                    $(this).parent().removeClass('focused');
                }
            });

            // Input validation styling
            $('.form-control').on('input', function() {
                if ($(this).val().length > 0) {
                    $(this).addClass('has-value');
                } else {
                    $(this).removeClass('has-value');
                }
            });

            // Initialize input states
            $('.form-control').each(function() {
                if ($(this).val().length > 0) {
                    $(this).addClass('has-value');
                }
            });

            // Add subtle entrance animation for form elements
            setTimeout(function() {
                $('.form-group').each(function(index) {
                    $(this).css({
                        'opacity': '0',
                        'transform': 'translateY(10px)'
                    }).delay(100 * index).animate({
                        'opacity': '1',
                        'transform': 'translateY(0)'
                    }, 400);
                });
            }, 500);
        });
    </script>
</body>
</html>