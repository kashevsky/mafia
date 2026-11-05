<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Результаты мафии</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Roboto+Mono:wght@500&display=swap');

        body {
            margin: 0;
            padding: 0;
            background-color: #1a1a1d;
            font-family: 'Montserrat', sans-serif;
            color: #fff;
            width: 1080px;
            height: 1920px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        header {
            width: 100%;
            padding: 40px 0;
            text-align: center;
            border-bottom: 1px solid #333;
            margin-bottom: 30px;
        }

        .logo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid #00ff88;
            object-fit: cover;
            margin-bottom: 15px;
        }

        h1 {
            font-size: 36px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin: 0;
        }

        .meta {
            font-size: 14px;
            color: #aaa;
            margin-top: 10px;
        }

        .cards-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            width: 100%;
            max-width: 1000px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }

        .card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 15px;
            width: 300px;
            text-align: center;
            position: relative;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }

        .card.winner {
            border-color: #ffd700;
            box-shadow: 0 0 20px rgba(255, 215, 0, 0.5);
        }

        .card-img-wrapper {
            width: 100%;
            height: 200px;
            overflow: hidden;
            border-radius: 15px;
            margin-bottom: 15px;
            position: relative;
        }

        .card-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .card.winner .card-img-wrapper {
            border: 3px solid #ffd700;
        }

        .card-name {
            font-size: 20px;
            font-weight: 700;
            margin: 0 0 5px 0;
        }

        .card-role {
            font-size: 14px;
            color: #aaa;
            margin: 0 0 15px 0;
            text-transform: uppercase;
        }

        .score-badge {
            background: #2a2a2e;
            padding: 8px 16px;
            border-radius: 50px;
            font-family: 'Roboto Mono', monospace;
            font-weight: bold;
            color: #00ff88;
            display: inline-block;
            box-shadow: 0 0 10px rgba(0, 255, 136, 0.4);
        }

        .illustration-area {
            width: 100%;
            height: 400px;
            background: linear-gradient(to top, #1a1a1d, #333);
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .illustration-placeholder {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(0.8);
        }

        .illustration-text {
            position: absolute;
            bottom: 20px;
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #fff;
            text-shadow: 0 2px 4px rgba(0,0,0,0.8);
        }
    </style>
</head>
<body>
    <header>
        <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI4MCIgaGVpZ2h0PSI4MCIgZmlsbD0ibm9uZSI+PGNpcmNsZSBjeD0iNDAiIGN5PSI0MCIgcj0iNDAiIGZpbGw9IiMwMDAiLz48dGV4dCB4PSI0MCIgeT0iNDgiIGZvbnQtZmFtaWx5PSJTYW5zLVNlcmlmIiBmb250LXNpemU9IjE0IiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmaWxsPSIjZmZmIi50ZXh0LWFuY2hvcj0ibWlkZGxlIiBkb21pbmFudC1iYXNlbGluZT0iY2VudHJhbCI+TUFGSUE8L3RleHQ+PC9zdmc+" class="logo">
        <h1>Игроки вечера</h1>
        <div class="meta">Кутуза | {{ date('d.m.Y') }} ({{ now()->format('H:i:s') }})</div>
    </header>

    <main class="cards-container">
        @foreach ($sorted as $index => $player)

            @php $isWinner = $index === 0; @endphp
            <div class="card {{ $isWinner ? 'winner' : '' }}">
                <div class="card-img-wrapper">
                    <!-- Важно: используем asset() для абсолютного URL -->
                    <img src="{{ asset($player['avatar']) }}" alt="{{ $player['name'] }}" class="card-img">
                </div>
                <h3 class="card-name">{{ $player['name'] }}</h3>
                <div class="card-role">{{ $player['role'] }}</div>
                <div class="score-badge">+{{ $player['rating'] }} балла</div>
            </div>
        @endforeach
    </main>

    <div class="illustration-area">

        <img src="{{ $illustrationUrl }}" class="illustration-placeholder">
        <div class="illustration-text">Спасибо за игру!</div>
    </div>
</body>
</html>
