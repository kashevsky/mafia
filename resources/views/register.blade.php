@extends('layouts.club')
@section('content')
    <div class="container" x-data="registerPage()">
        <style>
            .register-container {
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
                padding: 20px;
            }

            .register-card {
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
                font-size: 16px;
                transition: all 0.2s;
            }

            .form-input:focus {
                outline: none;
                border-color: #f43f5e;
                background: rgba(17, 24, 39, 0.9);
            }

            .register-btn {
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

            .register-btn:hover {
                opacity: 0.9;
                transform: translateY(-2px);
            }

            .login-link {
                text-align: center;
                color: #cbd5e1;
                font-size: 14px;
                margin-top: 16px;
            }

            .login-link a {
                color: #b91c1c;
                text-decoration: none;
                font-weight: 500;
            }

            .terms-text {
                font-size: 12px;
                color: #9ca3af;
                text-align: center;
                margin-top: 8px;
            }

            .error-message {
                color: #b91c1c;
                font-size: 12px;
                margin-top: 4px;
                height: 16px;
            }
            .terms-text a {
                color: #b91c1c;
                text-decoration: none;
                font-weight: 500;
            }
        </style>



        <div class="register-container">
            <div class="register-card">
                <div class="logo">
                    <div class="logo">
                        <img src="/images/logo.png" alt="Logo">
                        ФОКС
                    </div>

                </div>

                <form action="{{ route('register.make') }}" method="POST" class="register-form">
                    @csrf
                    <div class="form-group">
                        <label class="form-label" for="nickname">Никнейм</label>
                        <input type="text" id="nickname" class="form-input" name="nickname"
                            placeholder="Придумайте уникальный ник" required>
                        <div class="error-message" x-show="errors.nickname" x-text="errors.nickname"></div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">Пароль</label>
                        <input type="password" id="password" class="form-input" name="password"
                            placeholder="Придумайте пароль" required>
                        <div class="error-message" x-show="errors.password" x-text="errors.password"></div>
                    </div>

                    <button type="submit" class="register-btn">Создать аккаунт</button>
                </form>

                <div class="terms-text">
                    Нажимая кнопку «Создать аккаунт», вы соглашаетесь с <a href="#">правилами клуба</a> и <a
                        href="#">политикой конфиденциальности</a>.
                </div>

                <div class="login-link">
                    Уже есть аккаунт? <a href="/login">Войти</a>
                </div>
            </div>
        </div>

        <script>
            function registerPage() {
                return {
                    nickname: '',
                    password: '',
                    errors: {
                        nickname: '',
                        password: ''
                    },

                    register() {
                        // Сброс ошибок
                        this.errors.nickname = '';
                        this.errors.password = '';

                        let isValid = true;

                        // Валидация ника
                        if (!this.nickname) {
                            this.errors.nickname = 'Никнейм обязателен для заполнения';
                            isValid = false;
                        } else if (this.nickname.length < 3) {
                            this.errors.nickname = 'Никнейм должен содержать минимум 3 символа';
                            isValid = false;
                        }

                        // Валидация пароля
                        if (!this.password) {
                            this.errors.password = 'Пароль обязателен для заполнения';
                            isValid = false;
                        } else if (this.password.length < 6) {
                            this.errors.password = 'Пароль должен содержать минимум 6 символов';
                            isValid = false;
                        }

                        if (!isValid) return;

                        // В реальном приложении здесь будет запрос к API
                        alert(`Аккаунт создан с ником "${this.nickname}"!`);
                        // window.location.href = '/login';
                    }
                };
            }
        </script>
    </div>

@endsection
