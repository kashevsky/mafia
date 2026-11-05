<template x-if="showModalViolations">

    <div class="modal-layout">
        <div class="modal" @click.outside="showModalViolations=false">

            <div class="modal__inner">

                <div class="modal__head">
                    <h2>Нарушения игроков</h2>
                </div>

                <div class="modal__body">
                    <div class="violations-wrap">
                        <template x-for="user in users" :key="user.id">
                            <div class="violation-item"
                                 :class="!user.is_alive ? 'disabled' : ''">
                                <div class="violation-info">
                                    <span x-text="`№${user.number} - ${user.nickname || user.name}`"></span>
                                    <span class="violation-count" x-text="user.foul || 0"></span>
                                </div>
                                <button class="btn"
                                        :disabled="!user.is_alive || (user.foul || 0) >= 4"
                                        @click="eventAddViolation(user.id)">
                                    Наказать
                                </button>
                            </div>
                        </template>
                    </div>

                    <button class="btn-green" @click="showModalViolations=false">Закрыть</button>
                </div>

            </div>
        </div>
    </div>
</template>
