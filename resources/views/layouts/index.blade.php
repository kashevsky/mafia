<!DOCTYPE html>
@php
    $theme = session()->get('theme');
@endphp
<html class="{{ $theme }}" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <base href="{{ URL('/') }}">

    @stack('meta')
    <meta name="robots" content="noindex" />
    {{-- css --}}
    <link rel="stylesheet" href="libs/vbox/style.css">
    <link rel="stylesheet" href="css/style.css?v={{ time() }}">

</head>

<body x-data="initApp" x-ref="body" class="body-game" style="background-image: url('images/table.png')">

    {{-- header --}}
    @include('include.header.index')

    {{-- page --}}
    @yield('content')

    {{-- footer --}}
    @include('include.footer.index')

    <div class="modal-layout" x-cloak x-show="alertPage.show">
        <div class="alert-page">
            <div class="alert-page__inner">
                <div x-html="alertPage.text"></div>
                <button class="btn" @click.stop="alertPage.show = false">Закрыть</button>
            </div>

        </div>
    </div>

    {{-- icons --}}
    @include('include.icons-sprite.index')


    <script>
        document.addEventListener('alpine:init', () => {

            Alpine.store('globalData', {
                users: @json($users),
                TOKEN: '{{ csrf_token() }}',
                apiFinish: '/admin/table/make-public-question/{{ $table->id }}',
                // apiFinish: '/admin/table/create-after-game/{{ $table->id }}',
                apiKillUser: '{{ route('kill.player') }}',
                apiGamePublic: '/make-game-public/{{ $game->id }}',
                tableId: '{{ $table->id }}',
                isPrispeshnik: '{{ $table->is_with_hanchman == 1 }}',
                countPrespechnik: '{{ $table->count_hanchman }}', // количетво ночей для приспешника

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
            })
        })
    </script>

    <script src="libs/vbox/script.js"></script>
    <script src="js/vbox.js"></script>
    <script src="js/index.js"></script>


</body>

</html>
