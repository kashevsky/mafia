<!DOCTYPE html>
<html>
<head>
    <style>body{font-family:Arial;padding:20px} .card{border:1px solid #ccc;padding:10px;margin:5px}</style>
</head>
<body>
    <h1>Раскладка мафии: {{ $game->name }}</h1>
    @foreach($players as $p)
        <div class="card">{{ $p['nickname'] }} — {{ $p['role'] }}</div>
    @endforeach
</body>
</html>
