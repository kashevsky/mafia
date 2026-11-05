    <style>
        /* Кнопки общего назначения */
        .nav-item.active svg {
            stroke: #e41f1f;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-primary {
            background: #b91c1c;
            color: white;
        }

        .btn-primary:hover {
            background: #e11d48;
        }

        .btn-secondary {
            background: #374151;
            color: #f9fafb;
            border: 1px solid #4b5563;
        }

        .btn-secondary:hover {
            background: #4b5563;
        }

        /* Анимации */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.3s ease-out;
        }

        /* Адаптивность */
        @media (max-width: 480px) {
            .container {
                max-width: 100%;
            }

            .user-avatar {
                width: 64px;
                height: 64px;
            }
        }

        /* Фиксированное меню внизу экрана */
        .bottom-nav {
            margin: 1px auto;
            position: fixed;
            bottom: -2px;
            left: 0;
            right: 0;
            z-index: 1000;
            max-width: 446px;
        }

        .glass-menu {
            display: flex;
            justify-content: stretch;
            align-items: center;
            background: #1e2433;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            padding: 0;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.4);
        }

        .nav-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 6px;
            text-decoration: none;
            color: #c8cdd8;
            font-size: 12px;
            font-family: Arial, sans-serif;
            transition: color 0.2s;
            padding: 12px 8px 10px;
            position: relative;
        }

        /* Вертикальный разделитель между пунктами */
        .nav-item+.nav-item::before {
            content: "";
            position: absolute;
            left: 0;
            top: 18%;
            height: 64%;
            width: 1px;
            background: rgba(255, 255, 255, 0.15);
        }

        .nav-item:hover {
            color: #f0f2f5;
        }

        .nav-item.active {
            color: #e41f1f;
        }

        /* Иконки — stroke-стиль, не fill */
        .nav-item svg {
            width: 26px;
            height: 26px;
            fill: none;
            stroke: currentColor;
            stroke-width: 1.6;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        @media (max-width: 360px) {
            .nav-item span {
                font-size: 10px;
            }
        }
    </style>
    {{-- <br><br><br><br> --}}
    @php
        $url = url()->current();
    @endphp
    <div class="bottom-nav">
        <nav class="glass-menu">

            <!-- Главная -->
            <!-- Главная -->
            <a href="/" class="nav-item {{ request()->routeIs('club.index') ? 'active' : '' }}">
                <img src="new/m1{{ request()->routeIs('club.index') ? '-active' : '' }}.svg">
                <span>Главная</span>
            </a>


            <!-- Афиша -->
            <a href="/events" class="nav-item {{ str_contains($url, '/event') ? 'active' : '' }}">
                <img src="new/m2{{ str_contains($url, '/event') ? '-active' : '' }}.svg">
                <span>Афиша</span>
            </a>

            <!-- Магазин -->
            <a href="/store" class="nav-item {{ str_contains($url, '/store') ? 'active' : '' }}">
     <img src="new/m3{{ str_contains($url, '/store') ? '-active' : '' }}.svg">
                <span>Магазин</span>
            </a>

            <!-- Профиль -->
            <a href="/account" class="nav-item {{ str_contains($url, '/account') ? 'active' : '' }}">
               <img src="new/m4{{ str_contains($url, '/account') ? '-active' : '' }}.svg">
                <span>Профиль</span>
            </a>

        </nav>
    </div>
