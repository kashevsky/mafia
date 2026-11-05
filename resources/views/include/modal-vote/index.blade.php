{{-- stageGame == 5 --}}
<template x-if="showModalVote">

    <div class="modal-layout" x-data="initModalVote">
        <div class="modal" @click.outside="showModalVote=false">

            <div class="modal__inner">

                <div class="modal__head">
                    <h2>Итоги голосования</h2>
                </div>

                <div class="modal__body">

                    <div class="delete-user-wrap">
                        <template x-for="user in maxVotes">
                            <div class="user-item" :class="user.isLeader ? 'leader' : ''">
                                <div class="delete-user-item">
                                    <span
                                        x-text="user.nickname ? `${user.nickname}${user.name ? ` (${user.name})` : ''}` : (user.name || '')">Буратино</span>
                                    <div x-text="user.count"></div>
                                </div>
                                <hr>
                            </div>
                        </template>
                    </div>

                    <button class="btn-green" @click="showModalVote=false">Закрыть</button>
                </div>

            </div>
        </div>
    </div>
</template>


<script>
    function initModalVote() {
        return {
            maxVotes: [],

            init() {
                this.counterMaxVotes();
            },
            counterMaxVotes() {

                let users = [...this.aliveUsers];
                if (users.length === 0) return;

                // сортируем по количеству голосов
                users.sort((a, b) => b.count - a.count);



                // 2. Максимальное количество голосов теперь у самого первого юзера
                const maxVotesCount = users[0].count;

                // 3. Проходим по исходному массиву (или по копии) и выставляем флаги
                this.aliveUsers.forEach(user => {
                    // Если голосов больше нуля и они равны максимальным — это лидер
                    if (user.count === maxVotesCount && maxVotesCount > 0) {
                        user.isLeader = true;
                    } else {
                        user.isLeader = false;
                    }
                });

                // Сохраняем отсортированный список, если это необходимо для вашей логики
                this.maxVotes = users;

            }

        }
    }
</script>
