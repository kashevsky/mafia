{{-- resources/views/club/game.blade.php --}}
@extends('layouts.club')

@section('content')
    @php
        $totalParticipants = $game->applications->count();
        $currentApplication = $game->applications->firstWhere('account_id', $account->id);
        $currentBookingDops = (int) ($currentApplication->dops ?? 0);
        $occupiedSeats = $game->applications->count() + $game->applications->sum('dops');
        $ratingApplications = $game->applications
            ->sortByDesc(fn($application) => $application->account->rating ?? 0)
            ->values();
    @endphp

    <div class="container" x-data="eventPage()">
        <style>
            [x-cloak] {
                display: none !important;
            }

            body {
                background: #000000;
            }

            .logo {
                display: flex;
                align-items: center;
                gap: 8px;
                font-size: 24px;
                font-weight: bold;
            }

            .header-actions {
                display: flex;
                gap: 16px;
                align-items: center;
            }

            .icon-btn {
                display: flex;
                align-items: center;
                padding: 8px;
                color: white;
                cursor: pointer;
                background: none;
                border: none;
            }

            .icon-btn:hover {
                opacity: 0.8;
            }

            .event-header {
                position: relative;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                height: 260px;
                padding: 20px 16px;
                text-align: center;
                background-image: url('../..{{ $game->cover ?? $game->avatar }}');
                background-position: center;
                background-size: cover;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
            }

            .event-header::before {
                position: absolute;
                inset: 0;
                z-index: 1;
                content: '';
            }

            .event-header>* {
                position: relative;
                z-index: 2;
            }

            .event-title {
                z-index: 1;
                margin-bottom: 18px;
                color: #f9fafb;
                font-size: 32px;
                font-weight: bold;
                line-height: 1.2;
                text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
            }

            .event-date {
                margin-bottom: 12px;
                color: #e3e3e3;
                font-size: 14px;
            }

            .event-location {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 6px;
                color: #e3e3e3;
                font-size: 14px;
            }

            .photo-gallery {
                padding: 16px;
            }

            .gallery-title {
                margin-bottom: 12px;
                color: #f9fafb;
                font-size: 18px;
                font-weight: 600;
            }

            .photo-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 8px;
            }

            .photo-item {
                position: relative;
                height: 150px;
                overflow: hidden;
                border: 1px solid rgba(255, 255, 255, 0.1);
                border-radius: 12px;
            }

            .photo-item img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.3s;
            }

            .photo-item:hover img {
                transform: scale(1.05);
            }

            .booking-info {
                padding: 16px;
                border-top: 1px solid rgba(255, 255, 255, 0.1);
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);
            }

            .seats-info {
                display: flex;
                justify-content: space-between;
                margin-bottom: 16px;
            }

            .seat-status {
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .seat-count {
                color: #f43f5e;
                font-size: 24px;
                font-weight: bold;
            }

            .booking-btn {
                width: 100%;
                padding: 14px;
                color: white;
                font-size: 16px;
                font-weight: 600;
                cursor: pointer;
                background: red;
                border: none;
                border-radius: 8px;
                transition: all 0.2s;
            }

            .booking-btn:hover {
                opacity: 0.9;
                transform: translateY(-2px);
            }

            .player-avatar-sm {
                position: relative;
            }

            .player-avatar-sm img {
                width: 80px;
                height: 80px;
                object-fit: cover;
                border-radius: 50%;
            }

            .participant-dops-badge {
                position: absolute;
                top: -9px;
                right: -41px;
                z-index: 2;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 24px;
                height: 24px;
                box-sizing: border-box;
                color: #22c55e;
                font-size: 10px;
                font-weight: 400;
                line-height: 1;
                background: #2B313C;
                border: 1px solid #22c55e;
                border-radius: 50%;
            }

            .custom-order {
                padding: 12px;
                margin-top: 8px;
                color: #fbbf24;
                font-size: 14px;
                font-weight: 500;
                text-align: center;
                background: rgba(244, 63, 94, 0.15);
                border: 1px solid rgba(244, 63, 94, 0.3);
                border-radius: 8px;
            }

            .info-section {
                padding: 16px;
            }

            .info-title {
                margin-bottom: 12px;
                color: #f9fafb;
                font-size: 18px;
                font-weight: 600;
            }

            .info-text {
                margin-bottom: 16px;
                color: #cbd5e1;
                line-height: 1.6;
            }

            .info-text ul {
                margin-top: 8px;
                margin-left: 20px;
            }

            .info-text li {
                margin-bottom: 4px;
            }

            .host-info {
                padding: 16px;
                margin-bottom: 16px;
                border: 1px solid rgba(255, 255, 255, 0.1);
                border-radius: 12px;
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);
            }

            .host-header {
                display: flex;
                align-items: center;
                gap: 12px;
                margin-bottom: 12px;
            }

            .host-avatar {
                width: 60px;
                height: 60px;
                overflow: hidden;
                border: 2px solid #f43f5e;
                border-radius: 50%;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            }

            .host-avatar img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .host-name {
                color: #f9fafb;
                font-size: 16px;
                font-weight: 600;
            }

            .host-experience {
                color: #9ca3af;
                font-size: 14px;
            }

            .map-section {
                padding: 16px;
            }

            .map-title {
                margin-bottom: 12px;
                color: #f9fafb;
                font-size: 18px;
                font-weight: 600;
            }

            .map-container {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 100%;
                height: 200px;
                color: #9ca3af;
                border: 1px solid rgba(255, 255, 255, 0.1);
                border-radius: 12px;
                box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.2);
            }

            .map-container svg {
                margin-bottom: 8px;
            }

            .menu-overlay {
                position: fixed;
                inset: 0;
                z-index: 998;
            }

            .menu-sidebar {
                position: fixed;
                top: 0;
                right: -100%;
                z-index: 999;
                width: 280px;
                height: 100vh;
                padding: 20px;
                box-shadow: -2px 0 10px rgba(0, 0, 0, 0.3);
                transition: right 0.3s ease;
            }

            .menu-sidebar.open {
                right: 0;
            }

            .menu-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding-bottom: 12px;
                margin-bottom: 20px;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }

            .menu-title {
                color: #f9fafb;
                font-size: 18px;
                font-weight: 600;
            }

            .menu-close {
                padding: 8px;
                color: #9ca3af;
                cursor: pointer;
                background: none;
                border: none;
            }

            .menu-close:hover {
                color: white;
            }

            .menu-items {
                display: flex;
                flex-direction: column;
                gap: 16px;
            }

            .menu-item {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 12px 16px;
                color: #e2e8f0;
                text-decoration: none;
                border-radius: 8px;
                transition: all 0.2s;
            }

            .menu-item:hover {
                color: white;
                background: rgba(244, 63, 94, 0.2);
                transform: translateX(4px);
            }

            .menu-item svg {
                width: 20px;
                height: 20px;
                color: red;
            }

            @media (max-width: 480px) {
                .container {
                    max-width: 100%;
                    padding: 0;
                }
            }

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

            .slide-in {
                animation: fadeIn 0.5s ease forwards;
            }

            .event-card {
                width: 100%;
                max-width: 820px;
                overflow: hidden;
                border-radius: 16px;
            }

            .event-card__content {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 6px;
                padding: 82px 24px;
                text-align: center;
            }

            .event-card__label {
                color: white;
                font-size: clamp(14px, 2.5vw, 18px);
                font-weight: 400;
                letter-spacing: 0.02em;
            }

            .event-card__datetime {
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                justify-content: center;
                gap: 0 12px;
                line-height: 1;
                text-align: center;
            }

            .event-card__date {
                color: red;
                font-size: 40px;
                font-weight: 800;
                letter-spacing: -1px;
            }

            .event-card__time {
                color: red;
                font-size: clamp(36px, 8vw, 72px);
                font-weight: 900;
                letter-spacing: -1px;
            }

            .event-card__location {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                margin-top: 8px;
                color: white;
                font-size: clamp(14px, 2.5vw, 20px);
                font-weight: 400;
                text-align: center;
            }

            .event-card__location svg {
                flex-shrink: 0;
                width: clamp(16px, 2.5vw, 20px);
                height: clamp(16px, 2.5vw, 20px);
                opacity: 0.85;
            }

            .player-name-sm {
                color: red;
                text-transform: uppercase;
            }

            .player-club-sm {
                color: white;
                font-size: 12px;
                font-weight: 300;
            }

            .participants-section {
                padding: 16px 0 16px 16px;
            }

            .participants-title {
                padding-right: 16px;
                margin-bottom: 12px;
                color: #f9fafb;
                font-size: 18px;
                font-weight: 600;
            }

            .participants-slider {
                display: grid;
                grid-auto-flow: column;
                grid-auto-columns: calc((100% - 10px) / 2);
                gap: 10px;
                padding-right: 16px;
                padding-bottom: 8px;
                overflow-x: auto;
                scroll-snap-type: x mandatory;
                -webkit-overflow-scrolling: touch;
            }

            .participants-slider::-webkit-scrollbar {
                display: none;
            }

            .participant-card {
                position: relative;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 8px;
                padding: 14px 12px;
                text-align: center;
                background: #2B313C;
                border-radius: 12px;
                scroll-snap-align: start;
            }

            .participants-rating-btn {
                display: flex;
                align-items: center;
                justify-content: center;
                width: calc(100% - 16px);
                padding: 12px 16px;
                margin-top: 12px;
                color: #f9fafb;
                font-size: 16px;
                font-weight: 600;
                cursor: pointer;
                background: transparent;
                border: 1px solid #22c55e;
                border-radius: 8px;
                transition: all 0.2s;
            }

            .participants-rating-btn:hover {
                background: rgba(34, 197, 94, 0.12);
            }

            .signup-block {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 20px;
                width: 100%;
                max-width: 448px;
                padding: 28px 20px 24px;
            }

            .seats-row {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 100%;
            }

            .seat-item {
                display: flex;
                flex: 1;
                flex-direction: column;
                align-items: center;
                gap: 6px;
            }

            .seat-number {
                font-size: 52px;
                font-weight: 800;
                line-height: 1;
            }

            .seat-number.free,
            .seat-label.free {
                color: #22c55e;
            }

            .seat-number.taken {
                color: red;
            }

            .seat-label {
                color: #9ca3af;
                font-size: 15px;
                font-weight: 400;
            }

            .seat-label.taken {
                color: #ef4444;
            }

            .seats-divider {
                flex-shrink: 0;
                width: 1px;
                height: 64px;
                margin: 0 16px;
                background: #333;
            }

            .price-line {
                color: #f1f5f9;
                font-size: 20px;
                font-weight: 700;
                text-align: center;
            }

            .price-value {
                color: #f1f5f9;
            }

            .btn-signup {
                width: 100%;
                padding: 18px;
                color: #fff;
                font-size: 18px;
                font-weight: 700;
                letter-spacing: 0.2px;
                cursor: pointer;
                background: red;
                border: none;
                border-radius: 14px;
                transition: background 0.18s;
            }

            .btn-signup:hover {
                background: #c91f1f;
            }

            .about-section-main {
                padding: 16px;
                margin: 20px;
                background: rgba(31, 41, 55, 0.7);
            }

            .info-section ul {
                padding-left: 20px;
            }

            .modal {
                position: fixed;
                inset: 0;
                z-index: 1000;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .modal-overlay {
                position: absolute;
                inset: 0;
                background: rgba(0, 0, 0, 0.65);
            }

            .modal-content {
                position: relative;
                z-index: 1001;
                width: 90%;
                max-width: 400px;
                padding: 24px;
                background: #1e293b;
                border-radius: 16px;
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.4);
                animation: modalFadeIn 0.3s ease;
            }

            @keyframes modalFadeIn {
                from {
                    opacity: 0;
                    transform: scale(0.9);
                }

                to {
                    opacity: 1;
                    transform: scale(1);
                }
            }

            .modal-title {
                margin-bottom: 12px;
                color: #f9fafb;
                font-size: 20px;
                font-weight: 600;
                text-align: center;
            }

            .modal-text {
                margin-bottom: 20px;
                color: #cbd5e1;
                text-align: center;
            }

            .quantity-selector {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 8px;
                margin-bottom: 24px;
            }

            .quantity-display {
                min-width: 40px;
                padding: 8px 12px;
                color: #fbbf24;
                font-size: 18px;
                font-weight: bold;
                text-align: center;
                background: rgba(244, 63, 94, 0.1);
                border-radius: 6px;
            }

            .quantity-btn {
                padding: 8px 16px;
                color: #e2e8f0;
                cursor: pointer;
                background: rgba(31, 41, 55, 0.8);
                border: 1px solid rgba(255, 255, 255, 0.1);
                border-radius: 6px;
                transition: all 0.2s;
            }

            .quantity-btn:hover:not(:disabled) {
                color: white;
                background: rgba(244, 63, 94, 0.2);
                transform: translateY(-1px);
            }

            .quantity-btn:disabled,
            .modal-btn:disabled {
                cursor: not-allowed;
                opacity: 0.45;
            }

            .modal-actions {
                display: flex;
                gap: 12px;
            }

            .modal-btn {
                flex: 1;
                padding: 12px;
                font-weight: 600;
                cursor: pointer;
                border: none;
                border-radius: 8px;
                transition: all 0.2s;
            }

            .modal-btn-primary {
                color: white;
                background: red;
            }

            .modal-btn-primary:hover:not(:disabled) {
                opacity: 0.9;
                transform: translateY(-2px);
            }

            .modal-btn-secondary {
                color: #e2e8f0;
                background: rgba(255, 255, 255, 0.1);
            }

            .modal-btn-secondary:hover {
                background: rgba(255, 255, 255, 0.2);
            }

            .participant-modal-avatar {
                width: 96px;
                height: 96px;
                margin: 0 auto 16px;
                overflow: hidden;
                background: #1e293b;
                border: 3px solid #f43f5e;
                border-radius: 50%;
                box-shadow: 0 4px 16px rgba(244, 63, 94, 0.3);
            }

            .participant-modal-avatar img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .participant-modal-rating {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 5px;
                margin-bottom: 8px;
                color: #fbbf24;
                font-size: 18px;
                font-weight: 700;
            }

            .participant-modal-rating svg {
                width: 18px;
                height: 18px;
                fill: #fbbf24;
            }

            .participant-modal-name {
                margin-bottom: 12px;
                color: #f9fafb;
                font-size: 20px;
                font-weight: 700;
                text-align: center;
            }

            .participant-modal-bio {
                min-height: 20px;
                margin-bottom: 20px;
                color: #cbd5e1;
                font-size: 14px;
                line-height: 1.6;
                text-align: center;
            }

            .telegram-btn {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                width: 100%;
                padding: 13px;
                color: white;
                font-size: 15px;
                font-weight: 700;
                letter-spacing: 0.5px;
                text-decoration: none;
                cursor: pointer;
                background: linear-gradient(135deg, #229ED9 0%, #1a7cb0 100%);
                border: none;
                border-radius: 10px;
                transition: all 0.2s;
            }

            .telegram-btn:hover {
                opacity: 0.9;
                box-shadow: 0 4px 12px rgba(34, 158, 217, 0.4);
                transform: translateY(-2px);
            }

            .telegram-btn svg {
                flex-shrink: 0;
                width: 22px;
                height: 22px;
            }

            .rating-list {
                display: flex;
                flex-direction: column;
                gap: 10px;
                max-height: 60vh;
                margin-top: 16px;
                overflow-y: auto;
            }

            .rating-list-item {
                display: grid;
                grid-template-columns: 28px 44px minmax(0, 1fr) auto;
                gap: 10px;
                align-items: center;
                padding: 10px 20px 10px 0;
                background: rgba(255, 255, 255, 0.06);
                border-radius: 10px;
            }

            .rating-list-position {
                color: #22c55e;
                font-size: 16px;
                font-weight: 700;
                text-align: center;
            }

            .rating-list-avatar {
                width: 44px;
                height: 44px;
                object-fit: cover;
                border-radius: 50%;
            }

            .rating-list-name {
                overflow: hidden;
                color: #f9fafb;
                font-size: 14px;
                font-weight: 600;
                text-overflow: ellipsis;
                white-space: nowrap;
            }
            .rating-list-name span {
                color: #22c55e;
                padding-left: 5px;
                font-size: 10px;
                position: relative;
                top: -1px;
                font-weight: 400;
            }

            .rating-list-value {
                color: #ff002b;
                font-size: 14px;
                font-weight: 700;
                white-space: nowrap;
            }
        </style>

        @include('include.club.header')

        <div class="event-card"
            style="
                background:
                    linear-gradient(
                        to top,
                        rgba(0, 0, 0, 0.92) 0%,
                        rgba(0, 0, 0, 0.55) 45%,
                        rgba(0, 0, 0, 0.15) 100%
                    ),
                    url('{{ $game->cover ?? $game->avatar }}') center/cover no-repeat;
            ">
            <div class="event-card__content">
                <div>
                    <p class="event-card__label">{{ $game->title }}</p>
                    <div class="event-card__datetime">
                        <span class="event-card__date">
                            {{ \Carbon\Carbon::parse($game->date)->format('d.m.Y') }},
                        </span>
                        <span class="event-card__time">{{ $game->time }}</span>
                    </div>
                    <p class="event-card__location">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#e0e0e0" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z" />
                            <circle cx="12" cy="10" r="3" />
                        </svg>
                        {{ $game->address }}
                    </p>
                </div>
            </div>
        </div>

        <div class="participants-section">
            <h2 class="participants-title">
                Участники
                <span style="font-size: 14px; color: #9ca3af; font-weight: 400;">
                    ({{ $totalParticipants }})
                </span>
            </h2>

            <div class="participants-slider">
                @foreach ($game->applications as $application)
                    <div class="participant-card" x-on:click="window.location.href = '/account/show/{{ $application->account->id }}'">


                        <div class="player-avatar-sm">
                            <img src="{{ $application->account->avatar ?? '/new/def-ava.svg' }}"
                                alt="{{ $application->account->nickname ?? $application->account->name }}">

                            @if ($application->dops > 0)
                                <span class="participant-dops-badge">
                                    +{{ $application->dops }}
                                </span>
                            @endif
                        </div>

                        <div class="player-name-sm">
                            {{ $application->account->nickname ?? $application->account->name }}
                        </div>

                        <div class="player-club-sm">
                            {{ $application->account->club->title ?? '' }}
                        </div>

                        <div class="player-rating-sm">
                            <img src="/new/rating-sm.svg" alt="Рейтинг">
                            <span>{{ $application->account->rating }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="button" class="participants-rating-btn" @click="openRatingModal()">
                Рейтинг участников
            </button>
        </div>

        <div class="signup-block">
            <div class="seats-row">
                <div class="seat-item">
                    <span class="seat-number free">{{ $game->total_space ?? '-' }}</span>
                    <span class="seat-label free">Всего мест</span>
                </div>

                <div class="seats-divider"></div>

                <div class="seat-item">
                    <span class="seat-number taken" x-text="occupiedSeats"></span>
                    <span class="seat-label taken">Занятых мест</span>
                </div>
            </div>

            <div class="price-line">
                Стоимость: <span class="price-value">{{ $game->price }} ₽</span>
            </div>

            <button class="btn-signup" :style="isBooked ? 'background: #374151;' : ''" @click="openModal()">
                <span x-text="isBooked ? 'Вы записаны (Управление бронью)' : 'Записаться на игру'"></span>
            </button>
        </div>

        @isset($game->description)
            <div class="about-section-main">
                <span class="corner-tr"></span>
                <span class="corner-bl"></span>
                <div class="about-text-main_">
                    <div class="info-section">
                        {!! $game->description !!}
                    </div>
                </div>
            </div>
        @endisset

        <template x-if="menuOpen">
            <div>
                <div class="menu-overlay" @click="menuOpen = false"></div>
                <div class="menu-sidebar" :class="{ 'open': menuOpen }">
                    <div class="menu-header">
                        <div class="menu-title">Меню</div>
                        <button class="menu-close" @click="menuOpen = false">
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="menu-items">
                        <a href="#" class="menu-item">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                </path>
                            </svg>
                            <span>Мой профиль</span>
                        </a>

                        <a href="#" class="menu-item">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 00-2 2v0m2-2a2 2 0 002 2v0m8-2v12m0 0H9">
                                </path>
                            </svg>
                            <span>Афиша событий</span>
                        </a>

                        <a href="#" class="menu-item">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-20h-5M7 20h-5v-20h5M12 20v-20"></path>
                            </svg>
                            <span>Правила игры</span>
                        </a>

                        <a href="#" class="menu-item">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                </path>
                            </svg>
                            <span>Отзывы</span>
                        </a>
                    </div>
                </div>
            </div>
        </template>

        <br><br><br>

        <!-- Modal for Participant -->
        <div class="modal" x-show="isParticipantModalOpen" x-cloak>
            <div class="modal-overlay" @click="closeParticipantModal()"></div>
            <div class="modal-content">
                <button
                    style="position:absolute;top:12px;right:12px;background:none;border:none;color:#9ca3af;cursor:pointer;padding:4px;"
                    @click="closeParticipantModal()">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>

                <div class="participant-modal-avatar">
                    <img :src="selectedParticipant.avatar" alt="Участник">
                </div>

                <div class="participant-modal-rating">
                    <svg viewBox="0 0 24 24">
                        <path
                            d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                    </svg>
                    <span x-text="selectedParticipant.rating"></span>
                </div>

                <div class="participant-modal-name" x-text="selectedParticipant.name"></div>
                <div class="participant-modal-bio" x-text="selectedParticipant.bio"></div>

                <template x-if="selectedParticipant.telegram_username">
                    <a :href="'https://t.me/' + selectedParticipant.telegram_username" target="_blank"
                        class="telegram-btn">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z" />
                        </svg>
                        НАПИСАТЬ В ТЕЛЕГРАМ
                    </a>
                </template>
            </div>
        </div>

        <!-- Modal for Participants Rating -->
        <div class="modal" x-show="isRatingModalOpen" x-cloak>
            <div class="modal-overlay" @click="closeRatingModal()"></div>
            <div class="modal-content">
                <button
                    style="position:absolute;top:12px;right:12px;background:none;border:none;color:#9ca3af;cursor:pointer;"
                    @click="closeRatingModal()">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>

                <h3 class="modal-title">Рейтинг участников</h3>

                <div class="rating-list">
                    @forelse ($ratingApplications as $ratingIndex => $application)
                        <div class="rating-list-item" x-on:click="window.location.href = '/account/show/{{ $application->account->id }}'">
                            <div class="rating-list-position">{{ $ratingIndex + 1 }}</div>
                            <img class="rating-list-avatar" src="{{ $application->account->avatar ?? '/def.svg' }}"
                                alt="{{ $application->account->nickname ?? $application->account->name }}">

                            <div class="rating-list-name">
                                {{ $application->account->nickname ?? $application->account->name }}
                                                            <span>
                                {{ $application->dops > 0 ? '+' . $application->dops : '' }}
                            </span>
                            </div>

                            <div class="rating-list-value">
                                {{ $application->account->rating ?? 0 }}
                            </div>
                        </div>
                    @empty
                        <p class="modal-text">Участников пока нет.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Modal for Booking -->
        <div class="modal" x-show="isModalOpen" x-cloak>
            <div class="modal-overlay" @click="closeModal()"></div>
            <div class="modal-content">
                <button
                    style="position:absolute;top:12px;right:12px;background:none;border:none;color:#9ca3af;cursor:pointer;"
                    @click="closeModal()">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>

                <h3 class="modal-title">Запись на игру</h3>

                <template x-if="isBooked">
                    <div>
                        <p class="modal-text" style="color: #f43f5e; font-weight: 600;">
                            Вы уже записаны на это мероприятие!
                        </p>

                        <p class="modal-text" style="margin-bottom: 10px;">
                            Дополнительные места:
                        </p>

                        <div class="quantity-selector">
                            <button type="button" class="quantity-btn" :disabled="loading || bookingDops <= 0"
                                @click="decreaseBookingDops()">−1</button>

                            <span class="quantity-display" x-text="'+' + bookingDops"></span>

                            <button type="button" class="quantity-btn" :disabled="loading || availableSeats <= 0"
                                @click="increaseBookingDops()">+1</button>
                        </div>

                        <div class="modal-actions" style="margin-bottom: 12px;">
                            <button class="modal-btn modal-btn-primary"
                                :disabled="loading || bookingDops === originalBookingDops" @click="updateBookingDops()">
                                Сохранить
                            </button>

                            <button class="modal-btn modal-btn-secondary" @click="closeModal()">
                                Назад
                            </button>
                        </div>

                        <button class="modal-btn modal-btn-secondary" style="width: 100%; color: #f43f5e;"
                            :disabled="loading" @click="cancelBooking()">
                            Отменить бронь
                        </button>
                    </div>
                </template>

                <template x-if="!isBooked">
                    <div>
                        <p class="modal-text">Выберите количество мест:</p>

                        <div class="quantity-selector">
                            <button type="button" class="quantity-btn" @click="decreaseQuantity()">−</button>
                            <span class="quantity-display" x-text="selectedQuantity"></span>
                            <button type="button" class="quantity-btn" @click="increaseQuantity(1)">+1</button>
                            <button type="button" class="quantity-btn" @click="increaseQuantity(2)">+2</button>
                            <button type="button" class="quantity-btn" @click="increaseQuantity(3)">+3</button>
                        </div>

                        <div class="modal-actions">
                            <button class="modal-btn modal-btn-primary" :disabled="loading" @click="confirmBooking()">
                                Записаться
                            </button>
                            <button class="modal-btn modal-btn-secondary" @click="closeModal()">
                                Отмена
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <script>
            function eventPage() {
                const total = @json((int) ($game->total_space ?? 0));
                const initialOccupied = @json((int) $occupiedSeats);
                const alreadyBooked = @json($currentApplication !== null);
                const currentBookingDops = @json($currentBookingDops);

                return {
                    account: @json($account),
                    menuOpen: false,
                    gameId: @json($game->id),
                    totalSeats: total,
                    occupiedSeats: initialOccupied,
                    isBooked: alreadyBooked,
                    isModalOpen: false,
                    isParticipantModalOpen: false,
                    isRatingModalOpen: false,
                    selectedParticipant: {},
                    selectedQuantity: 1,
                    bookingDops: currentBookingDops,
                    originalBookingDops: currentBookingDops,
                    loading: false,

                    get availableSeats() {
                        return Math.max(0, this.totalSeats - this.occupiedSeats);
                    },

                    openModal() {
                        this.isModalOpen = true;
                    },

                    closeModal() {
                        this.isModalOpen = false;
                        this.selectedQuantity = 1;
                        this.occupiedSeats += this.originalBookingDops - this.bookingDops;
                        this.bookingDops = this.originalBookingDops;
                    },

                    openRatingModal() {
                        this.isRatingModalOpen = true;
                    },

                    closeRatingModal() {
                        this.isRatingModalOpen = false;
                    },

                    closeParticipantModal() {
                        this.isParticipantModalOpen = false;
                        this.selectedParticipant = {};
                    },

                    increaseQuantity(amount) {
                        if (this.availableSeats <= 0) {
                            alert('Свободных мест больше нет.');
                            return;
                        }

                        const nextQuantity = Math.min(
                            this.selectedQuantity + amount,
                            this.availableSeats
                        );

                        if (nextQuantity < this.selectedQuantity + amount) {
                            alert(`Доступно только ${this.availableSeats} мест`);
                        }

                        this.selectedQuantity = Math.max(1, nextQuantity);
                    },

                    decreaseQuantity() {
                        if (this.selectedQuantity > 1) {
                            this.selectedQuantity--;
                        }
                    },

                    increaseBookingDops() {
                        if (this.availableSeats <= 0) {
                            alert('Свободных мест больше нет.');
                            return;
                        }

                        this.bookingDops++;
                        this.occupiedSeats++;
                    },

                    decreaseBookingDops() {
                        if (this.bookingDops <= 0) return;

                        this.bookingDops--;
                        this.occupiedSeats--;
                    },

                    async readJson(response) {
                        const data = await response.json().catch(() => ({}));

                        if (!response.ok) {
                            const validationMessage = data.errors ?
                                Object.values(data.errors).flat()[0] :
                                null;

                            throw new Error(validationMessage || data.message || 'Ошибка сервера');
                        }

                        return data;
                    },

                    updateBookingDops() {
                        if (this.loading || this.bookingDops === this.originalBookingDops) return;

                        this.loading = true;

                        fetch('/update-booking-game', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    account_id: this.account.id,
                                    game_id: this.gameId,
                                    dops: this.bookingDops
                                })
                            })
                            .then(response => this.readJson(response))
                            .then(data => {
                                if (!data.success) {
                                    throw new Error(data.message || 'Не удалось изменить бронь.');
                                }

                                this.originalBookingDops = Number(data.dops);
                                this.bookingDops = Number(data.dops);
                                this.occupiedSeats = Number(data.occupied_seats);
                                alert('Количество дополнительных мест изменено.');
                                this.closeModal();
                                location.reload();
                            })
                            .catch(error => {
                                this.occupiedSeats += this.originalBookingDops - this.bookingDops;
                                this.bookingDops = this.originalBookingDops;
                                alert(error.message);
                            })
                            .finally(() => {
                                this.loading = false;
                            });
                    },

                    confirmBooking() {
                        if (this.loading) return;

                        if (this.availableSeats <= 0) {
                            alert('Извините, свободных мест больше нет.');
                            this.closeModal();
                            return;
                        }

                        this.loading = true;

                        fetch('/booking-game', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    quantity: this.selectedQuantity,
                                    account_id: this.account.id,
                                    game_id: this.gameId
                                })
                            })
                            .then(response => this.readJson(response))
                            .then(data => {
                                if (!data.success) {
                                    throw new Error(data.message || 'Произошла ошибка при бронировании.');
                                }

                                this.occupiedSeats += this.selectedQuantity;
                                this.isBooked = true;
                                this.originalBookingDops = Math.max(0, this.selectedQuantity - 1);
                                this.bookingDops = this.originalBookingDops;
                                alert('Вы успешно записались на игру!');
                                this.closeModal();
                                location.reload();
                            })
                            .catch(error => {
                                console.error('Ошибка:', error);
                                alert(error.message);
                            })
                            .finally(() => {
                                this.loading = false;
                            });
                    },

                    cancelBooking() {
                        if (this.loading) return;
                        if (!confirm('Вы уверены, что хотите отменить запись на игру?')) return;

                        this.loading = true;

                        fetch('/cancel-booking-game', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    account_id: this.account.id,
                                    game_id: this.gameId
                                })
                            })
                            .then(response => this.readJson(response))
                            .then(data => {
                                if (!data.success) {
                                    throw new Error(data.message || 'Не удалось отменить бронирование.');
                                }

                                const freed = Number(data.freed_seats || (1 + this.originalBookingDops));
                                this.occupiedSeats = Math.max(0, this.occupiedSeats - freed);
                                this.isBooked = false;
                                this.bookingDops = 0;
                                this.originalBookingDops = 0;
                                alert('Ваша запись успешно отменена.');
                                this.closeModal();
                                location.reload();
                            })
                            .catch(error => {
                                console.error('Ошибка:', error);
                                alert(error.message);
                            })
                            .finally(() => {
                                this.loading = false;
                            });
                    }
                };
            }
        </script>
    </div>

    @include('include.club.bottom-nav')
@endsection
