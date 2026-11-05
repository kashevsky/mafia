@extends('layouts.index')

@push('style')
    <link rel="stylesheet" href="assets/css/swiper.min.css">
    <link rel="stylesheet" href="assets/css/fancybox.css">
    @vite(['resources/scss/page/publicity.scss'])
    <style>
    .cases__el-img {
        height: 300px !important;
    }
    @media screen and (max-width: 887px) {
        .cases__el-img {
            height: 180px !important;
        }
    }
    </style>
@endpush
@push('seo')
    <title>{{ $page->seo_title }}</title>
    <meta name="description" content="{{ $page->seo_description }}">
@endpush

@section('content')
    <section class="welcome parallax">

        <div class="welcome-wrap">
            <h1 class="title-h1  el-anim-left">
                {{ $page->title }}
            </h1>
        </div>

        <img src="c2.png" class="welcome__photo" alt="фото девушки" width="560" height="767">

        <div class="welcome-footer">
            <div class="welcome-footer__el">
                <div class="icon bg-gradient">i</div>
                <p class="text">{{ $page->ad_1_title }}</p>
            </div>

            <div class="welcome-footer__el">
                <div class="icon bg-gradient">i</div>
                <p class="text">{{ $page->ad_2_title }}</p>
            </div>

            <div class="welcome-footer__el">
                <div class="icon bg-gradient">i</div>
                <p class="text">{{ $page->ad_3_title }}</p>
            </div>
        </div>


        <img class="star" src="assets/img/star.png" alt="амёба" width="600" height="600">


    </section>

    <section class="publicity mb-80">

        <h2 class="publicity__title services__title  title el-anim-left">{{ $page->title_2 }}</h2>

        <img class="publicity__star-1 star" data-src="assets/img/star-white.png" alt="амёба 1" width="600"
            height="600">

        <img class="publicity__star-2 star" data-src="assets/img/star-white.png" alt="амёба 2" width="600"
            height="600">

        <div class="publicity-wrap container">
            <ul class="publicity__list">
                @foreach ($contextPageItems as $key => $item)
                    <li class="publicity__list-el">
                        <div class="publicity__list-num">0{{ $key + 1 }}</div>
                        <p class="publicity__list-text">{{ $item->title }}</p>
                    </li>
                @endforeach
            </ul>

        </div>
    </section>

    <section class="budget">

        <img class="budget__star-2 star" data-src="assets/img/star-white.png" alt="амёба 1" width="600" height="600">

        <div class="budget-container container">
            <h2 class="budget__title title el-anim-left">{{ $page->title_3 }}</h2>

            <div class="budget-wrap">
                <article class="budget__card">
                    <h3>{{ $page->factor_1_title }}</h3>
                    <p>{{ $page->factor_1_text }}</p>
                </article>
                <article class="budget__card">
                    <h3>{{ $page->factor_2_title }}</h3>
                    <p>{{ $page->factor_2_text }}</p>
                </article>
                <article class="budget__card">
                    <h3>{{ $page->factor_3_title }}</h3>
                    <p>{{ $page->factor_3_text }}</p>
                </article>
                <article class="budget__card">
                    <h3>{{ $page->factor_4_title }}</h3>
                    <p>{{ $page->factor_4_text }}</p>
                </article>
                <div class="budget__circle title">Бюджет</div>
                <div class="budget__line-centre"></div>
                <div class="budget__line budget__line-left"></div>
                <div class="budget__line budget__line-right"></div>
            </div>

        </div>
    </section>

    <section class="calc">
        <div class="calc-container container">
            <h2 class="calc__title title">Стоимость контекстной рекламы</h2>


            <ul class="calc__list">

                <li class="calc__el js-calc">
                    <input type="hidden" value="102">
                    <div class="calc__el-title">Поисковая система </div>
                    <div class="calc__el-wrap">
                        <input class="js-check calc__el-check" type="checkbox" name="check-1" id="label-1-1" value="11">
                        <label for="label-1-1">
                            <div class="calc__el-box"></div>
                            Яндекс
                        </label>
                        <input class="js-check calc__el-check" type="checkbox" name="check-1" id="label-1-2" value="12">
                        <label for="label-1-2">
                            <div class="calc__el-box"></div>
                            Google
                        </label>
                    </div>
                </li>

                <li class="calc__el js-calc">
                    <input type="hidden" value="102">
                    <div class="calc__el-title">Количество рекламируемых страниц сайта </div>
                    <div class="calc__el-wrap">
                        <input class="js-check calc__el-check" type="radio" name="check-2" id="label-2-1" value="11">
                        <label for="label-2-1">
                            <div class="calc__el-box"></div>
                            1-3 страницы
                        </label>
                        <input class="js-check calc__el-check" type="radio" name="check-2" id="label-2-2"
                            value="12">
                        <label for="label-2-2">
                            <div class="calc__el-box"></div>
                            4-6 страниц
                        </label>
                        <input class="js-check calc__el-check" type="radio" name="check-2" id="label-2-3"
                            value="13">
                        <label for="label-2-3">
                            <div class="calc__el-box"></div>
                            7 и более страниц
                        </label>
                    </div>
                </li>

                <li class="calc__el js-calc">
                    <input type="hidden" value="102">
                    <div class="calc__el-title">Количество ключевых слов </div>
                    <div class="calc__el-wrap">
                        <input class="js-check calc__el-check" type="radio" name="check-3" id="label-3-1"
                            value="11">
                        <label for="label-3-1">
                            <div class="calc__el-box"></div>
                            до 70 слов
                        </label>
                        <input class="js-check calc__el-check" type="radio" name="check-3" id="label-3-2"
                            value="12">
                        <label for="label-3-2">
                            <div class="calc__el-box"></div>
                            до 150 слов
                        </label>
                        <input class="js-check calc__el-check" type="radio" name="check-3" id="label-3-3"
                            value="13">
                        <label for="label-3-3">
                            <div class="calc__el-box"></div>
                            до 300 слов
                        </label>
                    </div>
                </li>


                <li class="calc__el js-calc">
                    <input type="hidden" value="102">
                    <div class="calc__el-title">Создание рекламного аккаунта </div>
                    <div class="calc__el-wrap">
                        <input class="js-check calc__el-check" type="radio" name="check-4" id="label-4-1"
                            value="11">
                        <label for="label-4-1">
                            <div class="calc__el-box"></div>
                            Уже есть аккаунт
                        </label>
                        <input class="js-check calc__el-check" type="radio" name="check-4" id="label-4-2"
                            value="12">
                        <label for="label-4-2">
                            <div class="calc__el-box"></div>
                            Создание нового аккаунта
                        </label>
                    </div>
                </li>


                <li class="calc__el js-calc">
                    <input type="hidden" value="102">
                    <div class="calc__el-title">Количество креативов для баннерной рекламы</div>
                    <div class="calc__el-wrap">
                        <input class="js-check calc__el-check" type="radio" name="check-5" id="label-5-1"
                            value="11">
                        <label for="label-5-1">
                            <div class="calc__el-box"></div>
                            До 10 креативов
                        </label>
                        <input class="js-check calc__el-check" type="radio" name="check-5" id="label-5-2"
                            value="12">
                        <label for="label-5-2">
                            <div class="calc__el-box"></div>
                            До 20 креативов
                        </label>
                        <input class="js-check calc__el-check" type="radio" name="check-5" id="label-5-3"
                            value="13">
                        <label for="label-5-3">
                            <div class="calc__el-box"></div>
                            До 30 креативов
                        </label>
                    </div>
                </li>


            </ul>

            <div class="calc__price">
                <div class="calc__price-title">Общая стоимость</div>
                <div class="calc__price-res">от <span id="calc-res">0</span> р</div>
            </div>



        </div>
    </section>

    <section class="basic parallax ">
        <div class="basic-container container">
            <h2 class="basic__title title">{{ $page->title_4 }}</h2>
            <p class="basic__text text">{{ $page->title_4_under }}</p>

            <div class="acc" data-acc>
                @foreach ($steps as $key => $step)
                    <div class="acc__el">
                        <button class="acc__btn" data-acc-btn>
                            <span>{{ $key + 1 }}. {{ $step->title }}.</span>
                            <div class="acc__btn-icon" data-acc-icon></div>
                        </button>
                        <div class="acc__panel" data-acc-panel>
                            <div class="acc__content basic__content">
                                {!! $step->text !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <p class="basic__text text">{{ $page->title_4_down }}!</p>
            <a href="{{ url()->current() }}#s-form" class="basic__btn btn bg-gradient">Заказать звонок </a>
        </div>
    </section>

    {{-- <section class="portfolio ">
        <div class="portfolio-container container">
            <h2 class="portfolio__title title">Портфолио</h2>

            <div class="swiper-portfolio-wrap">

                <div class="swiper swiper-portfolio">
                    <div class="swiper-wrapper">

                        <div class="swiper-slide">
                            <div class="portfolio__img">
                                <img src="assets/img/portfolio/portfolio-1.png" alt="фото портфолио 1" width="580"
                                    height="580">
                            </div>
                            <div class="portfolio__info">
                                <p class="portfolio__info-text">Контекстная реклама для компании по монтажу отопления</p>
                                <a href="#" class="portfolio__info-btn btn bg-gradient">Смотреть подробнее</a>
                            </div>
                        </div>

                        <div class="swiper-slide">
                            <div class="portfolio__img">
                                <img src="assets/img/portfolio/portfolio-1.png" alt="фото портфолио 1" width="580"
                                    height="580">
                            </div>
                            <div class="portfolio__info">
                                <p class="portfolio__info-text">Контекстная реклама для компании по монтажу отопления</p>
                                <a href="#" class="portfolio__info-btn btn bg-gradient">Смотреть подробнее</a>
                            </div>
                        </div>

                        <div class="swiper-slide">
                            <div class="portfolio__img">
                                <img src="assets/img/portfolio/portfolio-1.png" alt="фото портфолио 1" width="580"
                                    height="580">
                            </div>
                            <div class="portfolio__info">
                                <p class="portfolio__info-text">Контекстная реклама для компании по монтажу отопления</p>
                                <a href="#" class="portfolio__info-btn btn bg-gradient">Смотреть подробнее</a>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="button-next">
                    <svg width="9" height="15" viewBox="0 0 9 15" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M0.182878 0.168977C0.426715 -0.0563256 0.822053 -0.0563256 1.06589 0.168977L9 7.5L1.06589 14.831C0.822053 15.0563 0.426715 15.0563 0.182878 14.831C-0.0609593 14.6057 -0.0609593 14.2404 0.182878 14.0151L7.23398 7.5L0.182878 0.984869C-0.0609593 0.759567 -0.0609593 0.394279 0.182878 0.168977Z"
                            fill="#7A234D" />
                    </svg>

                </div>
                <div class="button-prev">
                    <svg width="8" height="16" viewBox="0 0 8 16" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M7.83744 15.8198C7.6207 16.0601 7.26929 16.0601 7.05254 15.8198L0 8L7.05254 0.180241C7.26929 -0.0600815 7.6207 -0.0600815 7.83744 0.180241C8.05419 0.420565 8.05419 0.810204 7.83744 1.05053L1.5698 8L7.83744 14.9495C8.05419 15.1898 8.05419 15.5794 7.83744 15.8198Z"
                            fill="#7A234D" />
                    </svg>

                </div>

            </div>
        </div>
    </section> --}}

    <section class="basic parallax ">
        <div class="basic-container container">
            <h2 class="basic__title title">Вопрос-ответ</h2>

            <div class="acc" data-acc>
                @foreach ($faqs as $key => $faq)
                    <div class="acc__el">
                        <button class="acc__btn" data-acc-btn>
                            <span>{{ $key + 1 }}. {{ $faq->title }} </span>
                            <div class="acc__btn-icon" data-acc-icon></div>
                        </button>
                        <div class="acc__panel" data-acc-panel>
                            <div class="acc__content basic__content">
                                <p>{!! $faq->text !!}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="certificate">
        <div class="certificate-container container">
            <h2 class="certificate__title title">Сертификаты</h2>
            <div class="certificate-wrap">
                <div class="swiper certificate-swiper">
                    <div class="swiper-wrapper">
                        @foreach ($sertificates as $key => $certificate)
                            <div class="swiper-slide">
                                <a href="{{ $certificate->src }}" class="certificate__img" data-fancybox="gallery">
                                    <img src="{{ $certificate->src }}" width="280" height="197"
                                        alt="фото сертификата {{ $key + 1 }}">
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="certificate__nav">

                    <div class="button-prev">
                        <svg width="8" height="16" viewBox="0 0 8 16" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M7.83744 15.8198C7.6207 16.0601 7.26929 16.0601 7.05254 15.8198L0 8L7.05254 0.180241C7.26929 -0.0600815 7.6207 -0.0600815 7.83744 0.180241C8.05419 0.420565 8.05419 0.810204 7.83744 1.05053L1.5698 8L7.83744 14.9495C8.05419 15.1898 8.05419 15.5794 7.83744 15.8198Z"
                                fill="#7A234D" />
                        </svg>
                    </div>

                    <div class="button-next">
                        <svg width="9" height="15" viewBox="0 0 9 15" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M0.182878 0.168977C0.426715 -0.0563256 0.822053 -0.0563256 1.06589 0.168977L9 7.5L1.06589 14.831C0.822053 15.0563 0.426715 15.0563 0.182878 14.831C-0.0609593 14.6057 -0.0609593 14.2404 0.182878 14.0151L7.23398 7.5L0.182878 0.984869C-0.0609593 0.759567 -0.0609593 0.394279 0.182878 0.168977Z"
                                fill="#7A234D" />
                        </svg>
                    </div>
                </div>


            </div>
        </div>
    </section>
    @if (count($cases) > 0)
        <section class="cases">

            <img class="advertising__star-1 star entered loaded" data-src="assets/img/star-white.png" alt="амёба 2"
                width="600" height="600" data-ll-status="loaded" src="assets/img/star-white.png">
            <img class="advertising__star-2 star entered loaded" data-src="assets/img/star-white.png" alt="амёба 2"
                width="600" height="600" data-ll-status="loaded" src="assets/img/star-white.png">

            <h2 class="why__title services__title title el-anim-left ">Мои проекты (Кейсы)</h2>

            <div class="cases__grid container">
                @foreach ($cases as $case)
                    <a href="{{ route('show-case-context', $case->id) }}" class="cases__el">
                        <div class="cases__el-img">
                            <img src="{{ $case->preview }}" alt="#" width="600" height="600">
                        </div>
                        <h3 class="cases__el-title">{{ $case->title }}</h3>
                    </a>
                @endforeach
            </div>


        </section>
    @endif
    <section class="services min-services mb-80" id="services">
        <h2 class="services__title title el-anim-left el-show">
            Другие услуги</h2>
        <div class="container">

            <div class="services-grid">
                <a href="{{ route('seo') }}" class="services__el el-anim-left">
                    <h3 class="services__el-title">SEO-продвижение</h3>
                    <svg class="services__el-icon">
                        <use href="#iconS5"></use>
                    </svg>
                </a>
                {{-- <a href="{{ route('publicity') }}" class="services__el el-anim-right">
                    <h3 class="services__el-title">Контекстная реклама </h3>
                    <svg class="services__el-icon">
                        <use href="#iconS6"></use>
                    </svg>
                </a> --}}
                <a href="{{ route('target') }}" class="services__el el-anim-right">
                    <h3 class="services__el-title">Таргетированная реклама </h3>
                    <svg class="services__el-icon">
                        <use href="#iconS2"></use>
                    </svg>
                </a>
                <a href="{{ route('lid') }}" class="services__el el-anim-right">
                    <h3 class="services__el-title">Лидогенерация</h3>
                    <svg class="services__el-icon">
                        <use href="#iconS4"></use>
                    </svg>
                </a>
                <a href="{{ route('smm') }}" class="services__el el-anim-left">
                    <h3 class="services__el-title">SMM</h3>
                    <svg class="services__el-icon">
                        <use href="#iconS1"></use>
                    </svg>
                </a>
                <a href="{{ route('sites') }}" class="services__el el-anim-left">
                    <h3 class="services__el-title">Разработка сайтов</h3>
                    <svg class="services__el-icon">
                        <use href="#iconS3"></use>
                    </svg>
                </a>
            </div>
        </div>
    </section>


    {{-- form --}}
    @include('include.form.index')
@endsection

@push('script')
    <script defer src="assets/js/swiper.min.js"></script>
    <script defer src="assets/js/fancybox.min.js"></script>
    @vite(['resources/js/publicity.js'])
@endpush
