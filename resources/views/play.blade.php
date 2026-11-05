@extends('layouts.index')

@section('content')
    <div class="game-container" x-ref="gameContainer">
        <div class="game" :class="styleGame()">

            <template x-for="(user, key) in Object.values(users).sort((a, b) => a.position - b.position)">
                <div class="user-wrap" :class="[
                    `user-wrap-${key+1}`
                ]">
                    <div class="user"
                        :class="[
                            `user-${key+1}`,
                            isImmunitiesClass(user),
                            user.active ? 'active' : '',
                            user.target ? 'target' : '',
                            !user.is_alive ? 'dead' : '',
                            user.start_game ? 'start' : ''

                        ]"
                        @click.stop="eventUser(user.id)">

                        <div class="user__count">
                            <span x-text="user.count"></span>
                        </div>
                        <div class="user__avatar">
                            <img :src="user.avatar ? user.avatar : '/images/user.svg'"
                                :style="user.avatar ? 'object-fit: cover;' : ''" alt="Аватар пользователя">

                        </div>

                        <div class="user__name">
                            <span x-text="user.nickname || user.name" :class="{ '_small': user.name.length > 10 }"></span>
                        </div>
                        <div class="user__role" x-show="isHiddenRoles"
                            :class="[
                                user.character == 'N' ? 'mafia' : '',
                                user.character == 'Y' && user.role !== 'Мирный' ? 'good' : '',
                            ]">
                            <span x-text="filterRole(user.role)"></span>
                        </div>

                        <div class="user__buff-wrap">
                            <template x-for="buff in user.buff_role">
                                <div class="contents">

                                    <img class="user__buff" x-show="buff === 'Доктор'" width="20" height="20"
                                        src="images/icons/icon-red-cross.png"></img>



                                    <img class="user__buff" x-show="buff === 'Дон'" width="20" height="20"
                                        src="images/icons/icon-padlock.png"></img>

                                    <img class="user__buff" x-show="buff === 'Детектив-убийца'" width="20"
                                        height="20" src="images/icons/icon-gun.png"></img>

                                    <img class="user__buff" x-show="buff === 'Детектив'" width="20" height="20"
                                        src="images/icons/icon-observation.png"></img>

                                    <img class="user__buff" x-show="buff === 'Журналист'" width="20" height="20"
                                        src="images/icons/free-icon-microphone.png"></img>
                                </div>
                            </template>

                            {{-- иконка Приспешник --}}
                            <template x-if="user.id === saveNightGame.idPlayerBlockedThePrespechnik">
                                <img class="user__buff" width="20" height="20" src="images/icons/icon-hat.png"></img>
                            </template>

                            {{-- иконка мафия --}}
                            <template x-if="user.id === saveNightGame.idPlayerKillsTheMafia">
                                <img class="user__buff" width="20" height="20" src="images/icons/icon-gun.png"></img>
                            </template>

                            {{-- иконка каратель --}}
                            <template x-if="user.id === saveNightGame.idPlayerKillTheKaratel">
                                <img class="user__buff" width="20" height="20" src="images/icons/icon-gun.png"></img>
                            </template>


                            {{-- иконка судия --}}
                            <template x-if="user.id === saveNightGame.idPlayerBlockTheSudiy">
                                <img class="user__buff" width="20" height="20"
                                    src="images/icons/free-icon-judge.png"></img>
                            </template>

                            {{-- иконка адвоката --}}
                            <template x-if="user.id === saveNightGame.idPlayerImmunityTheAdvokat">
                                <img class="user__buff" width="20" height="20"
                                    src="images/icons/icon-gavel.png"></img>
                            </template>

                            {{-- иконка bomba --}}
                            <template x-for="bombaId in saveNightGame.arrIdsPlayersMinesTheBomba">
                                <template x-if="user.id === bombaId">
                                    <img class="user__buff" width="20" height="20"
                                        src="images/icons/icon-bomba.png"></img>
                                </template>
                            </template>

                            {{-- иконка Стрелочник --}}
                            <template x-if="user.id ===  saveNightGame.arrIdsPlayerKillTheStrelochnik">
                                <img class="user__buff" width="20" height="20" src="images/icons/icon-gun.png"></img>
                            </template>

                        </div>
                    </div>
                </div>
            </template>
        </div>


    </div>

    <div class="board">
        <div class="board-mess">
            <div class="text" x-text="boardMess"></div>
        </div>
        <div class="boat-alert">
            <div class="name" x-show="messBoardAlert" x-text="messBoardAlert"></div>

            <!-- Добавлена безопасная проверка targetUser?.nickname -->
            <template x-if="targetUser && whatDay === 'День'">
                <div class="name">
                    <span x-text="targetUser?.nickname || targetUser?.name"></span>
                    <template x-if="targetUser?.nickname && targetUser?.name">
                        <span x-text="`(${targetUser?.nickname})`"></span>
                    </template>
                </div>
            </template>
        </div>

        <template x-if="targetUser">
            <button class="vote-btn btn btn-green" @click.stop="eventVote()"
                x-text="whatDay == 'День' ? 'Проголосовать' : 'Принять'"></button>
        </template>
    </div>

    {{-- результат игры (ночь) --}}
    {{-- результат игры (ночь) --}}
    <template x-if="resumeKillUsers && resumeKillUsers.length">
        <div class="modal-layout">
            <div class="modal">
                <div class="modal__inner">
                    <div class="modal__head">
                        <h2 class="modal__title"> Результаты голосования</h2>
                    </div>
                    <div class="modal__body">
                        <template x-for="resume in resumeKillUsers">
                            <div class="contents">
                                <div class="resume" @click="console.log(resume)">
                                    <template x-if="resume.user">
                                        <div>
                                            <!-- Выводим номер игрока -->
                                            <span class="resume__number" x-text="`№ ${resume.user.number}`"></span>

                                            <span class="resume__name">
                                                <!-- Выводим Никнейм, а если его нет - Имя -->
                                                <span x-text="resume.user?.nickname || resume.user?.name || ''"></span>

                                                <!-- Если есть и никнейм, и имя, то выводим имя в скобках -->
                                                <template x-if="resume.user?.nickname && resume.user?.name">
                                                    <span x-text="` (${resume.user.name})`"></span>
                                                </template>
                                            </span>
                                        </div>
                                    </template>
                                    <span class="resume__status" :class="resume.status"
                                        x-text="`- ${resume.text}`"></span>
                                </div>
                            </div>
                        </template>
                        <button class="btn" @click.stop="resumeKillUsers=[]">Закрыть</button>
                    </div>
                </div>
            </div>
        </div>
    </template>




    {{-- / результат игры (ночь) --}}


    {{-- roulette --}}
    @include('include.roulette.index')

    {{-- модальное окно игры (ночь) --}}
    @include('include.modal-night.index')

    {{-- модальное окно выбора направления голосования --}}
    @include('include.modal-direction.index')

    {{-- модальное окно удаления игрока --}}
    @include('include.modal-delete-user.index')

    {{-- модальное окно голосования --}}
    @include('include.modal-vote.index')

    {{-- модальное окно конца игры --}}
    @include('include.modal-finish.index')

    {{-- модальное окно знакомства --}}
    @include('include.modal-knowing.index')

    {{-- модальное окно иммунитет --}}
    @include('include.modal-immunity.index')

    {{-- модальное окно нарушений --}}
    @include('include.modal-violations.index')

    {{-- sidebar меню --}}
    <div class="sidebar-overlay" :class="{ 'is-open': showSidebarMenu }" @click="showSidebarMenu = false"></div>

    <div class="sidebar" :class="{ 'is-open': showSidebarMenu }">
        <div class="sidebar__header">
            <h2>Управление</h2>
            <button class="sidebar__close" @click="showSidebarMenu = false">✕</button>
        </div>
        <div class="sidebar__content">
            <template x-if="countNights===0">
                <button class="btn btn-primary sidebar__btn"
                    @click.stop="showModalImmunity = true; showSidebarMenu = false" x-show="whatDay === 'День'">
                    Дать иммунитет
                </button>
            </template>

            <button class="btn btn-primary sidebar__btn" @click.stop="showModalVote = true; showSidebarMenu = false"
                x-show="whatDay === 'День'">
                Голоса
            </button>

            <button class="btn btn-primary sidebar__btn"
                @click.stop="eventOpenModalViolations(); showSidebarMenu = false">
                Нарушения
            </button>

            <button class="btn btn-danger sidebar__btn" @click.stop="showModalFinishGame = true; showSidebarMenu = false">
                Завершить игру
            </button>

            <button class="btn btn-danger sidebar__btn" @click.stop="showModalDeleteUser = true; showSidebarMenu = false">
                Удалить игрока
            </button>
        </div>
    </div>
@endsection
