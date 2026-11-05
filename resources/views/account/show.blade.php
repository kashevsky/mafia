@extends('layouts.club')
@section('content')
    <title>Личный кабинет игрока в Мафию</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #0f172a;
            background-color: black;
            /* глубокий тёмно‑синий */
            color: #e2e8f0;
            /* светлый текст */
            line-height: 1.5;
        }

        .container {
            max-width: 448px;
            margin: 0 auto;
            background-color: black;
            /* тёмно‑серый фон контейнера */
            min-height: 100vh;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
            border-radius: 12px;
        }

        .user-bar {
            padding-bottom: 40px;
        }


        .tab-button {
            flex: 1;
            padding: 10px 6px;
            border: none;
            background: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: #f8f8f8;
            border-bottom: none;
            transition: all 0.2s;
            font-size: 14px;
            font-weight: 300;
            border-radius: 6px;
        }

        .tab-button.active {
            background: #000000;
            color: #ffffff;
            border-radius: 6px;
        }

        .tab-button:hover {
            background-color: #1e293b;
            /* затемнение при наведении */
            color: #f8fafc;
        }

        .tab-icon {
            width: 20px;
            height: 20px;
        }

        .content {
            padding: 16px;
        }

        .profile-header {
            text-align: center;
            margin-top: 34px;
            margin-bottom: 24px;
        }

        .avatar {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            background: #334155;
            /* тёмно‑серый фон аватара */
            margin: 0 auto 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            /* background-image: url('../../uploads/avatars/dima.jpg'); */
            background-size: cover;
            background-position: center;

            /* синяя рамка */
        }

        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar-icon {
            width: 48px;
            height: 48px;
            color: #64748b;
        }

        .nickname {
            font-size: 24px;
            font-weight: bold;
            color: #ff0000;
            margin-bottom: 8px;
        }

        .bio {
            color: #9ca3af;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 300;
            padding: 4px 28px;
            text-align: start;
            overflow-y: scroll;
            height: 90px;
        }

        .edit-button {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            color: white;
            /* красный цвет для редактирования */
            background: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
            padding: 4px 8px;
            text-decoration: underline;
            text-underline-offset: 4px;
        }

        .edit-button:hover {
            color: #dc2626;
        }

        .edit-form {
            background: #1e293b;
            /* тёмно‑синий фон формы */
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            border: 1px solid #334155;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #cbd5e1;
            /* светло‑серый текст */
            margin-bottom: 4px;
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #334155;
            /* тёмно‑серая граница */
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            background-color: #0f172a;
            color: #f8fafc;
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: #3b82f6;
            /* синий акцент при фокусе */
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-textarea {
            resize: vertical;
        }

        .required {
            color: #b91c1c;
            /* красный для обязательных полей */
        }

        .button-group {
            display: flex;
            gap: 8px;
        }

        .btn {
            flex: 1;
            padding: 10px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-primary {
            background: #b91c1c;
            /* красный основной */
            color: white;
        }

        .btn-primary:hover {
            background: #dc2626;
        }

        .btn-secondary {
            background: #374151;
            /* тёмно‑серый вторичный */
            color: #f8fafc;
        }

        .btn-secondary:hover {
            background: #4b5563;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-bottom: 24px;
        }

        .stat-card {
            padding: 16px;
            border-radius: 12px;
            color: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2);
            background: rgba(31, 41, 55, 0.7);
            /* полупрозрачный тёмный фон */
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
        }

        .stat-card.red {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }

        .stat-card.blue {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        }

        .stat-card.green {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .stat-card.purple {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        }

        .stat-label {
            font-size: 14px;
            opacity: 0.8;
        }

        .stat-value {
            font-size: 30px;
            font-weight: bold;
            margin-top: 4px;
        }

        .card {
            background: #1e293b;
            /* тёмно‑синий фон карточки */
            border: 1px solid #334155;
            /* тёмно‑серая граница */
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 24px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.2);
        }

        .card-title {
            font-size: 16px;
            font-weight: 600;
            color: #f8fafc;
            /* почти белый текст */
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-icon {
            width: 20px;
            height: 20px;
            color: #9ca3af;
        }

        .chart-container {
            position: relative;
            height: 250px;
            margin-bottom: 16px;
        }

        .chart-info {
            padding-top: 16px;
            border-top: 1px solid #334155;
            font-size: 14px;
            color: #9ca3af;
        }

        .chart-info strong {
            color: #f8fafc;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .info-label {
            color: #9ca3af;
        }

        .info-value {
            font-weight: 500;
            color: #f8fafc;
        }

        .contact-link {
            display: inline-block;
            background: #3b82f6;
            /* синий цвет ссылки */
            color: white;
            padding: 10px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            transition: background 0.2s;
        }

        .contact-link:hover {
            background: #2563eb;
        }

        .badges-container {
            text-align: center;
            padding: 48px 0;
        }

        .large-icon {
            width: 64px;
            height: 64px;
            color: #64748b;
            margin: 0 auto 16px;
        }

        .badges-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #f8fafc;
        }

        .badges-subtitle {
            color: #9ca3af;
            margin-bottom: 32px;
        }

        .badges-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .badge-item {
            background: #1e293b;
            /* тёмно‑синий фон бейджа */
            border-radius: 12px;
            padding: 16px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
            border: 1px solid #334155;
        }

        .badge-body {
            flex: 1;
            min-width: 0;
        }

        .badge-text {
            font-size: 13px;
            color: #cbd5e1;
            line-height: 1.5;
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }

        .badge-text.expanded {
            -webkit-line-clamp: unset;
            display: block;
        }

        .badge-more-btn {
            margin-top: 6px;
            font-size: 12px;
            color: #b91c1c;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            font-weight: 500;
        }

        .badge-more-btn:hover {
            color: #dc2626;
        }

        .badge-icon {
            width: 64px;
            height: 64px;
            background: #334155;
            /* тёмно‑серый фон иконки */
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
        }

        .badge-icon svg {
            width: 32px;
            height: 32px;
            color: #9ca3af;
        }

        .badge-name {
            font-size: 13px;
            font-weight: 600;
            color: #f8fafc;
            display: block;
            margin-bottom: 4px;
        }

        .balance-container {
            text-align: center;
            padding: 8px 0;
        }

        .balance-amount {
            font-size: 48px;
            font-weight: bold;
            color: #f8fafc;
            /* яркий белый текст баланса */
            margin: 12px 0;
        }

        .balance-subtitle {
            color: #9ca3af;
            margin-bottom: 32px;
        }

        .transactions {
            background: #1e293b;
            /* тёмно‑синий фон транзакций */
            border: 1px solid #334155;
            border-radius: 12px;
            padding: 16px;
            text-align: left;
        }

        .transactions-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 12px;
            color: #f8fafc;
        }

        .transaction-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #334155;
        }

        .transaction-item:last-child {
            border-bottom: none;
        }

        .transaction-desc {
            font-size: 14px;
            font-weight: 500;
            color: #f8fafc;
        }

        .transaction-date {
            font-size: 12px;
            color: #9ca3af;
        }

        .transaction-amount {
            font-weight: bold;
        }

        .transaction-amount.positive {
            color: #10b981;
            /* зелёный для положительных сумм */
        }

        .transaction-amount.negative {
            color: #b91c1c;
            /* красный для отрицательных сумм */
        }

        .text-link {
            color: #ffffff;
            text-decoration: none;
            font-weight: 500;
        }

        .text-att {
            color: #ff0000;
            text-decoration: none;
            font-weight: 500;
        }

        .avatar-upload-container {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .current-avatar {
            text-align: center;
        }

        .current-avatar svg {
            width: 48px;
            height: 48px;
            color: #64748b;
        }

        .avatar-preview {
            display: block;
            width: 96px;
            height: 96px;
            border-radius: 50%;
            object-fit: cover;
            background: #334155;
            margin: 0 auto;

        }

        #avatarUpload {
            display: none;
        }

        .form-hint {
            font-size: 12px;
            color: #fbbf24;
            margin-top: 4px;
        }

        /* Telegram */
        .telegram-card {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 24px;
        }

        .telegram-status {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 4px;

        }

        .telegram-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .telegram-icon svg {
            width: 20px;
            height: 20px;
            fill: white;
        }

        .telegram-info {
            flex: 1;
        }

        .telegram-info-title {
            font-size: 14px;
            font-weight: 600;
            color: #f8fafc;
        }

        .telegram-info-sub {
            font-size: 12px;
            color: #9ca3af;
            margin-top: 2px;
        }

        .telegram-badge-linked {
            font-size: 11px;
            background: #064e3b;
            color: #34d399;
            border: 1px solid #065f46;
            padding: 2px 8px;
            border-radius: 99px;
        }

        .telegram-badge-unlinked {
            font-size: 11px;
            background: #451a03;
            color: #fb923c;
            border: 1px solid #7c2d12;
            padding: 2px 8px;
            border-radius: 99px;
        }

        .btn-telegram {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            background: #2563eb;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-telegram:hover {
            background: #1d4ed8;
        }

        .btn-telegram-unlink {
            background: #374151;
            color: #f87171;
            margin-top: 8px;
        }

        .btn-telegram-unlink:hover {
            background: #4b5563;
        }

        .telegram-modal-step {
            background: #0f172a;
            border: 1px solid #334155;
            border-radius: 8px;
            padding: 14px;
            margin: 12px 0;
        }

        .telegram-modal-step p {
            font-size: 13px;
            color: #cbd5e1;
            margin-bottom: 8px;
        }

        .telegram-code-box {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 6px;
            padding: 10px 14px;
            font-family: monospace;
            font-size: 15px;
            color: #fbbf24;
            letter-spacing: 0.05em;
            text-align: center;
            word-break: break-all;
        }

        .telegram-bot-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px;
            background: #2563eb;
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            margin-top: 12px;
            transition: background 0.2s;
        }

        .telegram-bot-link:hover {
            background: #1d4ed8;
        }

        .telegram-hint {
            font-size: 12px;
            color: #9ca3af;
            text-align: center;
            margin-top: 10px;
        }

        /* Отдельный класс для Telegram-модалки — без display:none,
                                                                                                                                               Alpine управляет видимостью через x-show самостоятельно */
        .tg-modal {
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .profile-wrap {
            position: relative;
        }

        :root {
            --primary-color: #ff0000;
        }

        .tabs-header {
            display: flex;
            align-items: center;
            background: var(--primary-color);
            width: 92%;
            margin: 1px auto;
            position: absolute;
            bottom: -20px;
            left: 50%;
            z-index: 2;
            transform: translateX(-50%);
            border-radius: 8px;
            padding: 6px;
            gap: 2px;
        }
    </style>


    <style>
        /* ════════════════════════════════════
                                                                                                                   STATS GRID
                                                                                                                ════════════════════════════════════ */
        .stats-section {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .top-row {
            display: flex;
            gap: 16px;
        }

        /* ── Очки ── */
        .card-points {
            background: #424a5a;
            border-radius: 10px;
            width: 48%;
            height: 100px;
            flex-shrink: 0;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding-top: 20px;
        }

        .card-points-label {
            font-size: 16px;
            font-weight: 400;
            color: #ffffff;
            line-height: 18px;
            text-align: center;
        }

        .card-points-value {
            font-size: 48px;
            font-weight: 600;
            color: #ee0000;
            line-height: 1;
            text-align: center;
            margin-top: 2px;
            position: relative;
            z-index: 1;
        }

        .card-points::after {
            content: '';
            position: absolute;
            right: -30px;
            top: 50%;
            transform: translateY(-50%);
            width: 60px;
            height: 60px;
            background: #ee0000;
            border-radius: 50%;
        }

        /* ── Баланс ── */
        .card-balance {
            background: #ee0000;
            border-radius: 10px;
            width: 48%;
            height: 100px;
            flex-shrink: 0;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding-top: 20px;
        }

        .card-balance-label {
            font-size: 16px;
            font-weight: 400;
            color: #ffffff;
            line-height: 18px;
            text-align: center;
        }

        .card-balance-value {
            font-size: 48px;
            font-weight: 600;
            color: #ffffff;
            line-height: 1;
            text-align: center;
            margin-top: 2px;
            position: relative;
            right: -5px;
            z-index: 1;
        }

        .card-balance::after {
            content: '';
            position: absolute;
            left: -30px;
            top: 50%;
            transform: translateY(-50%);
            width: 60px;
            height: 60px;
            background: #2b313c;
            border-radius: 50%;
        }

        /* ── Игровой стаж ── */
        .card-experience {
            background: #424a5a;
            border-radius: 10px;
            height: 100px;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 16px 16px 0 16px;
            position: relative;
        }

        .experience-title-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
        }

        .experience-title {
            font-size: 20px;
            font-weight: 600;
            color: #ffffff;
            line-height: 18px;
            white-space: nowrap;
        }

        .experience-data {
            display: flex;
            align-items: flex-start;
            justify-content: center;
            width: 100%;
        }

        .experience-col {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
        }

        .exp-col-label {
            font-size: 16px;
            font-weight: 400;
            color: #ffffff;
            line-height: 22px;
            text-align: center;
        }

        .exp-col-value {
            font-size: 16px;
            font-weight: 700;
            color: #ffffff;
            line-height: 22px;
            text-align: center;
        }

        .experience-divider {
            width: 1px;
            height: 32px;
            background: #d9d9d9;
            margin: 0 8px;
            align-self: center;
        }

        /* ── Telegram ── */
        .card-telegram {
            background: #2b313c;
            border-radius: 10px;
            height: 100px;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 16px;
        }

        .telegram-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 14px;
        }

        .telegram-title {
            font-size: 20px;
            font-weight: 600;
            color: #009eeb;
            line-height: 18px;
            white-space: nowrap;
        }

        .telegram-status {
            font-size: 16px;
            font-weight: 400;
            color: #ffffff;
            line-height: 22px;
            white-space: nowrap;
        }

        .telegram-btn {
            background: #009eeb;
            border-radius: 10px;
            width: 190px;
            height: 38px;
            border: none;
            cursor: pointer;
            font-size: 18px;
            font-weight: 400;
            color: #ffffff;
            font-family: 'Inter', sans-serif;
        }

        .icon-tg {
            width: 16px;
            height: 14px;
            flex-shrink: 0;
        }

        /* ════════════════════════════════════
                                                                                                       EDIT PROFILE DROPDOWN
                                                                                                    ════════════════════════════════════ */
        .edit-dropdown-wrap {
            overflow: hidden;
            max-height: 0;
            transition: max-height 0.4s ease;
        }

        .edit-dropdown-wrap.active {
            max-height: 1000px;
        }

        /* ── Edit form panel ── */
        .edit-form {
            background: #424a5a;
            padding: 20px 20px 24px 20px;
            position: relative;
        }

        /* close × button */
        .edit-close {
            position: absolute;
            top: 20px;
            right: 20px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
            line-height: 0;
        }

        /* field label */
        .field-label {
            font-size: 20px;
            font-weight: 600;
            color: #ffffff;
            line-height: 18px;
            margin-bottom: 8px;
            display: block;
        }

        /* text input / textarea */
        .field-input {
            display: block;
            width: 100%;
            background: #2b313c;
            border: none;
            border-radius: 8px;
            height: 34px;
            padding: 0 12px;
            font-size: 18px;
            font-weight: 400;
            color: #ffffff;
            font-family: 'Inter', sans-serif;
            outline: none;
        }

        .field-input::placeholder {
            color: #6a768f;
        }

        .field-textarea {
            display: block;
            width: 100%;
            background: #2b313c;
            border: none;
            border-radius: 8px;
            height: 68px;
            padding: 8px 12px;
            font-size: 18px;
            font-weight: 400;
            color: #ffffff;
            font-family: 'Inter', sans-serif;
            outline: none;
            resize: none;
        }

        .field-textarea::placeholder {
            color: #6a768f;
        }

        .field-group {
            margin-bottom: 20px;
        }

        /* avatar row inside form */
        .avatar-row {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: #2b313c;
            flex-shrink: 0;
            overflow: hidden;
        }

        .form-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .choose-file-btn {
            background: #2b313c;
            border: 1px solid #ee0000;
            border-radius: 10px;

            padding: 5px 20px;
            font-size: 18px;
            font-weight: 400;
            color: #ffffff;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            white-space: nowrap;
        }

        /* bottom action buttons */
        .form-actions {
            display: flex;
            gap: 16px;
            margin-top: 24px;
        }

        .btn-cancel {
            flex: 1;
            height: 38px;
            background: #6a768f;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            font-weight: 400;
            color: #ffffff;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
        }

        .btn-save {
            flex: 1;
            height: 38px;
            background: #ee0000;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            font-weight: 400;
            color: #ffffff;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
        }
    </style>
    </head>

    <body>
        <div class="container" x-data="mafiaPlayer()">
            <div class="profile-wrap">
                @include('include.club.header')
                <div class="tabs-header">
                    <button class="tab-button" :class="{ 'active': activeTab === 'profile' }" @click="activeTab = 'profile'">
                        <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>Профиль</span>
                    </button>
                    <button class="tab-button" :class="{ 'active': activeTab === 'badges' }"
                        @click="activeTab = 'badges'">
                        <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                            </path>
                        </svg>
                        <span>Медальки</span>
                    </button>
                    <button class="tab-button" :class="{ 'active': activeTab === 'balance' }"
                        @click="activeTab = 'balance'">
                        <svg class="tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                        <span>Баланс</span>
                    </button>
                </div>
            </div>

            <div class="content">
                <div x-show="activeTab === 'profile'" x-transition>
                    <div class="profile-header">
                        <div class="avatar">
                            <img :src="player.avatar ? player.avatar : '/new/def-ava.svg'" alt="Текущий аватар">
                        </div>

                        <div class="nickname" x-text="player.nickname"></div>
                        <div class="bio" x-text="player.bio"></div>
                        <button class="edit-button" @click="isEditing = !isEditing">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                            <span>Редактировать профиль</span>

                        </button>
                    </div>
                    <div x-show="isEditing" x-transition class="edit-form">

                        <!-- × close -->
                        <button class="edit-close" @click="isEditing = false" aria-label="Закрыть">
                            <svg width="16" height="14" viewBox="0 0 16 14" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M1 1L15 13M15 1L1 13" stroke="#6A768F" stroke-width="2" stroke-linecap="round" />
                            </svg>
                        </button>

                        <form @submit.prevent="saveProfile()" enctype="multipart/form-data">

                            <!-- Ник -->
                            <div class="field-group">
                                <span class="field-label">Ник</span>
                                <input class="field-input" type="text" x-model="player.nickname" @input="naming()">

                            </div>
                            <!-- Имя -->
                            <div class="field-group">
                                <span class="field-label">Имя</span>
                                <input class="field-input" type="text" x-model="player.name">
                            </div>

                            <!-- Аватар -->
                            <div class="field-group">
                                <span class="field-label">Аватар</span>
                                <div class="avatar-row">
                                    <div class="form-avatar">
                                        <img :src="player.avatar || '/new/def-ava.svg'" alt="Аватар">
                                    </div>
                                    <input type="file" id="avatarUpload" accept="image/*" style="display:none"
                                        @change="handleAvatarUpload">
                                    <label for="avatarUpload" class="choose-file-btn">Выбрать файл</label>
                                </div>
                            </div>

                            <!-- День рождения -->
                            <div class="field-group">
                                <span class="field-label" @click="console.log(player)">День рождения</span>
                                <input class="field-input" type="date" x-model="player.birthday" />
                            </div>

                            <!-- Пол -->
                            <div class="field-group">
                                <span class="field-label">Пол</span>
                                <select class="field-input" x-model="player.gender">
                                    <option {{ $account->gender == 'Мужской' ? 'selected' : '' }}>Мужской</option>
                                    <option {{ $account->gender == 'Женский' ? 'selected' : '' }}>Женский</option>
                                    {{-- <option>Другой</option> --}}
                                </select>
                            </div>

                            <!-- О себе -->
                            <div class="field-group">
                                <span class="field-label">О себе</span>
                                <textarea class="field-textarea" x-model="player.bio"></textarea>
                            </div>

                            <!-- Почта -->
                            <div class="field-group">
                                <span class="field-label">Почта</span>
                                <input class="field-input" type="email" x-model="player.email">
                            </div>

                            <!-- Ссылка на мессенджер -->
                            {{-- <div class="field-group" style="margin-bottom: 0;">
                                <span class="field-label">Ссылка на мессенджер</span>
                                <input class="field-input" type="text" x-model="player.messenger"
                                    placeholder="https://t.me/username" />
                            </div> --}}

                            <!-- Кнопки -->
                            <div class="form-actions">
                                <button type="button" class="btn-cancel" @click="isEditing = false">Отмена</button>
                                <button type="submit" class="btn-save">Сохранить</button>
                            </div>

                        </form>

                    </div>


                    <div class="stats-section">

                        <div class="top-row">
                            <div class="card-points">
                                <span class="card-balance-label">Очки</span>
                                <span class="card-points-value" x-text="player.points"></span>


                            </div>

                            <div class="card-balance">
                                <span class="card-balance-label">Баланс</span>
                                <span class="card-balance-value" x-text="player.balance"></span>
                            </div>
                        </div>

                        <div class="card-experience">
                            <div class="experience-title-row">
                                <svg width="18" height="18" viewBox="0 0 20 18" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path clip-rule="evenodd"
                                        d="M2 4C2 2.895 2.895 2 4 2H16C17.105 2 18 2.895 18 4V15C18 16.105 17.105 17 16 17H4C2.895 17 2 16.105 2 15V4ZM6 1V4M14 1V4M2 7H18"
                                        fill-rule="evenodd" stroke="white" stroke-miterlimit="22.9256"
                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <span class="experience-title">Игровой стаж</span>
                            </div>

                            <div class="experience-data">
                                {{-- Блок первой игры --}}
                                <div class="experience-col">
                                    <span class="exp-col-label">Первая игра:</span>
                                    <span class="exp-col-value">
                                        {{ !empty($account->first_game) ? \Carbon\Carbon::parse($account->first_game)->format('d.m.Y') : '-' }}
                                    </span>
                                </div>

                                <div class="experience-divider"></div>

                                {{-- Блок стажа --}}
                                <div class="experience-col">
                                    <span class="exp-col-label">Стаж:</span>
                                    <span class="exp-col-value">
                                        @if (!empty($account->first_game) && \Carbon\Carbon::parse($account->first_game)->getTimestamp() > 0)
                                            {{ \Carbon\Carbon::parse($account->first_game)->diffInDays(now()) }} дн.
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>
                            </div>

                        </div>

                        <div class="card-telegram">
                            <div class="telegram-header">
                                <svg class="icon-tg" viewBox="0 0 16 14" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M15.9688 1.45312L13.5156 13.0469C13.5156 13.0469 13.1875 13.9219 12.2344 13.5L6.85938 9.32812L4.82812 8.32812L1.39062 7.20312C1.39062 7.20312 0.859375 7.02344 0.8125 6.54688C0.765625 6.07031 1.40625 5.85938 1.40625 5.85938L14.8594 0.609375C14.8594 0.609375 15.9688 0.140625 15.9688 1.45312Z"
                                        fill="#009EEB"></path>
                                    <path
                                        d="M6.125 12.9219C6.125 12.9219 5.96094 12.9063 5.75781 12.2344C5.55469 11.5625 4.82812 8.32812 4.82812 8.32812L13.125 3.125C13.125 3.125 13.5938 2.84375 13.5781 3.125C13.5781 3.125 13.6563 3.17188 13.4063 3.40625C13.1563 3.64063 6.875 9.28125 6.875 9.28125"
                                        fill="#007AC2"></path>
                                    <path
                                        d="M8.6875 10.9219L6.79688 12.8594C6.79688 12.8594 6.64844 12.9766 6.5 12.9219L6.85938 9.34375"
                                        fill="#007AC2"></path>
                                </svg>
                                <span class="telegram-title">Telegram</span>
                                <span class="telegram-status" x-show="!telegramLinked">Не привязан</span>
                                <span class="telegram-status" x-show="telegramLinked">Привязан</span>
                            </div>
                            <button class="telegram-btn" @click="openTelegramModal()"
                                x-show="telegramLinked">Привязать</button>
                        </div>


                    </div>









                    {{-- <div class="card">
                        <h3 class="card-title"><svg class="card-icon" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                </path>
                            </svg>Контакты</h3>
                        <a :href="player.messenger" target="_blank" rel="noopener noreferrer"
                            class="contact-link">Написать в мессенджер</a>
                    </div> --}}

                    <!-- Telegram привязка -->

                </div>

                <div x-show="activeTab === 'badges'" x-transition>
                    <div class="badges-container">


                        <style>
                            h1 {
                                font-size: 32px;
                                font-weight: 800;
                                color: #ffffff;
                                margin-bottom: 12px;
                                text-align: center;
                            }

                            .subtitle {
                                font-size: 18px;
                                color: #cccccc;
                                margin-bottom: 56px;
                                text-align: center;
                            }

                            .medals-grid {
                                display: grid;
                                grid-template-columns: repeat(3, 1fr);
                                gap: 40px 16px;
                                width: 100%;
                                padding: 0 20px;
                                max-width: 720px;
                                align-self: stretch;
                            }

                            .medal-card {
                                display: flex;
                                flex-direction: column;
                                align-items: center;
                                gap: 4px;
                                min-width: 0;
                            }

                            .medal-wrapper {
                                position: relative;
                                width: 100%;
                                aspect-ratio: 1;
                            }

                            .medal-coin {
                                width: 100%;
                                height: 100%;
                                border-radius: 50%;

                                display: flex;
                                align-items: center;
                                justify-content: center;
                                position: relative;
                                overflow: hidden;
                            }



                            .medal-text-ring {
                                position: absolute;
                                inset: 0;

                            }

                            .medal-text-ring svg {
                                width: 100%;
                                height: 100%;
                                position: absolute;
                                top: 0;
                                left: 0;
                                overflow: visible;
                            }

                            .medal-text-ring img {
                                object-fit: cover;
                                width: 90%;
                            }

                            .medal-text-ring textPath {
                                font-size: 9.5px;
                                font-weight: 700;
                                fill: rgba(80, 48, 0, 0.75);
                                letter-spacing: 2.4px;
                                font-family: Arial, sans-serif;
                            }

                            .medal-icon {
                                position: relative;
                                z-index: 2;
                                width: 48%;
                                height: 48%;
                                filter:
                                    drop-shadow(-3px -3px 6px rgba(255, 230, 100, 0.5)) drop-shadow(3px 3px 8px rgba(80, 40, 0, 0.7));
                            }

                            .laurel-left,
                            .laurel-right {
                                position: absolute;
                                bottom: 22px;
                                width: 30px;
                                height: 44px;
                                z-index: 1;
                            }

                            .laurel-left {
                                left: 18px;
                            }

                            .laurel-right {
                                right: 18px;
                                transform: scaleX(-1);
                            }

                            .question-badge {
                                position: absolute;
                                top: 4px;
                                right: 4px;
                                width: 26px;
                                height: 26px;
                                border-radius: 50%;
                                background: #2a2a2a;
                                border: 1.5px solid #555;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                font-size: 13px;
                                font-weight: 600;
                                color: #aaa;
                                cursor: pointer;
                                z-index: 10;
                                user-select: none;
                                transition: background 0.2s, border-color 0.2s;
                            }

                            .question-badge:hover {
                                background: #3a3a3a;
                                border-color: #888;
                                color: #fff;
                            }

                            /* Modal */
                            .modal-overlay {
                                display: none;
                                position: fixed;
                                inset: 0;
                                background: rgba(0, 0, 0, 0.75);
                                z-index: 1000;
                                align-items: center;
                                justify-content: center;
                                padding: 24px;
                            }

                            .modal-overlay.active {
                                display: flex;
                            }

                            .medal-modal {
                                background: #1a1a1a;
                                border: 1px solid #333;
                                border-radius: 16px;
                                padding: 32px 28px 28px;
                                max-width: 380px;
                                width: 100%;
                                position: relative;
                                animation: modalIn 0.2s ease;
                            }

                            @keyframes modalIn {
                                from {
                                    opacity: 0;
                                    transform: scale(0.92);
                                }

                                to {
                                    opacity: 1;
                                    transform: scale(1);
                                }
                            }

                            .modal-title {
                                font-size: 18px;
                                font-weight: 700;
                                color: #f5c842;
                                margin-bottom: 12px;
                            }

                            .modal-body {
                                font-size: 15px;
                                color: #cccccc;
                                line-height: 1.6;
                            }

                            .modal-close {
                                position: absolute;
                                top: 14px;
                                right: 16px;
                                background: none;
                                border: none;
                                color: #777;
                                font-size: 22px;
                                cursor: pointer;
                                line-height: 1;
                                padding: 4px;
                            }

                            .modal-close:hover {
                                color: #fff;
                            }

                            .medal-label {
                                text-align: center;
                                font-size: 13px;
                                font-weight: 300;
                                line-height: 1.2;
                                color: #e8e8e8;
                            }

                            .medal-label .date {
                                font-weight: 700;
                            }
                        </style>


                        <h1 style="width: 100% !important">Медальки</h1>
                        <p class="subtitle">Здесь будут отображаться ваши достижения</p>

                        <div class="medals-grid">




                            <template x-for="i in achievements" :key="i">
                                <div class="medal-card">
                                    <div class="medal-wrapper">
                                        <div class="medal-coin">
                                            <div class="medal-text-ring">
                                                <img src="/ach.svg">
                                            </div>

                                        </div>
                                        <div class="question-badge" @click="openMedalModal(i)">?</div>
                                    </div>
                                    <div class="medal-label" x-text="shortText(i.title ?? i.text)"></div>
                                </div>
                            </template>

                        </div>
                        <br><br>
                        <!-- Medal Modal (Alpine-controlled) -->
                        <div class="modal-overlay" :class="{ active: showMedalModal }"
                            @click.self="showMedalModal = false">
                            <div class="medal-modal">
                                <button class="modal-close" @click="showMedalModal = false">&times;</button>
                                <div class="modal-title" x-text="medalModalTitle"></div>
                                <div class="modal-body" x-text="medalModalText"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-show="activeTab === 'balance'" x-transition>

                    <style>
                        .balance-wrap {
                            width: 100%;
                            max-width: 448px;
                            padding: 32px 20px 40px;
                            display: flex;
                            flex-direction: column;
                            align-items: center;
                        }

                        /* ── Title ── */
                        .balance-title {
                            font-size: 22px;
                            font-weight: 700;
                            color: #f1f5f9;
                            text-align: center;
                            margin-bottom: 22px;
                        }

                        /* ── Amount row ── */
                        .balance-row {
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            gap: 16px;
                            margin-bottom: 14px;
                        }

                        .coin-icon {


                            display: flex;
                            align-items: center;
                            justify-content: center;
                            color: #ef4444;
                            font-size: 14px;
                            font-weight: 700;
                            flex-shrink: 0;
                        }

                        .balance-amount {
                            font-size: 72px;
                            font-weight: 900;
                            color: #ff0000;
                            line-height: 1;
                            letter-spacing: -2px;
                        }

                        /* ── Subtitle ── */
                        .balance-subtitle {
                            font-size: 16px;
                            color: #94a3b8;
                            text-align: center;
                            margin-bottom: 28px;
                        }

                        /* ── Button ── */
                        .btn-topup {
                            width: 100%;
                            max-width: 340px;
                            padding: 18px;
                            background: #ff0000;
                            color: #fff;
                            border: none;
                            border-radius: 14px;
                            font-size: 18px;
                            font-weight: 600;
                            cursor: pointer;
                            transition: background 0.18s;
                            margin-bottom: 36px;
                        }

                        .btn-topup:hover {
                            background: #c91f1f;
                        }

                        /* ── Transactions card ── */
                        .transactions-card {
                            width: 100%;
                            background: #1c2333;
                            border-radius: 18px;
                            padding: 22px 20px 8px;
                        }

                        .transactions-title {
                            font-size: 20px;
                            font-weight: 700;
                            color: #f1f5f9;
                            text-align: center;
                            margin-bottom: 18px;
                        }

                        .transaction-row {
                            display: flex;
                            justify-content: space-between;
                            align-items: center;
                            padding: 14px 0;
                            border-bottom: 1px solid #2d3748;
                        }

                        .transaction-row:last-child {
                            border-bottom: none;
                        }

                        .tx-info {
                            display: flex;
                            flex-direction: column;
                            gap: 4px;
                        }

                        .tx-name {
                            font-size: 15px;
                            font-weight: 700;
                            color: #f1f5f9;
                        }

                        .tx-date {
                            font-size: 14px;
                            color: #64748b;
                        }

                        .tx-amount {
                            font-size: 30px;
                            font-weight: 700;
                        }

                        .tx-amount.positive {
                            color: #22c55e;
                        }

                        .tx-amount.negative {
                            color: #ef4444;
                        }
                    </style>
                    </head>

                    <body>
                        <div class="balance-wrap">

                            <div class="balance-title">Баланс серебра</div>

                            <div class="balance-row">
                                <div class="coin-icon"><img src="new/money.svg" alt="Coin"></div>
                                <div class="balance-amount" x-text="user.balance"></div>
                                <div class="coin-icon"><img src="new/money.svg" alt="Coin"></div>
                            </div>

                            <p class="balance-subtitle">Ваш текущий баланс игрового серебра</p>

                            <button class="btn-topup" id="topUpBalanceBtn">Пополнить</button>



                        </div>













                        <div class="balance-container">

                            <!-- Модальное окно 1: выбор количества серебра -->
                            <div id="coinsModal" class="modal">
                                <div class="modal-content">
                                    <span class="close">&times;</span>

                                    <div class="coins-options">
                                        <h3 style="line-height: 1">Сколько серебра хотите купить?</h3>
                                        <button class="coin-option" data-coins="500">500 Sil</button>
                                        <button class="coin-option" data-coins="1000">1000 Sil</button>
                                        <button class="coin-option" data-coins="1500">1500 Sil</button>
                                        <button class="coin-option" data-coins="2000">2000 Sil</button>
                                        <button class="coin-option" data-coins="3000">3000 Sil</button>
                                        <button class="coin-option" data-coins="10000">10000 Sil</button>
                                    </div>
                                    <button class="btn btn-confirm" id="confirmCoinsBtn">Подтвердить</button>
                                </div>
                            </div>

                            <!-- Модальное окно 2: подтверждение оплаты -->
                            <div id="paymentModal" class="modal">
                                <div class="modal-content">
                                    <span class="close">&times;</span>
                                    <h3>Оплата</h3>
                                    <p class="text-link">Перейдите по ссылке для оплаты:</p>
                                    <a href="#" id="paymentLink" class="payment-link" target="_blank">Ссылка на
                                        оплату</a>
                                    <p class="text-att">Чтобы оплата зафиксировалась, отправьте нам чек!</p>
                                    <a href="/orders" class="btn btn-send" id="sendReceiptBtn">Отправить чек</a>
                                </div>
                            </div>

                            <!-- Стили -->
                            <style>
                                .modal {
                                    display: none;
                                    /* Скрыто по умолчанию */
                                    position: fixed;
                                    z-index: 1000;
                                    left: 0;
                                    top: 0;
                                    width: 100%;
                                    height: 100%;
                                    background-color: rgba(0, 0, 0, 0.5);
                                    /* Полупрозрачный фон */
                                }

                                .modal-content {
                                    background-color: #1e293b;
                                    margin: 5% auto;
                                    padding: 20px;
                                    border-radius: 8px;
                                    width: 90%;
                                    max-width: 400px;
                                    position: relative;
                                }

                                .modal-content h3 {
                                    font-weight: 600;
                                    font-size: 36px;
                                }

                                .close {
                                    color: #aaa;
                                    float: right;
                                    font-size: 28px;
                                    font-weight: bold;
                                    cursor: pointer;
                                }

                                .close:hover {
                                    color: black;
                                }

                                /* Стили кнопок */
                                .btn {
                                    padding: 10px 20px;
                                    border: none;
                                    border-radius: 4px;
                                    cursor: pointer;
                                    font-size: 16px;
                                }

                                .btn-primary {
                                    border: 2px solid #b91c1c;
                                    padding: 12px 42px;
                                    color: white;
                                    font-weight: 400;
                                    background: transparent;
                                    margin-bottom: 40px;
                                    font-size: 15px;
                                    border-radius: 48px;
                                }

                                .btn-confirm {
                                    background-color: transparent;
                                    color: white;
                                    width: 100%;
                                    margin-top: 15px;
                                    border: 3px solid #b91c1c;
                                    border-radius: 40px;
                                }

                                .btn-send {
                                    display: block;
                                    padding: 12px 45px;
                                    border: 4px solid #5cda1d;
                                    border-radius: 44px;
                                    text-decoration: none;
                                    color: #5cda1d;
                                    background: transparent;
                                    font-weight: 900;
                                    margin: 25px 0;
                                }

                                .coin-option {
                                    display: block;
                                    width: 40%;
                                    padding: 12px;
                                    margin: 5px 0;
                                    background: red;
                                    border: 1px solid #ffffff;
                                    border-radius: 4px;
                                    text-align: center;
                                    color: #ffffff;
                                    font-weight: 600;
                                }

                                .coin-option:hover {
                                    background: #e9ecef;
                                }

                                .payment-link {
                                    display: inline-block;
                                    padding: 10px 35px;
                                    border: 2px solid #ff0000;
                                    border-radius: 44px;
                                    text-decoration: none;
                                    color: #ffffff;
                                    font-weight: 600;
                                    margin: 25px 0;
                                }

                                .coins-options {
                                    margin: 20px 0;
                                    display: flex;
                                    flex-wrap: wrap;
                                    justify-content: center;
                                    gap: 10px;
                                }

                                .coins-options h3 {
                                    font-size: 26px;
                                }

                                .payment-link:hover {
                                    background: #007bff;
                                    color: #fff;
                                }
                            </style>

                            <!-- Скрипт логики окон -->
                            <script>
                                // Элементы DOM
                                const topUpBalanceBtn = document.getElementById('topUpBalanceBtn');
                                const coinsModal = document.getElementById('coinsModal');
                                const paymentModal = document.getElementById('paymentModal');
                                const closeButtons = document.querySelectorAll('.close');
                                const coinOptions = document.querySelectorAll('.coin-option');
                                const confirmCoinsBtn = document.getElementById('confirmCoinsBtn');
                                const sendReceiptBtn = document.getElementById('sendReceiptBtn');
                                const paymentLink = document.getElementById('paymentLink');

                                // Выбранное количество серебра
                                let selectedCoins = null;

                                // Открыть модальное окно выбора серебра по кнопке (если она есть на странице)
                                if (topUpBalanceBtn) {
                                    topUpBalanceBtn.addEventListener('click', () => {
                                        coinsModal.style.display = 'block';
                                    });
                                }

                                // Закрыть модальные окна по крестику
                                closeButtons.forEach(btn => {
                                    btn.addEventListener('click', () => {
                                        coinsModal.style.display = 'none';
                                        paymentModal.style.display = 'none';
                                    });
                                });

                                // Выбрать количество серебра
                                coinOptions.forEach(option => {
                                    option.addEventListener('click', () => {
                                        // Снимаем выделение с других кнопок
                                        coinOptions.forEach(opt => opt.style.backgroundColor = '');
                                        // Выделяем выбранную
                                        option.style.backgroundColor = '#d4edda';
                                        // Сохраняем значение
                                        selectedCoins = parseInt(option.getAttribute('data-coins'));
                                    });
                                });

                                // Подтвердить выбор серебра → открыть окно оплаты
                                confirmCoinsBtn.addEventListener('click', () => {
                                    if (selectedCoins) {
                                        let payUrl = '';

                                        switch (Number(selectedCoins)) {
                                            case 500:
                                                payUrl = 'https://secure.atolpay.ru/links/VqVzhxh7vGlPZQMo';
                                                break;
                                            case 1000:
                                                payUrl = 'https://secure.atolpay.ru/links/3kIH46rBwxFj8uZ1';
                                                break;
                                            case 2000:
                                                payUrl = 'https://secure.atolpay.ru/links/YZprY3hXZwO3AIA7';
                                                break;
                                            case 3000:
                                                payUrl = 'https://secure.atolpay.ru/links/AHfH3BCzyXjf0EfM';
                                                break;
                                            case 5000:
                                                payUrl = 'https://secure.atolpay.ru/links/ipccvRe1yJJADLP5';
                                                break;
                                            case 10000:
                                                payUrl = 'https://secure.atolpay.ru/links/7t0iiOs8Vten1Qxn';
                                                break;
                                            default:
                                                // Ссылка по умолчанию, если количество монет нестандартное
                                                payUrl = `/pay?coins=${selectedCoins}`;
                                        }

                                        // Присваиваем сформированную ссылку в href
                                        paymentLink.href = payUrl;

                                        // Переключаем модальные окна
                                        coinsModal.style.display = 'none';
                                        paymentModal.style.display = 'block';
                                    } else {
                                        alert('Пожалуйста, выберите количество серебра!');
                                    }
                                });

                                // Нажатие кнопки "Прислать"
                                sendReceiptBtn.addEventListener('click', () => {
                                    paymentModal.style.display = 'none';
                                });

                                // Закрытие модальных окон при клике вне их области
                                window.addEventListener('click', (event) => {
                                    if (event.target === coinsModal) {
                                        coinsModal.style.display = 'none';
                                    }
                                    if (event.target === paymentModal) {
                                        paymentModal.style.display = 'none';
                                    }
                                });

                                // --- ЛОГИКА ДЛЯ LARAVEL URL ПАРАМЕТРОВ ---
                                window.addEventListener('DOMContentLoaded', () => {
                                    const urlParams = new URLSearchParams(window.location.search);

                                    // Если в адресе есть параметр topup (например: ?topup или ?topup=1500)
                                    if (urlParams.has('topup') || urlParams.get('open') === 'modal') {
                                        if (coinsModal) {
                                            coinsModal.style.display = 'block';
                                        }

                                        const topupValue = urlParams.get('topup');
                                        if (topupValue) {
                                            // Ищем кнопку с нужным количеством серебра
                                            const targetOption = document.querySelector(`.coin-option[data-coins="${topupValue}"]`);

                                            if (targetOption) {
                                                // Снимаем выделение со всех
                                                coinOptions.forEach(opt => opt.style.backgroundColor = '');
                                                // Выделяем текущую
                                                targetOption.style.backgroundColor = '#d4edda';
                                                // Сохраняем значение в выбранные монеты
                                                selectedCoins = parseInt(topupValue);
                                            }
                                        }
                                    }
                                });
                            </script>







                            <!-- История транзакций (Alpine.js) -->
                            <div class="transactions">
                                <h4 class="transactions-title">История транзакций</h4>

                                <template x-for="transaction in transactions" :key="transaction.id">
                                    <div class="transaction-item">
                                        <div>
                                            <div class="transaction-desc" x-text="transaction.comment"></div>
                                            <div class="transaction-date" x-text="transaction.date"></div>
                                        </div>
                                        <div class="transaction-amount"
                                            :class="{
                                                'positive': transaction.amount.startsWith('+'),
                                                'negative': transaction.amount
                                                    .startsWith('-')
                                            }"
                                            x-text="transaction.amount"></div>
                                    </div>
                                </template>
                                <br><br><br>
                            </div>
                        </div>











                </div>
            </div>

            <!-- Модальное окно привязки Telegram (внутри x-data контейнера) -->
            <div class="tg-modal" x-show="showTelegramModal" x-cloak @click.self="closeTelegramModal()">
                <div class="modal-content">
                    <span class="close" @click="closeTelegramModal()">&times;</span>
                    <h3 style="font-size: 20px; font-weight: 600; margin-bottom: 4px;">Привязка Telegram</h3>
                    <p style="font-size: 13px; color: #9ca3af; margin-bottom: 12px;">Выполните шаги ниже, чтобы привязать
                        аккаунт</p>

                    <div class="telegram-modal-step">
                        <p>1. Перейдите в нашего Telegram-бота и нажмите <strong style="color: #f8fafc;">Старт</strong></p>
                        <a href="https://t.me/kashevsky_studio_bot?start={{ $account->id }}" target="_blank"
                            rel="noopener noreferrer" class="telegram-bot-link">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z" />
                            </svg>
                            Открыть бота
                        </a>
                    </div>



                    <p class="telegram-hint">После отправки кода страница обновится автоматически. Если этого не произошло
                        — обновите вручную.</p>
                </div>
            </div>
        </div>

        <script>
            function mafiaPlayer() {
                return {
                    activeTab: new URLSearchParams(window.location.search).get('open') === 'modal' ?
                        'balance' : 'profile',
                    isEditing: false,
                    showTelegramModal: false,
                    showMedalModal: false,
                    medalModalTitle: '',
                    medalModalText: '',
                    telegramLinked: {{ $account->telegram_id ? 'true' : 'false' }},
                    achievements: @json($account->achievements),
                    telegramUsername: '{{ $account->telegram_username ?? '' }}',
                    telegramCode: '{{ $account->telegram_token ?? \Illuminate\Support\Str::random(12) }}',
                    player: {
                        avatar: '{{ $account->avatar ?? '' }}',
                        nickname: '{{ !empty($account->nickname) ? $account->nickname : $account->name }}',
                        birthday: '{{ $account->birth ?? '' }}',
                        name: '{{ $account->name ?? '' }}',
                        bio: '{{ $account->bio ?? '' }}',
                        gender: '{{ $account->gender ?? '' }}',
                        email: '{{ $account->email ?? '' }}',
                        points: {{ $account->rating ?? 0 }},
                        messenger: '{{ $account->messenger ?? '' }}',
                        totalPoints: {{ $account->balance ?? 0 }},
                        monthPoints: 0,
                        averageScore: 0,
                        winRate: 0,
                        firstGameDate: '{{ $account->created_at ?? '' }}',
                        gamesAsMafia: 45,
                        gamesAsCivilian: 78,
                        gamesAsWerewolf: 12,
                        balance: {{ $account->balance ?? 0 }},
                        password: '{{ $account->password ?? '' }}',
                    },
                    transactions: @json($account->transactions),
                    chart: null,

                    init() {

                    },
                    naming() {
                        document.getElementById('user-name').innerHTML = this.player.nickname;

                    },

                    // Обработка загрузки аватара
                    handleAvatarUpload(event) {
                        const file = event.target.files[0];
                        if (file && file.type.match(/image.*/)) {
                            this.selectedAvatar = file;
                            // Создаём предварительный просмотр
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                this.player.avatar = e.target.result;
                                document.getElementById('avatar').src = e.target.result;
                            };
                            reader.readAsDataURL(file);

                            // После присвоения



                        } else {
                            alert('Пожалуйста, выберите изображение');
                        }
                    },
                    setDefaultAvatar(event) {
                        event.target.src = '/images/default-avatar.png';
                    },


                    initChart() {
                        const ctx = document.getElementById('gamesChart');
                        // Проверяем, что элемент существует и библиотека Chart.js загружена
                        if (ctx && typeof Chart !== 'undefined') {
                            // Уничтожаем старую диаграмму, если она есть
                            if (this.chart) {
                                this.chart.destroy();
                            }
                            this.chart = new Chart(ctx, {
                                type: 'pie',
                                data: {
                                    labels: ['Мафия', 'Мирные', 'Оборотень'],
                                    datasets: [{
                                        data: [
                                            this.player.gamesAsMafia,
                                            this.player.gamesAsCivilian,
                                            this.player.gamesAsWerewolf
                                        ],
                                        backgroundColor: ['#ef4444', '#3b82f6', '#8b5cf6'],
                                        borderWidth: 2,
                                        borderColor: '#1e293b'
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: {
                                            position: 'bottom',
                                            labels: {
                                                padding: 15,
                                                font: {
                                                    size: 12,
                                                    color: '#f8fafc' // белый цвет текста легенды
                                                }
                                            }
                                        }
                                    }
                                }
                            });

                        } else {
                            console.warn('Canvas с id="gamesChart" не найден или Chart.js не загружен');
                        }
                    },

                    totalGames() {
                        return this.player.gamesAsMafia + this.player.gamesAsCivilian + this.player.gamesAsWerewolf;
                    },

                    formatDate(dateString) {
                        try {
                            return new Date(dateString).toLocaleDateString('ru-RU');
                        } catch (error) {
                            console.error('Ошибка форматирования даты:', error);
                            return dateString;
                        }
                    },

                    getExperience() {
                        try {
                            const firstDate = new Date(this.player.firstGameDate);
                            const now = new Date();
                            // Корректный расчёт разницы в днях
                            const diffTime = Math.abs(now - firstDate);
                            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                            const years = Math.floor(diffDays / 365);
                            const months = Math.floor((diffDays % 365) / 30);

                            if (years > 0) {
                                const yearWord = years === 1 ? 'год' : years < 5 ? 'года' : 'лет';
                                const monthWord = months === 1 ? 'месяц' : months < 5 ? 'месяца' : 'месяцев';
                                return `${years} ${yearWord} ${months} ${monthWord}`;
                            }

                            const monthWord = months === 1 ? 'месяц' : months < 5 ? 'месяца' : 'месяцев';
                            return `${months} ${monthWord}`;
                        } catch (error) {
                            console.error('Ошибка расчёта стажа:', error);
                            return 'Неизвестно';
                        }
                    },

                    openMedalModal(achievement) {
                        this.medalModalTitle = achievement.name ?? '';
                        this.medalModalText = achievement.text ?? '';
                        this.showMedalModal = true;
                    },

                    shortText(str) {
                        if (!str) return '';
                        const words = str.trim().split(/\s+/);
                        if (words.length <= 5) return str;
                        return words.slice(0, 5).join(' ') + '…';
                    },

                    openTelegramModal() {
                        this.showTelegramModal = true;
                    },

                    closeTelegramModal() {
                        this.showTelegramModal = false;
                    },

                    unlinkTelegram() {
                        if (!confirm('Отвязать Telegram от профиля?')) return;

                        fetch('/account/telegram/unlink', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    this.telegramLinked = false;
                                    this.telegramUsername = '';
                                    alert('Telegram успешно отвязан');
                                } else {
                                    alert(data.message ?? 'Произошла ошибка');
                                }
                            })
                            .catch(() => alert('Ошибка соединения'));
                    },

                    saveProfile() {
                        this.isEditing = false;
                        const formData = new FormData();

                        // Добавляем все поля игрока
                        Object.keys(this.player).forEach(key => {
                            if (key !== 'avatar' && key !== 'selectedAvatar') {
                                formData.append(key, this.player[key]);
                            }
                        });

                        // Добавляем аватар, если выбран новый
                        if (this.selectedAvatar) {
                            formData.append('avatar', this.selectedAvatar);
                        }



                        fetch('/account/update', {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                console.log('Профиль сохранён', data);

                                // Берем ссылку на аватар из ответа сервера (подставьте ваш ключ, если он другой, например data.account.avatar)
                                if (data && data.avatar) {
                                    document.getElementById('user-avatar').src = data.avatar;
                                }
                            })
                            .catch(error => {
                                console.error('Ошибка при сохранении профиля:', error);
                                // В случае ошибки можно поставить заглушку (placeholder) или оставить как есть
                                // document.getElementById('user-avatar').src = '/images/default-avatar.png';
                            });

                    }

                };
            }
        </script>
        @include('include.club.bottom-nav')
    @endsection
