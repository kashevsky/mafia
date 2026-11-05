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
            object-fit: cover;
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
            <h1>Управление играми (список)</h1>
            <a href="{{ route('admin.game.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Добавить игру
            </a>
        </div>

        <nav aria-label="breadcrumb" class="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/admin/dashboard">Главная</a></li>
                <li class="breadcrumb-item active" aria-current="page">Игры</li>
            </ol>
        </nav>
    </div>

    <!-- Фильтры и поиск -->
    <div class="filters">
        @if ($admin->role == 'superadmin')
            <div class="filter-group">
                <label class="form-label">Клуб</label>
                <select class="form-select" id="clubFilter">
                    <option value="">Все клубы</option>
                    @foreach ($clubs as $club)
                        <option value="{{ $club->id }}">{{ $club->title }}</option>
                    @endforeach
                </select>
            </div>
        @endif
        <div class="filter-group search-box">
            <label class="form-label">Поиск</label>
            <input type="text" id="searchInput" placeholder="Введите название игры...">
            <i class="fas fa-search" style="top: 56px"></i>
        </div>
    </div>

    <h2>Список игр</h2>

    <!-- Сетка игр -->
    <div class="clubs-grid" id="gamesGrid">
        @foreach ($games as $game)
            <div class="club-card" data-club-id="{{ $game->club_id }}">
                <div class="club-image">
                    <img src="{{ $game->avatar }}" alt="{{ $game->title }}">
                </div>
                <div class="club-info">
                    <h3 class="club-title">{{ $game->title }}</h3>
                    <p class="club-city"><i class="fas fa-map-marker-alt"></i> {{ \Carbon\Carbon::parse($game->date)->format('d.m.Y') }} в {{ $game->time }}</p>
                    <p class="club-city" style="color: #dc3546a5; margin-top: 2px; font-size: 10px">
                        {{ $game->club->title }}
                    </p>
                    <p class="club-city"
                        style="color: {{ $game->newApplications->count() > 0 ? '#129700a5; font-weight: 600' : '#6c757d' }}; margin-top: 2px; font-size: 10px">
                        Заявок: {{ $game->newApplications->count() }}
                    </p>
                </div>
                <div class="club-actions">
                    <a href="{{ route('admin.game.edit', $game->id) }}" class="btn-action btn-edit">
                        <i class="fas fa-edit"></i>
                        @if ($admin->role == 'hoster')
                            В игру
                        @else
                            Редактировать
                        @endif
                    </a>
                    @if ($admin->role != 'hoster')
                        <a href="{{ route('admin.game.delete', $game->id) }}" class="btn-action btn-delete"
                            onclick="return confirm('Вы уверены, что хотите удалить эту игру?')">
                            <i class="fas fa-trash"></i> Удалить
                        </a>
                    @endif


                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Если игр нет -->
    @if ($games->isEmpty())
        <div class="no-data">
            <p>Игры не найдены. Добавьте первую игру.</p>
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const clubFilter = document.getElementById('clubFilter');
            const gamesGrid = document.getElementById('gamesGrid');
            const allGames = Array.from(gamesGrid.querySelectorAll('.club-card'));
            const searchInput = document.getElementById('searchInput');

            // Функция для применения фильтров
            function applyFilters() {
                const selectedClubId = clubFilter.value;
                const searchTerm = searchInput.value.toLowerCase().trim();

                allGames.forEach(gameCard => {
                    const gameClubId = gameCard.getAttribute('data-club-id');
                    const titleElement = gameCard.querySelector('.club-title');
                    const titleText = titleElement.textContent.toLowerCase();

                    // Условия видимости: соответствует клубу И (соответствует поиску ИЛИ поиск пуст)
                    const matchesClub = !selectedClubId || gameClubId === selectedClubId;
                    const matchesSearch = !searchTerm || titleText.includes(searchTerm);

                    if (matchesClub && matchesSearch) {
                        gameCard.style.display = 'block';
                    } else {
                        gameCard.style.display = 'none';
                    }
                });
            }

            // Обработчики событий
            clubFilter.addEventListener('change', applyFilters);
            searchInput.addEventListener('input', applyFilters);
        });
    </script>
@endsection
