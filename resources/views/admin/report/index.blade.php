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
            z-index: 100;
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

        /* Summary Cards */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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

        .summary-card.green {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }

        .summary-card.blue {
            background: linear-gradient(135deg, #2980b9 0%, #6dd5fa 100%);
        }

        .summary-card.orange {
            background: linear-gradient(135deg, #f39c12 0%, #f8d800 100%);
        }

        .summary-card.red {
            background: linear-gradient(135deg, #ee0979 0%, #ff6a00 100%);
        }

        .summary-card.purple {
            background: linear-gradient(135deg, #8e44ad 0%, #c39bd3 100%);
        }

        .summary-card h3 {
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 10px;
            opacity: 0.9;
        }

        .summary-card .amount {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .summary-card .count {
            font-size: 13px;
            opacity: 0.8;
        }

        /* Filters */
        .filters-row {
            display: flex;
            gap: 20px;
            align-items: flex-end;
            flex-wrap: wrap;
            margin-bottom: 0;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .filter-label {
            font-size: 13px;
            font-weight: 600;
            color: #7f8c8d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control {
            border: 1px solid #dfe6e9;
            border-radius: 6px;
            padding: 10px 15px;
            font-size: 14px;
            transition: all 0.3s ease;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: white;
            color: #2c3e50;
            min-width: 220px;
        }

        .form-control:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.15);
        }

        /* Type Filter Buttons */
        .type-filter {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .type-btn {
            padding: 10px 18px;
            background: white;
            border: 2px solid #dfe6e9;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            color: #2c3e50;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .type-btn:hover {
            border-color: #3498db;
            color: #3498db;
        }

        .type-btn.active {
            background: #3498db;
            border-color: #3498db;
            color: white;
        }

        /* Payments Table */
        .payments-table {
            width: 100%;
            border-collapse: collapse;
        }

        .payments-table thead {
            background-color: #ecf0f1;
        }

        .payments-table th {
            padding: 14px 16px;
            text-align: left;
            font-weight: 600;
            color: #2c3e50;
            border-bottom: 2px solid #dfe6e9;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .payments-table td {
            padding: 14px 16px;
            border-bottom: 1px solid #ecf0f1;
            font-size: 14px;
            color: #2c3e50;
        }

        .payments-table tr:hover td {
            background-color: #f8f9fa;
        }

        .payments-table tfoot td {
            padding: 14px 16px;
            font-weight: 700;
            background-color: #f8f9fa;
            border-top: 2px solid #dfe6e9;
        }

        .amount-cell {
            font-weight: 700;
            color: #27ae60;
            font-size: 15px;
        }

        /* Type Badge */
        .type-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .type-badge.game {
            background: #eaf6ff;
            color: #2980b9;
        }

        .type-badge.dopgame {
            background: #eafaf1;
            color: #27ae60;
        }

        .type-badge.immunity {
            background: #f5eef8;
            color: #8e44ad;
        }

        .type-badge.other {
            background: #fef9e7;
            color: #d35400;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 50px 20px;
            color: #7f8c8d;
        }

        .empty-state i {
            font-size: 48px;
            margin-bottom: 15px;
            opacity: 0.4;
        }

        .empty-state p {
            font-size: 16px;
        }

        /* Buttons */
        .btn-primary {
            background-color: #3498db;
            border: none;
            padding: 10px 20px;
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
                grid-template-columns: 1fr 1fr;
            }

            .filters-row {
                flex-direction: column;
            }

            .form-control {
                min-width: 100%;
            }
        }
    </style>
</head>
@extends('admin.layouts.main')
@section('content')
    <div x-data="reportsApp()" x-init="init()">

        <!-- Page Header -->
        <div class="page-header">
            <h1><i class="fas fa-chart-bar" style="color: #3498db; margin-right: 12px;"></i>Отчеты по оплатам</h1>
            <div class="breadcrumb">
                Главная / Отчеты
            </div>
        </div>

        <!-- Filters -->
        <div class="content-card">
            <div class="section-title">
                <span><i class="fas fa-filter" style="color: #3498db;"></i> Фильтры</span>
                <button class="btn-primary" @click="resetFilters()">
                    <i class="fas fa-times"></i> Сбросить
                </button>
            </div>

            <div class="filters-row">
                <!-- Club Filter -->
                <div class="filter-group">
                    <span class="filter-label"><i class="fas fa-building"></i> Клуб</span>
                    <select class="form-control" x-model="selectedClub" @change="applyFilters()">
                        <option value="">Все клубы</option>
                        <template x-for="club in clubs" :key="club.id">
                            <option :value="club.id" x-text="club.title"></option>
                        </template>
                    </select>
                </div>

                <!-- Type Filter -->
                <div class="filter-group">
                    <span class="filter-label"><i class="fas fa-tags"></i> Тип оплаты</span>
                    <div class="type-filter">
                        <button class="type-btn" :class="{ 'active': selectedType === '' }"
                            @click="selectedType = ''; applyFilters()">
                            Все
                        </button>
                        <template x-for="type in paymentTypes" :key="type.value">
                            <button class="type-btn" :class="{ 'active': selectedType === type.value }"
                                @click="selectedType = type.value; applyFilters()" x-text="type.label">
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Date From Filter -->
                <div class="filter-group">
                    <span class="filter-label"><i class="fas fa-calendar-alt"></i> Дата с</span>
                    <input type="date" class="form-control" x-model="dateFrom" @change="applyFilters()">
                </div>

                <!-- Date To Filter -->
                <div class="filter-group">
                    <span class="filter-label"><i class="fas fa-calendar-alt"></i> Дата по</span>
                    <input type="date" class="form-control" x-model="dateTo" @change="applyFilters()">
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="summary-grid">
            <div class="summary-card green">
                <h3><i class="fas fa-ruble-sign"></i> Общая сумма</h3>
                <div class="amount" x-text="formatCurrency(totalAmount())"></div>
                <div class="count" x-text="clubFilteredPayments.length + ' транзакций'"></div>
            </div>
            <div class="summary-card blue">
                <h3><i class="fas fa-gamepad"></i> Игра</h3>
                <div class="amount" x-text="formatCurrency(amountByType('Игра'))"></div>
                <div class="count" x-text="countByType('Игра') + ' транзакций'"></div>
            </div>
            <div class="summary-card purple">
                <h3><i class="fas fa-plus-square"></i> ДопИгра</h3>
                <div class="amount" x-text="formatCurrency(amountByType('ДопИгра'))"></div>
                <div class="count" x-text="countByType('ДопИгра') + ' транзакций'"></div>
            </div>
            <div class="summary-card orange">
                <h3><i class="fas fa-music"></i> Иммунитет/музыка</h3>
                <div class="amount" x-text="formatCurrency(amountByType('Иммунитет/музыка'))"></div>
                <div class="count" x-text="countByType('Иммунитет/музыка') + ' транзакций'"></div>
            </div>
            <div class="summary-card red">
                <h3><i class="fas fa-ellipsis-h"></i> Прочее</h3>
                <div class="amount" x-text="formatCurrency(amountByType('Прочее'))"></div>
                <div class="count" x-text="countByType('Прочее') + ' транзакций'"></div>
            </div>
        </div>

        <!-- Payments Table -->
        <div class="content-card">
            <div class="section-title">
                <span>
                    <i class="fas fa-list" style="color: #3498db;"></i> Оплаты
                    <span style="font-size: 14px; font-weight: 400; color: #7f8c8d; margin-left: 10px;"
                        x-text="'(' + filteredPayments.length + ' записей)'"></span>
                </span>
            </div>

            <!-- Table -->
            <div x-show="filteredPayments.length > 0" style="overflow-x: auto;">
                <table class="payments-table">
                    <thead>
                        <tr>
                            <th style="width: 32px;"></th>
                            <th>Игра</th>

                            <th>Тип</th>
                            <th>Сумма</th>
                            <th>Клуб</th>
                            <th>Стол</th>
                            <th>Аккаунт</th>
                            <th>Дата</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="row in flatRows()" :key="row.key">
                            <tr :style="row.rowType === 'group' ? 'cursor: pointer; background: #f0f4f8;' : (row
                                .rowType === 'child' ? 'background: #fafcff;' : '')"
                                @click="row.rowType === 'group' && toggleGroup(row.groupKey)">

                                <td style="text-align: center; color: #3498db; width: 32px;">
                                    <span x-show="row.rowType === 'group'">
                                        <i
                                            :class="expandedGroups.includes(row.groupKey) ? 'fas fa-chevron-down' :
                                                'fas fa-chevron-right'"></i>
                                    </span>
                                </td>
                                     <td style="color: #7f8c8d;" x-text="row.payment.game ? row.payment.game.title : '—'" @click="console.log(row.payment.game)"></td>

                                <td>
                                    <span x-show="row.rowType === 'group'" style="font-size: 13px; color: #7f8c8d;"
                                        x-text="row.count + ' оплат'"></span>
                                    <span x-show="row.rowType !== 'group'" class="type-badge"
                                        :class="getTypeBadgeClass(row.payment.type)" x-text="row.payment.type"></span>
                                </td>

                                <td>
                                    <span class="amount-cell" x-text="formatCurrency(row.totalAmount)"></span>
                                </td>
                                <td>
                                    <span
                                        :style="row.rowType === 'child' ?
                                            'padding-left: 16px; color: #7f8c8d; font-size: 13px;' : 'font-weight: 600;'"
                                        x-text="row.payment.club.title">
                                    </span>
                                </td>
                                <td style="color: #7f8c8d;" x-text="row.payment.table.title ?? '—'"></td>
                                <td style="color: #7f8c8d;"
                                    x-text="row.rowType === 'group' ? '—' : (row.payment.account ? row.payment.account.name : '—')">
                                </td>
                                <td style="color: #7f8c8d; font-size: 13px;" x-text="formatDate(row.payment.created_at)">
                                </td>
                            </tr>
                        </template>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3">Итого</td>
                            <td><span class="amount-cell" x-text="formatCurrency(totalAmount())"></span></td>
                            <td colspan="4"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Empty State -->
            <div class="empty-state" x-show="filteredPayments.length === 0">
                <i class="fas fa-search"></i>
                <p>Нет оплат по выбранным фильтрам</p>
            </div>
        </div>

    </div>

    <script>
        function reportsApp() {
            return {
                clubs: @json($clubs),
                allPayments: @json($payments),
                filteredPayments: [],
                clubFilteredPayments: [],
                selectedClub: '',
                selectedType: '',
                dateFrom: '',
                dateTo: '',
                expandedGroups: [],
                paymentTypes: [{
                        value: 'Игра',
                        label: 'Игра'
                    },
                    {
                        value: 'ДопИгра',
                        label: 'ДопИгра'
                    },
                    {
                        value: 'Иммунитет/музыка',
                        label: 'Иммунитет/музыка'
                    },
                    {
                        value: 'Прочее',
                        label: 'Прочее'
                    },
                ],

                init() {
                    this.applyFilters();
                },

                applyFilters() {
                    this.clubFilteredPayments = this.allPayments.filter(payment => {
                        const matchesClub = this.selectedClub === '' || payment.club_id == this.selectedClub;
                        const matchesDate = this.matchesDateRange(payment.created_at);
                        return matchesClub && matchesDate;
                    });
                    this.filteredPayments = this.clubFilteredPayments.filter(payment => {
                        return this.selectedType === '' || payment.type === this.selectedType;
                    });
                },

                matchesDateRange(dateStr) {
                    if (!dateStr) return true;
                    const paymentDate = dateStr.substring(0, 10);
                    if (this.dateFrom && paymentDate < this.dateFrom) return false;
                    if (this.dateTo && paymentDate > this.dateTo) return false;
                    return true;
                },

                resetFilters() {
                    this.selectedClub = '';
                    this.selectedType = '';
                    this.dateFrom = '';
                    this.dateTo = '';
                    this.expandedGroups = [];
                    this.applyFilters();
                },

                flatRows() {
                    const groups = {};
                    const order = [];

                    this.filteredPayments.forEach(payment => {
                        const groupKey = payment.game_id ? String(payment.game_id) : null;
                        const mapKey = groupKey ?? ('single_' + payment.id);

                        if (!groups[mapKey]) {
                            groups[mapKey] = {
                                groupKey: mapKey,
                                isGroup: !!groupKey,
                                payments: [],
                                totalAmount: 0,
                                count: 0
                            };
                            order.push(mapKey);
                        }
                        groups[mapKey].payments.push(payment);
                        groups[mapKey].totalAmount += Number(payment.amount);
                        groups[mapKey].count++;
                    });

                    const rows = [];
                    order.forEach(mapKey => {
                        const g = groups[mapKey];
                        const first = g.payments[0];

                        if (g.isGroup) {
                            rows.push({
                                key: 'group_' + mapKey,
                                rowType: 'group',
                                groupKey: mapKey,
                                payment: first,
                                count: g.count,
                                totalAmount: g.totalAmount,
                            });
                            if (this.expandedGroups.includes(mapKey)) {
                                g.payments.forEach(p => {
                                    rows.push({
                                        key: 'child_' + p.id,
                                        rowType: 'child',
                                        groupKey: mapKey,
                                        payment: p,
                                        count: 1,
                                        totalAmount: Number(p.amount),
                                    });
                                });
                            }
                        } else {
                            rows.push({
                                key: 'single_' + first.id,
                                rowType: 'single',
                                groupKey: mapKey,
                                payment: first,
                                count: 1,
                                totalAmount: Number(first.amount),
                            });
                        }
                    });

                    return rows;
                },

                toggleGroup(key) {
                    const idx = this.expandedGroups.indexOf(key);
                    if (idx === -1) {
                        this.expandedGroups.push(key);
                    } else {
                        this.expandedGroups.splice(idx, 1);
                    }
                },

                totalAmount() {
                    return this.clubFilteredPayments.reduce((sum, p) => sum + Number(p.amount), 0);
                },

                amountByType(type) {
                    return this.clubFilteredPayments
                        .filter(p => p.type === type)
                        .reduce((sum, p) => sum + Number(p.amount), 0);
                },

                countByType(type) {
                    return this.clubFilteredPayments.filter(p => p.type === type).length;
                },

                getClubName(clubId) {
                    const club = this.clubs.find(c => c.id == clubId);
                    return club ? club.name : ('Клуб #' + clubId);
                },

                getTypeBadgeClass(type) {
                    const map = {
                        'Игра': 'game',
                        'ДопИгра': 'dopgame',
                        'Иммунитет/музыка': 'immunity',
                        'Прочее': 'other',
                    };
                    return map[type] || 'other';
                },

                formatCurrency(amount) {
                    return new Intl.NumberFormat('ru-RU', {
                        style: 'currency',
                        currency: 'RUB',
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    }).format(amount);
                },

                formatDate(dateStr) {
                    if (!dateStr) return '—';
                    const date = new Date(dateStr);
                    return date.toLocaleString('ru-RU', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                }
            }
        }
    </script>
@endsection
