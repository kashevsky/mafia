<template x-if="showModalDirectionVote">

    <div class="modal-layout">

        <div class="modal">

            <div class="modal__inner">

                <div class="modal__head">
                    <h2>Выбор направления голосования</h2>
                </div>

                <div class="modal__body">
                        <button class="btn" @click="eventDirection(true)">По часовой</button>
                        <button class="btn" @click="eventDirection(false)">Против часовой</button>

                </div>

            </div>
        </div>

    </div>
</template>


<script></script>
