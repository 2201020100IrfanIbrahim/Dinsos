<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login NAMA SISTEM - Sistem Informasi Manajemen</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            display: flex;
            overflow: hidden;
        }

        .login-wrapper {
            display: flex;
            width: 100%;
            height: 100vh;
        }

        /* Left Side - Image */
        .left-side {
            flex: 1.5;
            position: relative;
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), 
                url('<?= base_url('images/login.jpeg') ?>') center/cover;            
            display: flex;
            align-items: flex-end;
            justify-content: flex-start;
            margin: 10px;
            border-radius:20px;
            padding: 40px;
        }
        
        .left-content {
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        }

        .left-content h1 {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: -1px;
        }

        .left-content p {
            font-size: 18px;
            font-weight: 300;
            opacity: 0.9;
        }

        /* Right Side - Login Form */
        .right-side {
            flex: 1;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 0 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .title {
            color: #2c3e50;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .subtitle {
            color: #7f8c8d;
            font-size: 14px;
            font-weight: 400;
            margin-bottom: 4px;
            line-height: 1.4;
        }

        .government-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #28a745;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 15px;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            color: #6c757d;
            font-weight: 500;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-input {
            width: 100%;
            padding: 15px 45px 15px 15px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: white;
            color: #495057;
        }

        .form-input:focus {
            outline: none;
            border-color: #6c5ce7;
            box-shadow: 0 0 0 3px rgba(108, 92, 231, 0.1);
        }

        .form-input::placeholder {
            color: #adb5bd;
        }

        .input-icon {
            position: absolute;
            right: 15px;
            top: 65%;
            transform: translateY(-50%);
            color: #6c757d;
            font-size: 16px;
            cursor: pointer;
        }

        .input-icon.active {
            color: #6c5ce7;
        }

        .login-button {
            width: 100%;
            padding: 15px;
            background: #6c5ce7;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .login-button:hover {
            background: #5a4fcf;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(108, 92, 231, 0.3);
        }

        .login-button:active {
            transform: translateY(0);
        }

        .error-message {
            background: #dc3545;
            color: white;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 500;
            text-align: center;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            color: #6c757d;
            font-size: 13px;
            line-height: 1.4;
        }

        .footer p {
            margin-bottom: 4px;
        }

        .footer strong {
            color: #495057;
        }

        /* Loading Animation */
        .loading {
            opacity: 0.7;
            pointer-events: none;
        }

        .loading .login-button {
            background: #6c757d;
            cursor: not-allowed;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .login-wrapper {
                flex-direction: column;
            }
            
            .left-side {
                flex: 0 0 200px;
                padding: 20px;
            }
            
            .left-content h1 {
                font-size: 28px;
            }
            
            .left-content p {
                font-size: 14px;
            }
            
            .right-side {
                flex: 1;
                padding: 20px;
            }
            
            .title {
                font-size: 24px;
            }
        }

        @media (max-width: 480px) {
            .left-side {
                flex: 0 0 150px;
            }
            
            .login-container {
                padding: 0 10px;
            }
            
            .header {
                margin-bottom: 30px;
            }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <!-- Left Side - Image -->
        <div class="left-side">
            <div class="left-content">
                <h1>SPBS-D Kepri v1.0</h1>
                <p>Sistem Bantuan Pengelolaan Bantuan Sosial & Difabel</p>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="right-side">
            <div class="login-container">
                <div class="header">
                    <h1 class="title">SPPS</h1>
                    <p class="subtitle">Sistem Pengamatan & Penilaian Bantuan Sosial</p>
                    <p class="subtitle">Dinas Sosial Provinsi Kepulauan Riau</p>
                    <!-- <div class="government-badge">
                        <span>🏛️</span>
                        Portal Resmi Pemerintah
                    </div> -->
                </div>

                <!-- Error Message (PHP will be processed server-side) -->
                <?php if(session()->getFlashdata('error')): ?>
                    <div class="error-message">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= site_url('login') ?>" method="post" id="loginForm">
                    <?= csrf_field() ?>
                    
                    <div class="form-group">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" id="username" name="username" class="form-input" required 
                               placeholder="superadmin" value="superadmin">
                        <span class="input-icon">👤</span>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-input" required 
                               placeholder="superadmin123" value="superadmin123">
                        <span id="togglePassword" class="input-icon">👁</span>
                    </div>

                    <button type="submit" class="login-button">
                        Masuk
                    </button>
                </form>

                <div class="footer">
                    <p>&copy; 2025 NAMA SISTEM | Dinas Provinsi Kepulauan Riau</p>
                    <p>Untuk bantuan teknis, hubungi: <strong>support@sim-bankel.go.id</strong></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Form submission handling
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const button = this.querySelector('.login-button');
            const container = this.closest('.login-container');
            
            // Add loading state
            container.classList.add('loading');
            button.innerHTML = '🔄 Memverifikasi...';
            
            // Note: In real implementation, this would be handled by the server
            // This is just for visual feedback
        });

        // Prevent multiple submissions
        let isSubmitting = false;
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            if (isSubmitting) {
                e.preventDefault();
                return false;
            }
            isSubmitting = true;
        });

        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            if (type === 'password') {
                this.innerHTML = '👁';
                this.classList.remove('active');
            } else {
                this.innerHTML = '👁';
                this.classList.add('active');
            }
        });
    </script>
</body>
</html>