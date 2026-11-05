@extends('layouts.club')
@section('content')
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Calendar Section -->
    <div class="calendar-section" x-data="calendarPage()">
        <h2 class="section-title">Расписание игр</h2>
        <p x-text="selectedDate"></p>
        <!-- Calendar Header -->
        <div class="calendar-header">
            <button class="nav-btn" @click="prevMonth()">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <div class="month-year" x-text="currentMonthYear"></div>
            <button class="nav-btn" @click="nextMonth()">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>

        <!-- Weekdays -->
        <div class="weekdays-grid">
            <div>Пн</div>
            <div>Вт</div>
            <div>Ср</div>
            <div>Чт</div>
            <div>Пт</div>
            <div>Сб</div>
            <div>Вс</div>
        </div>

        <!-- Dates Grid -->
        <div class="dates-grid">
            <template x-for="blank in blankDaysStart" :key="blank">
                <div class="date-cell blank"></div>
            </template>
            <template x-for="day in daysInMonth" :key="day">
                <div class="date-cell"
                    :class="{
                        'has-event': hasEventOnDate(currentYear, currentMonth, day),
                        'selected': isSelected(day),
                        'today': isToday(day)
                    }"
                    @click="selectDate(day)" x-text="day"></div>
            </template>
        </div>
    </div>

    <!-- Event Popup -->
    <div class="event-popup" x-show="false" @click="closePopupIfOutside($event)">

        <div class="popup-content" @click.stop>
            <div class="popup-header">
                <h3 class="popup-title" x-text="`Игры на ${selectedDateFormatted}`"></h3>
                <button class="popup-close" @click="closePopup()">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <div class="events-list">
                <template x-for="event in getEventsForSelectedDate()" :key="event.id">
                    <div class="event-item">
                        <div>
                            <div class="event-time" x-text="event.time"></div>
                            <div class="event-title" x-text="event.title"></div>
                            <div class="event-location" x-text="event.location"></div>
                        </div>
                        <button class="event-details-btn" @click="showEventDetails(event)">
                            Подробнее
                        </button>
                    </div>
                </template>

                <div x-show="getEventsForSelectedDate().length === 0" class="no-events">
                    На эту дату нет запланированных игр
                </div>
            </div>
        </div>
    </div>


    <style>
        .calendar-section {
            padding: 16px;
            background: white;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 16px;
        }

        .calendar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .nav-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: #6b7280;
            padding: 8px;
        }

        .month-year {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
        }

        .weekdays-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 4px;
            margin-bottom: 8px;
        }

        .weekdays-grid div {
            text-align: center;
            font-size: 12px;
            font-weight: 500;
            color: #6b7280;
            padding: 8px 4px;
        }

        .dates-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 4px;
        }

        .date-cell {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 500;
            color: #1f2937;
            background: #f9fafb;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }

        .date-cell:hover {
            background: #e5e7eb;
        }

        .date-cell.selected {
            background: #dc2626;
            color: white;
        }

        .date-cell.today {
            border: 2px solid #dc2626;
        }

        .date-cell.has-event::after {
            content: '';
            position: absolute;
            bottom: 4px;
            left: 50%;
            transform: translateX(-50%);
            width: 6px;
            height: 6px;
            background: #dc2626;
            border-radius: 50%;
        }

        .date-cell.blank {
            background: transparent;
            cursor: default;
        }

        /* Event Popup Styles */
        .event-popup {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s;
        }

        .event-popup[x-show] {
            opacity: 1;
            pointer-events: auto;
        }

        [x-cloak] {
            display: none !important;
        }

        .popup-content {
            background: white;
            max-width: 90vw;
            max-height: 90vh;
            overflow-y: auto;
            border-radius: 12px;
            width: 400px;
        }

        .popup-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 16px 0 16px;
        }

        .popup-title {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
        }

        .popup-close {
            background: none;
            border: none;
            cursor: pointer;
            color: #6b7280;
            padding: 4px;
        }

        .events-list {
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .event-item {
            display: flex;
            gap: 12px;
            align-items: start;
            padding: 12px;
            background: #f9fafb;
            border-radius: 8px;
            justify-content: space-between;
        }

        .event-time {
            flex-shrink: 0;
            width: 60px;
            font-size: 14px;
            color: #6b7280;
        }

        .event-title {
            font-size: 14px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .event-location {
            font-size: 12px;
            color: #6b7280;
        }

        .event-details-btn {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            border: none;
            border-radius: 6px;
            padding: 8px 12px;
            font-size: 12px;
            cursor: pointer;
        }

        .event-details-btn:hover {
            opacity: 0.9;
        }

        .no-events {
            text-align: center;
            color: #6b7280;
            padding: 16px;
            font-style: italic;
        }

        .event-popup[x-show] {
            opacity: 1;
            pointer-events: auto;
        }
    </style>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <script>
        function calendarPage() {
            return {
                currentDate: new Date(),
                selectedDate: null,
                isPopupOpen: true, // новый флаг
                events: [{
                        id: 1,
                        date: '2026-04-15',
                        time: '19:00',
                        title: 'Вечер психологической игры «Мафия»',
                        location: 'Клуб на Пушкинской, 12',
                        description: 'Классическая игра для новичков и опытных игроков. 3 раунда, разбор ошибок от ведущего.'
                    },
                    {
                        id: 2,
                        date: '2026-04-17',
                        time: '20:00',
                        title: 'Турнир новичков',
                        location: 'Клуб на Пушкинской, 12',
                        description: 'Специальный турнир для тех, кто только знакомится с игрой. Обучение в процессе.'
                    },
                    {
                        id: 3,
                        date: '2026-04-20',
                        time: '18:30',
                        title: 'Обучающая игра для новичков',
                        location: 'Клуб на Пушкинской, 12',
                        description: 'Подробное объяснение правил, разбор стратегий. Подходит для первого знакомства с игрой.'
                    },
                    {
                        id: 4,
                        date: '2026-04-25',
                        time: '19:30',
                        title: 'Тематическая игра: Детектив',
                        location: 'Клуб на Пушкинской, 12',
                        description: 'Необычный сценарий с детективными элементами. Для опытных игроков.'
                    }
                ],

                get currentMonth() {
                    return this.currentDate.getMonth();
                },

                get currentYear() {
                    return this.currentDate.getFullYear();
                },

                get currentMonthYear() {
                    const monthNames = [
                        'Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь',
                        'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'
                    ];
                    return `${monthNames[this.currentMonth]} ${this.currentYear}`;
                },

                get blankDaysStart() {
                    const firstDay = new Date(this.currentYear, this.currentMonth, 1).getDay();
                    // В JS неделя начинается с воскресенья (0), нам нужно с понедельника (пн=1, вс=0)
                    return firstDay === 0 ? 6 : firstDay - 1;
                },

                get daysInMonth() {
                    return new Date(this.currentYear, this.currentMonth + 1, 0).getDate();
                },

                isToday(day) {
                    const today = new Date();
                    return today.getDate() === day &&
                        today.getMonth() === this.currentMonth &&
                        today.getFullYear() === this.currentYear;
                },

                isSelected(day) {
                    if (!this.selectedDate) return false;

                    const selected = new Date(this.selectedDate);
                    this.isPopupOpen = true;
                    return selected.getDate() === day &&
                        selected.getMonth() === this.currentMonth &&
                        selected.getFullYear() === this.currentYear;
                },

                hasEventOnDate(year, month, day) {
                    const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                    return this.events.some(event => event.date === dateStr);
                },

                selectDate(day) {
                    this.selectedDate = new Date(this.currentYear, this.currentMonth, day);
                },

                get selectedDateFormatted() {
                    if (!this.selectedDate) return '';
                    const options = {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    };
                    return this.selectedDate.toLocaleDateString('ru-RU', options);
                },

                getEventsForSelectedDate() {
                    if (!this.selectedDate) return [];
                    const dateStr = this.selectedDate.toISOString().split('T')[0];
                    return this.events.filter(event => event.date === dateStr);
                },

                prevMonth() {
                    this.currentDate = new Date(this.currentYear, this.currentMonth - 1, 1);
                    this.selectedDate = null; // Сбрасываем выбранную дату при смене месяца
                },

                nextMonth() {
                    this.currentDate = new Date(this.currentYear, this.currentMonth + 1, 1);
                    this.selectedDate = null; // Сбрасываем выбранную дату при смене месяца
                },

                closePopup() {
                    this.selectedDate = null;
                    this.isPopupOpen = false; // явно закрываем попап
                },
                closePopupIfOutside(event) {
                    // Закрываем только если клик был вне .popup-content
                    if (!event.target.closest('.popup-content')) {
                        this.closePopup();
                    }
                },

                showEventDetails(event) {
                    alert(
                        `Подробности игры:\n\n${event.title}\nВремя: ${event.time}\nМесто: ${event.location}\n\nОписание: ${event.description}`
                    );
                },

            };
        }
    </script>
@endsection
