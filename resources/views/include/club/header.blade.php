<!-- ===================== HTML ===================== -->

<header>
    <div class="header">
        <a class="logo" href="/" style="color: white; text-decoration: none;">
            <img src="new/logo.svg" alt="Logo">
        </a>
        <div class="header-actions">
            {{-- <button class="icon-btn" @click="showSearch = !showSearch">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </button> --}}
            {{-- <button class="icon-btn" @click="menuOpen = true">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
            </button> --}}
        </div>
    </div>

    <!-- User Bar -->
    <div class="user-bar">
        <a href="/account" style="text-decoration: none;">
            <div class="user-avatar">
                <template x-if="!user.avatar">
                    <img src="new/def-ava.svg" alt="Avatar" id="user-avatar">
                </template>
                <template x-if="user.avatar">
                    <!-- Добавляем ?v= и Date.now() -->
                    <img :src="user.avatar" alt="Avatar" id="user-avatar">
                </template>

            </div>
        </a>

        <div class="user-details">
            <div class="user-name" x-text="user.name" id="user-name"></div>
            <div class="user-meta">Ранг: Новенький</div>
            <div class="user-meta">Тотем: {{ $account->club->title }}</div>
        </div>

        <div class="user-stats">
            <div class="user-stat-row">
                <span class="user-stat-value" x-text="user.points"></span>
                <img src="new/rating.svg" alt="Coin" class="stat-icon">
            </div>
            <div class="user-stat-divider"></div>
            <div class="user-stat-row">
                <span class="user-stat-value" x-text="user.balance"></span>
                <img src="new/money.svg" alt="Coin" class="stat-icon">
            </div>
        </div>
    </div>
</header>
