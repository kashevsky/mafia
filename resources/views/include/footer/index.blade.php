<footer class="footer">

    <div class="footer-container">
        <div class="footer__btns">
            <template x-if="countDays <= 1 && countNights < 1">
                <button class="btn btn-green" x-show="pasBtn && countDays <= 1" @click.stop="eventPasBtn">Пас</button>
            </template>
            <template x-if="stageGame == 10">
                <button class="btn btn-green"  @click.stop="eventOpenNightKnowing()">Мафия знакомится</button>
            </template>
            <br>
            <button class="btn btn-green" x-text="nameBtn" x-show="nameBtn" @click.stop="eventBtn"></button>
            <br>
            <button class="btn btn-green" x-show="showBtnModalNight" x-text="`открыть доску`" x-show="nameBtn"
                @click.stop="showModalNight = true"></button>


        </div>
        <div class="footer__btns">
            <button class="btn btn-primary" @click="eventToggleSidebarMenu()">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layout-grid-circles preview-icon"><circle cx="17.5" cy="17.5" r="3.5"/><circle cx="17.5" cy="6.5" r="3.5"/><circle cx="6.5" cy="17.5" r="3.5"/><circle cx="6.5" cy="6.5" r="3.5"/></svg>                Меню управления
            </button>

            <button class="btn btn-primary" @click="hideRoles()"
                x-text="isHiddenRoles ? 'Спрятать роли' : 'Показать роли'"></button>
        </div>
    </div>

    <div class="timer" x-ref='timer' x-show="stageGame === 1">00:00</div>
</footer>
