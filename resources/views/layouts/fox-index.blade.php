<!DOCTYPE html>
@php

    @endphp
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    @stack('meta')
    <meta name="robots" content="noindex"/>
    {{-- css --}}
    <link rel="stylesheet" href="{{ asset('libs/swiper/swiper.min.css') }}">

    <link rel="stylesheet" href="{{ asset('css/fox/reset.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fox/var.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fox/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fox/animation.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fox/general.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fox/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fox/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fox/footer.css') }}">

</head>

<body>

<div class="app" x-data="initApp">

    {{-- header --}}
    @include('include.header-fox.index')

    {{-- page --}}
    @yield('content')

    {{-- footer --}}
    @include('include.footer-fox.index')


    <script defer src="{{ asset('libs/alpine.min.js') }}"></script>
    <script defer src="{{ asset('libs/swiper/swiper.min.js') }}"></script>

    <script src="{{ asset('js/fox/index.js') }}"></script>


    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('initApp', initApp);
        });


        function initApp() {
            return {
                showMenu: false,
                selected: '',

                success: {
                    show: false,
                    text: '',
                },

                init() {

                },


                // отправка ajax
                _sendAjax(url, body) {
                    return fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(body)
                    });
                },


                sendForm(event) {

                    const formElement = event.target;
                    const formData = new FormData(formElement);


                    this._sendAjax('#', {
                        data: formData
                    })
                        .then((response) => response.json())
                        .then((data) => {
                            console.log('data', data);
                           this.success = {
                               show: true,
                               text: "Ваша заявка успешно отправленна",
                           }
                        })
                        .catch((error) => {
                            console.error('Ошибка отправки:', error);
                            this.success = {
                                show: true,
                                text: "Упс! Ошибка",
                            }
                        })
                        .finally(() => {
                        });
                },


                setDay(day) {
                    const footerForm = document.querySelector('.footer-form');
                    this.selected = day;

                    footerForm.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        }
    </script>

</div>

@stack('script')
</body>

</html>
