@extends('layouts.club')

@section('content')
    <title>Профиль игрока {{ $player->nickname ?: $player->name }}</title>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @php
        $displayName = $player->nickname ?: $player->name;
        $avatar = $player->avatar ?: '/new/def-ava.svg';
        $achievements = $player->achievements ?? collect();
        $achievementsCount = is_countable($achievements) ? count($achievements) : 0;
        $gamesCount = $player->games_count ?? 0;
        $winsCount = $player->wins_count ?? 0;
        $firstGame = !empty($player->first_game) ? \Carbon\Carbon::parse($player->first_game) : null;
        $experienceDays = $firstGame ? (int) $firstGame->diffInDays(now()) : null;
        $telegramUrl = !empty($player->telegram_username)
            ? 'https://t.me/' . ltrim($player->telegram_username, '@')
            : null;
        $contactUrl = $telegramUrl ?: $player->messenger ?? null;
    @endphp

    <style>
        [x-cloak] {
            display: none !important;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #ee0000;
            --page-bg: #000000;
            --card-bg: #424a5a;
            --card-dark: #2b313c;
            --muted: #9ca3af;
            --line: #6a768f;
            --white: #ffffff;
            --telegram: #009eeb;
        }

        body {
            margin: 0;
            background: var(--page-bg);
            color: var(--white);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            line-height: 1.5;
        }

        button,
        a {
            font: inherit;
            -webkit-tap-highlight-color: transparent;
        }

        .player-page {
            width: 100%;
            max-width: 448px;
            min-height: 100vh;
            margin: 0 auto;
            padding-bottom: 90px;
            background: var(--page-bg);
            overflow: hidden;
        }

        .profile-wrap {
            position: relative;
        }

        .tabs-header {
            position: absolute;
            z-index: 20;
            left: 50%;
            bottom: -40px;
            width: 92%;
            display: flex;
            align-items: center;
            gap: 2px;
            margin: 1px auto;
            padding: 6px;
            background: var(--primary-color);
            border-radius: 8px;
            transform: translateX(-50%);
        }

        .tab-button {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 6px;
            color: #f8f8f8;
            background: transparent;
            border: 0;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 300;
            cursor: pointer;
            transition: background .2s ease, color .2s ease;
        }

        .tab-button.active {
            color: #ffffff;
            background: #000000;
        }

        .tab-button:hover {
            color: #ffffff;
            background: #1e293b;
        }

        .tab-icon {
            width: 20px;
            height: 20px;
        }

        .content {
            padding: 16px;
        }

        .back-button {
            width: fit-content;
            min-height: 40px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 30px;
            padding: 8px 14px 8px 11px;
            color: #ffffff;
            background: #2b313c;
            border: 1px solid #424a5a;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: background .2s ease, border-color .2s ease;
        }

        .back-button:hover {
            background: #424a5a;
            border-color: #ee0000;
        }

        .back-button svg {
            flex-shrink: 0;
        }

        .public-profile-header {
            padding: 22px 12px;
            text-align: center;
        }

        .avatar-wrap {
            position: relative;
            width: 110px;
            height: 110px;
            margin: 0 auto 14px;
            margin-top: 20px;
        }

        .avatar {
            width: 110px;
            height: 110px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: #334155;
            border: 3px solid #424a5a;
            border-radius: 50%;
            box-shadow: 0 0 0 2px #ee0000;
        }

        .avatar img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
        }





        .nickname {
            color: #ff0000;
            font-size: 26px;
            font-weight: 700;
            line-height: 1.15;
            overflow-wrap: anywhere;
        }

        .real-name {
            margin-top: 4px;
            color: #ffffff;
            font-size: 15px;
            font-weight: 400;
        }

        .bio {
            max-width: 350px;
            max-height: 92px;
            margin: 12px auto 0;
            padding: 0 10px;
            overflow-y: auto;
            color: var(--muted);
            font-size: 14px;
            font-weight: 300;
            line-height: 1.55;
            white-space: pre-line;
        }

        .profile-actions {
            display: flex;
            gap: 10px;
            margin-top: 18px;
        }

        .contact-button,
        .share-button {
            min-height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 400;
            cursor: pointer;
            transition: background .2s ease, border-color .2s ease;
        }

        .contact-button {
            flex: 1;
            padding: 8px 16px;
            color: #ffffff;
            background: #ee0000;
            border: 1px solid #ee0000;
            text-decoration: none;
        }

        .contact-button:hover {
            background: #c90000;
        }

        .contact-button.disabled {
            color: #9ca3af;
            background: #2b313c;
            border-color: #424a5a;
            cursor: default;
        }

        .share-button {
            width: 44px;
            flex-shrink: 0;
            padding: 0;
            color: #ffffff;
            background: #2b313c;
            border: 1px solid #424a5a;
        }

        .share-button:hover,
        .share-button.copied {
            color: #ffffff;
            background: #424a5a;
            border-color: #ee0000;
        }

        .stats-section {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .top-row {
            display: flex;
            gap: 16px;
        }

        .card-points,
        .card-games {
            position: relative;
            width: 50%;
            height: 100px;
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            overflow: hidden;
            padding-top: 20px;
            border-radius: 10px;
        }

        .card-points {
            background: #424a5a;
        }

        .card-games {
            background: #ee0000;
        }

        .card-points::after,
        .card-games::after {
            content: "";
            position: absolute;
            top: 50%;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            transform: translateY(-50%);
        }

        .card-points::after {
            right: -30px;
            background: #ee0000;
        }

        .card-games::after {
            left: -30px;
            background: #2b313c;
        }

        .stat-label {
            position: relative;
            z-index: 1;
            color: #ffffff;
            font-size: 16px;
            font-weight: 400;
            line-height: 18px;
        }

        .stat-value {
            position: relative;
            z-index: 1;
            margin-top: 2px;
            color: #ffffff;
            font-size: 48px;
            font-weight: 600;
            line-height: 1;
        }

        .card-points .stat-value {
            color: #ee0000;
        }

        .card-experience {
            min-height: 110px;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 16px;
            background: #424a5a;
            border-radius: 10px;
        }

        .experience-title-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
        }

        .experience-title {
            color: #ffffff;
            font-size: 20px;
            font-weight: 600;
            line-height: 18px;
        }

        .experience-data {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .experience-col {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .exp-col-label {
            color: #ffffff;
            font-size: 15px;
            font-weight: 400;
            line-height: 22px;
        }

        .exp-col-value {
            color: #ffffff;
            font-size: 16px;
            font-weight: 700;
            line-height: 22px;
        }

        .experience-divider {
            width: 1px;
            height: 32px;
            margin: 0 8px;
            background: #d9d9d9;
        }

        .public-card {
            padding: 16px;
            background: #2b313c;
            border-radius: 10px;
        }

        .public-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 14px;
        }

        .public-card-title {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #ffffff;
            font-size: 20px;
            font-weight: 600;
        }

        .public-card-count {
            min-width: 28px;
            padding: 3px 8px;
            color: #ffffff;
            background: #ee0000;
            border-radius: 99px;
            font-size: 12px;
            font-weight: 600;
            text-align: center;
        }

        .public-info-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            padding: 11px 0;
            border-bottom: 1px solid #424a5a;
        }

        .public-info-row:last-child {
            padding-bottom: 0;
            border-bottom: 0;
        }

        .public-info-label {
            color: #9ca3af;
            font-size: 14px;
        }

        .public-info-value {
            color: #ffffff;
            font-size: 14px;
            font-weight: 600;
            text-align: right;
        }

        .preview-medals {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }

        .preview-medal {
            min-width: 0;
            padding: 0;
            color: inherit;
            background: transparent;
            border: 0;
            cursor: pointer;
        }

        .preview-medal-image {
            width: 100%;
            display: block;
            aspect-ratio: 1;
            object-fit: contain;
        }

        .preview-medal-name {
            display: -webkit-box;
            margin-top: 5px;
            overflow: hidden;
            color: #e8e8e8;
            font-size: 11px;
            font-weight: 300;
            line-height: 1.25;
            text-align: center;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }

        .show-all-button {
            width: 100%;
            margin-top: 14px;
            padding: 9px 12px;
            color: #ffffff;
            background: transparent;
            border: 1px solid #ee0000;
            border-radius: 10px;
            font-size: 14px;
            cursor: pointer;
        }

        .show-all-button:hover {
            background: rgba(238, 0, 0, .12);
        }

        .medals-container {
            padding: 34px 0 20px;
            text-align: center;
        }

        .medals-title {
            color: #ffffff;
            font-size: 32px;
            font-weight: 800;
            text-align: center;
            width: 100%;
        }

        .medals-subtitle {
            margin: 8px 0 34px;
            color: #cccccc;
            font-size: 16px;
            text-align: center;
        }

        .medals-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 34px 16px;
            width: 100%;
            padding: 0 12px;
        }

        .medal-card {
            min-width: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
            padding: 0;
            color: inherit;
            background: transparent;
            border: 0;
            cursor: pointer;
        }

        .medal-wrapper {
            position: relative;
            width: 100%;
            aspect-ratio: 1;
        }

        .medal-image {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: contain;
        }

        .question-badge {
            position: absolute;
            z-index: 2;
            top: 3px;
            right: 3px;
            width: 25px;
            height: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #aaaaaa;
            background: #2a2a2a;
            border: 1.5px solid #555555;
            border-radius: 50%;
            font-size: 13px;
            font-weight: 600;
        }

        .medal-card:hover .question-badge {
            color: #ffffff;
            background: #3a3a3a;
            border-color: #888888;
        }

        .medal-label {
            display: -webkit-box;
            overflow: hidden;
            color: #e8e8e8;
            font-size: 13px;
            font-weight: 300;
            line-height: 1.2;
            text-align: center;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }

        .empty-medals {
            padding: 52px 20px;
            color: #9ca3af;
            background: #2b313c;
            border-radius: 10px;
            text-align: center;
        }

        .empty-medals svg {
            margin-bottom: 12px;
            color: #6a768f;
        }

        .empty-medals-title {
            color: #ffffff;
            font-size: 18px;
            font-weight: 600;
        }

        .empty-medals-text {
            margin-top: 5px;
            font-size: 13px;
        }

        .modal-overlay {
            position: fixed;
            z-index: 1000;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background: rgba(0, 0, 0, .76);
        }

        .medal-modal {
            position: relative;
            width: 100%;
            max-width: 380px;
            padding: 32px 28px 28px;
            background: #1a1a1a;
            border: 1px solid #333333;
            border-radius: 16px;
        }

        .modal-title {
            padding-right: 22px;
            color: #fe0000;
            font-size: 18px;
            font-weight: 700;
        }

        .modal-body {
            margin-top: 12px;
            color: #cccccc;
            font-size: 15px;
            line-height: 1.6;
            white-space: pre-line;
        }

        .modal-close {
            position: absolute;
            top: 13px;
            right: 15px;
            padding: 4px;
            color: #777777;
            background: transparent;
            border: 0;
            font-size: 24px;
            line-height: 1;
            cursor: pointer;
        }

        .modal-close:hover {
            color: #ffffff;
        }

        .toast {
            position: fixed;
            z-index: 1100;
            left: 50%;
            bottom: 84px;
            padding: 9px 14px;
            color: #ffffff;
            background: #424a5a;
            border-radius: 99px;
            font-size: 12px;
            white-space: nowrap;
            transform: translateX(-50%);
        }

        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                animation-duration: .01ms !important;
                transition-duration: .01ms !important;
            }
        }
    </style>

    <div class="player-page" x-data="publicPlayerProfile({
        profileUrl: @js(url()->current()),
        achievements: @js($achievements)
    })" @keydown.escape.window="closeMedal()">
        <div class="profile-wrap">
            @include('include.club.header')

            <div class="tabs-header">
                <button class="tab-button" :class="{ 'active': activeTab === 'profile' }" type="button"
                    @click="activeTab = 'profile'">
                    <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span>Профиль</span>
                </button>

                <button class="tab-button" :class="{ 'active': activeTab === 'medals' }" type="button"
                    @click="activeTab = 'medals'">
                    <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                    <span>Медальки</span>
                </button>
            </div>
        </div>

        <main class="content">
            <a class="back-button" href="#" id="backButton" style="display: none;"
                aria-label="Вернуться на предыдущую страницу">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    aria-hidden="true">
                    <path d="M15 18l-6-6 6-6" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <span>Назад</span>
            </a>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const backBtn = document.getElementById('backButton');


                    if (document.referrer && document.referrer.includes(window.location.hostname)) {

                        backBtn.style.display = 'inline-flex';


                        backBtn.addEventListener('click', function(e) {
                            e.preventDefault();
                            history.back();
                        });
                    }
                });
            </script>

            <div x-show="activeTab === 'profile'" x-transition.opacity>
                <section class="public-profile-header">
                    <div class="avatar-wrap">
                        <div class="avatar">
                            <img src="{{ $avatar }}" alt="Аватар игрока {{ $displayName }}"
                                onerror="this.src='/new/def-ava.svg'">
                        </div>

                    </div>

                    <h1 class="nickname" style="text-align: center; width: 100%;">{{ $displayName }}</h1>

                    @if (!empty($player->nickname) && !empty($player->name))
                        <div class="real-name">{{ $player->name }}</div>
                    @endif

                    <div class="bio">
                        {{ !empty($player->bio) ? $player->bio : 'Игрок пока ничего не рассказал о себе.' }}
                    </div>

                    {{-- <div class="profile-actions">
                        @if ($contactUrl)
                            <a class="contact-button" href="{{ $contactUrl }}" target="_blank" rel="noopener noreferrer">
                                @if ($telegramUrl)
                                    <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"
                                        aria-hidden="true">
                                        <path
                                            d="M21.6 3.2L18.5 19c-.2 1.1-.9 1.4-1.8.9l-4.7-3.5-2.3 2.2c-.3.3-.5.5-1 .5l.3-4.8 8.8-8c.4-.3-.1-.5-.6-.2L6.3 13 1.6 11.5c-1-.3-1-1 .2-1.5L20.2 2.9c.9-.3 1.6.2 1.4.3z" />
                                    </svg>
                                    Написать в Telegram
                                @else
                                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" aria-hidden="true">
                                        <path d="M21 15a4 4 0 01-4 4H8l-5 3V7a4 4 0 014-4h10a4 4 0 014 4v8z"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    Написать игроку
                                @endif
                            </a>
                        @else
                            <span class="contact-button disabled">Контакты не указаны</span>
                        @endif

                        <button class="share-button" :class="{ 'copied': copied }" type="button" @click="shareProfile()"
                            aria-label="Поделиться профилем">
                            <svg x-show="!copied" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" aria-hidden="true">
                                <circle cx="18" cy="5" r="3" stroke-width="2" />
                                <circle cx="6" cy="12" r="3" stroke-width="2" />
                                <circle cx="18" cy="19" r="3" stroke-width="2" />
                                <path d="M8.6 10.6l6.8-4.2M8.6 13.4l6.8 4.2" stroke-width="2" stroke-linecap="round" />
                            </svg>
                            <svg x-show="copied" x-cloak width="19" height="19" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" aria-hidden="true">
                                <path d="M5 12l4 4L19 6" stroke-width="2.4" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </button>
                    </div> --}}
                </section>

                <section class="stats-section">
                    <div class="top-row">
                        <div class="card-points">
                            <span class="stat-label">Очки</span>
                            <strong class="stat-value">{{ $player->rating ?? 0 }}</strong>
                        </div>

                        <div class="card-games">
                            <span class="stat-label">Игр</span>
                            <strong class="stat-value">{{ $gamesCount }}</strong>
                        </div>
                    </div>

                    <div class="card-experience">
                        <div class="experience-title-row">
                            <svg width="18" height="18" viewBox="0 0 20 18" fill="none" aria-hidden="true">
                                <path
                                    d="M2 4C2 2.895 2.895 2 4 2H16C17.105 2 18 2.895 18 4V15C18 16.105 17.105 17 16 17H4C2.895 17 2 16.105 2 15V4ZM6 1V4M14 1V4M2 7H18"
                                    stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span class="experience-title">Игровой стаж</span>
                        </div>

                        <div class="experience-data">
                            <div class="experience-col">
                                <span class="exp-col-label">Первая игра:</span>
                                <strong class="exp-col-value">
                                    {{ $firstGame ? $firstGame->format('d.m.Y') : '-' }}
                                </strong>
                            </div>

                            <div class="experience-divider"></div>

                            <div class="experience-col">
                                <span class="exp-col-label">Стаж:</span>
                                <strong class="exp-col-value">
                                    {{ $experienceDays !== null ? $experienceDays . ' дн.' : '-' }}
                                </strong>
                            </div>
                        </div>
                    </div>

                    <div class="public-card">
                        <div class="public-card-header">
                            <h2 class="public-card-title">
                                <svg width="19" height="19" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" aria-hidden="true">
                                    <path d="M8 21h8M12 17v4M7 4h10v4a5 5 0 01-10 0V4z" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M7 6H4v2a4 4 0 004 4M17 6h3v2a4 4 0 01-4 4" stroke-width="2"
                                        stroke-linecap="round" />
                                </svg>
                                Статистика
                            </h2>
                        </div>

                        {{-- <div class="public-info-row">
                            <span class="public-info-label">Победы</span>
                            <span class="public-info-value">{{ $winsCount }}</span>
                        </div>
                        <div class="public-info-row">
                            <span class="public-info-label">Процент побед</span>
                            <span class="public-info-value">
                                {{ $gamesCount > 0 ? round(($winsCount / $gamesCount) * 100) : 0 }}%
                            </span>
                        </div> --}}
                        <div class="public-info-row">
                            <span class="public-info-label">Медальки</span>
                            <span class="public-info-value">{{ $achievementsCount }}</span>
                        </div>
                    </div>

                    @if ($achievementsCount > 0)
                        <div class="public-card">
                            <div class="public-card-header">
                                <h2 class="public-card-title">Последние медальки</h2>
                                <span class="public-card-count">{{ $achievementsCount }}</span>
                            </div>

                            <div class="preview-medals">
                                <template x-for="(achievement, index) in achievements.slice(0, 3)"
                                    :key="achievement.id ?? index">
                                    <button class="preview-medal" type="button" @click="openMedal(achievement)">
                                        <img class="preview-medal-image" src="/ach.svg" alt="">
                                        <span class="preview-medal-name" x-text="medalTitle(achievement)"></span>
                                    </button>
                                </template>
                            </div>

                            <button class="show-all-button" type="button"
                                @click="activeTab = 'medals'; window.scrollTo({ top: 0, behavior: 'smooth' })">
                                Посмотреть все
                            </button>
                        </div>
                    @endif
                </section>
            </div>

            <div x-show="activeTab === 'medals'" x-cloak x-transition.opacity>
                <section class="medals-container">
                    <h1 class="medals-title">Медальки</h1>
                    <p class="medals-subtitle">Достижения игрока {{ $displayName }}</p>

                    <template x-if="achievements.length > 0">
                        <div class="medals-grid">
                            <template x-for="(achievement, index) in achievements" :key="achievement.id ?? index">
                                <button class="medal-card" type="button" @click="openMedal(achievement)">
                                    <span class="medal-wrapper">
                                        <img class="medal-image" src="/ach.svg" alt="">
                                        <span class="question-badge">?</span>
                                    </span>
                                    <span class="medal-label" x-text="medalTitle(achievement)"></span>
                                </button>
                            </template>
                        </div>
                    </template>

                    <template x-if="achievements.length === 0">
                        <div class="empty-medals">
                            <svg width="58" height="58" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                aria-hidden="true">
                                <path d="M8 21h8M12 17v4M7 4h10v4a5 5 0 01-10 0V4z" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M7 6H4v2a4 4 0 004 4M17 6h3v2a4 4 0 01-4 4" stroke-width="1.5"
                                    stroke-linecap="round" />
                            </svg>
                            <div class="empty-medals-title">Медалей пока нет</div>
                            <div class="empty-medals-text">Достижения игрока появятся здесь.</div>
                        </div>
                    </template>
                </section>
            </div>
        </main>

        <div class="modal-overlay" x-show="showMedalModal" x-cloak x-transition.opacity @click.self="closeMedal()"
            role="dialog" aria-modal="true" aria-labelledby="medal-modal-title">
            <div class="medal-modal" x-transition.scale>
                <button class="modal-close" type="button" @click="closeMedal()" aria-label="Закрыть">&times;</button>
                <h2 class="modal-title" id="medal-modal-title" x-text="selectedMedal.title"></h2>
                <div class="modal-body" x-text="selectedMedal.text"></div>
            </div>
        </div>

        <div class="toast" x-show="showToast" x-cloak x-transition.opacity x-text="toastText"></div>
    </div>

    <script>
        function publicPlayerProfile(config) {
            return {
                activeTab: 'profile',
                achievements: Array.isArray(config.achievements) ?
                    config.achievements :
                    Object.values(config.achievements || {}),
                profileUrl: config.profileUrl,
                copied: false,
                showToast: false,
                toastText: '',
                toastTimer: null,
                showMedalModal: false,
                selectedMedal: {
                    title: '',
                    text: ''
                },

                medalTitle(achievement) {
                    return achievement?.name || achievement?.title || achievement?.text || 'Достижение';
                },

                openMedal(achievement) {
                    this.selectedMedal = {
                        title: achievement?.name || achievement?.title || 'Достижение',
                        text: achievement?.text || achievement?.description || 'Описание достижения отсутствует.'
                    };
                    this.showMedalModal = true;
                    document.body.style.overflow = 'hidden';
                },

                closeMedal() {
                    this.showMedalModal = false;
                    document.body.style.overflow = '';
                },

                async shareProfile() {
                    if (navigator.share) {
                        try {
                            await navigator.share({
                                title: document.title,
                                url: this.profileUrl
                            });
                            return;
                        } catch (error) {
                            if (error.name === 'AbortError') {
                                return;
                            }
                        }
                    }

                    try {
                        await navigator.clipboard.writeText(this.profileUrl);
                        this.copied = true;
                        this.notify('Ссылка на профиль скопирована');
                        window.setTimeout(() => {
                            this.copied = false;
                        }, 1800);
                    } catch (error) {
                        this.notify('Не удалось скопировать ссылку');
                    }
                },

                notify(message) {
                    window.clearTimeout(this.toastTimer);
                    this.toastText = message;
                    this.showToast = true;
                    this.toastTimer = window.setTimeout(() => {
                        this.showToast = false;
                    }, 2200);
                }
            };
        }
    </script>

    @include('include.club.bottom-nav')
@endsection
