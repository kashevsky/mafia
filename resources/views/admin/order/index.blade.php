@extends('admin.layouts.main')
<script src="//unpkg.com/alpinejs" defer></script>
@section('content')
    <style>
        /* Стили для сетки чеков */
        .receipt-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            transition: all 0.3s ease;
            position: relative;
        }

        .receipt-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
        }

        .receipt-image {
            width: 100%;
            height: 180px;
            object-fit: contain;
            cursor: pointer;
        }

        .receipt-info {
            padding: 15px;
        }

        .user-name {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .receipt-date {
            color: #7f8c8d;
            font-size: 14px;
            margin-bottom: 10px;
        }

        /* Статус чека */
        .receipt-status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-confirmed {
            background-color: #d4edda;
            color: #155724;
        }

        .status-rejected {
            background-color: #f8d7da;
            color: #721c24;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        /* Действия с чеком */
        .receipt-actions {
            display: flex;
            gap: 8px;
            margin-top: 10px;
        }

        .btn-status {
            flex: 1;
            padding: 6px 12px;
            font-size: 13px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-confirm {
            background-color: #27ae60;
            color: white;
        }

        .btn-reject {
            background-color: #e74c3c;
            color: white;
        }

        .btn-status:hover {
            opacity: 0.9;
            transform: scale(1.02);
        }

        .fancybox-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            z-index: 2000;
            justify-content: center;
            align-items: center;
            display: flex;
        }

        .fancybox-content {
            max-width: 90%;
            max-height: 90%;
        }

        .fancybox-content img {
            max-width: 100%;
            max-height: 90vh;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            transition: opacity 0.3s ease;
        }

        /* Состояние загрузки */
        .fancybox-loading {
            color: white;
            font-size: 18px;
        }

        /* Уведомления */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 6px;
            z-index: 3000;
            font-size: 14px;
            font-weight: 600;
            opacity: 0;
            transition: opacity 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .notification-success {
            background-color: #27ae60;
            color: white;
        }

        .notification-error {
            background-color: #e74c3c;
            color: white;
        }

        /* Модальное окно подтверждения с суммой */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 2500;
            justify-content: center;
            align-items: center;
            display: flex;
        }

        .modal-content {
            background: white;
            padding: 25px;
            border-radius: 8px;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .modal-content h3 {
            margin-top: 0;
            color: #2c3e50;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .form-control {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .modal-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-primary {
            background-color: #27ae60;
            color: white;
        }

        .btn-secondary {
            background-color: #95a5a6;
            color: white;
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
    </style>

    <div class="page-header">
        <h1>Управление чеками</h1>
        <nav class="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Главная</a></li>
                <li class="breadcrumb-item active">Чеки об оплате</li>
            </ol>
        </nav>
    </div>

    <div class="content-card" x-data="receiptManager">
        <div class="section-title">Список чеков</div>

        <!-- Фильтры -->
        <div class="filters mb-4">
            <div class="filter-group">
                <label class="form-label">Статус чека</label>
                <select class="form-select" x-model="statusFilter">
                    <option value="all">Все</option>
                    <option value="confirmed">Подтверждённые</option>
                    <option value="rejected">Отклонённые</option>
                    <option value="pending">На проверке</option>
                </select>
            </div>

            <div class="filter-group search-box">
                <label class="form-label">Поиск по имени пользователя</label>
                <input type="text" class="form-control" x-model.debounce.300ms="searchUser"
                    placeholder="Введите имя пользователя...">
            </div>
        </div>

        <!-- Сетка чеков -->
        <div class="row g-4">
            <template x-for="receipt in paginatedReceipts" :key="receipt.id">
                <div class="col-md-6 col-lg-4">
                    <div class="receipt-card">
                        <img :src="receipt.src" alt="Чек" class="receipt-image" @click="openFancybox(receipt)">
                        <div class="receipt-info">
                            <div class="user-name" x-text="receipt.account.nickname || receipt.account.name"></div>
                            <div class="receipt-date" x-text="receipt.date"></div>
                            <div :class="`receipt-status status-${receipt.status}`" x-text="getStatusText(receipt.status)">
                            </div>
                            <div class="receipt-actions" x-show="receipt.status === 'pending'">
                                <button class="btn-status btn-confirm" @click="openConfirmModal(receipt.id)"
                                    :disabled="receipt.status === 'confirmed'" x-text="'Подтвердить'"></button>
                                <button class="btn-status btn-reject" @click="rejectReceipt(receipt.id)"
                                    :disabled="receipt.status === 'rejected'" x-text="'Отклонить'"></button>
                            </div>
                            <div class="receipt-actions" x-show="receipt.status != 'pending'">
                                <br>
                            </div>
                        </div>
                    </div>
                </div>  {{-- ИСПРАВЛЕНО: добавлен закрывающий тег для col-md-6 col-lg-4 --}}
            </template>

            <!-- Сообщение, если нет чеков -->
            <div class="col-12 text-center text-muted" x-show="paginatedReceipts.length === 0">
                Чеки не найдены
            </div>
        </div>

        <!-- Пагинация -->
        <div class="pagination mt-4" x-show="totalPages > 1">
            <button class="page-item" @click="goToPreviousPage()" :disabled="currentPage === 1">Назад</button>
            <span class="page-info" x-text="`Страница ${currentPage} из ${totalPages}`"></span>
            <button class="page-item" @click="goToNextPage()" :disabled="currentPage >= totalPages">Вперёд</button>
        </div>

        <div class="filter-group">
            <label class="form-label">&nbsp;</label>
            <button class="btn btn-primary" @click="confirmAllReceipts()" :disabled="!hasPendingReceipts">
                <i class="fas fa-check-circle"></i> Подтвердить все
            </button>
        </div>

        <!-- Fancybox для просмотра чека -->
        <div class="fancybox-overlay" x-show="fancybox.isOpen" @click="closeFancybox()">
            <div class="fancybox-content" @click.stop>
                <img :src="fancybox.image" alt="Полный чек">
                <div style="color: white; margin-top: 15px; text-align: center;">
                    <div><strong>Пользователь:</strong> <span x-text="fancybox.userName"></span></div>
                    <div><strong>Дата:</strong> <span x-text="fancybox.date"></span></div>
                    <div><strong>Статус:</strong>
                        <span :class="`receipt-status status-${fancybox.status}`"
                            x-text="getStatusText(fancybox.status)"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Уведомления -->
        <div x-show="notifications.length > 0" style="z-index: 9999;">
            <template x-for="notification in notifications" :key="notification.id">
                <div class="notification" :class="`notification-${notification.type}`" x-text="notification.message"
                    @click="removeNotification(notification.id)"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 transform translate-y-0"
                    x-transition:leave-end="opacity-0 transform -translate-y-2">
                </div>
            </template>
        </div>

        <!-- Модальное окно подтверждения с суммой -->
        <div class="modal-overlay" x-show="confirmModal.isOpen" @click="closeConfirmModal()">
            <div class="modal-content" @click.stop>
                <h3>Подтверждение чека</h3>
                <p>Укажите сумму для подтверждения:</p>

                <div class="form-group">
                    <label class="form-label">Сумма (руб.)</label>
                    <input type="number" class="form-control" x-model="confirmModal.amount" placeholder="Введите сумму"
                        min="0" step="0.01" required>
                </div>

                <div class="modal-actions">
                    <button class="btn btn-secondary" @click="closeConfirmModal()">Отмена</button>
                    <button class="btn btn-primary" @click="submitConfirmWithAmount()"
                        :disabled="!confirmModal.amount || parseFloat(confirmModal.amount) <= 0">Подтвердить</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('receiptManager', () => ({
                receipts: @json($receipts),
                statusFilter: 'all',
                searchUser: '',
                currentPage: 1,
                itemsPerPage: 6,
                notifications: [],
                fancybox: {
                    isOpen: false,
                    image: '',
                    userName: '',
                    date: '',
                    status: ''
                },
                confirmModal: {
                    isOpen: false,
                    receiptId: null,
                    amount: ''
                },

                get filteredReceipts() {
                    return this.receipts
                        .filter(receipt => {
                            const receiptStatus = receipt.status.toLowerCase();
                            const filterStatus = this.statusFilter.toLowerCase();

                            const matchesStatus = this.statusFilter === 'all' ||
                                receiptStatus === filterStatus;
                            const matchesUser = (receipt.account.nickname || receipt.account.name || '').toLowerCase().includes(
                                this.searchUser.toLowerCase());
                            return matchesStatus && matchesUser;
                        })
                        .sort((a, b) => {
                            if (a.status === 'pending' && b.status !== 'pending') {
                                return -1;
                            }
                            if (a.status !== 'pending' && b.status === 'pending') {
                                return 1;
                            }
                            return new Date(b.date) - new Date(a.date);
                        });
                },

                get paginatedReceipts() {
                    const startIndex = (this.currentPage - 1) * this.itemsPerPage;
                    return this.filteredReceipts.slice(startIndex, startIndex + this.itemsPerPage);
                },

                get totalPages() {
                    return Math.ceil(this.filteredReceipts.length / this.itemsPerPage);
                },

                getStatusText(status) {
                    const statusMap = {
                        'confirmed': 'Подтверждён',
                        'rejected': 'Отклонён',
                        'pending': 'На проверке'
                    };
                    return statusMap[status] || status;
                },

                addNotification(message, type) {
                    const id = Date.now();
                    this.notifications.push({ id, message, type });
                    setTimeout(() => {
                        this.removeNotification(id);
                    }, 5000);
                },

                removeNotification(id) {
                    this.notifications = this.notifications.filter(n => n.id !== id);
                },

                openFancybox(receipt) {
                    this.fancybox = {
                        isOpen: true,
                        image: receipt.src,
                        userName: receipt.account.nickname || receipt.account.name,
                        date: receipt.date,
                        status: receipt.status
                    };
                },

                closeFancybox() {
                    this.fancybox.isOpen = false;
                },

                openConfirmModal(receiptId) {
                    this.confirmModal = {
                        isOpen: true,
                        receiptId: receiptId,
                        amount: ''
                    };
                },

                closeConfirmModal() {
                    this.confirmModal.isOpen = false;
                    this.confirmModal.receiptId = null;
                    this.confirmModal.amount = '';
                },

                async submitConfirmWithAmount() {
                    if (!this.confirmModal.amount || parseFloat(this.confirmModal.amount) <= 0) {
                        this.addNotification('Пожалуйста, укажите корректную сумму', 'error');
                        return;
                    }

                    try {
                        let response = await fetch(
                            `/admin/orders/confirm/${this.confirmModal.receiptId}`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    amount: parseFloat(this.confirmModal.amount)
                                })
                            });

                        if (!response.ok) {
                            throw new Error('Ошибка сервера');
                        }

                        const data = await response.json();
                        this.receipts = data.receipts;

                        this.addNotification(
                            `Чек подтверждён на сумму ${this.confirmModal.amount} руб.`,
                            'success'
                        );

                        this.closeConfirmModal();

                    } catch (error) {
                        this.addNotification('Ошибка подтверждения чека', 'error');
                        console.error('Error confirming receipt with amount:', error);
                    }
                },

                async rejectReceipt(receiptId) {
                    try {
                        let response = await fetch(`/admin/orders/unconfirm/${receiptId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content')
                            }
                        });

                        if (!response.ok) {
                            throw new Error('Ошибка сервера');
                        }

                        response = await response.json();
                        this.receipts = response.receipts;

                        this.addNotification('Чек отклонён', 'warning');
                    } catch (error) {
                        this.addNotification('Ошибка отклонения чека', 'error');
                        console.error('Error rejecting receipt:', error);
                    }
                },

                async confirmAllReceipts() {
                    const pendingReceipts = this.receipts.filter(receipt => receipt.status === 'pending');

                    if (pendingReceipts.length === 0) {
                        this.addNotification('Нет чеков на проверке', 'info');
                        return;
                    }

                    try {
                        let response = await fetch('/api/admin/receipts/confirm-all', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                receiptIds: pendingReceipts.map(r => r.id)
                            })
                        });

                        if (!response.ok) {
                            throw new Error('Ошибка сервера');
                        }

                        pendingReceipts.forEach(receipt => {
                            receipt.status = 'confirmed';
                        });

                        this.addNotification(`Подтверждены ${pendingReceipts.length} чеков`, 'success');
                    } catch (error) {
                        this.addNotification('Ошибка подтверждения всех чеков', 'error');
                        console.error('Error confirming all receipts:', error);
                    }
                },

                get hasPendingReceipts() {
                    return this.receipts.some(receipt => receipt.status === 'pending');
                },

                goToPreviousPage() {
                    if (this.currentPage > 1) {
                        this.currentPage--;
                    }
                },

                goToNextPage() {
                    if (this.currentPage < this.totalPages) {
                        this.currentPage++;
                    }
                }
            }));
        });
    </script>
@endsection
