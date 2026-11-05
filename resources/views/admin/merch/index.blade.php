@extends('admin.layouts.main')
@section('content')
    <style>
        .clubs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .club-card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, .1);
            transition: transform .2s;
        }

        .club-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, .15);
        }

        .club-image {
            height: 180px;
            overflow: hidden;
        }

        .club-image img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .club-info {
            padding: 15px;
            text-align: center;
        }

        .club-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0 0 8px 0;
            color: #333;
        }

        .club-city {
            color: #6c757d;
            margin: 0;
            font-size: 0.9rem;
        }

        .club-actions {
            padding: 15px;
            display: flex;
            gap: 10px;
            justify-content: center;
            border-top: 1px solid #dee2e6;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 0.875rem;
        }

        .btn-edit {
            background: #28a745;
            color: white;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }
    </style>
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1>Управление мерчом (список)</h1>
            <a href="{{ route('admin.merch.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Добавить товар
            </a>
        </div>
        <nav aria-label="breadcrumb" class="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/admin/dashboard">Главная</a></li>
                <li class="breadcrumb-item active" aria-current="page">Мерч</li>
            </ol>
        </nav>
    </div>

    {{-- <!-- Фильтры и поиск -->
    <div class="filters">
        <div class="filter-group">
            <label class="form-label">Город</label>
            <select class="form-select" id="cityFilter">
                <option value="">Все города</option>
                @foreach ($cities as $city)
                    <option value="{{ $city->title }}">{{ $city->title }}</option>
                @endforeach
            </select>
        </div>

        <div class="filter-group search-box">
            <label class="form-label">Поиск</label>
            <input type="text" id="searchInput" placeholder="Введите название клуба...">
            <i class="fas fa-search"></i>
        </div>
    </div> --}}

    <h2>Мерч</h2>

    <!-- Сетка клубов -->
    <div class="clubs-grid" id="clubsGrid">
        @foreach ($merches as $merch)
            <div class="club-card" data-city="{{ $club->city->title ?? '' }}">
                <div class="club-image">

                    <img src="{{ $merch->src }}" alt="{{ $merch->title }}">

                </div>
                <div class="club-info">
                    <h3 class="club-title">{{ $merch->title }}</h3>

                    <p class="club-city"><i class="fas fa-ruble-sign"></i> {{ $merch->price }}</p>

                </div>
                <div class="club-actions">
                    <a href="{{ route('admin.merch.delete', $merch->id) }}" class="btn-action btn-edit">
                        <i class="fas fa-edit"></i> Редактировать
                    </a>
                    <a href="{{ route('admin.merch.delete', $merch->id) }}" class="btn-action btn-delete"
                        onclick="return confirm('Вы уверены, что хотите удалить этот мерч?')">
                        <i class="fas fa-trash"></i> Удалить
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Если клубов нет -->
    @if ($merches->isEmpty())
        <div class="no-data">
            <p>Мерч не найден. Добавьте первый мерч.</p>
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cityFilter = document.getElementById('cityFilter');
            const searchInput = document.getElementById('searchInput');
            const clubsGrid = document.getElementById('clubsGrid');
            const allClubs = Array.from(clubsGrid.querySelectorAll('.club-card'));

            // Функция для применения фильтров
            function applyFilters() {
                const selectedCity = cityFilter.value.toLowerCase().trim();
                const searchTerm = searchInput.value.toLowerCase().trim();

                allClubs.forEach(clubCard => {
                    const clubCity = clubCard.getAttribute('data-city').toLowerCase();
                    const titleElement = clubCard.querySelector('.club-title');
                    const titleText = titleElement.textContent.toLowerCase();

                    // Условия видимости: соответствует городу И (соответствует поиску ИЛИ поиск пуст)
                    const matchesCity = !selectedCity || clubCity === selectedCity;
                    const matchesSearch = !searchTerm || titleText.includes(searchTerm);

                    if (matchesCity && matchesSearch) {
                        clubCard.style.display = 'block';
                    } else {
                        clubCard.style.display = 'none';
                    }
                });
            }

            // Обработчики событий
            cityFilter.addEventListener('change', applyFilters);
            searchInput.addEventListener('input', applyFilters);
        });
    </script>
@endsection
