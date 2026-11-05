<template x-if="showModalDeleteUser">
    <div style="display: contents">
        <div class="modal-layout">
            <div class="modal">

                <div class="modal__inner">

                    <div class="modal__head">
                        <h2>Удалить игрока</h2>
                    </div>

                    <div class="modal__body">
                        <div class="delete-user-wrap">
                            <template x-for="user in users">
                                <div>
                                    <div class="delete-user-item">
                                        <span x-text="`№${user.number} - ${user.name}`"></span>
                                        <button class="btn btn-danger" @click.stop="eventDeleteUser(user.id)"
                                            :disabled="!user.is_alive">Удалить</button>
                                    </div>
                                    <hr>
                                </div>
                            </template>

                        </div>
                        <button class="btn-green" @click="showModalDeleteUser=false">Закрыть</button>
                    </div>
                </div>
            </div>
        </div>


        {{-- окно подтверждения удаления --}}
        <template x-if="showModalDeleteUserConfirm">
            <div class="modal-layout">
                <div class="modal">

                    <div class="modal__inner">

                        <div class="modal__head">
                            <h2>Удалить игрока <span x-text="userDelete.name"></span>?</h2>
                        </div>

                        <div class="modal__body" style="flex-direction: row; justify-content: space-between">
                            <button class="btn btn-green" @click="showModalDeleteUserConfirm=false">Нет</button>
                            <button class="btn btn-danger"
                                @click="showModalDeleteUserConfirm=false, eventDeleteUserConfirm()">Удалить</button>
                        </div>
                    </div>
                </div>
            </div>
        </template>



    </div>
</template>


<script></script>
