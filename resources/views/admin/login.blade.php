@extends('admin.layouts.main')

@section('content')
    <style>
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
        }

        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 30px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .login-card .page-header h1 {
            font-size: 1.5rem;
            margin-bottom: 0;
            color: #333;
        }

        .login-card .form-label {
            font-weight: 500;
            color: #495057;
        }

        .login-card .form-control {
            padding: 10px 12px;
            font-size: 1rem;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }

        .login-card .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            outline: 0;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            font-size: 1rem;
            font-weight: 600;
        }

        .alert.alert-danger {
            padding: 12px 16px;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
    <div class="login-container">
        <div class="login-card">
            <div class="page-header text-center mb-4">
                <h1><i class="fas fa-sign-in-alt"></i> Вход в систему</h1>
            </div>

            <!-- Форма логина -->
            <form action="{{ route('admin.login.make') }}" method="POST">
                @csrf

                <div class="form-group mb-3">
                    <label for="name" class="form-label">Имя</label>
                    <input type="text" class="form-control" id="name" name="name"
                        placeholder="Введите имя" required autofocus>
                </div>

                <div class="form-group mb-4">
                    <label for="password" class="form-label">Пароль</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Введите пароль"
                        required>
                </div>

                <!-- Сообщение об ошибке -->
                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        @foreach ($errors->all() as $error)
                            <p class="mb-0">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-login">
                        <i class="fas fa-sign-in-alt"></i> Войти
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
