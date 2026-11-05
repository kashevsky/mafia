@extends('layouts.club')

@section('content')
    <div x-data="shopPage()">
        <div class="profile-wrap">
            @include('include.club.header')
            <div class="tabs-header">
                <button class="tab-button" :class="{ 'active': activeTab === 'merch' }" @click="activeTab = 'merch'">
                    Мерч
                </button>
                <button class="tab-button" :class="{ 'active': activeTab === 'probabilities' }"
                    @click="activeTab = 'probabilities'">
                    Вероятности
                </button>
                <button class="tab-button" :class="{ 'active': activeTab === 'gifts' }" @click="activeTab = 'gifts'">
                    Подарки
                </button>
                <button class="tab-button" :class="{ 'active': activeTab === 'immunities' }"
                    @click="activeTab = 'immunities'">
                    Иммунитеты
                </button>
            </div>
        </div>
        <style>
            root {
                --primary-color: #EA0101;
            }

            body {
                background-color: black !important;
            }

            .user-bar {
                padding-bottom: 40px;
            }

            .profile-wrap {
                position: relative;
            }

            .tabs-header {
                display: flex;
                align-items: center;
                /* Выравнивает элементы по центру и убирает stretch */
                background: var(--primary-color);

                width: 95% !important;
                margin: 1px auto;
                position: absolute;
                bottom: -30px;
                left: 50%;
                z-index: 2;
                transform: translateX(-50%);
                border-radius: 8px;
            }

            .container {
                max-width: 448px;
                margin: 0 auto;

            }






            .tabs-header {
                display: flex;
                background: var(--primary-color) !important;
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

            .tab-button {
                flex: 1;
                padding: 10px 6px;
                border: none;
                background: none;
                cursor: pointer;
                color: #f8f8f8;
                font-size: 13px;
                font-weight: 300;
                border-radius: 6px;
                transition: all 0.2s;
            }

            .tab-button.active {
                background: #000000;
                color: #ffffff;
            }

            .tab-button:hover {
                opacity: 0.8;
            }

            /* Content */
            .content {
                padding: 16px;

                background: black;
            }



            .cart-badge {
                position: absolute;
                top: 2px;
                right: 2px;
                background: #fbbf24;
                color: #1f2937;
                width: 18px;
                height: 18px;
                border-radius: 50%;
                font-size: 11px;
                font-weight: bold;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            /* Balance Banner */
            .balance-banner {
                background: linear-gradient(135deg, #1a1b26 0%, #2d3748 100%);
                padding: 20px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                border: 1px solid #475569;
                border-radius: 12px;
                margin: 16px;
            }

            .balance-info {
                display: flex;
                align-items: center;
                gap: 12px;
            }

            .coin-icon {
                width: 48px;
                height: 48px;
                background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 24px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.4);
            }

            .balance-text {
                color: #fbbf24;
            }

            .balance-label {
                font-size: 12px;
                font-weight: 500;
                color: #94a3b8;
            }

            .balance-amount {
                font-size: 28px;
                font-weight: bold;
                color: #f1f5f9;
            }

            .topup-btn {
                background: #dc2626;
                color: white;
                border: none;
                padding: 10px 20px;
                border-radius: 8px;
                font-size: 14px;
                font-weight: 600;
                cursor: pointer;
            }

            .topup-btn:hover {
                background: #b91c1c;
            }


            /* Product Grid */
            .product-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
                padding-top: 30px;

            }

            .product-card {
                background: #2d3748;
                border: 1px solid #475569;
                border-radius: 12px;
                padding: 12px;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
                transition: all 0.2s;
            }

            .product-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
            }

            .product-image {
                width: 100%;
                height: 120px;
                background: #334155;
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 48px;
                margin-bottom: 8px;
                color: #9ca3af;
                object-fit: contain;
                padding: 5px 0;
            }

            .product-name {
                font-size: 14px;
                font-weight: 600;
                color: #f1f5f9;
                margin-bottom: 4px;
            }

            .product-desc {
                font-size: 11px;
                color: #94a3b8;
                margin-bottom: 8px;
                line-height: 1.4;
            }

            .product-price {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 8px;
            }

            .price-amount {
                font-size: 16px;
                font-weight: bold;
                color: #fbbf24;
                display: flex;
                align-items: center;
                gap: 4px;
            }

            .coin-small {
                color: #fbbf24;
            }

            .buy-btn {
                width: 100%;
                background: #dc2626;
                color: white;
                border: none;
                padding: 8px;
                border-radius: 8px;
                font-size: 13px;
                font-weight: 600;
                cursor: pointer;
                transition: background 0.2s;
            }

            .buy-btn:hover {
                background: #b91c1c;
            }

            .buy-btn:disabled {
                background: #475569;
                color: #64748b;
                cursor: not-allowed;
            }

            /* List View (for probabilities and gifts) */
            .item-list {
                display: grid;
                grid-template-columns: repeat(2, 1fr);

                gap: 12px;
            }

            .item-card {
                background: #2d3748;
                border: 1px solid #475569;
                border-radius: 12px;
                padding: 16px;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
                transition: all 0.2s;
            }

            .item-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
            }

            .item-header {
                display: flex;
                align-items: center;
                flex-direction: column;
                gap: 12px;
                margin-bottom: 12px;
            }

            .item-icon {
                width: 76px;
                height: 76px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 32px;
                flex-shrink: 0;
            }

            .item-icon.red {
                background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
                color: #dc2626;
            }

            .item-icon.blue {
                background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
                color: #3b82f6;
            }

            .item-icon.purple {
                background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%);
                color: #a855f7;
            }

            .item-icon.yellow {
                background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
                color: #d97706;
            }

            .item-icon.green {
                background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
                color: #10b981;
            }

            .item-info {
                flex: 1;
                display: flex;
                flex-direction: column;
            }

            .item-title {
                font-size: 16px;
                font-weight: 600;
                color: #f1f5f9;
                margin-bottom: 4px;
            }

            .item-desc {
                font-size: 13px;
                color: #94a3b8;
                line-height: 1.4;
            }

            .item-footer {
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .item-price {
                font-size: 18px;
                font-weight: bold;
                color: #fbbf24;
                display: flex;
                align-items: center;
                gap: 6px;
            }

            .item-btn {
                background: #dc2626;
                color: white;
                border: none;
                padding: 10px 24px;
                border-radius: 8px;
                font-size: 14px;
                font-weight: 600;
                cursor: pointer;
                transition: background 0.2s;
            }

            .item-btn:hover {
                background: #b91c1c;
            }

            .item-btn:disabled {
                background: #475569;
                color: #64748b;
                cursor: not-allowed;
            }

            /* Badge */
            .badge {
                display: inline-block;
                padding: 4px 8px;
                border-radius: 6px;
                font-size: 11px;
                font-weight: 600;
            }

            .badge.limited {
                background: #374151;
                color: #fbbf24;
            }

            .badge.new {
                background: #2563eb;
                color: white;
            }

            .badge.popular {
                background: #dc2626;
                color: white;
            }

            /* Probability Bar */
            .prob-bar {
                margin-top: 8px;
            }

            .prob-label {
                font-size: 12px;
                color: #94a3b8;
                margin-bottom: 4px;
                display: flex;
                justify-content: space-between;
            }

            .prob-current {
                color: #f1f5f9;
                font-weight: 600;
            }

            .prob-progress {
                background: #334155;
                height: 6px;
                border-radius: 3px;
                overflow: hidden;
            }

            .prob-fill {
                height: 100%;
                background: linear-gradient(90deg, #ef4444 0%, #dc2626 100%);
                transition: width 0.3s;
            }

            /* Section Title */
            .section-title {
                font-size: 16px;
                font-weight: 600;
                color: #f1f5f9;
                margin-bottom: 12px;
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .section-icon {
                width: 20px;
                height: 20px;
                color: #fbbf24;
            }

            /* Gift Info Banner */
            .gift-info-banner {
                margin-bottom: 16px;
                padding: 12px;
                background: #374151;
                border-radius: 8px;
                font-size: 13px;
                color: #fbbf24;
                border-left: 4px solid #fbbf24;
            }

            /* Модальное окно */
            .modal-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.7);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 1000;
            }

            .modal {
                background: #1e2433;

                border-radius: 16px;
                width: 90%;
                max-width: 400px;

            }

            .modal-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                font-size: 16px;

                padding: 16px 20px 8px;
                flex-direction: row-reverse;
            }

            .modal-title {
                font-size: 24px;
                font-weight: 600;
                color: white;
                font-weight: 700;
                text-align: center;
                margin: 1px auto;
                margin-bottom: 15px;
            }

            .modal-close {
                background: none;
                border: none;
                cursor: pointer;
                color: #94a3b8;
            }

            .modal-body {
                padding: 0;
            }

            .modal-body p {
                font-size: 14px;
                color: white;
                font-weight: 300;
                text-align: center;

            }

            .modal-coins {

                color: white;
                text-align: center;
                margin: 10px 0;
                display: flex;
                justify-content: center;
                gap: 10px;
            }

            .modal-coins span {
                font-size: 28px;
                color: red;
            }

            .modal-footer {
                display: flex;
                gap: 12px;
                padding: 8px 20px 20px;

            }

            .btn-cancel,
            .btn-confirm {
                flex: 1;
                padding: 12px;
                border: none;
                border-radius: 8px;
                font-size: 14px;
                font-weight: 600;
                cursor: pointer;
            }

            .btn-cancel {
                background: #55657b;
                color: #fefeff;
            }

            .btn-confirm {
                background: red;
                color: white;
            }

            .btn-cancel:hover {
                background: #475569;
            }

            .btn-confirm:hover {
                background: #b91c1c;
            }

            /* Уведомление об ошибке */
            .error-notification {
                position: fixed;
                bottom: 20px;
                left: 50%;
                transform: translateX(-50%);
                background: #dc2626;
                color: white;
                padding: 12px 20px;
                border-radius: 8px;
                display: flex;
                align-items: center;
                gap: 8px;
                z-index: 1100;
                animation: slideUp 0.3s ease;
            }

            .error-content {
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .topup-link {
                color: #fbbf24;
                text-decoration: underline;
                margin-left: 4px;
            }

            @keyframes slideUp {
                from {
                    transform: translateX(-50%) translateY(100px);
                    opacity: 0;
                }

                to {
                    transform: translateX(-50%) translateY(0);
                    opacity: 1;
                }
            }

            /* Метка купленного товара */
            .purchased-badge {
                position: absolute;
                top: 8px;
                right: 8px;
                background: #10b981;
                color: white;
                width: 60px;
                height: 24px;
                border-radius: 12px;
                font-size: 10px;
                font-weight: bold;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            /* ══════════════════════════════════════
                                                                                       Стили для вкладки Вероятности (новые)
                                                                                    ══════════════════════════════════════ */
            .prob-list {
                display: flex;
                flex-direction: column;
                gap: 16px;
            }

            .prob-card {
                background: #1e2433;
                border-radius: 18px;
                padding: 22px 20px;
                overflow: hidden;
            }

            .prob-card-header {
                color: #fff;
                font-size: 18px;
                font-weight: 700;
                margin-bottom: 18px;
            }

            .prob-card-title {
                font-size: 16px;
                font-weight: 700;
                color: #f1f5f9;
            }

            .prob-card-desc {
                font-size: 12px;
                color: #94a3b8;
                margin-top: 3px;
                line-height: 1.4;
            }

            .purchased-tag {
                background: #059669;
                color: #fff;
                font-size: 10px;
                font-weight: 700;
                padding: 3px 9px;
                border-radius: 20px;
                white-space: nowrap;
                flex-shrink: 0;
            }

            .slider-wrap {
                position: relative;

            }

            .slider-track {
                display: flex;
                gap: 10px;
                overflow-x: auto;
                scroll-snap-type: x mandatory;
                scroll-behavior: smooth;
                scrollbar-width: none;
                padding: 4px 16px;
            }

            .slider-track::-webkit-scrollbar {
                display: none;
            }

            .role-slide {
                flex-shrink: 0;
                width: 88px;
                scroll-snap-align: start;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 6px;
                cursor: pointer;
            }

            .role-img-wrap {
                width: 80px;
                height: 80px;
                border-radius: 12px;
                /* background: #1e293b; */
                /* border: 2px solid #334155; */
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 34px;
                overflow: hidden;
                transition: border-color 0.2s, transform 0.15s;
            }

            .role-img-wrap img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .role-slide.active .role-img-wrap {
                border-color: #fbbf24;
                transform: scale(1.06);
            }

            .role-name {
                font-size: 11px;
                color: #94a3b8;
                text-align: center;
                line-height: 1.3;
                transition: color 0.2s;
            }

            .role-slide.active .role-name {
                color: #fbbf24;
                font-weight: 600;
            }

            .slider-nav {
                position: absolute;
                top: 44px;
                transform: translateY(-50%);
                background: rgba(15, 23, 42, 0.85);
                border: 1px solid #475569;
                color: #f1f5f9;
                width: 26px;
                height: 26px;
                border-radius: 50%;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 16px;
                z-index: 2;
                transition: background 0.15s;
            }

            .slider-nav:hover:not([disabled]) {
                background: #dc2626;
                border-color: #dc2626;
            }

            .slider-nav[disabled] {
                opacity: 0.25;
                cursor: default;
            }

            .slider-nav.prev {
                left: -18px;
            }

            .slider-nav.next {
                right: -18px;
            }

            .prob-bar-wrap {
                margin: 0 16px 12px;
            }

            .prob-bar-labels {
                display: flex;
                justify-content: space-between;
                font-size: 11px;
                color: #64748b;
                margin-bottom: 5px;
            }

            .prob-bar-labels .val {
                color: #fbbf24;
                font-weight: 700;
            }

            .prob-bar-track {
                height: 6px;
                background: #1e293b;
                border-radius: 3px;
                overflow: hidden;
            }

            .prob-bar-fill {
                height: 100%;
                border-radius: 3px;
                background: linear-gradient(90deg, #b91c1c, #ef4444);
                transition: width 0.4s ease;
            }




            [x-cloak] {
                display: none !important;
            }

            /* Info popup для вероятностей */
            .prob-section-header {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                margin-bottom: 16px;
            }

            .prob-section-title {
                font-size: 16px;
                font-weight: 600;
                color: #f1f5f9;
            }

            .info-btn {
                width: 22px;
                height: 22px;
                border-radius: 50%;
                background: #334155;
                border: 1px solid #475569;
                color: #94a3b8;
                font-size: 13px;
                font-weight: 700;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
                transition: background 0.15s, color 0.15s;
                line-height: 1;
            }

            .info-btn:hover {
                background: #475569;
                color: #f1f5f9;
            }

            .info-overlay {
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.6);
                z-index: 1200;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 24px;
            }

            .info-popup {
                background: #1e293b;
                border: 1px solid #475569;
                border-radius: 14px;
                padding: 20px;
                max-width: 380px;
                width: 100%;
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.5);
            }

            .info-popup-header {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 12px;
                margin-bottom: 14px;
            }

            .info-popup-title {
                font-size: 15px;
                font-weight: 700;
                color: #fbbf24;
                line-height: 1.4;
            }

            .info-popup-close {
                background: none;
                border: none;
                color: #64748b;
                cursor: pointer;
                font-size: 20px;
                line-height: 1;
                padding: 0;
                flex-shrink: 0;
            }

            .info-popup-close:hover {
                color: #f1f5f9;
            }

            .info-popup-body {
                font-size: 13px;
                color: #cbd5e1;
                line-height: 1.65;
            }
        </style>

        <div class="container">


            <style>
                .event-card {
                    background: #1e2433;
                    border-radius: 16px;
                    overflow: hidden;
                    display: flex;
                    flex-direction: column;
                    margin-bottom: 16px;
                    position: relative;
                    justify-content: center;
                    align-items: center;
                    padding-bottom: 40px;

                }



                .event-card .card-info {
                    flex: 1;
                    display: flex;
                    flex-direction: column;
                    padding-right: 16px;
                }

                .event-card .card-date {
                    font-size: 24px;
                    font-weight: 700;
                    color: #ff0000;
                    letter-spacing: 0.5px;
                    margin-bottom: 8px;
                }

                .event-card .card-title {
                    font-size: 22px;
                    font-weight: 700;
                    color: #f0f2f5;
                    line-height: 1.2;
                    margin-bottom: 20px;
                }

                .event-card .card-meta {
                    display: flex;
                    flex-direction: column;
                }

                .event-card .meta-row {
                    display: flex;
                    align-items: flex-start;
                    gap: 10px;
                    padding: 12px 0;
                    color: #d1d5db;
                    font-size: 14px;
                    line-height: 1.4;
                }

                .event-card .meta-row+.meta-row {
                    border-top: 1px solid rgba(255, 255, 255, 0.1);
                }

                .event-card .meta-icon {
                    width: 20px;
                    height: 20px;
                    flex-shrink: 0;
                    margin-top: 1px;
                }

                .event-card .card-image {
                    width: 150px;
                    flex-shrink: 0;

                    border-radius: 12px;
                    overflow: hidden;
                    margin-right: 20px;
                }

                .event-card .card-image img {
                    width: 100%;
                    height: 220px;
                    object-fit: cover;
                    object-position: top center;
                    display: block;
                    background: #2d3748;
                }

                .event-card .card-footer {
                    padding: 0 24px 24px;
                    position: absolute;
                    bottom: -25px;
                }

                .event-card .btn-details {
                    display: block;
                    width: 100%;
                    padding: 14px;
                    background: #ff0000;
                    color: #fff;
                    font-size: 14px;
                    font-weight: 200 !important;

                    text-align: center;
                    text-decoration: none;
                    border: none;
                    border-radius: 5px 5px 0 0;
                    padding: 4px 30px 5px 30px;
                    cursor: pointer;
                    transition: background 0.2s;
                }

                .event-card .btn-details:hover {
                    background: #c01515;
                }

                /* футер */
                .card-footer {
                    display: flex;
                    align-items: center;
                    margin-top: 2px;
                    padding: 16px 0;
                    gap: 16px;
                    position: relative;
                }

                .price-block {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    flex-shrink: 0;

                }

                .coin-icon {
                    width: 26px;
                    height: 26px;
                    border-radius: 50%;
                    border: 2.5px solid #e02020;
                    color: #e02020;
                    font-size: 11px;
                    font-weight: 800;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                .price-value {
                    color: #ff0000;
                    font-size: 24px;
                    font-weight: 800;
                }

                .divider {
                    flex: 1;
                    height: 1px;
                    background: #3e4156;
                }

                .buy-btn {
                    background: #e02020;
                    color: #fff;
                    border: none;
                    border-radius: 10px;
                    padding: 12px 36px;
                    font-size: 17px;
                    font-weight: 700;
                    cursor: pointer;
                    transition: background 0.2s, transform 0.1s;
                    flex-shrink: 0;
                }

                .buy-btn__ {
                    background: #ff0000;
                    position: absolute;
                    right: 20px;
                    top: 57px;

                    color: #fff;
                    border: none;
                    border-radius: 9px 9px 0 0;
                    padding: 9px 36px;
                    font-size: 14px;
                    font-weight: 300;
                    cursor: pointer;
                    transition: background 0.2s, transform 0.1s;
                    flex-shrink: 0;

                    width: 40%;
                }

                .buy-btn__:hover:not([disabled]):not(.bought) {
                    background: #c41818;
                }

                .buy-btn__:active {
                    transform: scale(0.97);
                }

                .buy-btn__[disabled] {
                    background: #475569;
                    color: #94a3b8;
                    cursor: not-allowed;
                }

                .buy-btn__.bought {
                    background: #059669;
                    color: white;
                    cursor: default;
                }

                .btn-details[disabled] {
                    background: #475569;
                    color: #94a3b8;
                    cursor: not-allowed;
                }

                .btn-details.bought {
                    background: #059669;
                    color: white;
                    cursor: default;
                }

                /* градиенты карточек */
                .bg-detective {
                    background: linear-gradient(160deg, #2a2a3e, #4a3060);
                }

                .bg-doctor {
                    background: linear-gradient(160deg, #1e3828, #3a6040);
                }

                .bg-werewolf {
                    background: linear-gradient(160deg, #2a2e42, #3a3e56);
                }

                .bg-sheriff {
                    background: linear-gradient(160deg, #1e2a3c, #2e4060);
                }

                .bg-maniac {
                    background: linear-gradient(160deg, #3a1a1a, #601a1a);
                }

                .bg-don {
                    background: linear-gradient(160deg, #2a1e10, #50380a);
                }

                .bg-mafia {
                    background: linear-gradient(160deg, #1e1020, #3a1a40);
                }
            </style>


            <!-- Content -->
            <div class="content mt-40">
                <!-- Мерч -->
                <style>
                    .card-image_ {
                        width: 110px;
                        padding: 10px 0;
                        flex-shrink: 0;

                        border-radius: 12px;
                        overflow: hidden;

                        text-align: center;
                    }

                    .card-image_ img {
                        width: 100%;
                        max-height: 200px;
                        text-align: center;
                        height: auto;
                        object-fit: cover;
                        object-position: top center;
                        display: block;

                    }

                    .card-body {
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        justify-content: center;

                    }

                    .card-h2 {
                        text-align: center;
                        font-weight: 300;
                        font-size: 14px;
                    }

                    .card-price {
                        text-align: center;
                        font-size: 16px;
                        font-weight: 600;
                        color: red;
                    }

                    .mt-40 {
                        margin-top: 30px;
                    }
                </style>
                <div x-show="activeTab === 'merch'" x-transition>
                    <div class="product-grid">
                        <template x-for="item in merch" :key="item.id">
                            <div class="event-card" :class="{ 'purchased': item.purchased }">
                                <div class="card-body" style="position: relative;">
                                    <div class="card-image_">
                                        <img :src="item.src" :alt="item.title">
                                    </div>
                                    <h2 class="card-h2" x-text="item.title"></h2>
                                    <div style="display: flex; align-items: center; gap: 4px">
                                        {{-- <img src="/new/money.svg"> --}}

                                        <p class="card-price" x-text="item.price"></p>
                                        <span style="font-size: 14px">₽</span>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button class="btn-details" :class="{ bought: item.purchased }"
                                        :disabled="item.purchased" @click="openConfirmModal(item)"
                                        x-text="item.purchased ? 'Куплено' : 'Купить'">
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                    <br><br>
                </div>
                <!-- Вероятности — исправлено -->
                <div x-show="activeTab === 'probabilities'" x-transition x-data="{ showInfo: false }">

                    <div class="prob-section-header">
                        <span class="prob-section-title">Вероятности</span>
                        <button class="info-btn" @click="showInfo = true">?</button>
                    </div>

                    <!-- Info popup -->
                    <div class="info-overlay" x-show="showInfo" x-cloak @click.self="showInfo = false">
                        <div class="info-popup">
                            <div class="info-popup-header">
                                <div class="info-popup-title">Что такое вероятности и как они работают?</div>
                                <button class="info-popup-close" @click="showInfo = false">✕</button>
                            </div>
                            <div class="info-popup-body">
                                Пакет вероятностей — платная опция, которую игрок может приобрести перед началом
                                определенной игры, чтобы выбрать роль или набор ролей на выбранную игру.

                                Пакеты не дают гарантии, а только повышают вероятность выпадения тех ролей, которые
                                предпочтительнее для игрока. Всегда помните, что желаемой роли может не быть в раскладке на
                                игру ВООБЩЕ.
                            </div>
                        </div>
                    </div>

                    <div class="prob-list">
                        <template x-for="prob in probabilities" :key="prob.id">
                            <div class="prob-card">

                                <div class="prob-card-header">
                                    <div>
                                        <div class="prob-card-title" x-text="prob.title"></div>
                                    </div>

                                </div>

                                <!-- Слайдер ролей — изолированный x-data на каждую карточку -->
                                <div class="slider-wrap" x-data="slider(prob.roles)">

                                    <button class="slider-nav prev" @click="prev()" :disabled="current === 0">‹</button>

                                    <div class="slider-track" x-ref="track" @scroll.passive="onScroll()">
                                        <!-- Динамические роли -->
                                        <template x-for="(role, ri) in roles" :key="role.id">
                                            <div class="role-slide" @click="goTo(ri)">
                                                <div class="role-img-wrap">
                                                    <template x-if="role.src">
                                                        <img :src="role.src" :alt="role.name">
                                                    </template>
                                                </div>
                                                <span class="role-name" x-text="role.title"></span>
                                            </div>
                                        </template>

                                        <!-- СТАТИЧНАЯ КАРТИНКА В КОНЦЕ СЛАЙДЕРА -->
                                        <!-- Клик по ней перенаправит на индекс последней вкладки (равный roles.length) -->
                                        <div class="role-slide" @click="goTo(roles.length)">
                                            <div class="role-img-wrap">
                                                <img src="/cub.png" alt="Cub">
                                            </div>
                                            <span class="role-name">Рандом</span>
                                            <!-- Можно заменить текст или оставить пустым -->
                                        </div>
                                    </div>

                                    <!-- Изменили roles.length - 1 на totalSlides - 1 -->
                                    <button class="slider-nav next" @click="next()"
                                        :disabled="current === totalSlides - 1">›</button>
                                </div>


                                <div class="card-footer" bis_skin_checked="1">
                                    <div class="price-block" bis_skin_checked="1">
                                        <img src="new/money.svg" alt="Coin" class="stat-icon">
                                        <span class="price-value" x-text="prob.price"></span>
                                    </div>
                                    <div class="divider" bis_skin_checked="1"></div>
                                    <button class="buy-btn__" :class="{ bought: prob.purchased }"
                                        :disabled="prob.purchased" @click="openConfirmModal(prob)"
                                        x-text="prob.purchased ? 'Куплено' : 'Купить'">
                                    </button>
                                </div>


                            </div>
                        </template>
                    </div>
                    <br><br><br>
                </div>

                <!-- Иммунитеты -->
                <div x-show="activeTab === 'immunities'" x-transition>
                    <div class="prob-section-header">
                        <span class="prob-section-title">Иммунитеты</span>
                    </div>
                    <div class="item-list">
                        <template x-for="immunity in immunities" :key="immunity.id">
                            <div class="event-card" :class="{ 'purchased': immunity.purchased }">
                                <div class="card-body" style="position: relative;">
                                    <div class="card-image_" style="width: 100%">
                                    </div>
                                    <h2 class="card-h2" x-text="immunity.title"></h2>
                                    <div style="margin-bottom: 6px; font-size: 12px; color: #94a3b8; text-align: center;">
                                        <span
                                            x-text="immunity.type === 'd/n' ? 'ДЕНЬ/НОЧЬ' : (immunity.type === 'd/n/d' ? 'ДЕНЬ НОЧЬ ДЕНЬ' : immunity.type)"></span>
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 4px">
                                        <img src="/new/money.svg">
                                        <p class="card-price" x-text="immunity.price"></p>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <template x-if="immunity.purchased && immunity.is_active == 1">
                                        <span class="btn-details bought"
                                            style="display:block; text-align:center; cursor:default;">Активирован</span>
                                    </template>
                                    <template x-if="immunity.purchased && immunity.is_active != 1">
                                        <a :href="'/activate-immunity/' + immunity.id" class="btn-details"
                                            style="display:block; text-align:center; text-decoration:none;">Активировать</a>
                                    </template>
                                    <template x-if="!immunity.purchased">
                                        <button class="btn-details" @click="openConfirmModal(immunity)">
                                            Купить
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Подарки -->
                <div x-show="activeTab === 'gifts'" x-transition>
                    <div class="prob-section-header">
                        <span class="prob-section-title">Подарки</span>
                    </div>
                    <div class="item-list">
                        <template x-for="gift in gifts" :key="gift.id">
                            <div class="event-card" :class="{ 'purchased': gift.purchased }">
                                <div class="card-body" style="position: relative;">
                                    <span class="purchased-badge" x-show="gift.purchased">Куплено</span>
                                    <div class="card-image_" style="width: 100%">
                                        <img src="/new/gift.svg" :alt="gift.title" src="/images/store/fut.png">
                                    </div>
                                    <h2 class="card-h2" x-text="gift.title"></h2>
                                    <div style="display: flex; align-items: center; gap: 4px">
                                        <img src="/new/money.svg">
                                        <p class="card-price" x-text="gift.price"></p>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button class="btn-details" :class="{ bought: gift.purchased }"
                                        :disabled="gift.purchased" @click="openConfirmModal(gift)"
                                        x-text="gift.purchased ? 'Куплено' : 'Подарить'">
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
            <!-- Модальное окно подтверждения покупки -->
            <div x-show="showConfirmModal" x-cloak class="modal-overlay" @click="closeModal">
                <div class="modal" @click.stop>
                    <div class="modal-header">
                        <button class="modal-close" @click="closeModal">
                            <svg width="24" height="24" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" />
                            </svg>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h3 class="modal-title">Купить <span x-text="currentItem?.title"></span>?</h3>
                        <p>Вы уверены, что хотите купить этот товар за <span x-text="currentItem?.price"></span> серебра?
                        </p>
                    </div>
                    <div class="modal-coins">
                        <img src="new/money.svg" alt="Coin" class="stat-icon">
                        <span class="price-value" x-text="currentItem?.price"></span>
                    </div>
                    <div class="modal-footer">
                        <button class="btn-cancel" @click="closeModal">Нет</button>
                        <button class="btn-confirm" @click="confirmPurchase">Да, купить</button>
                    </div>
                </div>
            </div>

            <!-- Уведомление об ошибке -->
            <div x-show="showErrorNotification" x-cloak class="error-notification">
                <div class="error-content">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" y1="8" x2="12" y2="12" />
                        <line x1="12" y1="16" x2="12" y2="16" />
                    </svg>
                    <span>Недостаточно серебра! <a href="/account?open=modal" class="topup-link">Пополнить
                            баланс</a></span>
                </div>
            </div>
        </div>

        <script>
            /* Слайдер — отдельный Alpine-скоуп для каждой карточки вероятности */
            function slider(roles) {
                return {
                    roles: roles || [],
                    current: 0,
                    isMoving: false, // Флаг, блокирующий onScroll во время программной анимации

                    get totalSlides() {
                        return this.roles.length + 1;
                    },

                    goTo(index) {
                        if (!this.totalSlides) return;
                        this.current = index;

                        const track = this.$refs.track;
                        if (!track) return;

                        // Находим ВСЕ реальные слайды на странице
                        const slides = track.querySelectorAll('.role-slide');
                        const targetSlide = slides[index];

                        if (targetSlide) {
                            this.isMoving = true; // Включаем защиту от дерганья

                            // Браузер сам идеально отцентрирует или подвинет нужный слайд
                            targetSlide.scrollIntoView({
                                behavior: 'smooth',
                                block: 'nearest', // Не двигает страницу по вертикали
                                inline: 'start' // Выравнивает слайд по левому краю трека
                            });

                            // Выключаем защиту после завершения плавной прокрутки
                            setTimeout(() => {
                                this.isMoving = false;
                            }, 400); // 400мс — стандартное время smooth-скролла в браузерах
                        }
                    },

                    next() {
                        if (this.current < this.totalSlides - 1) {
                            this.goTo(this.current + 1);
                        }
                    },

                    prev() {
                        if (this.current > 0) {
                            this.goTo(this.current - 1);
                        }
                    },

                    onScroll() {
                        // Если слайдер сейчас двигается программно (от кнопки) — игнорируем событие скролла
                        if (this.isMoving) return;

                        const track = this.$refs.track;
                        if (!track || !this.totalSlides) return;

                        const slides = track.querySelectorAll('.role-slide');
                        if (!slides.length) return;

                        // Определяем, какой слайд сейчас ближе всего к левому краю трека
                        let closestIndex = 0;
                        let minDistance = Infinity;

                        slides.forEach((slide, idx) => {
                            // Расстояние от левого края трека до левого края конкретного слайда
                            const diff = Math.abs(slide.offsetLeft - track.scrollLeft);
                            if (diff < minDistance) {
                                minDistance = diff;
                                closestIndex = idx;
                            }
                        });

                        this.current = closestIndex;
                    }
                };
            }



            function shopPage() {
                return {
                    activeTab: 'merch',
                    balance: {{ $account->balance ?? 0 }},
                    cartCount: 0,
                    showConfirmModal: false,
                    currentItem: null,
                    showErrorNotification: false,

                    merch: @json($merch),
                    probabilities: @json($chances),
                    immunities: @json($immunities),
                    gifts: [{
                        id: 1,
                        emoji: '/images/achivments/1.png',
                        title: 'Маленький подарок',
                        desc: 'Подарить 100 серебра другу',
                        color: 'red',
                        price: 100,
                        purchased: false
                    }],

                    openConfirmModal(item) {
                        this.currentItem = item;
                        if (this.balance < item.price) {
                            this.triggerError();
                            return;
                        }
                        this.showConfirmModal = true;
                    },

                    closeModal() {
                        this.showConfirmModal = false;
                        this.currentItem = null;
                    },

                    triggerError() {
                        this.showErrorNotification = true;
                        setTimeout(() => {
                            this.showErrorNotification = false;
                        }, 5000);
                    },

                    async confirmPurchase() {
                        if (!this.currentItem) return;

                        try {
                            const response = await fetch('/buy-item', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-Token': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    itemId: this.currentItem.id,
                                    type: this.activeTab
                                })
                            });

                            const result = await response.json();

                            if (result.error) {
                                this.closeModal();
                                this.triggerError();
                            } else {
                                this.balance = result.points;

                                const sourceArray = this.activeTab === 'merch' ? this.merch :
                                    this.activeTab === 'probabilities' ? this.probabilities :
                                    this.activeTab === 'immunities' ? this.immunities :
                                    this.gifts;

                                const sourceItem = sourceArray.find(i => i.id === this.currentItem.id);
                                if (sourceItem) sourceItem.purchased = true;

                                this.cartCount++;
                                this.closeModal();
                            }
                        } catch (error) {
                            console.error('Ошибка при покупке:', error);
                            this.closeModal();
                            this.triggerError();
                        }
                    },

                    goToTopup() {
                        window.location.href = '/topup';
                    }
                };
            }
        </script>
    </div>
    @include('include.club.bottom-nav')
@endsection
