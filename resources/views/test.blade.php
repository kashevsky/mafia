<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль игрока - Мафия</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background-color: #0f0f1a; color: #e2e8f0; line-height: 1.5; }
        .container { max-width: 448px; margin: 0 auto; background: #1a1b26; min-height: 100vh; }

        /* Header */
        .header { background: #4f46e5; color: white; padding: 16px; display: flex; align-items: center; gap: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.3); }
        .back-btn { background: none; border: none; color: white; cursor: pointer; padding: 8px; display: flex; align-items: center; }
        .back-btn:hover { opacity: 0.8; }
        .header-title { font-size: 18px; font-weight: 600; }

        /* Profile Header */
        .profile-header { background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%); color: white; padding: 32px 16px 24px; text-align: center; position: relative; }
        .avatar-large { width: 120px; height: 120px; border-radius: 50%; background: #2d3748; margin: 0 auto 16px; display: flex; align-items: center; justify-content: center; overflow: hidden; border: 4px solid #667eea; box-shadow: 0 4px 12px rgba(0,0,0,0.4); }
        .avatar-large img { width: 100%; height: 100%; object-fit: cover; }
        .avatar-large svg { width: 60px; height: 60px; color: #667eea; }
        .player-name { font-size: 28px; font-weight: bold; margin-bottom: 8px; }
        .player-rank { display: inline-block; background: rgba(255,255,255,0.2); padding: 6px 16px; border-radius: 20px; font-size: 14px; font-weight: 500; margin-bottom: 8px; }
        .player-bio { font-size: 14px; opacity: 0.9; max-width: 300px; margin: 0 auto; line-height: 1.5; }
        .player-info { display: flex; justify-content: center; gap: 24px; margin-top: 16px; }
        .info-item { text-align: center; }
        .info-value { font-size: 24px; font-weight: bold; }
        .info-label { font-size: 12px; opacity: 0.7; margin-top: 4px; }

        /* Content */
        .content { padding: 16px; }

        /* Section */
        .section { margin-bottom: 24px; }
        .section-title { font-size: 18px; font-weight: 600; color: #e2e8f0; margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }
        .section-icon { width: 20px; height: 20px; color: #818cf8; }

        /* Stats Grid */
        .stats-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
        .stat-card { background: #2d3748; border: 1px solid #4a5568; border-radius: 12px; padding: 16px; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.3); }
        .stat-icon { width: 32px; height: 32px; margin: 0 auto 8px; }
        .stat-icon.red { color: #f56565; }
        .stat-icon.blue { color: #63b3ed; }
        .stat-icon.green { color: #48bb78; }
        .stat-icon.purple { color: #a78bfa; }
        .stat-value { font-size: 28px; font-weight: bold; color: #fff; }
        .stat-label { font-size: 12px; color: #94a3b8; margin-top: 4px; }

        /* Chart Card */
        .chart-card { background: #2d3748; border: 1px solid #4a5568; border-radius: 12px; padding: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.3); }
        .chart-container { position: relative; height: 250px; margin: 16px 0; }
        .total-games { text-align: center; padding-top: 16px; border-top: 1px solid #4a5568; font-size: 14px; color: #94a3b8; }
        .total-games strong { color: #e2e8f0; font-size: 18px; }

        /* Info Card */
        .info-card { background: #2d3748; border: 1px solid #4a5568; border-radius: 12px; padding: 16px; }
        .info-row { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px; }
        .info-row:last-child { margin-bottom: 0; }
        .info-row-label { color: #94a3b8; }
        .info-row-value { font-weight: 500; color: #e2e8f0; }

        /* Badges */
        .badges-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
        .badge-item { background: #2d3748; border: 1px solid #4a5568; border-radius: 12px; padding: 12px; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.3); }
        .badge-icon { width: 48px; height: 48px; background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); border-radius: 50%; margin: 0 auto 8px; display: flex; align-items: center; justify-content: center; font-size: 24px; }
        .badge-icon.locked { background: #4a5568; opacity: 0.5; }
        .badge-name { font-size: 11px; color: #94a3b8; font-weight: 500; }
        .badge-empty { color: #64748b; }

        /* Recent Games */
        .game-list {        .game-list { display: flex; flex-direction: column; gap: 12px; }
        .game-item { background: #2d3748; border: 1px solid #4a5568; border-radius: 12px; padding: 12px 16px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 1px 3px rgba(0,0,0,0.3); }
        .game-info { flex: 1; }
        .game-date { font-size: 12px; color: #94a3b8; margin-bottom: 4px; }
        .game-role { font-size: 14px; font-weight: 600; color: #e2e8f0; }
        .game-result { font-size: 12px; color: #94a3b8; margin-top: 2px; }
        .game-badge { padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .game-badge.win { background: #1a6543; color: #b2f5ea; }
        .game-badge.lose { background: #4a1a1a; color: #fecaca; }

        /* Contact Button */
        .contact-btn { display: flex; align-items: center; justify-content: center; gap: 8px; background: #63b3ed; color: white; padding: 14px; border-radius: 12px; border: none; cursor: pointer; font-size: 15px; font-weight: 600; width: 100%; transition: background 0.2s; text-decoration: none; }
        .contact-btn:hover { background: #3182ce; }
        .contact-btn svg { width: 20px; height: 20px; }

        /* Progress Bar */
        .progress-bar { background: #4a5568; height: 8px; border-radius: 4px; overflow: hidden; margin-top: 8px; }
        .progress-fill { height: 100%; background: linear-gradient(90deg, #4f46e5 0%, #3730a3 100%); transition: width 0.3s; }
    </style>
</head>
<body>
    <div class="container" x-data="publicProfile()">
        <!-- Header -->
        <div class="header">
            <button class="back-btn" onclick="history.back()">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <div class="header-title">Профиль игрока</div>
        </div>

        <!-- Profile Header -->
        <div class="profile-header">
            <div class="avatar-large">
                <template x-if="player.avatar">
                    <img :src="player.avatar" alt="Avatar">
                </template>
                <template x-if="!player.avatar">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
                </template>
            </div>
            <div class="player-name" x-text="player.nickname"></div>
            <div class="player-rank">⭐ Опытный игрок</div>
            <p class="player-bio" x-text="player.bio"></p>
            <div class="player-info">
                <div class="info-item">
                    <div class="info-value" x-text="player.totalPoints"></div>
                    <div class="info-label">Баллов</div>
                </div>
                <div class="info-item">
                    <div class="info-value" x-text="player.totalGames"></div>
                    <div class="info-label">Игр</div>
                </div>
                <div class="info-item">
                    <div class="info-value" x-text="player.winRate + '%'"></div>
                    <div class="info-label">Побед</div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Статистика -->
            <div class="section">
                <h3 class="section-title">
                    <svg class="section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Статистика
                </h3>
                <div class="stats-grid">
                    <div class="stat-card">
                        <svg class="stat-icon red" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
                </svg>
                <div class="stat-value" x-text="player.rating"></div>
                <div class="stat-label">Всего баллов</div>
            </div>
            <div class="stat-card">
                <svg class="stat-icon blue" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
                </svg>
                <div class="stat-value" x-text="player.balance"></div>
                <div class="stat-label">За месяц</div>
            </div>
        </div>
    </div>

    <!-- Диаграмма игр по ролям -->
    <div class="section">
        <h3 class="section-title">
            <svg class="section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
            </svg>
            Распределение игр по ролям
        </h3>
        <div class="chart-card">
            <div class="chart-container">
                <canvas id="rolesChart"></canvas>
            </div>
            <div class="total-games">
                Всего игр: <strong x-text="player.totalGames"></strong>
            </div>
        </div>
    </div>

    <!-- Игровой стаж -->
    <div class="section">
        <h3 class="section-title">
            <svg class="section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            Игровой опыт
        </h3>
        <div class="info-card">
            <div class="info-row">
                <span class="info-row-label">Первая игра:</span>
                <span class="info-row-value" x-text="formatDate(player.firstGameDate)"></span>
            </div>
            <div class="info-row">
                <span class="info-row-label">Стаж:</span>
                <span class="info-row-value" x-text="getExperience()"></span>
            </div>
            <div class="info-row">
                <span class="info-row-label">Уровень:</span>
                <span class="info-row-value" x-text="player.level + ' (' + player.levelProgress + '/100)'"></span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" :style="`width: ${player.levelProgress}%`"></div>
            </div>
        </div>
    </div>

    <!-- Достижения -->
    <div class="section">
        <h3 class="section-title">
            <svg class="section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
            </svg>
            Достижения <span style="color: #94a3b8; font-weight: normal; font-size: 14px;">(5/12)</span>
        </h3>
        <div class="badges-grid">
            <div class="badge-item">
                <div class="badge-icon">🏆</div>
                <div class="badge-name">Первая победа</div>
            </div>
            <div class="badge-item">
                <div class="badge-icon">🎯</div>
                <div class="badge-name">Меткий</div>
            </div>
            <div class="badge-item">
                <div class="badge-icon">🔥</div>
                <div class="badge-name">На волне</div>
            </div>
            <div class="badge-item">
                <div class="badge-icon">⭐</div>
                <div class="badge-name">50 игр</div>
            </div>
            <div class="badge-item">
                <div class="badge-icon">🎭</div>
                <div class="badge-name">Актёр</div>
            </div>
            <div class="badge-item">
                <div class="badge-icon locked badge-empty">🔒</div>
                <div class="badge-name badge-empty">Заблокировано</div>
            </div>
        </div>
    </div>

    <!-- Последние игры -->
    <div class="section">
        <h3 class="section-title">
            <svg class="section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Последние игры
        </h3>
        <div class="game-list">
            <template x-for="game in recentGames" :key="game.id">
                <div class="game-item">
                    <div class="game-info">
                <div class="game-date" x-text="game.date"></div>
                <div class="game-role" x-text="game.role"></div>
                <div class="game-result" x-text="'Баллов: +' + game.points"></div>
            </div>
            <div class="game-badge" :class="game.win ? 'win' : 'lose'" x-text="game.win ? '✓ Победа' : '✗ Поражение'"></div>
        </div>
    </template>
</div>
</div>

<!-- Контакт -->
<div class="section">
    <a :href="player.messenger" target="_blank" rel="noopener noreferrer" class="contact-btn">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
        </svg>
        Написать сообщение
    </a>
</div>
</div>
</div>

<script>
function publicProfile() {
    return {
        player: {
            avatar: '',
            nickname: 'Игрок123',
            bio: 'Люблю играть за мафию и разгадывать других игроков',
            totalPoints: 1250,
            monthPoints: 180,
            averageScore: 8.5,
            winRate: 65,
            firstGameDate: '2023-01-15',
            gamesAsMafia: 45,
            gamesAsCivilian: 78,
            gamesAsWerewolf: 12,
            totalGames: 135,
            level: 15,
            levelProgress: 73,
            messenger: 'https://t.me/player123'
        },
        recentGames: [
            { id: 1, date: '30 марта 2026', role: 'Мафия', points: 12, win: true },
            { id: 2, date: '28 марта 2026', role: 'Мирный житель', points: 8, win: false },
            { id: 3, date: '25 марта 2026', role: 'Шериф', points: 15, win: true },
            { id: 4, date: '22 марта 2026', role: 'Мафия', points: 5, win: false },
            { id: 5, date: '20 марта 2026', role: 'Мирный житель', points: 10, win: true }
        ],

        formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('ru-RU');
        },

        getExperience() {
            const firstGame = new Date(this.player.firstGameDate);
            const now = new Date();
            const diffYears = now.getFullYear() - firstGame.getFullYear();
            const diffMonths = now.getMonth() - firstGame.getMonth();
            let totalMonths = diffYears * 12 + diffMonths;

            if (now.getDate() < firstGame.getDate()) {
                totalMonths--;
            }

            const years = Math.floor(totalMonths / 12);
            const months = totalMonths % 12;

            if (years > 0) {
                return `${years} год${years > 1 ? 'а' : ''} ${months} мес.`;
            } else {
                return `${months} месяцев`;
            }
        }
    };
}

// Инициализация диаграммы после загрузки DOM
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('rolesChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Мафия', 'Мирные жители', 'Оборотень'],
            datasets: [{
                data: [
                    this.player.gamesAsMafia,
                    this.player.gamesAsCivilian,
                    this.player.gamesAsWerewolf
                ],
                backgroundColor: [
                    '#f56565',
                    '#48bb78',
            '#a78bfa'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
            labels: {
                color: '#e2e8f0'
            }
                }
            }
        }
    });
});
</script>
</body>
</html>

