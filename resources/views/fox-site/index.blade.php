@extends('layouts.fox-index')

@section('content')
    <main>

        <section class="intro intro--desktop">
            <div class="intro-container container">
                <div class="intro-bg-left">
                    <img src="{{ asset('images/fox/mafia-bg.png') }}" alt="mafia-bg">
                </div>

                <div class="intro-content">
                    <div class="intro-left">
                        <p>
                            <b>Наша целевая аудитория:</b> <br>
                            предприниматели, блогеры, <br> успешные личности 25–45 лет,<br> которые ищут
                            качественный <br> «общительный» досуг и <br> интересные, долгосрочные <br> знакомства.
                        </p>
                    </div>

                    <div class="intro-center">
                        <img class="intro-center__bg" src="{{ asset('images/fox/center-bg.png') }}">
                        <div class="intro-center-inner">
                            <h1 class="intro-title h2">Игры в мафию <br> в Москве </h1>
                            <img class="intro-center__logo" src="{{ asset('images/fox/fox-logo.png') }}" alt="">
                            <p class="intro-subtitle">Добро пожаловать <br>
                                в Федерацию Ф.О.К.С. <br>
                                (клуб мафии "Хитрый Лис")</p>
                            <p class="intro-quote">Место, где ложь становится <br> искусством, а победа — результатом
                                <br> выбранной стратегии</p>
                        </div>

                    </div>
                    <div class="intro-right">
                        <p>
                            <b> Ключевая задача клуба:</b><br>
                            превратить посетителя <br> в постоянного резидента, <br> который станет частью <br>
                            сообщества, где все
                            друг <br> другу нужны и полезны <br> взаимно.
                        </p>
                    </div>
                </div>
                <div class="intro-bg-right">
                    <img src="{{ asset('images/fox/peaceful-bg.png') }}" alt="peaceful-bg">
                </div>

            </div>
        </section>

        <section class="intro intro--mob">
            <div class="intro-container ">
                <div class="container">
                    <h1 class="intro-title">Игры в мафию в Москве </h1>

                    <div class="intro-bg-top">
                        <img src="{{ asset('images/fox/mafia-bg.png') }}" alt="mafia-bg">
                    </div>

                    <div class="intro-top">
                        <img class="intro-center__logo" src="{{ asset('images/fox/fox-logo.png') }}" alt="">
                        <p class="intro-quote">
                            Место, где ложь <br> становится <br> искусством,<br>
                            а победа — <br> результатом <br> выбранной <br> стратегии
                        </p>
                    </div>
                </div>


                <div class="intro-content container">

                    <img class="intro-content__bg" src="{{ asset('images/fox/center-bg-mob.png') }}">
                    <p class="intro-subtitle">Добро пожаловать <br>
                        в Федерацию Ф.О.К.С. <br>
                        (клуб мафии "Хитрый Лис")
                    </p>
                    <p class="intro-content-top-text"><b>Ключевая задача клуба:</b> превратить посетителя в постоянного
                        резидента, который станет частью сообщества, где все друг другу нужны и полезны взаимно.
                    </p>
                    <br>
                    <p class="intro-content-bottom-text"><b>Наша целевая аудитория:</b> предприниматели, блогеры,
                        успешные личности 25–45 лет, которые ищут качественный «общительный» досуг и интересные,
                        долгосрочные знакомства.
                    </p>

                </div>
            </div>
        </section>

        {{--   SCHEDULE   --}}
        <section class="schedule" x-data="initSliderCalendar">
            <img class="mask-decor" src="{{ asset('images/fox/decor-left.png') }}">
            <img class="bg-table-decor" src="{{ asset('images/fox/bg-table.png') }}">


            <div class="schedule-top container">
                <div class="schedule-left">
                    <p>Стоимость игрового <br>
                        вечера — 2000 руб. <br>
                        (*заказы по меню в ресторане <br> оплачиваются отдельно)
                    </p>
                </div>
                <div class="schedule-center">
                    <form class="intro-form" @submit.prevent="sendForm($event)">
                        <div class="form-box">
                            <input class="form-input" type="text" name="name" placeholder="ФИО" required>
                        </div>
                        <div class="form-box">
                            <input class="form-input" type="text" name="phone" placeholder="+7 ( ) _________  ______  ______"
                                   required>
                        </div>
                        <div class="form-box">
                            <input class="form-input" type="text" name="mess" placeholder="Комментарии">
                        </div>
                        <button class="btn--primary btn-submit ripple-button">
                            <span>Записаться на игру</span>
                        </button>

                        <div class="schedule-center-mob-content">
                            <p class="_text text-md">Стоимость игрового
                                вечера — 2000 руб. <br>
                                (*заказы по меню в ресторане оплачиваются отдельно)
                            </p>
                            <p class="btn--primary _btn-text">Выезд ведущего на Ваше <br>
                                мероприятие (корпоратив)
                            </p>
                        </div>

                    </form>

                    <div class="intro-center-decor h4">
                        <span>РАСПИСАНИЕ</span>
                    </div>
                </div>

                <div class="schedule-right ">
                    <p class="btn--primary">Выезд ведущего на Ваше <br>
                        мероприятие (корпоратив)
                    </p>
                    <div class="decor-line --right">
                        <div class="decor-line-inner"></div>
                    </div>
                </div>

            </div>

            <div class="container-min" id="schedule">
                <div class="schedule-slide ">
                    <div class="swiper" x-ref="swiper">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="schedule-date">
                                    <div class="schedule-date__top">
                                        <span class="schedule-date__date h1">ПН</span>
                                    </div>
                                    <button @click="setDay('Понедельник')"
                                            class="schedule-date__btn btn--red ripple-button">
                                        <span>Записаться</span>
                                    </button>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="schedule-date">
                                    <div class="schedule-date__top">
                                        <span class="schedule-date__date h1">ВТ</span>
                                    </div>
                                    <button @click="setDay('Вторник')"
                                            class="schedule-date__btn btn--red ripple-button">
                                        <span>Записаться</span>
                                    </button>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="schedule-date">
                                    <div class="schedule-date__top">
                                        <span class="schedule-date__date h1">СР</span>
                                    </div>
                                    <button @click="setDay('Среда')" class="schedule-date__btn btn--red ripple-button">
                                        <span>Записаться</span>
                                    </button>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="schedule-date">
                                    <div class="schedule-date__top">
                                        <span class="schedule-date__date h1">ЧТ</span>
                                    </div>
                                    <button @click="setDay('Четверг')"
                                            class="schedule-date__btn btn--red ripple-button">
                                        <span>Записаться</span>
                                    </button>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="schedule-date">
                                    <div class="schedule-date__top">
                                        <span class="schedule-date__date h1">ПТ</span>
                                    </div>
                                    <button @click="setDay('Пятница')"
                                            class="schedule-date__btn btn--red ripple-button">
                                        <span>Записаться</span>
                                    </button>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="schedule-date">
                                    <div class="schedule-date__top">
                                        <span class="schedule-date__date h1">СБ</span>
                                    </div>
                                    <button @click="setDay('Суббота')"
                                            class="schedule-date__btn btn--red ripple-button">
                                        <span>Записаться</span>
                                    </button>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="schedule-date">
                                    <div class="schedule-date__top">
                                        <span class="schedule-date__date h1">ВС</span>
                                    </div>
                                    <button @click="setDay('Воскресенье')"
                                            class="schedule-date__btn btn--red ripple-button">
                                        <span>Записаться</span>
                                    </button>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="schedule-date">
                                    <div class="schedule-date__top">
                                        <span class="schedule-date__date h1">ПН</span>
                                    </div>
                                    <button @click="setDay('Понедельник')"
                                            class="schedule-date__btn btn--red ripple-button">
                                        <span>Записаться</span>
                                    </button>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="schedule-date">
                                    <div class="schedule-date__top">
                                        <span class="schedule-date__date h1">ВТ</span>
                                    </div>
                                    <button @click="setDay('Вторник')"
                                            class="schedule-date__btn btn--red ripple-button">
                                        <span>Записаться</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-nav-prev pointer" x-ref="prev">
                        <svg width="9" height="15" viewBox="0 0 9 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M0.292893 8.07112C-0.097631 7.6806 -0.097631 7.04743 0.292893 6.65691L6.65685 0.292946C7.04738 -0.0975785 7.68054 -0.0975785 8.07107 0.292946C8.46159 0.68347 8.46159 1.31664 8.07107 1.70716L2.41421 7.36401L8.07107 13.0209C8.46159 13.4114 8.46159 14.0446 8.07107 14.4351C7.68054 14.8256 7.04738 14.8256 6.65685 14.4351L0.292893 8.07112ZM2 7.36401V8.36401H1V7.36401V6.36401H2V7.36401Z"
                                fill="black"/>
                        </svg>
                    </div>
                    <div class="swiper-nav-next pointer" x-ref="next">
                        <svg width="9" height="15" viewBox="0 0 9 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M8.07112 8.07112C8.46164 7.6806 8.46164 7.04743 8.07112 6.65691L1.70716 0.292946C1.31664 -0.0975785 0.68347 -0.0975785 0.292946 0.292946C-0.0975785 0.68347 -0.0975785 1.31664 0.292946 1.70716L5.9498 7.36401L0.292946 13.0209C-0.0975785 13.4114 -0.0975785 14.0446 0.292946 14.4351C0.68347 14.8256 1.31664 14.8256 1.70716 14.4351L8.07112 8.07112ZM6.36401 7.36401V8.36401H7.36401V7.36401V6.36401H6.36401V7.36401Z"
                                fill="black"/>
                        </svg>
                    </div>
                </div>
            </div>

            <p class="container schedule-bottom">
                Мы играем в мафию 5 дней в неделю в премиальных ресторанах Москвы.
            </p>

        </section>

        {{-- atmosphere --}}
        <section class="atmosphere container-min" id="atmosphere">

            <img class="atmosphere__decor-mask decor-mask" src="{{ asset('images/fox/decor-right.png') }}">

            <div class="atmosphere__top">
                <div class="atmosphere__top-line decor-line --left">
                    <div class="decor-line-inner"></div>
                </div>
                <h2 class="atmosphere__title h4">АТМОСФЕРА</h2>
            </div>

            <div class="atmosphere__slider" x-data="initSliderAtmosphere">
                <div class="swiper" x-ref="swiper">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <img src="{{ asset('images/fox/9075c7d680745842550f36dc0dc5d51061481891.jpg') }}"
                                 alt="gallery"/>
                        </div>
                        <div class="swiper-slide">
                            <img src="{{ asset('images/fox/9075c7d680745842550f36dc0dc5d51061481891.jpg') }}"
                                 alt="gallery"/>
                        </div>
                        <div class="swiper-slide">
                            <img src="{{ asset('images/fox/9075c7d680745842550f36dc0dc5d51061481891.jpg') }}"
                                 alt="gallery"/>
                        </div>
                        <div class="swiper-slide">
                            <img src="{{ asset('images/fox/9075c7d680745842550f36dc0dc5d51061481891.jpg') }}"
                                 alt="gallery"/>
                        </div>
                        <div class="swiper-slide">
                            <img src="{{ asset('images/fox/9075c7d680745842550f36dc0dc5d51061481891.jpg') }}"
                                 alt="gallery"/>
                        </div>
                    </div>
                </div>
                <div class="swiper-nav-prev pointer" x-ref="prev">
                    <svg width="9" height="15" viewBox="0 0 9 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M0.292893 8.07112C-0.097631 7.6806 -0.097631 7.04743 0.292893 6.65691L6.65685 0.292946C7.04738 -0.0975785 7.68054 -0.0975785 8.07107 0.292946C8.46159 0.68347 8.46159 1.31664 8.07107 1.70716L2.41421 7.36401L8.07107 13.0209C8.46159 13.4114 8.46159 14.0446 8.07107 14.4351C7.68054 14.8256 7.04738 14.8256 6.65685 14.4351L0.292893 8.07112ZM2 7.36401V8.36401H1V7.36401V6.36401H2V7.36401Z"
                            fill="black"/>
                    </svg>
                </div>
                <div class="swiper-nav-next pointer" x-ref="next">
                    <svg width="9" height="15" viewBox="0 0 9 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M8.07112 8.07112C8.46164 7.6806 8.46164 7.04743 8.07112 6.65691L1.70716 0.292946C1.31664 -0.0975785 0.68347 -0.0975785 0.292946 0.292946C-0.0975785 0.68347 -0.0975785 1.31664 0.292946 1.70716L5.9498 7.36401L0.292946 13.0209C-0.0975785 13.4114 -0.0975785 14.0446 0.292946 14.4351C0.68347 14.8256 1.31664 14.8256 1.70716 14.4351L8.07112 8.07112ZM6.36401 7.36401V8.36401H7.36401V7.36401V6.36401H6.36401V7.36401Z"
                            fill="black"/>
                    </svg>
                </div>
            </div>

            <div class="atmosphere__bottom-line decor-line --right">
                <div class="decor-line-inner"></div>
            </div>

        </section>


        {{--    rules    --}}
        <section class="rules container-min" id="rules">

            <img class="rules__decor-mask-right decor-mask" src="{{ asset('images/fox/decor-2-right.png') }}">

            <img class="rules__decor-mask-left decor-mask" src="{{ asset('images/fox/decor-2-left.png') }}">

            <div class="rules__top-line decor-line --left">
                <div class="decor-line-inner"></div>
            </div>

            <div class="rules__inner" x-data="initRules">
                <h2 class="rules__title h2">РОЛИ И ПРАВИЛА</h2>
                <div class="rules__slide">
                    <div class="swiper" x-ref="swiper">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="rules-item">
                                    <img class="rules-item__img" src="{{ asset('images/fox/rules-1.png') }}"
                                         alt="rules-1">
                                    <div class="rules-item__content">
                                        <h2 class="rules-item__title">Детектив</h2>
                                        <p class="rules-item__status text-lg">Роль за мирных жителей.</p>
                                        <p class="rules-item__text text-lg">
                                            Каждую ночь он может проверить
                                            одного игрока и узнать его роль
                                            или принадлежность к мафии.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="rules-item">
                                    <img class="rules-item__img" src="{{ asset('images/fox/rules-2.png') }}"
                                         alt="rules-1">
                                    <div class="rules-item__content">
                                        <h2 class="rules-item__title">Доктор</h2>
                                        <p class="rules-item__status text-lg">Роль за мирных жителей.</p>
                                        <p class="rules-item__text text-lg">
                                            Каждую ночь он может вылечить
                                            одного игрока (в т.ч. себя — один
                                            раз за игру), чтобы спасти его
                                            от «выстрела» мафии.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="rules-item">
                                    <img class="rules-item__img" src="{{ asset('images/fox/rules-3.png') }}"
                                         alt="rules-1">
                                    <div class="rules-item__content">
                                        <h2 class="rules-item__title">Шериф</h2>
                                        <p class="rules-item__status text-lg">Роль за мирных жителей.</p>
                                        <p class="rules-item__text text-lg">
                                            Каждую ночь он может проверить
                                            одного игрока и узнать, принадлежит ли тот к мафии
                                            (да/нет).
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="rules-item">
                                    <img class="rules-item__img" src="{{ asset('images/fox/rules-4.png') }}"
                                         alt="rules-1">
                                    <div class="rules-item__content">
                                        <h2 class="rules-item__title">Стрелочник</h2>
                                        <p class="rules-item__status text-lg">Роль за мирных жителей.</p>
                                        <p class="rules-item__text text-lg">
                                            Его задача — спровоцировать мафию и/или
                                            маньяка выстрелить в него ночью,
                                            чтобы после «пробуждения» раскрыть свою роль и предоставить городу
                                            информацию о
                                            том, кто в него
                                            целился.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            {{--  скрытые роли  --}}
                            <div class="swiper-slide" x-show="show">
                                <div class="rules-item">
                                    <img class="rules-item__img" src="{{ asset('images/fox/rules-1.png') }}"
                                         alt="rules-1">
                                    <div class="rules-item__content">
                                        <h2 class="rules-item__title">Детектив</h2>
                                        <p class="rules-item__status text-lg">Роль за мирных жителей.</p>
                                        <p class="rules-item__text text-lg">
                                            Каждую ночь он может проверить
                                            одного игрока и узнать его роль
                                            или принадлежность к мафии.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide" x-show="show">
                                <div class="rules-item">
                                    <img class="rules-item__img" src="{{ asset('images/fox/rules-2.png') }}"
                                         alt="rules-1">
                                    <div class="rules-item__content">
                                        <h2 class="rules-item__title">Доктор</h2>
                                        <p class="rules-item__status text-lg">Роль за мирных жителей.</p>
                                        <p class="rules-item__text text-lg">
                                            Каждую ночь он может вылечить
                                            одного игрока (в т.ч. себя — один
                                            раз за игру), чтобы спасти его
                                            от «выстрела» мафии.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide" x-show="show">
                                <div class="rules-item">
                                    <img class="rules-item__img" src="{{ asset('images/fox/rules-3.png') }}"
                                         alt="rules-1">
                                    <div class="rules-item__content">
                                        <h2 class="rules-item__title">Шериф</h2>
                                        <p class="rules-item__status text-lg">Роль за мирных жителей.</p>
                                        <p class="rules-item__text text-lg">
                                            Каждую ночь он может проверить
                                            одного игрока и узнать, принадлежит ли тот к мафии
                                            (да/нет).
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide" x-show="show">
                                <div class="rules-item">
                                    <img class="rules-item__img" src="{{ asset('images/fox/rules-4.png') }}"
                                         alt="rules-1">
                                    <div class="rules-item__content">
                                        <h2 class="rules-item__title">Стрелочник</h2>
                                        <p class="rules-item__status text-lg">Роль за мирных жителей.</p>
                                        <p class="rules-item__text text-lg">
                                            Его задача — спровоцировать мафию и/или
                                            маньяка выстрелить в него ночью,
                                            чтобы после «пробуждения» раскрыть свою роль и предоставить городу
                                            информацию о
                                            том, кто в него
                                            целился.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-nav-prev pointer" x-ref="prev">
                        <svg width="9" height="15" viewBox="0 0 9 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M0.292893 8.07112C-0.097631 7.6806 -0.097631 7.04743 0.292893 6.65691L6.65685 0.292946C7.04738 -0.0975785 7.68054 -0.0975785 8.07107 0.292946C8.46159 0.68347 8.46159 1.31664 8.07107 1.70716L2.41421 7.36401L8.07107 13.0209C8.46159 13.4114 8.46159 14.0446 8.07107 14.4351C7.68054 14.8256 7.04738 14.8256 6.65685 14.4351L0.292893 8.07112ZM2 7.36401V8.36401H1V7.36401V6.36401H2V7.36401Z"
                                fill="black"/>
                        </svg>
                    </div>
                    <div class="swiper-nav-next pointer" x-ref="next">
                        <svg width="9" height="15" viewBox="0 0 9 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M8.07112 8.07112C8.46164 7.6806 8.46164 7.04743 8.07112 6.65691L1.70716 0.292946C1.31664 -0.0975785 0.68347 -0.0975785 0.292946 0.292946C-0.0975785 0.68347 -0.0975785 1.31664 0.292946 1.70716L5.9498 7.36401L0.292946 13.0209C-0.0975785 13.4114 -0.0975785 14.0446 0.292946 14.4351C0.68347 14.8256 1.31664 14.8256 1.70716 14.4351L8.07112 8.07112ZM6.36401 7.36401V8.36401H7.36401V7.36401V6.36401H6.36401V7.36401Z"
                                fill="black"/>
                        </svg>
                    </div>

                    <div class="rules__more pointer" @click="show=!show" :class="{'_show': show}">
                        <span x-text="show?'Скрыть':'Смотреть все роли'"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m6 9 6 6 6-6"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="rules-info">
                <div class="rules-info__top">
                    <h2 class="rules-info__title">Правила</h2>
                    <div class="rules-info__line decor-line --right">
                        <div class="decor-line-inner"></div>
                    </div>
                </div>

                <div class="rules-info__text">
                    <p>Игра делится на дневную и ночную фазы. Днём все обсуждают и голосуют, ночью активны особые
                        роли.</p>
                    <p>Во время голосования каждый игрок поднимает руку за одного подозреваемого. Игрок с наибольшим
                        количеством голосов выбывает.</p>
                    <p>Запрещено показывать свою карту другим игрокам, использовать метаинформацию, выходить из образа
                        после выбывания.</p>
                    <p>Мирные жители побеждают, когда все члены мафии выбывают из игры.</p>
                    <p>Мафия побеждает, когда количество мафиози становится равным или превышает количество мирных
                        жителей.</p>
                </div>

            </div>

        </section>


        {{--    location   --}}
        <section class="location container-min" id="location">
            <img class="location__decor-mask-left decor-mask" src="{{ asset('images/fox/decor-3-left.png') }}">
            <div class="location__inner">
                <h2 class="location__title h4">ЛОКАЦИИ ИГР</h2>
                <p class="location__subtitle"><b>Игры проходят в ресторанах с качественным обслуживанием
                        премиального сегмента в центре Москвы</b></p>

                <div class="location__slider" x-data="initSwiperLocation">

                    <div class="location__top-line decor-line --left">
                        <div class="decor-line-inner"></div>
                    </div>

                    <div class="swiper" x-ref="swiper">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <img src="{{ asset('images/fox/9075c7d680745842550f36dc0dc5d51061481891.jpg') }}"
                                     alt="gallery"/>
                            </div>
                            <div class="swiper-slide">
                                <img src="{{ asset('images/fox/9075c7d680745842550f36dc0dc5d51061481891.jpg') }}"
                                     alt="gallery"/>
                            </div>
                            <div class="swiper-slide">
                                <img src="{{ asset('images/fox/9075c7d680745842550f36dc0dc5d51061481891.jpg') }}"
                                     alt="gallery"/>
                            </div>
                            <div class="swiper-slide">
                                <img src="{{ asset('images/fox/9075c7d680745842550f36dc0dc5d51061481891.jpg') }}"
                                     alt="gallery"/>
                            </div>
                            <div class="swiper-slide">
                                <img src="{{ asset('images/fox/9075c7d680745842550f36dc0dc5d51061481891.jpg') }}"
                                     alt="gallery"/>
                            </div>
                        </div>
                    </div>

                    <div class="location__bottom-line decor-line --right">
                        <div class="decor-line-inner"></div>
                    </div>

                    <div class="swiper-nav-prev pointer" x-ref="prev">
                        <svg width="9" height="15" viewBox="0 0 9 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M0.292893 8.07112C-0.097631 7.6806 -0.097631 7.04743 0.292893 6.65691L6.65685 0.292946C7.04738 -0.0975785 7.68054 -0.0975785 8.07107 0.292946C8.46159 0.68347 8.46159 1.31664 8.07107 1.70716L2.41421 7.36401L8.07107 13.0209C8.46159 13.4114 8.46159 14.0446 8.07107 14.4351C7.68054 14.8256 7.04738 14.8256 6.65685 14.4351L0.292893 8.07112ZM2 7.36401V8.36401H1V7.36401V6.36401H2V7.36401Z"
                                fill="black"/>
                        </svg>
                    </div>
                    <div class="swiper-nav-next pointer" x-ref="next">
                        <svg width="9" height="15" viewBox="0 0 9 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M8.07112 8.07112C8.46164 7.6806 8.46164 7.04743 8.07112 6.65691L1.70716 0.292946C1.31664 -0.0975785 0.68347 -0.0975785 0.292946 0.292946C-0.0975785 0.68347 -0.0975785 1.31664 0.292946 1.70716L5.9498 7.36401L0.292946 13.0209C-0.0975785 13.4114 -0.0975785 14.0446 0.292946 14.4351C0.68347 14.8256 1.31664 14.8256 1.70716 14.4351L8.07112 8.07112ZM6.36401 7.36401V8.36401H7.36401V7.36401V6.36401H6.36401V7.36401Z"
                                fill="black"/>
                        </svg>
                    </div>
                </div>

                <p class="location__bottom-text text-lg">Mansard restaurant and hall, Москва, улица Лавочкина, 7Ас1
                </p>

            </div>
        </section>


        {{--   PLATFORMA     --}}
        <section class="platform container-min" id="platform">
            <div class="platform__top-line decor-line --left">
                <div class="decor-line-inner"></div>
            </div>
            <div class="platform__inner">

                <img class="platform__decor-mask-left decor-mask" src="{{ asset('images/fox/decor-4-left.png') }}">

                <img class="platform__decor-mask-right decor-mask" src="{{ asset('images/fox/decor-3-right.png') }}">

                <h2 class="platform__title h4">НАША ПЛАТФОРМА</h2>

                <div class="platform__content">

                    <div class="platform__left">
                        <p>
                            У нас есть собственное приложение <br>
                            и Telegram-бот для записи, <br>
                            управления рейтингами, <br>
                            ИИ-оркестратором, ачивками <br>
                            и оплатой
                        </p>
                    </div>

                    <div class="platform__center">
                        <img src="{{ asset('images/fox/phone.png') }}" alt="phone">
                    </div>

                    <div class="platform__right">
                        <div class="platform__tg">
                            <div class="platform__tg-label text-lg">Наш Telegram-бот</div>
                            <a class="h4" href="https://t.me/FoxFederation">@FOKSPORTALbot</a>
                        </div>
                    </div>

                </div>

            </div>

            <div class="platform__bottom-line decor-line --left">
                <div class="decor-line-inner"></div>
            </div>

        </section>

        {{--     STANDARDS       --}}
        <section class="standards container-min" id="standards">

            <img class="location__decor-mask-left decor-mask" src="{{ asset('images/fox/decor-5-left.png') }}">

            <img class="location__decor-mask-right decor-mask" src="{{ asset('images/fox/decor-4-right.png') }}">

            <div class="standards__top-line decor-line --right">
                <div class="decor-line-inner"></div>
            </div>

            <div class="standards__bottom-line decor-line --left">
                <div class="decor-line-inner"></div>
            </div>

            <div class="standards__inner">
                <h2 class="standards__title h4">СТАНДАРТЫ</h2>

                <div class="standards__slider" x-data="initSwiperStandards">

                    <div class="swiper" x-ref="swiper">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="standard-el">
                                    <picture>
                                        <source srcset="{{ asset('images/fox/23-mob.png') }}"
                                                media="(max-width: 768px)">
                                        <img class="standard-el__img" src="{{ asset('images/fox/23.jpg') }}"
                                             alt="gallery"/>
                                    </picture>


                                    <div class="standard-el__content">
                                        <h3 class="standard-el__title">Стандарты для ведущего:</h3>
                                        <ul class="standard-el__list">
                                            <li class="standard-el__item">харизма и «держание зала»</li>
                                            <li class="standard-el__item"> актёрское мастерство</li>
                                            <li class="standard-el__item">чувство юмора</li>
                                            <li class="standard-el__item">грамотная речь и опрятный вид</li>
                                            <li class="standard-el__item">стрессоустойчивость и многозадачность</li>
                                        </ul>
                                    </div>

                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="standard-el">

                                    <img class="standard-el__img"
                                         src="{{ asset('images/fox/9075c7d680745842550f36dc0dc5d51061481891.jpg') }}"
                                         alt="gallery"/>

                                    <div class="standard-el__content">
                                        <h3 class="standard-el__title">Стандарты игрового процесса:</h3>
                                        <ul class="standard-el__list">
                                            <li class="standard-el__item">тайминг</li>
                                            <li class="standard-el__item">чёткость правил</li>
                                            <li class="standard-el__item">рейтинговый характер игр</li>
                                            <li class="standard-el__item">политичность и уважение внутри стола</li>
                                            <li class="standard-el__item">обучение и адаптация новых игроков</li>
                                        </ul>
                                    </div>

                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="standard-el">

                                    <img class="standard-el__img"
                                         src="{{ asset('images/fox/9075c7d680745842550f36dc0dc5d51061481891.jpg') }}"
                                         alt="gallery"/>

                                    <div class="standard-el__content">
                                        <h3 class="standard-el__title">Стандарты атмосферы и сервиса:</h3>
                                        <ul class="standard-el__list">
                                            <li class="standard-el__item">премиальные локации</li>
                                            <li class="standard-el__item">уважение и такт внутри сообщества</li>
                                            <li class="standard-el__item">комфорт и сервис</li>
                                            <li class="standard-el__item">сообщество (полезные встречи и досуг вне игр в мафию)</li>
                                        </ul>
                                    </div>

                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="standard-el">

                                    <img class="standard-el__img"
                                         src="{{ asset('images/fox/9075c7d680745842550f36dc0dc5d51061481891.jpg') }}"
                                         alt="gallery"/>

                                    <div class="standard-el__content">
                                        <h3 class="standard-el__title">Стандарты платформы и технологий:</h3>
                                        <ul class="standard-el__list">
                                            <li class="standard-el__item"> собственное приложение и бот для записи, управления рейтингами, достижения ачивок и получения уведомлений</li>
                                            <li class="standard-el__item">прозрачность: все результаты игр и изменения в рейтинге фиксируются и доступны через бот</li>
                                            <li class="standard-el__item">интеграция: плавная и удобная запись на игры через бот
                                                <a href="https://t.me/DeceptionFederation_bot">@DeceptionFederation_bot</a>   </li>
                                        </ul>
                                    </div>

                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="standard-el">

                                    <img class="standard-el__img"
                                         src="{{ asset('images/fox/9075c7d680745842550f36dc0dc5d51061481891.jpg') }}"
                                         alt="gallery"/>

                                    <div class="standard-el__content">
                                        <h3 class="standard-el__title">Стандарты для организаторов:</h3>
                                        <ul class="standard-el__list">
                                            <li class="standard-el__item">честность и открытость</li>
                                            <li class="standard-el__item">защита сообщества</li>
                                            <li class="standard-el__item">качество «продукта» и атмосферы</li>
                                            <li class="standard-el__item">комфорт и гостеприимство</li>
                                            <li class="standard-el__item">независимость и непредвзятость ко всем резидентам сообщества</li>
                                            <li class="standard-el__item">прозрачность «политики банов и наказаний» для нарушителей правил</li>
                                        </ul>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="swiper-nav-prev pointer" x-ref="prev">
                        <svg width="9" height="15" viewBox="0 0 9 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M0.292893 8.07112C-0.097631 7.6806 -0.097631 7.04743 0.292893 6.65691L6.65685 0.292946C7.04738 -0.0975785 7.68054 -0.0975785 8.07107 0.292946C8.46159 0.68347 8.46159 1.31664 8.07107 1.70716L2.41421 7.36401L8.07107 13.0209C8.46159 13.4114 8.46159 14.0446 8.07107 14.4351C7.68054 14.8256 7.04738 14.8256 6.65685 14.4351L0.292893 8.07112ZM2 7.36401V8.36401H1V7.36401V6.36401H2V7.36401Z"
                                fill="black"/>
                        </svg>
                    </div>
                    <div class="swiper-nav-next pointer" x-ref="next">
                        <svg width="9" height="15" viewBox="0 0 9 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M8.07112 8.07112C8.46164 7.6806 8.46164 7.04743 8.07112 6.65691L1.70716 0.292946C1.31664 -0.0975785 0.68347 -0.0975785 0.292946 0.292946C-0.0975785 0.68347 -0.0975785 1.31664 0.292946 1.70716L5.9498 7.36401L0.292946 13.0209C-0.0975785 13.4114 -0.0975785 14.0446 0.292946 14.4351C0.68347 14.8256 1.31664 14.8256 1.70716 14.4351L8.07112 8.07112ZM6.36401 7.36401V8.36401H7.36401V7.36401V6.36401H6.36401V7.36401Z"
                                fill="black"/>
                        </svg>
                    </div>
                </div>

            </div>
        </section>


        {{--   RATING     --}}
        <section class="rating container-min" id="rating">

            <img class="rating__decor-mask-left decor-mask" src="{{ asset('images/fox/decor-left.png') }}">

            <img class="rating__decor-mask-right decor-mask" src="{{ asset('images/fox/decor-5-right.png') }}">

            <div class="rating__top-line decor-line --left">
                <div class="decor-line-inner"></div>
            </div>

            <div class="rating__inner">
                <h2 class="rating__title h4">РЕЙТИНГ</h2>

                <div class="rating__list">
                    <img src="{{ asset('images/fox/rating.png') }}" alt="rating">
                </div>

            </div>
        </section>

        {{--   CONTACTS   --}}
        <section class="contacts container-min" id="contacts">

            <img class="contacts__decor-mask-left decor-mask" src="{{ asset('images/fox/decor-6-left.png') }}">

            <div class="contacts__top-line decor-line --right">
                <div class="decor-line-inner"></div>
            </div>
            <div class="contacts__inner">
                <h2 class="contacts__title h4">КОНТАКТЫ</h2>
                <div class="fox-footer__socials">
                    <a href="https://t.me" target="_blank" class="fox-footer__social-link _tg" aria-label="Telegram">
                        <img src="http://127.0.0.1:8000/images/fox/tg.svg" alt="telegram">
                    </a>
                    <a href="#" target="_blank" class="fox-footer__social-link _inst" aria-label="Instagram">
                        <img src="http://127.0.0.1:8000/images/fox/insta.svg" alt="instagram">
                    </a>
                    <a href="#" target="_blank" class="fox-footer__social-link _vk" aria-label="VK">
                        <img src="http://127.0.0.1:8000/images/fox/vk.svg" alt="vk">
                    </a>
                </div>
                <div class="contacts__box text-lg">
                    <p>Есть вопрос? Пишите в Telegram:</p>

                    <a href="https://t.me/FoxFederation" target="_blank" class="contacts__link">@FoxFederation</a>
                </div>
                <br>
                <div class="contacts__box text-lg">
                    <p>Telegram-бот:</p>

                    <a href="https://t.me/DeceptionFederation_bot" target="_blank" class="contacts__link">@DeceptionFederation_bot</a>
                </div>
            </div>
        </section>


        {{--   form     --}}
        <section class="footer-form container-min" id="footer-form">
            <div class="footer-form__inner">
                <form class="intro-form" @submit.prevent="sendForm($event)">
                    <div class="form-box">
                        <input class="form-input" type="text" name="name" placeholder="ФИО" required>
                    </div>
                    <div class="form-box">
                        <input class="form-input" type="text" name="phone" placeholder="+7 ( ) _________  ______  ______" required>
                    </div>
                    <div class="form-box">
                        <div
                            x-data="{
                                open: false,
                                options: ['Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота', 'Воскресенье',]
                              }"
                            @click.away="open = false"
                            class="app-select"
                        >

                            <!-- Скрытый инпут для отправки значения на бэкенд -->
                            <input type="hidden" name="participation_date" :value="selected" required>

                            <button
                                type="button"
                                class="app-select__button form-input"
                                @click="open = !open"
                            >
                                <!-- Плейсхолдер или выбранное значение -->
                                <input
                                    name="participation_date"
                                    class="app-select__title"
                                    style="pointer-events: none"
                                    :value="selected"
                                    required
                                    :class="{ 'app-select__title--active': selected }"
                                    placeholder="Выберите день"
                                />

                                <!-- Иконка стрелочки (вращается при открытии) -->
                                <svg
                                    class="app-select__arrow"
                                    :class="{ 'app-select__arrow--open': open }"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <!-- Выпадающее меню с опциями -->
                            <div
                                class="app-select__dropdown"
                                x-show="open"
                                x-transition
                            >
                                <template x-for="option in options" :key="option">
                                    <div
                                        class="app-select__option"
                                        x-text="option"
                                        @click="selected = option; open = false"
                                    ></div>
                                </template>
                            </div>

                        </div>
                    </div>

                    <div class="form-box">
                        <input class="form-input" type="text" name="mess" placeholder="Комментарии">
                    </div>

                    <div class="app-checkbox">
                        <label class="app-checkbox__label">

                            <input type="checkbox" name="agree" class="app-checkbox__real" required>

                            <span class="app-checkbox__fake">
                                  <svg class="app-checkbox__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                          d="M5 13l4 4L19 7"/>
                                  </svg>
                                </span>
                            <span class="app-checkbox__text">
                                  Соглашаюсь на обработку персональных данных
                            </span>
                        </label>
                    </div>

                    <button class="btn--primary btn-submit ripple-button">
                        <span>Записаться на игру</span>
                    </button>
                </form>
            </div>
        </section>

        <div class="modal" x-cloak x-show="success.show">
            <div class="modal-inner" @click.outside="success.show=false">
                <svg class="modal-close" @click="success.show=false" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18"/>
                    <path d="m6 6 12 12"/>
                </svg>
                <h1 class="modal-text h3" x-text="success.text"></h1>
            </div>
        </div>


    </main>
@endsection


@push('script')
    <script>
        document.addEventListener("alpine:init", () => {

            Alpine.data("initSliderCalendar", () => ({
                swiperInstance: null,


                init() {
                    this.$nextTick(() => {
                        setTimeout(() => this.initSwiper(), 50)
                    });
                },


                initSwiper() {
                    if (typeof Swiper !== "undefined" && this.$refs.swiper) {
                        this.swiperInstance = new Swiper(this.$refs.swiper, {
                            speed: 600,
                            centeredSlides: true,
                            loop: true,
                            initialSlide: window.innerWidth <= 769 ? 1 : 2,

                            breakpoints: {
                                300: {
                                    slidesPerView: 3,
                                    spaceBetween: 16
                                },

                                769: {
                                    slidesPerView: 3,
                                    spaceBetween: 20
                                },
                                1024: {
                                    slidesPerView: 5,
                                    spaceBetween: 20
                                }
                            },

                            navigation: {
                                nextEl: this.$refs.next,
                                prevEl: this.$refs.prev,
                            },
                        });
                    }
                },

            }));

            Alpine.data("initSliderAtmosphere", () => ({
                swiperInstance: null,

                init() {
                    this.$nextTick(() => {
                        this.initSwiper();
                    });
                },

                initSwiper() {
                    if (typeof Swiper !== "undefined" && this.$refs.swiper) {
                        this.swiperInstance = new Swiper(this.$refs.swiper, {
                            speed: 800,
                            centeredSlides: true,
                            initialSlide: 1,
                            loop: true,

                            breakpoints: {
                                300: {
                                    slidesPerView: 1,
                                    spaceBetween: 16
                                },

                                769: {
                                    slidesPerView: 3,
                                    spaceBetween: 50
                                },
                            },

                            navigation: {
                                nextEl: this.$refs.next,
                                prevEl: this.$refs.prev,
                            },
                        });
                    }
                },

            }));

            Alpine.data("initRules", () => ({
                swiperInstance: null,
                show: window.innerWidth < 769,

                init() {
                    this.$nextTick(() => {
                        if (window.innerWidth < 769) {
                            this.initSwiper();
                        }
                    });
                },

                initSwiper() {
                    if (typeof Swiper !== "undefined" && this.$refs.swiper) {
                        this.swiperInstance = new Swiper(this.$refs.swiper, {
                            speed: 800,
                            loop: true,
                            autoHeight: true,

                            breakpoints: {
                                300: {
                                    slidesPerView: 1,
                                    spaceBetween: 16
                                },
                            },

                            navigation: {
                                nextEl: this.$refs.next,
                                prevEl: this.$refs.prev,
                            },
                        });
                    }
                },

            }));

            Alpine.data("initSwiperLocation", () => ({
                swiperInstance: null,

                init() {
                    this.$nextTick(() => {
                        this.initSwiper();
                    });
                },

                initSwiper() {
                    if (typeof Swiper !== "undefined" && this.$refs.swiper) {
                        this.swiperInstance = new Swiper(this.$refs.swiper, {
                            speed: 1000,
                            loop: true,
                            centeredSlides: true,
                            breakpoints: {

                                100: {
                                    slidesPerView: 1,
                                    spaceBetween: 16
                                },

                                769: {
                                    slidesPerView: 3,
                                    spaceBetween: 16
                                },
                            },

                            navigation: {
                                nextEl: this.$refs.next,
                                prevEl: this.$refs.prev,
                            },
                        });
                    }
                },

            }));

            Alpine.data("initSwiperStandards", () => ({
                swiperInstance: null,

                init() {
                    this.$nextTick(() => {
                        this.initSwiper();
                    });
                },

                initSwiper() {
                    if (typeof Swiper !== "undefined" && this.$refs.swiper) {
                        this.swiperInstance = new Swiper(this.$refs.swiper, {
                            speed: 1000,
                            loop: true,
                            centeredSlides: true,
                            breakpoints: {
                                100: {
                                    slidesPerView: 1,
                                    spaceBetween: 16
                                },

                                768: {
                                    slidesPerView: 3,
                                    spaceBetween: 200
                                },

                                800: {
                                    slidesPerView: 3,
                                    spaceBetween: 220
                                },

                                1024: {
                                    slidesPerView: 3,
                                    spaceBetween: 260
                                },

                                1220: {
                                    slidesPerView: 3,
                                    spaceBetween: 300
                                },
                            },

                            navigation: {
                                nextEl: this.$refs.next,
                                prevEl: this.$refs.prev,
                            },
                        });
                    }
                },

            }));


        });


    </script>
@endpush


