@extends('admin.layouts.main')
@section('content')
    <style>
        .table-scheme {
            position: relative;
            min-height: 450px;
            background: #f8f9fa;
            border-radius: 8px;
            padding: 60px 30px;
            text-align: center;
        }

        .table-center {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #3498db;
            color: white;
            padding: 20px 40px;
            border-radius: 50%;
            text-align: center;
            font-weight: bold;
            z-index: 10;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .seating-positions {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 25px;
        }

        .seat-position {
            width: 130px;
            height: 150px;
            border: 2px dashed #dfe6e9;
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 12px;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
        }

        .seat-position:hover {
            border-color: #3498db;
            background-color: #e3f2fd;
        }

        .seat-selected {
            border-color: #27ae60 !important;
            background-color: #d4edda !important;
        }

        .position-number {
            font-size: 16px;
            font-weight: bold;
            color: #7f8c8d;
            margin-bottom: 8px;
        }

        .assigned-player {
            width: 100%;
            height: 90px;
            background: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 8px;
            overflow: hidden;
            transition: all 0.2s ease;
        }

        .assigned-player .placeholder {
            color: #95a5a6;
            font-style: italic;
            font-size: 14px;
        }

        .player-item {
            display: flex;
            align-items: center;
            padding: 12px 10px;
            border-bottom: 1px solid #ecf0f1;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }
        .remove-seat{
            background: #e74c3c;
            color: white;
            padding: 3px 3px;
            width: 16px;
            height: 16px;
            line-height: 16px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            border-radius: 44px;
            font-size: 8px;
        }

        .player-item:hover {
            background-color: #f8f9fa;
        }

        .player-item img {
            width: 32px;
            height: 32px;
            object-fit: cover;
            border-radius: 50%;
            margin-right: 14px;
        }

        .position-assigned {
            margin-left: auto;
            background: #3498db;
            color: white;
            padding: 3px 10px;
            border-radius: 14px;
            font-size: 13px;
            display: none;
        }

        .player-selected {
            background-color: #e3f2fd;
            border-left: 4px solid #3498db;
        }

        @media (max-width: 768px) {
            .seating-positions {
                gap: 15px;
            }

            .seat-position {
                width: 100px;
                height: 120px;
            }

            .player-item {
                padding: 8px 8px;
            }
        }
        .card{
            max-height: 1000px;
            overflow-y: auto;
        }

    </style>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label for="table_name" class="form-label">
                            <i class="fas fa-tag"></i> Название стола
                        </label>
                        <input type="text" id="table_name" name="table_name" class="form-control"
                            placeholder="Например: 'Основной стол', 'VIP-стол' или оставьте пустым"
                            value="{{ $table->title }}" maxlength="100">
                        <small class="text-muted">
                            Можно не указывать
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-header">
        <h1><i class="fas fa-table"></i> Рассадка игроков: {{ $game->title }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/admin/dashboard">Главная</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.game.index') }}">Игры</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.game.index') }}">{{ $table->game->title }}</a></li>
                <li class="breadcrumb-item active">Рассадка</li>
            </ol>
        </nav>
    </div>

    <form action="{{ route('admin.seats.update') }}" method="POST" id="seatingForm">
        @csrf
        <input type="hidden" name="table_id" value="{{ $table->id }}">
        <input type="hidden" name="game_id" value="{{ $game->id }}">
        <div class="content-card">
            <h2 class="section-title"><i class="fas fa-chess-board"></i> Схема стола</h2>

            <div class="row">
                <!-- Список игроков для выбора -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-users"></i> Доступные игроки</h5>
                        </div>
                        <div class="card-body">
                            <div class="search-box mb-3">
                                <input type="text" id="playerSearch" placeholder="Поиск игрока..." class="form-control">
                                <i class="fas fa-search"></i>
                            </div>
                            <div class="player-selector" id="availablePlayers">
                                @foreach ($players as $player)
                                    <div class="player-item" data-player-id="{{ $player->account_id }}"
                                        data-player-name="{{ $player->name }}"
                                        data-player-nickname="{{ $player->nickname ?? '' }}"
                                        data-player-avatar="{{ !empty($player->avatar) && strlen(trim($player->avatar)) > 0 ? $player->avatar : '/def.svg' }}"
                                        onclick="selectPlayer(this)">
                                        <img src="{{ !empty($player->avatar) && strlen(trim($player->avatar)) > 0 ? $player->avatar : '/def.svg' }}"
                                            alt="Avatar">

                                        <span>
                                            @if(!empty($player->nickname))
                                                {{ $player->nickname }} ({{ $player->name }})
                                            @else
                                                {{ $player->name }}
                                            @endif
                                        </span>
                                        <span class="position-assigned" id="pos-{{ $player->account_id }}"></span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-user-friends"></i> Все пользователи</h5>
                        </div>
                        <div class="card-body">
                            <div class="search-box mb-3">
                                <input type="text" id="allUserSearch" placeholder="Поиск пользователя..." class="form-control">
                                <i class="fas fa-search"></i>
                            </div>
                            <div class="player-selector" id="allUsersPlayers">
                                @foreach ($allUsers as $user)
                                    <div class="player-item" data-player-id="{{ $user->id }}"
                                        data-player-name="{{ $user->name }}"
                                        data-player-nickname="{{ $user->nickname ?? '' }}"
                                        data-player-avatar="{{ !empty($user->avatar) && strlen(trim($user->avatar)) > 0 ? $user->avatar : '/def.svg' }}"
                                        onclick="selectPlayer(this)">
                                        <img src="{{ !empty($user->avatar) && strlen(trim($user->avatar)) > 0 ? $user->avatar : '/def.svg' }}"
                                            alt="Avatar">

                                        <span>
                                            @if(!empty($user->nickname))
                                                {{ $user->nickname }} ({{ $user->name }})
                                            @else
                                                {{ $user->name }}
                                            @endif
                                        </span>
                                        <span class="position-assigned" id="pos-{{ $user->id }}"></span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Схема стола -->
                <div class="col-md-8">
                    <div class="table-scheme">
                        <div class="seating-positions" id="seatingPositions">
                            @for ($i = 1; $i <= min($table->total_space, $table->total_space); $i++)
                                <div class="seat-position" data-position="{{ $i }}"
                                    onclick="selectSeat({{ $i }})">
                                    <span class="position-number">{{ $i }}</span>
                                    <div class="assigned-player" id="seat-{{ $i }}">
                                        <span class="placeholder">Свободно</span>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-card">
            <h2 class="section-title"><i class="fas fa-info-circle"></i> Информация</h2>
            <p><strong>Игра:</strong> {{ $game->title }}</p>
            <p><strong>Дата:</strong> {{ \Carbon\Carbon::parse($game->date)->format('d.m.Y') }} в {{ $game->time }}</p>
            <p><strong>Всего мест:</strong> {{ min(21, $game->max_players) }}</p>
            <p><strong>Занято мест:</strong> <span id="occupiedSeats">0</span> из {{ min(21, $game->max_players) }}</p>
        </div>
        <div class="content-card">
            <div class="action-buttons">
                <button type="submit" class="btn btn-primary" id="saveSeatingBtn">
                    <i class="fas fa-save"></i> Сохранить рассадку
                </button>
                <a href="{{ route('admin.game.edit', $game->id) }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Вернуться к редактированию
                </a>
                <button type="button" class="btn btn-warning" id="resetSeatingBtn">
                    <i class="fas fa-undo"></i> Сбросить рассадку
                </button>
                <a href="{{ route('roles.index', $table->id) }}" id="rolesBtn" class="btn btn-secondary" style="background-color: green">
                    <i class=""></i> К назначению ролей
                </a>
            </div>
        </div>
    </form>

    @if (!$game->finished_at)
        <a href="{{ route('admin.game.makePublic', $game->id) }}" class="btn btn-success me-2"
            style="background-color: rgb(233, 155, 0); border-color: rgb(233, 155, 0);">
            <i class="fas fa-table"></i>
            Опубликовать результаты</a>
    @endif

    <script>
        let currentSeating = @json($currentSeating ?? []);

        @php
            $playersMap = [];
            foreach ($players as $p) {
                $displayName = !empty($p->nickname) ? $p->nickname . ' (' . $p->name . ')' : $p->name;
                $playersMap[$p->account_id] = [
                    'name' => $displayName,
                    'avatar' => !empty($p->avatar) && strlen(trim($p->avatar)) > 0 ? $p->avatar : '/def.svg',
                ];
            }
            foreach ($allUsers as $u) {
                if (!isset($playersMap[$u->id])) {
                    $displayName = !empty($u->nickname) ? $u->nickname . ' (' . $u->name . ')' : $u->name;
                    $playersMap[$u->id] = [
                        'name' => $displayName,
                        'avatar' => !empty($u->avatar) && strlen(trim($u->avatar)) > 0 ? $u->avatar : '/def.svg',
                    ];
                }
            }
        @endphp

        const playersMap = @json($playersMap);

        let selectedPlayer = null;
        let selectedSeat = null;

        document.addEventListener('DOMContentLoaded', function() {
            let tableName = null;
            const tableNameElement = document.getElementById('table_name');
            if (tableNameElement) {
                tableName = tableNameElement.value.trim() || null;
            }

            Object.entries(currentSeating).forEach(function([position, playerId]) {
                renderSeatFromDb(parseInt(position), parseInt(playerId));
            });

            updateOccupiedSeats();
            updateRolesBtn();

            document.getElementById('resetSeatingBtn').addEventListener('click', function(e) {
                e.preventDefault();
                resetSeating();
            });

            document.getElementById('playerSearch').addEventListener('input', function() {
                filterPlayers(this.value, 'availablePlayers');
            });

            document.getElementById('allUserSearch').addEventListener('input', function() {
                filterPlayers(this.value, 'allUsersPlayers');
            });
        });

        function updateRolesBtn() {
            const btn = document.getElementById('rolesBtn');
            if (!btn) return;

            const count = Object.keys(currentSeating).length;
            if (count < 8) {
                btn.classList.add('disabled');
                btn.setAttribute('aria-disabled', 'true');
                btn.style.pointerEvents = 'none';
                btn.style.opacity = '0.5';
                btn.title = 'Необходимо рассадить минимум 8 игроков';
            } else {
                btn.classList.remove('disabled');
                btn.removeAttribute('aria-disabled');
                btn.style.pointerEvents = '';
                btn.style.opacity = '';
                btn.title = '';
            }
        }

        function renderSeatFromDb(position, playerId) {
            const player = playersMap[playerId];
            if (!player) return;

            const seat = document.getElementById(`seat-${position}`);
            if (!seat) return;

            seat.innerHTML = buildSeatHtml(position, player.avatar, player.name);
            seat.querySelector('.remove-seat').addEventListener('click', function() {
                removeSeat(position);
            });

            seat.closest('.seat-position').classList.add('player-assigned');

            const positionSpan = document.getElementById(`pos-${playerId}`);
            if (positionSpan) {
                positionSpan.textContent = `#${position}`;
                positionSpan.style.display = 'inline-block';
            }
        }

        function buildSeatHtml(position, avatarSrc, playerName) {
            return `
            <div class="d-flex align-items-center justify-content-center w-100 px-1">
                <span style="font-size: 11px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 85px;" title="${playerName}">${playerName}</span>
                <button type="button" class="btn btn-sm btn-danger ms-1 remove-seat" data-position="${position}">
                    <i class="fas fa-times"></i>
                </button>
            </div>`;
        }

        function selectPlayer(playerElement) {
            const playerId = playerElement.dataset.playerId;

            if (selectedPlayer && selectedPlayer.dataset.playerId === playerId) {
                selectedPlayer.classList.remove('player-selected');
                selectedPlayer = null;
                return;
            }

            if (selectedPlayer) {
                selectedPlayer.classList.remove('player-selected');
            }

            selectedPlayer = playerElement;
            selectedPlayer.classList.add('player-selected');
        }

        function selectSeat(position) {
            if (currentSeating[position]) {
                const playerId = currentSeating[position];
                const player = playersMap[playerId];
                const displayName = player ? player.name : 'Игроком';
                alert(`Место ${position} занято: ${displayName}`);
                return;
            }

            selectedSeat = position;

            document.querySelectorAll('.seat-position').forEach(seat => {
                seat.classList.remove('seat-selected');
            });
            document.querySelector(`[data-position="${position}"]`).classList.add('seat-selected');

            if (selectedPlayer) {
                assignPlayerToSeat(position);
            } else {
                alert('Сначала выберите игрока из списка слева!');
            }
        }

        function assignPlayerToSeat(position) {
            if (!selectedPlayer || !selectedSeat) return;

            const playerId = selectedPlayer.dataset.playerId;
            const avatar = selectedPlayer.dataset.playerAvatar;

            const playerInMap = playersMap[playerId];
            const playerName = playerInMap ? playerInMap.name : selectedPlayer.dataset.playerName;

            for (const [pos, id] of Object.entries(currentSeating)) {
                if (id == playerId && pos != position) {
                    removeSeat(pos);
                    break;
                }
            }

            currentSeating[position] = playerId;

            const seat = document.getElementById(`seat-${position}`);
            seat.innerHTML = buildSeatHtml(position, avatar, playerName);
            seat.querySelector('.remove-seat').addEventListener('click', function() {
                removeSeat(position);
            });

            const positionSpan = document.getElementById(`pos-${playerId}`);
            if (positionSpan) {
                positionSpan.textContent = `#${position}`;
                positionSpan.style.display = 'inline-block';
            }

            selectedPlayer.classList.remove('player-selected');
            document.querySelector(`[data-position="${selectedSeat}"]`).classList.remove('seat-selected');
            selectedPlayer = null;
            selectedSeat = null;

            updateOccupiedSeats();
            updateSeatingDisplay();
            updateRolesBtn();
        }

        function removeSeat(position) {
            const playerId = currentSeating[position];

            delete currentSeating[position];

            const seat = document.getElementById(`seat-${position}`);
            seat.innerHTML = '<span class="placeholder">Свободно</span>';
            seat.closest('.seat-position').classList.remove('player-assigned');

            const positionSpan = document.getElementById(`pos-${playerId}`);
            if (positionSpan) {
                positionSpan.style.display = 'none';
            }

            updateOccupiedSeats();
            updateSeatingDisplay();
            updateRolesBtn();
        }

        function updateSeatingDisplay() {
            document.querySelectorAll('.seat-position').forEach(seat => {
                seat.classList.remove('player-assigned');
            });

            Object.keys(currentSeating).forEach(position => {
                const seatDiv = document.getElementById(`seat-${position}`);
                if (seatDiv) {
                    seatDiv.closest('.seat-position').classList.add('player-assigned');
                }
            });
        }

        function updateOccupiedSeats() {
            document.getElementById('occupiedSeats').textContent = Object.keys(currentSeating).length;
        }

        function filterPlayers(searchTerm, containerId) {
            const players = document.querySelectorAll(`#${containerId} .player-item`);
            searchTerm = searchTerm.toLowerCase().trim();

            players.forEach(player => {
                const name = (player.dataset.playerName || '').toLowerCase();
                const nickname = (player.dataset.playerNickname || '').toLowerCase();

                const matches = name.includes(searchTerm) || nickname.includes(searchTerm);
                player.style.display = matches ? 'flex' : 'none';
            });
        }

        function resetSeating() {
            if (confirm('Вы уверены, что хотите сбросить всю рассадку?')) {
                currentSeating = {};

                document.querySelectorAll('.assigned-player').forEach(seat => {
                    seat.innerHTML = '<span class="placeholder">Свободно</span>';
                });

                document.querySelectorAll('.position-assigned').forEach(span => {
                    span.style.display = 'none';
                });

                document.querySelectorAll('.player-selected, .seat-selected').forEach(el => {
                    el.classList.remove('player-selected', 'seat-selected');
                });

                document.querySelectorAll('.seat-position').forEach(seat => {
                    seat.classList.remove('player-assigned');
                });

                selectedPlayer = null;
                selectedSeat = null;
                updateOccupiedSeats();
                updateRolesBtn();
            }
        }

        document.getElementById('saveSeatingBtn').addEventListener('click', function(e) {
            e.preventDefault();

            const form = this.closest('form');

            form.querySelectorAll('input[name^="seating["]').forEach(input => input.remove());

            Object.entries(currentSeating).forEach(([position, playerId]) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `seating[${position}]`;
                input.value = playerId;
                form.appendChild(input);
            });

            const tableNameInput = document.getElementById('table_name');
            let currentTableName = '';
            if (tableNameInput) {
                currentTableName = tableNameInput.value.trim();
            }

            const hiddenTableNameInput = document.createElement('input');
            hiddenTableNameInput.type = 'hidden';
            hiddenTableNameInput.name = 'table_name';
            hiddenTableNameInput.value = currentTableName;

            form.appendChild(hiddenTableNameInput);
            form.submit();
        });
    </script>
@endsection
