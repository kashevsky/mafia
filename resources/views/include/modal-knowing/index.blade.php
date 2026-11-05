{{-- stageGame == 5 --}}
<template x-if="showModalNightKnowing">
    <div class="modal-layout">
        <div class="modal">

            <div class="modal__inner">

                <div class="modal__head">
                    <h2>Мафия знакомится</h2>
                </div>

                @if ($table->is_with_hanchman == 1)
                    <div class="modal__body">
                        <div class="delete-user-wrap">
                            <template x-for="user in usersMafia">
                                <div class='contents'>

                                    <div class="delete-user-item">
                                        <span x-text="`№ ${user.number} - ${user.nickname}- ${filterRole(user.role)}`"></span>
                                        <template x-if="user.role === 'Мафия'">
                                            <button class="btn"
                                                @click.stop="eventAssignPrispeshnik(user.id)">Назначить
                                                Приспешником</button>
                                        </template>
                                    </div>

                                </div>
                            </template>
                        </div>

                    </div>
                @else
                    <div class="modal__body">
                        <div class="delete-user-wrap">
                            <template x-for="user in usersMafia">
                                <div class='contents'>
                                    <div class="delete-user-item">
                                        <span x-text="`№ ${user.number} - ${user.name}- ${user.role}`"></span>
                                    </div>

                                </div>
                            </template>
                        </div>
                           <button class="btn-green" @click.stop="eventMakeAcquaintance()">Познакомились</button>
                    </div>
                @endif





            </div>
        </div>
    </div>
</template>
