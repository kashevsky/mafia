<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Отчеты - Админ панель</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
        }

        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .sidebar-header {
            padding: 25px 20px;
            background-color: rgba(0, 0, 0, 0.2);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header h3 {
            font-size: 22px;
            font-weight: 600;
            margin: 0;
            color: #fff;
        }

        .sidebar-header p {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.7);
            margin: 5px 0 0 0;
        }

        .sidebar-menu {
            list-style: none;
            padding: 20px 0;
        }

        .sidebar-menu li {
            margin: 0;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 15px 25px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .sidebar-menu a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #fff;
            border-left-color: #3498db;
        }

        .sidebar-menu a.active {
            background-color: rgba(52, 152, 219, 0.2);
            color: #fff;
            border-left-color: #3498db;
        }

        .sidebar-menu a i {
            margin-right: 12px;
            font-size: 18px;
            width: 25px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 30px;
        }

        .page-header {
            background: white;
            padding: 25px 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        .page-header h1 {
            font-size: 28px;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
        }

        .breadcrumb {
            background: transparent;
            padding: 0;
            margin: 8px 0 0 0;
            font-size: 14px;
            color: #7f8c8d;
        }

        .content-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 30px;
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #ecf0f1;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Month Selector */
        .month-selector {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
        }

        .month-btn {
            padding: 12px 24px;
            background: white;
            border: 2px solid #dfe6e9;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            color: #2c3e50;
            transition: all 0.3s ease;
        }

        .month-btn:hover {
            border-color: #3498db;
            color: #3498db;
        }

        .month-btn.active {
            background: #3498db;
            border-color: #3498db;
            color: white;
        }

        /* Summary Cards */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .summary-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 25px;
            border-radius: 8px;
            color: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .summary-card.income {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }

        .summary-card.expense {
            background: linear-gradient(135deg, #ee0979 0%, #ff6a00 100%);
        }

        .summary-card.info {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .summary-card.warning {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        }

        .summary-card h3 {
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 10px;
            opacity: 0.9;
        }

        .summary-card .amount {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .summary-card .count {
            font-size: 13px;
            opacity: 0.8;
        }

        /* Transaction List */
        .transaction-list {
            margin-top: 20px;
        }

        .transaction-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #ecf0f1;
            transition: background-color 0.2s ease;
        }

        .transaction-item:hover {
            background-color: #f8f9fa;
        }

        .transaction-item:last-child {
            border-bottom: none;
        }

        .transaction-info {
            flex: 1;
        }

        .transaction-comment {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .transaction-date {
            font-size: 13px;
            color: #7f8c8d;
        }

        .transaction-amount {
            font-size: 18px;
            font-weight: 700;
        }

        .transaction-amount.income {
            color: #27ae60;
        }

        .transaction-amount.expense {
            color: #e74c3c;
        }

        /* Buttons */
        .btn-primary {
            background-color: #3498db;
            border: none;
            padding: 12px 24px;
            font-weight: 600;
            border-radius: 6px;
            transition: all 0.3s ease;
            color: white;
            cursor: pointer;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
        }

        .btn-secondary {
            background-color: #95a5a6;
            border: none;
            padding: 12px 24px;
            font-weight: 600;
            border-radius: 6px;
            transition: all 0.3s ease;
            color: white;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-secondary:hover {
            background-color: #7f8c8d;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1100;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            align-items: center;
            justify-content: center;
        }

        .modal.show {
            display: flex;
        }

        .modal-content {
            background-color: white;
            padding: 0;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            animation: modalSlideIn 0.3s ease;
        }

        @keyframes modalSlideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            padding: 20px 25px;
            border-bottom: 1px solid #ecf0f1;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            margin: 0;
            font-size: 20px;
            color: #2c3e50;
        }

        .close {
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            color: #7f8c8d;
            line-height: 1;
        }

        .close:hover {
            color: #2c3e50;
        }

        .modal-body {
            padding: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
            display: block;
        }

        .form-control {
            width: 100%;
            border: 1px solid #dfe6e9;
            border-radius: 6px;
            padding: 12px 15px;
            font-size: 14px;
            transition: all 0.3s ease;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .form-control:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.15);
        }

        .modal-footer {
            padding: 20px 25px;
            border-top: 1px solid #ecf0f1;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #7f8c8d;
        }

        .empty-state i {
            font-size: 48px;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        .empty-state p {
            font-size: 16px;
        }

        /* Tabs for Expenses */
        .expense-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
            border-bottom: 2px solid #ecf0f1;
        }

        .expense-tab {
            padding: 12px 24px;
            background: transparent;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            color: #7f8c8d;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
            margin-bottom: -2px;
        }

        .expense-tab:hover {
            color: #2c3e50;
        }

        .expense-tab.active {
            color: #3498db;
            border-bottom-color: #3498db;
        }

        /* Main Tabs */
        .main-tabs {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .main-tab {
            flex: 1;
            padding: 15px 20px;
            background: transparent;
            border: 2px solid #dfe6e9;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;
            color: #2c3e50;
            transition: all 0.3s ease;
            text-align: center;
        }

        .main-tab:hover {
            border-color: #3498db;
            color: #3498db;
        }

        .main-tab.active {
            background: #3498db;
            border-color: #3498db;
            color: white;
        }

        .main-tab i {
            margin-right: 8px;
        }

        /* Statistics Table */
        .stats-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .stats-table thead {
            background-color: #ecf0f1;
        }

        .stats-table th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #2c3e50;
            border-bottom: 2px solid #dfe6e9;
        }

        .stats-table td {
            padding: 15px;
            border-bottom: 1px solid #ecf0f1;
        }

        .stats-table tr:hover {
            background-color: #f8f9fa;
        }

        .stats-value {
            font-weight: 700;
            color: #3498db;
            font-size: 16px;
        }

        /* Period Selector */
        .period-selector {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .period-btn {
            padding: 10px 20px;
            background: white;
            border: 2px solid #dfe6e9;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            color: #2c3e50;
            transition: all 0.3s ease;
        }

        .period-btn:hover {
            border-color: #3498db;
            color: #3498db;
        }

        .period-btn.active {
            background: #3498db;
            border-color: #3498db;
            color: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-wrapper {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main-content {
                margin-left: 0;
            }

            .summary-grid {
                grid-template-columns: 1fr;
            }

            .month-selector {
                flex-direction: column;
            }

            .expense-tabs {
                flex-direction: column;
                border-bottom: none;
            }

            .expense-tab {
                border-left: 3px solid transparent;
                border-bottom: none;
                text-align: left;
                margin: 0;
            }

            .expense-tab.active {
                border-left-color: #3498db;
                border-bottom-color: transparent;
            }

            .main-tabs {
                flex-direction: column;
            }

            .period-selector {
                flex-direction: column;
            }
        }
    </style>
</head>
@extends('admin.layouts.main')
@section('content')
<div x-data="reportsApp()">


        <!-- Main Content -->

            <!-- Page Header -->
            <div class="page-header">
                <h1>Финансовые отчеты</h1>
                <div class="breadcrumb">
                    Главная / Отчеты
                </div>
            </div>

            <!-- Month Selector -->
            <div class="month-selector">
                <button
                    class="month-btn"
                    :class="{ 'active': selectedMonth === 'current' }"
                    @click="selectedMonth = 'current'">
                    <i class="fas fa-calendar-day"></i> Текущий месяц (Апрель 2026)
                </button>
                <button
                    class="month-btn"
                    :class="{ 'active': selectedMonth === 'previous' }"
                    @click="selectedMonth = 'previous'">
                    <i class="fas fa-calendar-alt"></i> Предыдущий месяц (Март 2026)
                </button>
            </div>

            <!-- Summary Cards -->
            <div class="summary-grid">
                <div class="summary-card income">
                    <h3><i class="fas fa-arrow-up"></i> Общие приходы</h3>
                    <div class="amount" x-text="formatCurrency(getCurrentData().totalIncome)"></div>
                    <div class="count" x-text="`${getCurrentData().incomeTransactions.length} транзакций`"></div>
                </div>
                <div class="summary-card expense">
                    <h3><i class="fas fa-arrow-down"></i> Общие расходы</h3>
                    <div class="amount" x-text="formatCurrency(getCurrentData().totalExpense)"></div>
                    <div class="count" x-text="`${getCurrentData().expenseTransactions.length} транзакций`"></div>
                </div>
                <div class="summary-card">
                    <h3><i class="fas fa-balance-scale"></i> Баланс</h3>
                    <div class="amount" x-text="formatCurrency(getCurrentData().totalIncome - getCurrentData().totalExpense)"></div>
                    <div class="count"
                        :style="`color: ${getCurrentData().totalIncome - getCurrentData().totalExpense >= 0 ? '#38ef7d' : '#ff6a00'}`"
                        x-text="getCurrentData().totalIncome - getCurrentData().totalExpense >= 0 ? 'Положительный' : 'Отрицательный'">
                    </div>
                </div>
                <div class="summary-card info">
                    <h3><i class="fas fa-user-plus"></i> Новые игроки</h3>
                    <div class="amount" x-text="getCurrentData().newPlayers"></div>
                    <div class="count">За выбранный период</div>
                </div>
                <div class="summary-card warning">
                    <h3><i class="fas fa-gamepad"></i> Проведено игр</h3>
                    <div class="amount" x-text="getCurrentData().gamesPlayed"></div>
                    <div class="count">За выбранный период</div>
                </div>
            </div>

            <!-- Main Tabs -->
            <div class="main-tabs">
                <button
                    class="main-tab"
                    :class="{ 'active': mainTab === 'finance' }"
                    @click="mainTab = 'finance'">
                    <i class="fas fa-wallet"></i> Финансы
                </button>
                <button
                    class="main-tab"
                    :class="{ 'active': mainTab === 'statistics' }"
                    @click="mainTab = 'statistics'">
                    <i class="fas fa-chart-line"></i> Статистика покупок
                </button>
            </div>

            <!-- Finance Tab -->
            <div x-show="mainTab === 'finance'">
                <!-- Income Section -->
                <div class="content-card">
                    <div class="section-title">
                        <span><i class="fas fa-plus-circle" style="color: #27ae60;"></i> Приходы</span>
                        <button class="btn-primary" @click="openModal('income')">
                            <i class="fas fa-plus"></i> Добавить приход
                        </button>
                    </div>

                    <div class="transaction-list" x-show="getCurrentData().incomeTransactions.length > 0">
                        <template x-for="transaction in getCurrentData().incomeTransactions" :key="transaction.id">
                            <div class="transaction-item">
                                <div class="transaction-info">
                                    <div class="transaction-comment" x-text="transaction.comment"></div>
                                    <div class="transaction-date" x-text="transaction.date"></div>
                                </div>
                                <div class="transaction-amount income" x-text="formatCurrency(transaction.amount)"></div>
                            </div>
                        </template>
                    </div>

                    <div class="empty-state" x-show="getCurrentData().incomeTransactions.length === 0">
                        <i class="fas fa-inbox"></i>
                        <p>Нет приходов за выбранный период</p>
                    </div>
                </div>

                <!-- Expense Sections -->
                <div class="content-card">
                    <div class="section-title">
                        <span><i class="fas fa-minus-circle" style="color: #e74c3c;"></i> Расходы</span>
                    </div>

                    <!-- Expense Tabs -->
                    <div class="expense-tabs">
                        <button
                            class="expense-tab"
                            :class="{ 'active': expenseTab === 'permanent' }"
                            @click="expenseTab = 'permanent'">
                            <i class="fas fa-sync-alt"></i> Постоянные расходы
                        </button>
                        <button
                            class="expense-tab"
                            :class="{ 'active': expenseTab === 'additional' }"
                            @click="expenseTab = 'additional'">
                            <i class="fas fa-random"></i> Дополнительные расходы
                        </button>
                    </div>

                    <!-- Permanent Expenses -->
                    <div x-show="expenseTab === 'permanent'">
                        <div style="margin-bottom: 20px;">
                            <button class="btn-primary" @click="openModal('permanent')">
                                <i class="fas fa-plus"></i> Добавить постоянный расход
                            </button>
                        </div>

                        <div class="transaction-list" x-show="getCurrentData().permanentExpenses.length > 0">
                            <template x-for="transaction in getCurrentData().permanentExpenses" :key="transaction.id">
                                <div class="transaction-item">
                                    <div class="transaction-info">
                                        <div class="transaction-comment" x-text="transaction.comment"></div>
                                        <div class="transaction-date" x-text="transaction.date"></div>
                                    </div>
                                    <div class="transaction-amount expense" x-text="formatCurrency(transaction.amount)"></div>
                                </div>
                            </template>
                        </div>

                        <div class="empty-state" x-show="getCurrentData().permanentExpenses.length === 0">
                            <i class="fas fa-inbox"></i>
                            <p>Нет постоянных расходов за выбранный период</p>
                        </div>
                    </div>

                    <!-- Additional Expenses -->
                    <div x-show="expenseTab === 'additional'">
                        <div style="margin-bottom: 20px;">
                            <button class="btn-primary" @click="openModal('additional')">
                                <i class="fas fa-plus"></i> Добавить дополнительный расход
                            </button>
                        </div>

                        <div class="transaction-list" x-show="getCurrentData().additionalExpenses.length > 0">
                            <template x-for="transaction in getCurrentData().additionalExpenses" :key="transaction.id">
                                <div class="transaction-item">
                                    <div class="transaction-info">
                                        <div class="transaction-comment" x-text="transaction.comment"></div>
                                        <div class="transaction-date" x-text="transaction.date"></div>
                                    </div>
                                    <div class="transaction-amount expense" x-text="formatCurrency(transaction.amount)"></div>
                                </div>
                            </template>
                        </div>

                        <div class="empty-state" x-show="getCurrentData().additionalExpenses.length === 0">
                            <i class="fas fa-inbox"></i>
                            <p>Нет дополнительных расходов за выбранный период</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Tab -->
            <div x-show="mainTab === 'statistics'">
                <!-- Period Selector -->
                <div class="content-card">
                    <div class="section-title">
                        <span><i class="fas fa-calendar-check"></i> Выберите период</span>
                    </div>
                    <div class="period-selector">
                        <button
                            class="period-btn"
                            :class="{ 'active': statisticsPeriod === 'week' }"
                            @click="statisticsPeriod = 'week'">
                            <i class="fas fa-calendar-week"></i> Неделя
                        </button>
                        <button
                            class="period-btn"
                            :class="{ 'active': statisticsPeriod === 'month' }"
                            @click="statisticsPeriod = 'month'">
                            <i class="fas fa-calendar-alt"></i> Месяц
                        </button>
                        <button
                            class="period-btn"
                            :class="{ 'active': statisticsPeriod === 'quarter' }"
                            @click="statisticsPeriod = 'quarter'">
                            <i class="fas fa-calendar"></i> Квартал
                        </button>
                        <button
                            class="period-btn"
                            :class="{ 'active': statisticsPeriod === 'year' }"
                            @click="statisticsPeriod = 'year'">
                            <i class="fas fa-calendar-plus"></i> Год
                        </button>
                    </div>
                </div>

                <!-- Merch Statistics -->
                <div class="content-card">
                    <div class="section-title">
                        <span><i class="fas fa-shopping-bag" style="color: #3498db;"></i> Статистика продажи мерча</span>
                    </div>
                    <table class="stats-table">
                        <thead>
                            <tr>
                                <th>Товар</th>
                                <th>Количество</th>
                                <th>Сумма</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="item in getStatisticsData().merch" :key="item.id">
                                <tr>
                                    <td x-text="item.name"></td>
                                    <td><span class="stats-value" x-text="item.quantity + ' шт.'"></span></td>
                                    <td><span class="stats-value" x-text="formatCurrency(item.amount)"></span></td>
                                </tr>
                            </template>
                        </tbody>
                        <tfoot style="background-color: #f8f9fa; font-weight: 700;">
                            <tr>
                                <td>Итого</td>
                                <td><span class="stats-value" x-text="getTotalQuantity('merch') + ' шт.'"></span></td>
                                <td><span class="stats-value" x-text="formatCurrency(getTotalAmount('merch'))"></span></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Probabilities Statistics -->
                <div class="content-card">
                    <div class="section-title">
                        <span><i class="fas fa-percent" style="color: #9b59b6;"></i> Статистика покупки вероятностей</span>
                    </div>
                    <table class="stats-table">
                        <thead>
                            <tr>
                                <th>Вероятность</th>
                                <th>Количество</th>
                                <th>Сумма</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="item in getStatisticsData().probabilities" :key="item.id">
                                <tr>
                                    <td x-text="item.name"></td>
                                    <td><span class="stats-value" x-text="item.quantity + ' шт.'"></span></td>
                                    <td><span class="stats-value" x-text="formatCurrency(item.amount)"></span></td>
                                </tr>
                            </template>
                        </tbody>
                        <tfoot style="background-color: #f8f9fa; font-weight: 700;">
                            <tr>
                                <td>Итого</td>
                                <td><span class="stats-value" x-text="getTotalQuantity('probabilities') + ' шт.'"></span></td>
                                <td><span class="stats-value" x-text="formatCurrency(getTotalAmount('probabilities'))"></span></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Coins Statistics -->
                <div class="content-card">
                    <div class="section-title">
                        <span><i class="fas fa-coins" style="color: #f39c12;"></i> Статистика покупки монет</span>
                    </div>
                    <table class="stats-table">
                        <thead>
                            <tr>
                                <th>Пакет монет</th>
                                <th>Количество</th>
                                <th>Сумма</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="item in getStatisticsData().coins" :key="item.id">
                                <tr>
                                    <td x-text="item.name"></td>
                                    <td><span class="stats-value" x-text="item.quantity + ' шт.'"></span></td>
                                    <td><span class="stats-value" x-text="formatCurrency(item.amount)"></span></td>
                                </tr>
                            </template>
                        </tbody>
                        <tfoot style="background-color: #f8f9fa; font-weight: 700;">
                            <tr>
                                <td>Итого</td>
                                <td><span class="stats-value" x-text="getTotalQuantity('coins') + ' шт.'"></span></td>
                                <td><span class="stats-value" x-text="formatCurrency(getTotalAmount('coins'))"></span></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>


        <!-- Modal for Adding Transactions -->
        <div class="modal" :class="{ 'show': showModal }" @click.self="closeModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 x-text="getModalTitle()"></h3>
                    <span class="close" @click="closeModal">&times;</span>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="submitTransaction">
                        <div class="form-group">
                            <label class="form-label">Комментарий</label>
                            <textarea
                                class="form-control"
                                x-model="newTransaction.comment"
                                rows="3"
                                placeholder="Введите описание транзакции"
                                required></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Сумма (₽)</label>
                            <input
                                type="number"
                                class="form-control"
                                x-model="newTransaction.amount"
                                placeholder="0.00"
                                step="0.01"
                                min="0"
                                required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn-secondary" type="button" @click="closeModal">Отмена</button>
                    <button class="btn-primary" type="button" @click="submitTransaction">Добавить</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function reportsApp() {
            return {
                selectedMonth: 'current',
                expenseTab: 'permanent',
                showModal: false,
                modalType: '',
                newTransaction: {
                    comment: '',
                    amount: ''
                },

                data: {
                    current: {
                        incomeTransactions: [
                            { id: 1, comment: 'Оплата за игру', amount: 15000, date: '10 апреля 2026' },
                            { id: 2, comment: 'Продажа мерча', amount: 8500, date: '12 апреля 2026' },
                            { id: 3, comment: 'Спонсорская поддержка', amount: 25000, date: '14 апреля 2026' }
                        ],
                        permanentExpenses: [
                            { id: 1, comment: 'Аренда помещения', amount: 30000, date: '1 апреля 2026' },
                            { id: 2, comment: 'Зарплата сотрудников', amount: 50000, date: '5 апреля 2026' },
                            { id: 3, comment: 'Коммунальные услуги', amount: 8000, date: '1 апреля 2026' }
                        ],
                        additionalExpenses: [
                            { id: 1, comment: 'Покупка оборудования', amount: 15000, date: '8 апреля 2026' },
                            { id: 2, comment: 'Реклама и маркетинг', amount: 7500, date: '11 апреля 2026' }
                        ],
                        newPlayers: 150,
                        gamesPlayed: 200
                    },
                    previous: {
                        incomeTransactions: [
                            { id: 1, comment: 'Оплата за игру', amount: 12000, date: '10 марта 2026' },
                            { id: 2, comment: 'Продажа мерча', amount: 6000, date: '15 марта 2026' }
                        ],
                        permanentExpenses: [
                            { id: 1, comment: 'Аренда помещения', amount: 30000, date: '1 марта 2026' },
                            { id: 2, comment: 'Зарплата сотрудников', amount: 50000, date: '5 марта 2026' },
                            { id: 3, comment: 'Коммунальные услуги', amount: 7500, date: '1 марта 2026' }
                        ],
                        additionalExpenses: [
                            { id: 1, comment: 'Ремонт техники', amount: 5000, date: '12 марта 2026' }
                        ],
                        newPlayers: 100,
                        gamesPlayed: 150
                    }
                },

                getCurrentData() {
                    const monthData = this.data[this.selectedMonth];
                    const totalIncome = monthData.incomeTransactions.reduce((sum, t) => sum + t.amount, 0);
                    const totalExpense = [
                        ...monthData.permanentExpenses,
                        ...monthData.additionalExpenses
                    ].reduce((sum, t) => sum + t.amount, 0);

                    return {
                        ...monthData,
                        expenseTransactions: [...monthData.permanentExpenses, ...monthData.additionalExpenses],
                        totalIncome,
                        totalExpense
                    };
                },

                formatCurrency(amount) {
                    return new Intl.NumberFormat('ru-RU', {
                        style: 'currency',
                        currency: 'RUB',
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    }).format(amount);
                },

                openModal(type) {
                    this.modalType = type;
                    this.showModal = true;
                    this.newTransaction = {
                        comment: '',
                        amount: ''
                    };
                },

                closeModal() {
                    this.showModal = false;
                    this.modalType = '';
                    this.newTransaction = {
                        comment: '',
                        amount: ''
                    };
                },

                getModalTitle() {
                    const titles = {
                        'income': 'Добавить приход',
                        'permanent': 'Добавить постоянный расход',
                        'additional': 'Добавить дополнительный расход'
                    };
                    return titles[this.modalType] || 'Добавить транзакцию';
                },

                submitTransaction() {
                    if (!this.newTransaction.comment || !this.newTransaction.amount) {
                        alert('Пожалуйста, заполните все поля');
                        return;
                    }

                    const transaction = {
                        id: Date.now(),
                        comment: this.newTransaction.comment,
                        amount: parseFloat(this.newTransaction.amount),
                        date: new Date().toLocaleDateString('ru-RU', {
                            day: 'numeric',
                            month: 'long',
                            year: 'numeric'
                        })
                    };

                    const monthData = this.data[this.selectedMonth];

                    if (this.modalType === 'income') {
                        monthData.incomeTransactions.unshift(transaction);
                    } else if (this.modalType === 'permanent') {
                        monthData.permanentExpenses.unshift(transaction);
                    } else if (this.modalType === 'additional') {
                        monthData.additionalExpenses.unshift(transaction);
                    }

                    this.closeModal();
                },

                mainTab: 'finance',
                statisticsPeriod: 'month',

                getStatisticsData() {
                    const periodData = this.data[this.selectedMonth];
                    return {
                        merch: [
                            { id: 1, name: 'Футболка', quantity: 50, amount: 2500 },
                            { id: 2, name: 'Кепка', quantity: 30, amount: 1500 },
                            { id: 3, name: 'Платье', quantity: 20, amount: 1000 }
                        ],
                        probabilities: [
                            { id: 1, name: '50%', quantity: 10, amount: 500 },
                            { id: 2, name: '25%', quantity: 20, amount: 1000 },
                            { id: 3, name: '10%', quantity: 30, amount: 1500 }
                        ],
                        coins: [
                            { id: 1, name: '100 монет', quantity: 5, amount: 500 },
                            { id: 2, name: '500 монет', quantity: 3, amount: 1500 },
                            { id: 3, name: '1000 монет', quantity: 2, amount: 2500 }
                        ]
                    };
                },

                getTotalQuantity(type) {
                    const data = this.getStatisticsData()[type];
                    return data.reduce((sum, item) => sum + item.quantity, 0);
                },

                getTotalAmount(type) {
                    const data = this.getStatisticsData()[type];
                    return data.reduce((sum, item) => sum + item.amount, 0);
                }
            }
        }
    </script>
</body>

</html>
@endsection
