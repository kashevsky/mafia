{{-- stageGame == 5 --}}
<template x-if="showModalNight">

    <div class="modal-layout">
        <div class="modal" @click.outside="showModalNight=false">

            <div class="modal__inner">

                <div class="modal__head">
                    <h2>Ночь</h2>
                </div>

                <div class="modal__body">
                    <div class="modal__group">
                        <div class="modal__group-title">Мафия</div>
                        {{-- usersMafia --}}
                        <div class="modal__list">
                            {{-- если много мафии --}}
                            <template x-if="usersMafia && usersMafia.length > 1">

                                <div class="modal__item">
                                    <div>
                                        <div x-text="`Мафия`"></div>
                                        <div x-text="`игроков: ${usersMafia.length}`"></div>
                                    </div>

                                    <button class="modal__item-action" :disabled="usersMafia[0].is_vote ? true : false"
                                        @click.stop="eventWakeUp('Мафия')" x-text="checkStatusMafia('Мафия')">
                                    </button>
                                </div>
                            </template>
                            {{-- если одна мафия --}}
                            <template x-if="usersMafia && usersMafia.length == 1">

                                <div class="modal__item">
                                    <div>
                                        <div x-text="`Мафия`"></div>
                                        <div x-text=" usersMafia[0].name"></div>
                                    </div>

                                    <button class="modal__item-action" :disabled="usersMafia[0].is_vote ? true : false"
                                        @click.stop="eventWakeUp('Мафия')" x-text="checkStatus(usersMafia[0].role)">
                                    </button>
                                </div>
                            </template>
                            {{-- Результат голосования мафии --}}
                            <template x-if="saveNightGame.idPlayerKillsTheMafia">
                                <div>
                                    <div
                                        x-text="`мафия убивает ${findUserId(saveNightGame.idPlayerKillsTheMafia).name}` ">
                                    </div>
                                </div>
                            </template>

                            {{-- / Результат голосования мафии --}}
                        </div>
                        <hr>
                        {{-- / usersMafia --}}

                        <div class="modal__list">
                            <template x-for="user in usersMafia">
                                <div class="contents">
                                    <template x-if="user.role == 'Дон'">
                                        <div>
                                            <div class="modal__item">
                                                <div>
                                                    <div x-text="filterRole(user.role)"></div>
                                                    <div x-text="user.name"></div>
                                                </div>
                                                <div class="modal__item-action-text" x-text="actionRoleText(user)">
                                                </div>
                                            </div>
                                            <template x-if="saveNightGame.idPlayerBlockedTheDon">
                                                <div>
                                                    <div
                                                        x-text="`Дон заблокировал: ${findUserId(saveNightGame.idPlayerBlockedTheDon).name}` ">
                                                    </div>
                                                </div>
                                            </template>
                                            <hr>
                                        </div>
                                    </template>
                                    <template x-if="user.role == 'Приспешник'">
                                        <div>
                                            <div class="modal__item">
                                                <div>
                                                    <div x-text="filterRole(user.role)"></div>
                                                    <div x-text="user.name"></div>
                                                </div>
                                                <div class="modal__item-action-text" x-text="actionRoleText(user)">
                                                </div>
                                            </div>
                                            <template x-if="!_isActiveSkillPrespechnik">
                                                <div>не может скомпрометировать!</div>
                                            </template>
                                            <template x-if="saveNightGame.idPlayerBlockedThePrespechnik">
                                                <div>
                                                    <div
                                                        x-text="`Приспешник запачкал: ${findUserId(saveNightGame.idPlayerBlockedThePrespechnik).name}` ">
                                                    </div>
                                                </div>
                                            </template>
                                            <hr>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>

                        <div class="modal__group">
                            <div class="modal__group-title">Город</div>
                            {{-- usersGood --}}
                            <div class="modal__list">
                                <template x-if="usersGood.length">
                                <template x-for="user in usersGood">
                                    <div class="contents">
                                        <template x-if="user.role == 'Шериф'">
                                            <div class="order-1">
                                                <div class="modal__item">
                                                    <div>
                                                        <div x-text="filterRole(user.role)"></div>
                                                        <div x-text="user.name"></div>
                                                    </div>

                                                    <button :disabled="disabledUserClass(user)"
                                                        class="modal__item-action" @click.stop="eventWakeUp(user.role)"
                                                        x-text="actionRoleText(user)"></button>
                                                </div>
                                                <template x-if="saveNightGame.idPlayerCheckTheSherif">
                                                    <div>
                                                        <div
                                                            x-text="`Проверил ${findUserId(saveNightGame.idPlayerCheckTheSherif).name}: ${
        findUserId(saveNightGame.idPlayerCheckTheSherif).role === 'Фредди' ? 'Мститель' :
        (findUserId(saveNightGame.idPlayerCheckTheSherif).role === 'Каратель' ? 'Ферзь' :
        findUserId(saveNightGame.idPlayerCheckTheSherif).role)
    }`">
                                                        </div>

                                                    </div>
                                                </template>
                                                <hr>
                                            </div>
                                        </template>

                                        <template x-if="user.role == 'Доктор'">
                                            <div class="order-2">
                                                <div class="modal__item">
                                                    <div>
                                                        <div x-text="filterRole(user.role)"></div>
                                                        <div x-text="user.name"></div>
                                                    </div>

                                                    <button :disabled="disabledUserClass(user)"
                                                        class="modal__item-action" @click.stop="eventWakeUp(user.role)"
                                                        x-text="actionRoleText(user)"></button>
                                                </div>
                                                <template x-if="saveNightGame.idPlayerTherapyTheDoctor">
                                                    <div
                                                        x-text="`Вылечил ${findUserId(saveNightGame.idPlayerTherapyTheDoctor).name}: ${findUserId(saveNightGame.idPlayerTherapyTheDoctor).role}`">
                                                    </div>
                                                </template>
                                                <hr>
                                            </div>
                                        </template>

                                        <template x-if="user.role == 'Детектив'">
                                            <div class="order-3">
                                                <div class="modal__item">
                                                    <div>
                                                        <div x-text="filterRole(user.role)"></div>
                                                        <div x-text="user.name"></div>
                                                    </div>

                                                    <button :disabled="disabledUserClass(user)"
                                                        class="modal__item-action" @click.stop="eventWakeUp(user.role)"
                                                        x-text="actionRoleText(user)"></button>
                                                </div>
                                                <template x-if="saveNightGame.idPlayerCheckTheDetective">
                                                    <div
                                                        x-text="`Проверил: ${findUserId(saveNightGame.idPlayerCheckTheDetective).name}: ${
        findUserId(saveNightGame.idPlayerCheckTheDetective).role === 'Фредди' ? 'Мститель' :
        (findUserId(saveNightGame.idPlayerCheckTheDetective).role === 'Каратель' ? 'Ферзь' :
        findUserId(saveNightGame.idPlayerCheckTheDetective).role)
    }`">
                                                    </div>

                                                </template>
                                                <template x-if="saveNightGame.idPlayerKillTheDetective">
                                                    <div
                                                        x-text="`Убивает: ${findUserId(saveNightGame.idPlayerKillTheDetective).nickname}: ${findUserId(saveNightGame.idPlayerKillTheDetective).role}`">
                                                    </div>
                                                </template>
                                                <hr>
                                            </div>
                                        </template>

                                        <template x-if="user.role == 'Адвокат'">
                                            <div class="order-4">
                                                <div class="modal__item">
                                                    <div>
                                                        <div x-text="filterRole(user.role)"></div>
                                                        <div x-text="user.name"></div>
                                                    </div>

                                                    <button :disabled="disabledUserClass(user)"
                                                        class="modal__item-action" @click.stop="eventWakeUp(user.role)"
                                                        x-text="actionRoleText(user)"></button>
                                                </div>
                                                <template x-if="saveNightGame.idPlayerImmunityTheAdvokat">
                                                    <div
                                                        x-text="`Дал иммунитет ${findUserId(saveNightGame.idPlayerImmunityTheAdvokat).name}: ${findUserId(saveNightGame.idPlayerImmunityTheAdvokat).role}`">
                                                    </div>
                                                </template>
                                                <hr>
                                            </div>
                                        </template>

                                        <template x-if="user.role == 'Фредди'">
                                            <div class="order-5">
                                                <div class="modal__item">
                                                    <div>
                                                        <div x-text="filterRole(user.role)"></div>
                                                        <div x-text="user.name"></div>
                                                    </div>

                                                    <button :disabled="disabledUserClass(user)"
                                                        class="modal__item-action" @click.stop="eventWakeUp(user.role)"
                                                        x-text="actionRoleText(user)"></button>
                                                </div>
                                                <template x-if="saveNightGame.idPlayerCheckTheFreddy">
                                                    <div
                                                        x-text="`Пометил: ${findUserId(saveNightGame.idPlayerCheckTheFreddy).name}: ${findUserId(saveNightGame.idPlayerCheckTheFreddy).role}`">
                                                    </div>
                                                </template>
                                                <hr>
                                            </div>
                                        </template>

                                        <template x-if="user.role == 'Судья'">
                                            <div class="order-6">
                                                <div class="modal__item">
                                                    <div>
                                                        <div x-text="filterRole(user.role)"></div>
                                                        <div x-text="user.name"></div>
                                                    </div>

                                                    <button :disabled="disabledUserClass(user)"
                                                        class="modal__item-action"
                                                        @click.stop="eventWakeUp(user.role)"
                                                        x-text="actionRoleText(user)"></button>
                                                </div>
                                                <template x-if="saveNightGame.idPlayerBlockTheSudiy">
                                                    <div
                                                        x-text="`Пометил: ${findUserId(saveNightGame.idPlayerBlockTheSudiy).name}: ${findUserId(saveNightGame.idPlayerBlockTheSudiy).role}`">
                                                    </div>
                                                </template>
                                                <hr>
                                            </div>
                                        </template>

                                        <template x-if="user.role == 'Каратель'">
                                            <div class="order-7">
                                                <div class="modal__item">
                                                    <div>
                                                        <div x-text="filterRole(user.role)"></div>
                                                        <div x-text="user.name"></div>
                                                    </div>

                                                    <button :disabled="disabledUserClass(user)"
                                                        class="modal__item-action"
                                                        @click.stop="eventWakeUp(user.role)"
                                                        x-text="actionRoleText(user)"></button>
                                                </div>
                                                <template x-if="saveNightGame.idPlayerKillTheKaratel">
                                                    <div
                                                        x-text="`Убивает: ${findUserId(saveNightGame.idPlayerKillTheKaratel).nickname}: ${findUserId(saveNightGame.idPlayerKillTheKaratel).role}`">
                                                    </div>
                                                </template>
                                                <hr>
                                            </div>
                                        </template>

                                        <template x-if="user.role == 'Журналист'">
                                            <div class="order-8">
                                                <div class="modal__item">
                                                    <div>
                                                        <div x-text="filterRole(user.role)"></div>
                                                        <div x-text="user.name"></div>
                                                    </div>
                                                    <button :disabled="disabledUserClass(user)"
                                                        class="modal__item-action"
                                                        @click.stop="eventWakeUp(user.role)"
                                                        x-text="actionRoleText(user)">
                                                    </button>
                                                </div>

                                                <!-- Прогресс проверок -->
                                                <div class="modal__progress"
                                                    x-text="`${saveNightGame.arrIdsPlayersCheckTheDzhurnalist.length}/2 проверок`">
                                                </div>

                                                <div class="modal__progress"
                                                    x-text="`${_quantityCheckDzhurnalist}/2 осталось шансов`">
                                                </div>

                                                <!-- Результаты проверок -->
                                                <template x-if="saveNightGame.arrIdsPlayersCheckTheDzhurnalist.length">
                                                    <div class="modal__checks">
                                                        <template
                                                            x-for="checkId in saveNightGame.arrIdsPlayersCheckTheDzhurnalist"
                                                            :key="checkId">
                       <div x-text="`Проверил: ${findUserId(checkId).name}: ${
    findUserId(checkId).role === 'Фредди' ? 'Мститель' :
    (findUserId(checkId).role === 'Каратель' ? 'Ферзь' :
    findUserId(checkId).role)
}`">
</div>

                                                        </template>
                                                    </div>
                                                </template>
                                                <hr>
                                            </div>
                                        </template>

                                        <template x-if="user.role == 'Бомба' && countNights <= 2">
                                            <div class="order-9">
                                                <div class="modal__item">
                                                    <div>
                                                        <div x-text="filterRole(user.role)"></div>
                                                        <div x-text="user.name"></div>
                                                    </div>

                                                    <button :disabled="disabledUserClass(user)"
                                                        class="modal__item-action"
                                                        @click.stop="eventWakeUp(user.role)"
                                                        x-text="actionRoleText(user)"></button>
                                                </div>

                                                <!-- Прогресс мин: текущие / максимум -->
                                                <div x-show="countNights <= 2" class="modal__bomb-progress"
                                                    x-text="getBombProgress()">
                                                </div>

                                                <!-- Список заминированных игроков -->
                                                <template x-if="saveNightGame.arrIdsPlayersMinesTheBomba.length">
                                                    <div class="modal__bomb-list">
                                                        <div class="modal__bomb-title">Заминированные:</div>
                                                        <template
                                                            x-for="mineId in saveNightGame.arrIdsPlayersMinesTheBomba"
                                                            :key="mineId">
                                                            <div class="modal__bomb-item"
                                                                x-text="findUserId(mineId).name">
                                                            </div>
                                                        </template>
                                                    </div>
                                                </template>
                                                <hr>
                                            </div>
                                        </template>

                                        <template x-if="user.role == 'Стрелочник'">
                                            <div class="order-10">
                                                <div class="modal__item">
                                                    <div>
                                                        <div x-text="filterRole(user.role)"></div>
                                                        <div x-text="user.name"></div>
                                                    </div>
                                                    <template x-if="isKillStrelochnik">
                                                        <button :disabled="disabledUserClass(user)"
                                                            class="modal__item-action"
                                                            @click.stop="eventWakeUp(user.role)"
                                                            x-text="actionRoleText(user)"></button>
                                                    </template>
                                                    <template x-if="!isKillStrelochnik">
                                                        <button disabled class="modal__item-action"
                                                            x-text="actionRoleText(user)"></button>
                                                    </template>

                                                </div>

                                                <hr>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                                </template>
                                {{-- Заполняем роли которых нет в игре --}}
                                <template x-for="role in arrUsersRoles">
                                    <template x-if="!users.some(user => user.role === role)">
                                        <div :class="orderRoleClass(role)">
                                            <div class="modal__item">
                                                <div>
                                                    <div x-text="filterRole(role)"></div>
                                                </div>
                                                <div class="btn btn-danger"> Не в игре </div>
                                            </div>
                                            <hr>
                                        </div>
                                    </template>
                                </template>
                            </div>
                        </div>


                    <template x-if="stageGame === 50">
                        <button class="btn" @click.stop="eventEndNight">Завершить ночь</button>
                    </template>
                </div>

            </div>
        </div>
    </div>
</template>


<script></script>
