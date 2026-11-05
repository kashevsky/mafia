@extends('layouts.club')
@section('content')
    <style>
        .receipt-page {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            font-family: Arial, sans-serif;
        }

        .upload-section,
        .history-section {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .upload-section h3,
        .history-section h3 {
            margin-top: 0;
            color: #333;
        }

        /* Стили для загрузки файла */
        .file-upload {
            margin-bottom: 15px;
        }

        .file-input {
            display: none;
        }

        .file-label {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
            border: 2px dashed #b91c1c;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .file-label:hover {
            border-color: #0056b3;
            background-color: #f8f9fa;
        }

        .upload-icon {
            margin-right: 10px;
            font-size: 18px;
        }

        .file-name {
            margin-top: 8px;
            font-size: 14px;
            color: #666;
        }

        /* Кнопка */
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .btn-primary {
            padding: 12px 42px;
            color: white;
            font-weight: 400;
            background: #b91c1c;
            margin-bottom: 40px;
            font-size: 15px;
            border-radius: 48px;
        }

        .btn-primary:hover {
            background-color: #b91c1c;
        }

        /* История платежей */
        .payments-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .payment-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 6px;
            border: 2px solid #b91c1c;
        }

        .payment-info {
            display: flex;
            gap: 40px;
        }

        .payment-amount {
            font-weight: bold;
            color: #333;
            font-size: 14px;
        }

        .payment-date {
            color: #666;
            font-size: 14px;
        }

        .payment-status {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 8px;
            font-weight: 200;
            text-transform: uppercase;
        }

        .status-confirmed {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .upload-text {
            color: #b91c1c;
        }

        /* Стили уведомлений */
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
            transform: translateY(-20px);
            transition: opacity 0.3s ease, transform 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .notification.show {
            opacity: 1;
            transform: translateY(0);
        }

        .notification-success {
            background-color: #27ae60;
            color: white;
        }

        .notification-error {
            background-color: #e74c3c;
            color: white;
        }
    </style>

    <div class="receipt-page" x-data="receiptManager">
        <h2>Отправка чека об оплате</h2>

        <!-- Форма загрузки чека -->
        <div class="upload-section">
            <h3>Загрузить чек</h3>
            <div class="file-upload">
                <input type="file" id="receiptFile" accept="image/*,.pdf" class="file-input"
                    @change="handleFileChange($event)">
                <label for="receiptFile" class="file-label">
                    <span class="upload-icon">📎</span>
                    <span class="upload-text">Выберите файл чека (изображение или PDF)</span>
                </label>
                <div class="file-name" x-text="fileName"></div>
            </div>

            <button class="btn btn-primary" @click="submitReceipt()" :disabled="isLoading"
                x-text="isLoading ? 'Отправка...' : 'Отправить чек'">
            </button>
        </div>

        <!-- История платежей -->
        <div class="history-section">
            <h3>История платежей</h3>
            <div class="payments-list">
                <template x-for="payment in payments" :key="payment.id">
                    <div class="payment-item" :style="`border: 2px solid ${getBorderColor(payment.status)}`">
                        <div class="payment-info">
                            <span class="payment-amount" x-text="payment.amount ?? '-'"></span>
                            <span class="payment-date" x-text="payment.date"></span>
                        </div>
                        <span class="payment-status" :class="getStatusClass(payment.status)"
                            x-text="getStatus(payment.status)"></span>
                    </div>
                </template>
            </div>
        </div>

        <!-- Уведомления -->
        <template x-for="notification in notifications" :key="notification.id">
            <div class="notification" :class="`notification-${notification.type} ${notification.show ? 'show' : ''}`"
                x-text="notification.message" @click="removeNotification(notification.id)">
            </div>
        </template>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('receiptManager', () => ({
                fileName: 'Файл не выбран',
                accountId: {{ $account->id }},
                isLoading: false,
                notifications: [],
                payments: @json($receipts),

                addNotification(message, type) {
                    const id = Date.now();
                    this.notifications.push({
                        id,
                        message,
                        type,
                        show: false
                    });

                    // Даём время на отрисовку, затем показываем
                    // Даём время на отрисовку, затем показываем
                    setTimeout(() => {
                        const notification = this.notifications.find(n => n.id === id);
                        if (notification) {
                            notification.show = true;
                        }
                    }, 100);

                    // Автоматическое скрытие через 5 секунд
                    setTimeout(() => {
                        this.removeNotification(id);
                    }, 5000);
                },

                removeNotification(id) {
                    // Находим уведомление и плавно убираем его
                    const notification = this.notifications.find(n => n.id === id);
                    if (notification) {
                        notification.show = false;
                    }

                    // Удаляем из массива после завершения анимации (300 мс)
                    setTimeout(() => {
                        this.notifications = this.notifications.filter(n => n.id !== id);
                    }, 300);
                },

                formatDate(dateString = null) {
                    if (!dateString) {
                        const now = new Date();
                        return `${now.getDate().toString().padStart(2, '0')}.${(now.getMonth() + 1).toString().padStart(2, '0')}.${now.getFullYear()}`;
                    }
                    return dateString;
                },

                getStatusClass(status) {
                    return status === 'подтверждено' ? 'status-confirmed' : 'status-pending';
                },

                getStatus(status) {
                    switch (status) {
                        case 'pending':
                            return 'Ожидает';
                        case 'confirmed':
                            return 'Подтверждён';
                        case 'rejected':
                            return 'Отклонён';
                        default:
                            return status;
                    }
                },

                getBorderColor(status) {
                    switch (status) {
                        case 'pending':
                            return '#4b4b4b';
                        case 'confirmed':
                            return '#11af00';
                        case 'rejected':
                            return '#af0000';
                        default:
                            return '#b91c1c';
                    }
                },

                handleFileChange(event) {
                    if (event.target.files.length > 0) {
                        this.fileName = event.target.files[0].name;
                    } else {
                        this.fileName = 'Файл не выбран';
                    }
                },

                async submitReceipt() {
                    const fileInput = document.querySelector('#receiptFile');
                    const file = fileInput.files[0];

                    // Валидация: проверяем, выбран ли файл
                    if (!file) {
                        this.addNotification('Пожалуйста, выберите файл чека', 'error');
                        return;
                    }

                    this.isLoading = true;

                    const formData = new FormData();
                    formData.append('receipt', file);
                    formData.append('account_id', this.accountId);

                    try {
                        const response = await fetch('/payment/receipt', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: formData
                        });

                        if (!response.ok) {
                            throw new Error('Ошибка сервера при отправке чека');
                        }

                        let result = await response.json();
                        this.payments = result.receipts;
                        window.location.href = '/thanks';

                        // Очищаем форму после успешной отправки
                        fileInput.value = '';
                        this.fileName = 'Файл не выбран';

                    } catch (error) {
                        console.error('Ошибка при отправке чека:', error);
                        this.addNotification(
                            'Не удалось отправить чек. Проверьте подключение к сети.', 'error'
                        );
                    } finally {
                        this.isLoading = false;
                    }
                }
            }));
        });
    </script>
@endsection
