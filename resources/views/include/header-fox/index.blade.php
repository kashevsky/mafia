<header class="header container">
    <div class="header-container ">
        <div class="header-logo">
            <img src="{{ asset('images/fox/logo.svg') }}" alt="logo fox">
        </div>

        <nav class="header-nav">
            <a href="#schedule" data-nav><span>Расписание</span></a>
            <a href="#atmosphere" data-nav><span>Атмосфера</span></a>
            <a href="#rules" data-nav><span>Роли и правила</span></a>
            <a href="#location" data-nav><span>Локации игр</span></a>
            <a href="#platform" data-nav><span>Платформа</span></a>
            <a href="#standards" data-nav><span>Стандарты</span></a>
            <a href="#rating" data-nav><span>Рейтинг</span></a>
            <a href="#contacts" data-nav><span>Контакты</span></a>
        </nav>

        <a href="#footer-form" class="header__btn btn--red ripple-button">
            <span>Записаться на игру</span>
        </a>

        <div class="header-burger" @click.stop="showMenu=true">
            <svg viewBox="0 0 24 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M24 16H0V18H24V16Z" fill="#EA0101"/>
                <path d="M24 0H0V2H24V0Z" fill="#EA0101"/>
                <path d="M24 8H0V10H24V8Z" fill="#EA0101"/>
            </svg>

        </div>
    </div>
</header>


<div class="mob-menu" :class="{'_active': showMenu}" >
    <div class="mob-menu__inner" @click.outside="showMenu=false">

        <div class="mob-menu__header">
            <div class="mob-menu__title">Меню</div>
            <div class="mob-menu__close" @click.stop="showMenu=false">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18"/>
                    <path d="m6 6 12 12"/>
                </svg>
            </div>
        </div>


        <nav class="mob-menu__nav">

            <a href="#schedule" data-nav @click.stop="showMenu=false"><span>Расписание</span></a>
            <a href="#atmosphere" data-nav @click.stop="showMenu=false"><span>Атмосфера</span></a>
            <a href="#rules" data-nav @click.stop="showMenu=false"><span>Роли и правила</span></a>
            <a href="#location" data-nav @click.stop="showMenu=false"><span>Локации игр</span></a>
            <a href="#platform" data-nav @click.stop="showMenu=false"><span>Платформа</span></a>
            <a href="#standards" data-nav @click.stop="showMenu=false"><span>Стандарты</span></a>
            <a href="#rating" data-nav @click.stop="showMenu=false"><span>Рейтинг</span></a>
            <a href="#contacts" data-nav @click.stop="showMenu=false"><span>Контакты</span></a>

        </nav>


        <div class="mob-menu__footer">
            <a href="#footer-form" class="mob-menu__btn btn--red ripple-button" @click.stop="showMenu=false">Записаться на игру</a>
        </div>
    </div>
</div>
