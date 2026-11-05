<!-- Alpine Component -->
<template x-if="stageGame == 4">
    <div class="roulette" x-data="initRoulette()">
        <div class="roulette__inner">

            <h1>Рулетка</h1>

            <div class="controls" x-init="initRoulette()">
                <div>Рулетка (Осталось чисел в пуле: <span x-text="availableNumbers.length"></span>)</div>

                <div class="private-number">
                    <template x-for="player in leaders" :key="player.id">
                        <div class="box private_number">
                            <span x-text="player.nickname || player.name"></span>



                            <!-- Контейнер кастомного селекта -->
                            <div x-data="{ open: false }" @click.away="open = false" class="custom-select-wrapper">

                                <!-- Кнопка-заголовок селекта (отображает выбранное число) -->
                                <div @click="open = !open" class="custom-select-trigger" :class="{ 'is-open': open }">
                                    <span x-text="playerChoices[player.id] || 'выбрать'"></span>
                                    <div class="arrow">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="lucide lucide-chevron-down-icon lucide-chevron-down">
                                            <path d="m6 9 6 6 6-6" />
                                        </svg>
                                    </div>
                                </div>

                                <!-- Выпадающий список опций -->
                                <div x-show="open" x-transition class="custom-options">

                                    {{--                                    <!-- Дефолтная пустая опция --> --}}
                                    {{--                                    <div @click="playerChoices[player.id] = ''; open = false" --}}
                                    {{--                                         class="custom-option" --}}
                                    {{--                                         :class="{ 'selected': playerChoices[player.id] === '' }"> --}}
                                    {{--                                        --}}
                                    {{--                                    </div> --}}

                                    <template x-for="num in availableNumbers" :key="num">
                                        <template x-if="isNumberAvailable(num, player.id)">
                                            <div @click="playerChoices[player.id] = num; open = false"
                                                class="custom-option"
                                                :class="{ 'selected': playerChoices[player.id] === num }"
                                                x-text="num">
                                            </div>
                                        </template>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <button class="btn-green" @click.stop="playRoulette()">Выстрел</button>

                {{-- <button class="btn-danger" @click.stop="stopRoulette()">Закончить игру</button> --}}
            </div>



            <div id="result" x-text="resultMessage"></div>

        </div>
    </div>
</template>


<template x-if='showResultRoulette'>
    <div class="roulette" x-data="initRoulette()">
        <div class="roulette__inner">
            <div class="controls" style="width: 60rem">
                <div class="roulette__text" x-html="alertRoulette.text"></div>

                <button class="btn-green" style="margin: 2rem auto"
                    @click.stop="stageGame = 2;eventBtn();showResultRoulette=false">Город засыпает</button>
            </div>

        </div>
    </div>

</template>
