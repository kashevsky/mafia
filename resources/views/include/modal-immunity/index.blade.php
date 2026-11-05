{{-- stageGame == 5 --}}
<template x-if="showModalImmunity">

    <div class="modal-layout">
        <div class="modal" @click.outside="showModalImmunity=false">

            <div class="modal__inner">

                <div class="modal__head">
                    <h2>Иммунитет</h2>
                </div>

                <div class="modal__body">

                    <div class="delete-user-wrap">
                        <template x-for="user in aliveUsers">
                            <div class="contents">
                                <div class="delete-user-item">
                                    <span x-text="`№ ${user.number} - ${user.nickname || user.name}`"></span>

                                        <button class="btn" @click.stop="eventSendImmunity(user)" :disabled="isImmunity(user)">Дать иммунитет</button>
                                </div>

                            </div>
                        </template>
                    </div>

                    <button class="btn-green" @click="showModalImmunity=false">Закрыть</button>
                </div>

            </div>
        </div>
    </div>
</template>


<script></script>
