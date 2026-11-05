@extends('layouts.club')
@section('content')
    <div class="container" x-data="loginPage()">
        <style>
            .login-container {
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
                padding: 20px;
            }

            .login-card {
                width: 100%;
                max-width: 400px;
                background: rgba(31, 41, 55, 0.9);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                border-radius: 16px;
                padding: 32px;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            }



            .form-group {
                margin-bottom: 15px;
            }

            .form-label {
                display: block;
                color: #e2e8f0;
                font-size: 14px;
                margin-bottom: 1px;
                font-weight: 300;
            }

            .form-input {
                width: 100%;
                padding: 12px 16px;
                background: rgba(17, 24, 39, 0.8);
                border: 1px solid rgba(255, 255, 255, 0.1);
                border-radius: 8px;
                color: #f9fafb;
                font-size: 14px;
                transition: all 0.2s;
            }

            .form-input:focus {
                outline: none;
                border-color: #b91c1c;
                background: rgba(17, 24, 39, 0.9);
            }

            .login-btn {
                width: 100%;
                padding: 14px;

                color: white;
                border: none;
                border-radius: 28px;
                font-size: 16px;
                font-weight: 500;
                cursor: pointer;
                transition: all 0.2s;
                border: 2px solid #b91c1c;
                background: transparent;
                margin: 15px 0 10px 0;

            }

            .login-btn:hover {
                opacity: 0.9;
                transform: translateY(-2px);
            }

            .forgot-password {
                text-align: center;
                margin: 16px 0;
            }

            .forgot-password a {
                color: #9ca3af;
                text-decoration: none;
                font-size: 14px;
            }

            .forgot-password a:hover {
                color: #b91c1c;
            }

            .signup-link {
                text-align: center;
                color: #cbd5e1;
                font-size: 14px;
            }

            .signup-link a {
                color:#b91c1c;
                text-decoration: none;
                font-weight: 500;
            }

            .social-login {
                margin: 24px 0;
                text-align: center;
            }

            .social-btn {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 10px 20px;
                background: rgba(255, 255, 255, 0.05);
                border: 1px solid rgba(255, 255, 255, 0.1);
                color: #f9fafb;
                border-radius: 8px;
                text-decoration: none;
                font-size: 14px;
                transition: all 0.2s;
            }

            .social-btn:hover {
                background: rgba(244, 63, 94, 0.15);
                transform: translateY(-1px);
            }
        </style>



        <div class="login-container">
            <div class="login-card">
                <div class="logo">
                    <img src="/images/logo.png" alt="Logo">
                    ФОКС
                </div>

                <form action="{{ route('login.make') }}" method="POST" class="login-form">
                    @csrf
                    <div class="form-group">
                        <label class="form-label" for="email">Ник</label>
                        <input type="text" id="email" class="form-input" name="nickname"
                            placeholder="Введите ваш ник" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">Пароль</label>
                        <input type="password" id="password" class="form-input" name="password"
                            placeholder="Введите пароль" required>
                    </div>

                    <button type="submit" class="login-btn">Войти в аккаунт</button>
                </form>





                <div class="signup-link">
                    Нет аккаунта? <a href="/register/user">Зарегистрироваться</a>
                </div>
            </div>
        </div>

        <script>
            function loginPage() {
                return {
                    email: '',
                    password: ''
                };
            }
        </script>
    </div>
@endsection
