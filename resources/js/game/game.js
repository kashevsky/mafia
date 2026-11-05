//***** стадия игры (stageGame)  ***** //
// users: [
//   {
//     id: 1, // id
//     name: 'Чебурашка', // Имя
//     role: 'Судья', // Роль
//     character: 'Y', // характер ( Y - город, N - мафия )
//     count: 0, // количество голосов
//     arr_id_vote: [], // массив проголосовавших ( !!! под вопросом !!! )
//     is_alive: 1, // живой или мертвый ( 0 || 1 )
//     active: 0, // активен  ( 0 || 1 )
//     target: 0, //  пользователь на кого голосуют ( 0 || 1 )
//     number: 1, // номер пользователя
//     position: 1, // номер места за столом ( !!! под вопросом !!! )
//     is_vote: 0, // пользователь проголосовал ( 0 || 1 )
//     start_game: 0, // пользователь начал игру ( 0 || 1 )
//     buff_role: [], // роли  игроков кто повесил бафф
//   },
// ],

const GAME_STAGES = {
    // === ОСНОВНЫЕ СТАДИИ ===
    CHOOSE_FIRST_PLAYER: 0,        // выбор первого игрока
    DAY_VOTE: 1,                   // голосование (день)
    VOTE_RESULT: 2,                // результат голосования (день)
    ROULETTE_CONFIRM: 3,           // рулетка (подтвердить игру)
    ROULETTE_PLAY: 4,              // рулетка (игра)
    NIGHT_WAIT: 5,                 // ночь (ожидаем пробуждение)
    NIGHT_END: 5.1,                // ночь (пробуждение - выбор кого разбудить)

    // === МАФИЯ ===
    MAFIA_KILL: 6,                 // сюжет игры (МАФИЯ) - убийство
    MAFIA_DON: 6.1,                // сюжет игры (МАФИЯ - Дон)
    MAFIA_PRESPECHNIK: 6.2,        // сюжет игры (МАФИЯ - Приспешник)

    // === МИРНЫЕ РОЛИ ===
    PEACE_SHERIF: 7.1,             // сюжет игры (МИРНЫЕ - Шериф)
    PEACE_DOCTOR: 7.2,             // сюжет игры (МИРНЫЕ - Доктор)
    PEACE_ADVOCAT: 7.3,            // сюжет игры (МИРНЫЕ - Адвокат)
    PEACE_KARATEL: 7.4,            // сюжет игры (МИРНЫЕ - Каратель)
    PEACE_FREDDI: 7.5,             // сюжет игры (МИРНЫЕ - Фредди)
    PEACE_SUDYA: 7.6,              // сюжет игры (МИРНЫЕ - Судья)
    PEACE_DETECTIVE: 7.7,          // сюжет игры (МИРНЫЕ - Детектив)
    PEACE_BOMBA: 7.8,              // сюжет игры (МИРНЫЕ - Бомба)
    PEACE_DZHURNALIST: 7.9,        // сюжет игры (МИРНЫЕ - Журналист)
    PEACE_STRELOCHNIK: 7.11,        // сюжет игры (МИРНЫЕ - Стрелочник)
    PEACE_OBOROTEN: 7.12,           // сюжет игры (МИРНЫЕ - Оборотень)
    // === СПЕЦИАЛЬНЫЕ ===
    NIGHT_KNOWING: 10,              // ночь знакомств мафии
    SKIP_VOTE: 20,                 // игрок пропускает голосование (заблокирован судьей)
    GAME_END_NIGHT: 50,            // конец игры (ночь)
};

const GAME_STATE_KEYS = [
    'stageGame', 'users', '_userStartGameId', 'countDays', 'countNights',
    '_quantityPossibleDoctor', '_quantityBlockDon', '_quantityCheckDzhurnalist',
    '_isActiveSkillPrespechnik', 'saveNightGame', '_directionVote', 'isMafiaVote',
    'boardMess', 'nameBtn', 'messBoardAlert', 'activeUser', 'targetUser',
    'aliveUsers', 'leaders', 'usersMafia', 'usersGood', 'pairKaratel',
    'pairSherif', 'pairAdvakant', 'pairDetective', 'showModalNight',
    'showBtnModalNight', 'showModalFinishGame', 'showModalDeleteUser',
    'showModalDeleteUserConfirm', 'showModalVote', 'showModalNightKnowing',
    'whatDay', 'pasBtn', 'showModalDirectionVote', 'resumeKillUsers',
    'isKillStrelochnik', 'stageGameObj', 'finishGame', '_numberActiveUser',
    '_fromVoteId', '_toVote', '_selectedUserId', 'userDelete'
];


/**
 * @this {any}
 */
export default function initGame() {
    return {

        // ********************************* //
        // ******* атрибуты  ******** //
        // ********************************* //

        stageGame: GAME_STAGES.NIGHT_KNOWING, // стадия игры

        users: [],
        _userStartGameId: null, // id пользователя который начал игру

        countDays: 0, // количество дней
        countNights: 0, // количество ночей

        _quantityPossibleDoctor: 2, // количество раз лечить себя (Доктор)
        _quantityBlockDon: 1, // количество раз блокирует (Дон)
        _quantityCheckDzhurnalist: 2, // количество раз проверяет (Журналист)
        _isActiveSkillPrespechnik: true, // активен ли скилл Приспешника


        // атрибуты для сохранения игры на ночь
        saveNightGame: {
            idPlayerKillsTheMafia: null, // id игрока которого убивает мафия
            idPlayerBlockedTheDon: null,
            idPlayerBlockedThePrespechnik: null, // id игрока который блокирует Приспешника
            idPlayerCheckTheSherif: null, // id игрока который проверяет шерифа
            idPlayerTherapyTheDoctor: null, // id игрока которого лечит доктор
            idPlayerImmunityTheAdvokat: null, // id игрока который иммунитет Адвоката
            idPlayerKillTheKaratel: null, // id игрока который убивает Карателя
            idPlayerCheckTheFreddy: null, // id игрока который проверяет Фредди
            idPlayerBlockTheSudiy: null, // id игрока который блокирует Судью
            arrIdsPlayersBlockTheSudiy: [], // массив id игроков которые блокирует Судья
            idPlayerCheckTheDetective: null, // id игрока который проверяет Детектива
            idPlayerKillTheDetective: null, // id игрока которого убивает Детектив
            arrIdsPlayersMinesTheBomba: [], // массив id игроков которых минирует Бомба
            arrIdsPlayersCheckTheDzhurnalist: [], // массив id игроков которых проверяет Джурналист
            arrIdsPlayerKillTheStrelochnik: [], // массив id игроков на кого переводит Стрелочник
            prespechnikHasChosen: false, // Приспешник уже выбрал игрока
        },

        _directionVote: false, // направление голосования ( true - по часовой, false - против часовой)
        isMafiaVote: false, // флаг что мафия проголосовала

        // ********************************* //
        // ******** атрибуты для игры ******** //
        // ********************************* //
        arrUsersRoles: ['Шериф', 'Доктор', 'Детектив', 'Адвокат', 'Фредди', 'Судья', 'Каратель', 'Журналист', 'Бомба'], // массив ролей по порядку (мирные)
        boardMess: '', // сообщение на доске
        nameBtn: '', // название кнопки
        messBoardAlert: '', // сообщение на доске
        activeUser: null, // активный игрок ( объект )
        targetUser: null, // пользователь на кого голосуют ( объект )
        aliveUsers: [], // живые игроки
        leaders: [], // лидеры
        usersMafia: [], // игроки мафии
        usersGood: [], // игроки мирные
        pairKaratel: [], // пара Карателя
        pairSherif: [], // пара Шерифа
        pairAdvakant: [], // пара Адвоката
        pairDetective: [], // пара Детектива
        showModalNight: false, // модальное окно ночи
        showBtnModalNight: false, // кнопка модального окна ночи
        showModalFinishGame: false, // модальное окно окончания игры
        showModalDeleteUser: false, // модальное окно удаления игрока
        showModalDeleteUserConfirm: false, // модальное окно подтверждения удаления игрока
        showModalVote: false, // модальное окно голосования
        showModalNightKnowing: false, // модальное окно знакомства мафии
        showModalImmunity: false, // модальное окно иммунитета
        showModalViolations: false, // модальное окно нарушений
        showBtnModalViolations: false, // кнопка открытия модального окна нарушений
        showSidebarMenu: false, // боковое меню управления

        whatDay: 'День', // день или ночь
        alertPage: { // сообщение
            show: false,
            text: "",
        },
        pasBtn: false, // кнопка паса
        showModalDirectionVote: false, // модальное окно выбора направления голосования
        resumeKillUsers: [],  // итоги ночной игры (смерти)
        isKillStrelochnik: false, // флаг что в Стрелочника стреляют

        stageGameObj: {}, // текст стадии игры
        finishGame: null,

        // приватные атрибуты
        _numberActiveUser: null, // номер активного игрока
        _fromVoteId: null, // id пользователя который голосует
        _toVote: null, // пользователя на кого голосуют
        _selectedUserId: null, // id выбранного пользователя
        userDelete: null, // игрока которого удаляют админом


        isHiddenRoles: false, // флаг что скрыты роли игроков
        tabs: [],

        timeLeft: 0,
        intervalId: null, // Храним ID интервала здесь
        timerElement: this.$refs.timer,


        arrUsersKillBomba: [], // игроки взорваные бомбой
        arrUsersKillCaratel: [], // игроки убитые карателем
        arrUsersKillFredy: [], // игроки убитые фреди
        arrUsersKillStrelochnik: [], // игроки убитые стрелочником
        arrUsersKillDetective: [], // игроки убитые детектовом


        showResultRoulette: false,
        alertRoulette: {
            text: ''
        },


        // ******************************//
        init() {

            this.users = this.$store.globalData.users;
            console.log('users', this.users);

            this._startGame();
            this._loadGameState();
        },

        // ******************************//
        // ***** публичные методы ***** //
        // ******************************//

        // *************EVENTS*************************//
        eventOpenNightKnowing() {
            this.showModalNightKnowing = true

            if (this.$store.globalData.isPrispeshnik == '') {
                this.alertPage = {
                    show: true,
                    text: 'В игре не будет Приспешника, но Дон всё равно обязан сделать назначение.'
                };
            }
        },

        eventAssignPrispeshnik(id) {
            let newPrispeshnik = this.findUserId(id);

            newPrispeshnik.role = 'Приспешник';
            this.saveNightGame.prespechnikHasChosen = false; // сбрасываем флаг при назначении Приспешника
            this.showModalNightKnowing = false;
            this.stageGame = GAME_STAGES.CHOOSE_FIRST_PLAYER;
            this._changeRole();
            this._startGame();
        },

        eventMakeAcquaintance() {
            this.showModalNightKnowing = false;
            this.stageGame = GAME_STAGES.CHOOSE_FIRST_PLAYER;
            this._startGame();
        },

        eventOpenModalViolations() {
            console.log(111)
            this.showModalViolations = true;
            this._saveGameState();
        },

        eventAddViolation(userId) {
            const user = this.users.find(u => u.id === userId);
            if (!user) return;

            // Проверяем что игрок жив
            if (!user.is_alive) return;

            // Проверяем что нарушений меньше 4
            if (!user.foul) user.foul = 0;
            if (user.foul >= 4) return;

            // Увеличиваем счетчик нарушений
            user.foul++;

            this._saveGameState();
        },

        eventToggleSidebarMenu() {
            this.showSidebarMenu = !this.showSidebarMenu;
        },

        eventUser(id) {
            let user = this.findUserId(id);
            // if (user && user.active === 1) return;
            this._selectedUserId = id;
            this._toVote = id;

            this.toUser = user;

            switch (this.stageGame) {
                case GAME_STAGES.CHOOSE_FIRST_PLAYER:
                    // выбор первого игрока
                    // Проверяем, не заблокирован ли игрок Судьей

                    if (user.id === this.saveNightGame.idPlayerBlockTheSudiy) {
                        this.alertPage = {
                            show: true,
                            text: `Игрок ${user.nickname || user.name} заблокирован Судьей и не может начинать круг!`
                        };
                        return;
                    }


                    this.nameBtn = `Начинает ${user.nickname || user.name}`;

                    this._resetUsersStartGame();

                    user.start_game = 1;
                    this.resumeKillUsers = [];
                    break;

                case GAME_STAGES.DAY_VOTE:
                    // голосование (день)


                    if (user && user.active === 1) return;


                    this.targetUser = user;
                    this._toggleUserTarget(1);

                    break;

                case GAME_STAGES.MAFIA_KILL:
                    // выбор игрока (убить)

                    if (user && user.active === 1) return;

                    this.targetUser = user;
                    this._toggleUserTarget(1);

                    break;

                case GAME_STAGES.MAFIA_DON:
                    // выбор игрока (заблокировать) Дон

                    this.targetUser = user;

                    if (user && user.active === 1) return;
                    this._toggleUserTarget(1);
                    break;

                case GAME_STAGES.MAFIA_PRESPECHNIK:
                    // выбор игрока (запачкать) Приспешник

                    if (user && user.active === 1) return;

                    this.targetUser = user;
                    this._toggleUserTarget(1);
                    break;

                case GAME_STAGES.PEACE_SHERIF:
                    // Проверить игрока (Шериф)

                    if (user && user.active === 1) return;

                    this.targetUser = user;
                    this._toggleUserTarget(1);
                    break;

                case GAME_STAGES.PEACE_DOCTOR:
                    // Вылечить игрока (Доктор)

                    this.targetUser = user;
                    this._toggleUserTarget(1);
                    break;

                case GAME_STAGES.PEACE_ADVOCAT:
                    // Дать иммунитет (Адвокат)

                    if (user && user.active === 1) return;

                    this.targetUser = user;
                    this._toggleUserTarget(1);
                    break;

                case GAME_STAGES.PEACE_KARATEL:
                    // Убивает (Ферзь)

                    if (user && user.active === 1) return;

                    this.targetUser = user;
                    this._toggleUserTarget(1);
                    break;

                case GAME_STAGES.PEACE_FREDDI:
                    // Выбор игрока (Мститель)

                    if (user && user.active === 1) return;

                    this.targetUser = user;
                    this._toggleUserTarget(1);
                    break;

                case GAME_STAGES.PEACE_SUDYA:
                    // Выбор игрока (Судья)

                    if (user && user.active === 1) return;


                    if (this.saveNightGame.arrIdsPlayersBlockTheSudiy.length && this.saveNightGame.arrIdsPlayersBlockTheSudiy[0] === user.id) {
                        this.alertPage = {
                            show: true,
                            text: `Нельзя помечать одного и того же игрока подряд!`
                        }
                        return;
                    }


                    this.targetUser = user;
                    this._toggleUserTarget(1);
                    break;

                case GAME_STAGES.PEACE_DETECTIVE:
                    // Выбор игрока (Детектив)

                    if (user && user.active === 1) return;

                    this.targetUser = user;
                    this._toggleUserTarget(1);
                    break;

                case GAME_STAGES.PEACE_BOMBA:
                    // Выбор игрока (Бомба)

                    if (user && user.active === 1) return;

                    this.targetUser = user;
                    this._toggleUserTarget(1);
                    break;

                case GAME_STAGES.PEACE_DZHURNALIST:
                    // Выбор игрока (Журналист)

                    if (user && user.active === 1) return;

                    this.targetUser = user;
                    this._toggleUserTarget(1);
                    break;

                case GAME_STAGES.PEACE_STRELOCHNIK:
                    // Выбор игрока (Стрелочник)

                    if (user && user.active === 1) return;

                    this.targetUser = user;
                    this._toggleUserTarget(1);
                    break;

                case GAME_STAGES.SKIP_VOTE:
                    // Игрок заблокирован Судьей — не даём голосовать
                    this.alertPage = {
                        show: true,
                        text: `Игрок заблокирован Судьей и не может голосовать!`
                    };
                    return;
            }

            this._saveGameState();

        },


        eventDirection(isDirection) {
            this._directionVote = isDirection;
            this.showModalDirectionVote = false;
            this.pasBtn = true;
            this.nameBtn = '';
            this.stageGame = GAME_STAGES.DAY_VOTE;
            this._outputAlertBoard();
            this._toggleUserTarget(0);

            // первый уже проголосовал — запускаем очередь от него
            this._nextUser();

            this._saveGameState();
            this.timer();
        },

        eventBtn() {
            console.log(this.stageGame);

            switch (this.stageGame) {

                case GAME_STAGES.CHOOSE_FIRST_PLAYER:
                    // выбор первого игрока

                    this.toUser = null;
                    this.countDays++;
                    this._fromVoteId = this._selectedUserId;

                    let nextUser = this.findUserId(this._selectedUserId);
                    this._nextStartUserGame(nextUser);

                    // переходим к дневному голосованию — первый игрок уже активен
                    this.pasBtn = true;
                    this.stageGame = GAME_STAGES.DAY_VOTE;
                    this._outputAlertBoard();
                    this.timer();

                    this._sendDataGame();

                    break;

                case GAME_STAGES.VOTE_RESULT:
                    // начало игры (ночь)
                    this.activeUser = null;
                    this.pasBtn = false;
                    this.countNights++;

                    this.whatDay = 'Ночь';
                    this.saveNightGame.idPlayerImmunityTheAdvokat = null;
                    this.saveNightGame.idPlayerBlockTheSudiy = null;

                    // сброс заминированных игроков во вторую ночь
                    if (this.countNights == 2) {
                        this.saveNightGame.arrIdsPlayersMinesTheBomba = [];
                    }

                    // бомба не может голосовать после второй ночи
                    if (this.countNights > 2) {
                        let bomba = this.findUserRole('Бомба');
                        if (bomba) {

                            bomba.is_vote = 1
                        }
                    }

                    if (this._quantityCheckDzhurnalist === 1) {
                        let jurnalist = this.findUserRole('Журналист');
                        if (jurnalist) {

                            jurnalist.is_vote = 1
                        }
                    }

                    this._resetUsersBuff(true); // сброс всех бафов без исключений
                    this._startNight();
                    this._sendDataGame();
                    break;

                case GAME_STAGES.ROULETTE_CONFIRM:
                    // подтверждение игры (рулетка)
                    this.activeUser = null;
                    this._startRoulette();
                    this._sendDataGame();
                    break;

                case GAME_STAGES.MAFIA_DON:
                    // Дон пасует

                    this.findUserRole("Дон").is_vote = 1;
                    this._resetUsersActive();
                    this._toggleNextRoleMafia();
                    this._sendDataGame();
                    break;

                case GAME_STAGES.SKIP_VOTE:
                    // игрок пасует (день)
                    this.nameBtn = '';

                    this.toUser = null;
                    this._sendDataGame();
                    this.stageGame = GAME_STAGES.DAY_VOTE;
                    this._completedVote(this.saveNightGame.idPlayerBlockTheSudiy);
                    this._nextUser();
                    this._outputAlertBoard();

                    break;

                case GAME_STAGES.PEACE_DZHURNALIST:
                    // Журналист пасует ход

                    this.toUser = null;
                    this.nameBtn = '';
                    let dzhurnalist = this.findUserRole("Журналист");

                    dzhurnalist.is_vote = 1;
                    this._resetUsersActive();
                    this._resetUserTarget();
                    this.showModalNight = true;
                    this._checkVoteRoleGood();
                    this.alertPage = {
                        show: true,
                        text: `Журналист пасовал ход (осталось: ${this._quantityCheckDzhurnalist}/2)`
                    };
                    this._sendDataGame();
                    break;

                case GAME_STAGES.MAFIA_PRESPECHNIK:
                    this.findUserRole("Приспешник").is_vote = 1;
                    this._resetUsersActive();
                    this._toggleNextRoleMafia();
                    this._sendDataGame();
                    break;

            }
            this._saveGameState();
        },

        eventWakeUp(role) {

            this._resetUsersActive();
            switch (role) {
                case 'Мафия':
                    // пробуждение мафии (мафия, Приспешник, дон)
                    this._toggleRoleActive(['Мафия', 'Дон', 'Приспешник']);

                    // проверяем роль Оборотень

                    let isAliveOboroten = this._findAliveUsers().find(user => user.character == "N" && user.role == "Оборотень");

                    if (isAliveOboroten) {
                        this._toggleRoleActive(['Оборотень']);
                    }

                    this.stageGame = GAME_STAGES.MAFIA_KILL;
                    break;
                case 'Шериф':
                    // пробуждение Шериф
                    this._toggleRoleActive(['Шериф']);
                    this.stageGame = GAME_STAGES.PEACE_SHERIF;
                    break;
                case 'Доктор':
                    // пробуждение Доктор
                    this._toggleRoleActive(['Доктор']);
                    this.stageGame = GAME_STAGES.PEACE_DOCTOR;
                    break;
                case 'Адвокат':
                    // пробуждение Адвокат
                    this._toggleRoleActive(['Адвокат']);
                    this.stageGame = GAME_STAGES.PEACE_ADVOCAT;
                    break;
                case 'Каратель':
                    // пробуждение Ферзь
                    this._toggleRoleActive(['Каратель']);
                    this.stageGame = GAME_STAGES.PEACE_KARATEL;
                    break;
                case 'Фредди':
                    // пробуждение Мститель
                    this._toggleRoleActive(['Фредди']);
                    this.stageGame = GAME_STAGES.PEACE_FREDDI;
                    break;
                case 'Судья':
                    // пробуждение Судья
                    this._toggleRoleActive(['Судья']);
                    this.stageGame = GAME_STAGES.PEACE_SUDYA;
                    break;
                case 'Детектив':
                    // пробуждение Детектив
                    this._toggleRoleActive(['Детектив']);
                    this.stageGame = GAME_STAGES.PEACE_DETECTIVE;
                    break;
                case 'Бомба':
                    // пробуждение Бомба
                    this._toggleRoleActive(['Бомба']);
                    this.stageGame = GAME_STAGES.PEACE_BOMBA;
                    break;
                case 'Журналист':
                    // пробуждение Журналист
                    this._toggleRoleActive(['Журналист']);
                    this.stageGame = GAME_STAGES.PEACE_DZHURNALIST;
                    break;
                case 'Стрелочник':
                    // пробуждение Стрелочник
                    this._toggleRoleActive(['Стрелочник']);
                    this.stageGame = GAME_STAGES.PEACE_STRELOCHNIK;
                    break;
            }

            this._resetUserTarget();
            this._outputAlertBoard();
            this.showModalNight = false;
            this._saveGameState();
        },

        eventEndNight() {
            this.resumeKillUsers = [];

            let userSaveDoctor = this.findUserId(this.saveNightGame.idPlayerTherapyTheDoctor);
            let userKillMafia = this.findUserId(this.saveNightGame.idPlayerKillsTheMafia);
            let userKillKaratel = this.findUserId(this.saveNightGame.idPlayerKillTheKaratel);
            let userMarkFreddy = this.findUserId(this.saveNightGame.idPlayerCheckTheFreddy);
            let userKillDetective = this.findUserId(this.saveNightGame.idPlayerKillTheDetective);
            let userMarkerSudia = this.findUserId(this.saveNightGame.idPlayerBlockTheSudiy);

            // Проверяем кого вылечил доктор
            let doctorSaved = false; // Флаг что доктор уже спас кого-то

            // Проверяем кого вылечил доктор (только один спас)
            if (userSaveDoctor && !doctorSaved) {
                // Доктор спасает от выстрела мафии

                if (userSaveDoctor.id == userKillMafia?.id) {
                    this.saveNightGame.idPlayerKillsTheMafia = null;
                    userKillMafia = null;

                    doctorSaved = true;

                } else if (userSaveDoctor.id == userKillKaratel?.id && !doctorSaved) { // Доктор спасает от выстрела карателя (если не спас от мафии)
                    this.saveNightGame.idPlayerKillTheKaratel = null;
                    userKillKaratel = null;

                    doctorSaved = true;

                } else if (userSaveDoctor.id == userKillDetective?.id && !doctorSaved) { // Доктор спасает от выстрела детектива (если не спас никого ранее)
                    // Проверяем: если цель детектива - мафия, лечение игнорируется
                    if (userKillDetective.character === 'N') {
                        // Не лечим мафию от детектива
                        doctorSaved = false;
                    } else {
                        // Лечим мирного от детектива
                        this.saveNightGame.idPlayerKillTheDetective = null;
                        userKillDetective = null;
                        doctorSaved = true;
                    }

                } else if (this.saveNightGame.arrIdsPlayerKillTheStrelochnik.includes(userSaveDoctor.id) && !doctorSaved) { // Доктор спасает от выстрела стрелочника (если не спас никого ранее)

                    const index = this.saveNightGame.arrIdsPlayerKillTheStrelochnik.indexOf(userSaveDoctor.id);
                    if (index !== -1) {
                        this.saveNightGame.arrIdsPlayerKillTheStrelochnik.splice(index, 1);

                        doctorSaved = true;
                    }
                }

            }

            // если доктор не выбрал, то один и тот же в кого выстрела мафия и Ферзь

            if (userKillMafia?.id == userKillKaratel?.id) {
                this.saveNightGame.idPlayerKillsTheMafia = null;
                userKillMafia = null
            }

            // Проверяем тех кого убила мафия
            if (userKillMafia) {


                if (userKillMafia.role === 'Оборотень' && !userKillMafia.buff_role.includes('Дон')) {

                    this.resumeKillUsers.push({
                        user: userKillMafia,
                        text: "мафия не убила оборотня",
                        status: 'save',
                    });
                    userKillMafia = null;
                } else {

                    userKillMafia.is_alive = 0;

                    this.resumeKillUsers.push({
                        user: userKillMafia,
                        text: "Убит",
                        status: 'kill',
                    });
                }
            }

            // Проверяем тех кого убил Ферзь
            if (userKillKaratel) {

                userKillKaratel.is_alive = 0;

                this.resumeKillUsers.push({
                    user: userKillKaratel,
                    text: "убит",
                    status: 'kill',
                });
                this.arrUsersKillCaratel.push(userKillKaratel)
            }

            // проверяем кого убил детектив
            if (userKillDetective) {

                userKillDetective.is_alive = 0;


                this.arrUsersKillDetective.push(userKillDetective);


                this.resumeKillUsers.push({
                    user: userKillDetective,
                    text: "Убит",
                    status: 'kill',
                });
            }

            // проверяем кого убил стрелочник
            if (this.saveNightGame.arrIdsPlayerKillTheStrelochnik.length) {
                this.saveNightGame.arrIdsPlayerKillTheStrelochnik.forEach(id => {
                    let user = this.findUserId(id);

                    user.is_alive = 0;

                    this.resumeKillUsers.push({
                        user: user,
                        text: "Убит",
                        status: 'kill',
                    });

                    this.arrUsersKillStrelochnik.push(user)
                });

            }

            // проверяем кого пометил Мститель и жив ли он
            let freddy = this.findUserRole('Фредди');

            if (userMarkFreddy && !freddy.is_alive) {


                if (userMarkFreddy.character === 'N') {
                    userMarkFreddy.is_alive = 0;
                    this.arrUsersKillFredy.push(userMarkFreddy);

                    this.resumeKillUsers.push({
                        user: userMarkFreddy,
                        text: "Убит",
                        status: 'kill',
                    });
                    this.saveNightGame.idPlayerCheckTheFreddy = null;
                }
            }

            // ЛОГИКА БОМБЫ - ВЗРЫВ при смерти Бомбы
            let bomba = this.findUserRole('Бомба');

            const isBombaKilled = bomba && !bomba.is_alive;

            this._nextStartUserGame();

            if (isBombaKilled && bomba && this.saveNightGame.arrIdsPlayersMinesTheBomba.length > 0) {
                this._explodeBombWithMines(bomba, 'взорван Бомбой (ночное убийство)');
                this.messBoardAlert = `Бомба ${bomba.nickname} взорвалась! Заминированные тоже погибли!`;
                this.alertPage = {
                    show: true,
                    text: `Бомба уничтожена вместе с заминированными!`
                };
            }

            // проверяем кого выбрал судья

            if (userMarkerSudia && userMarkerSudia.is_alive) {

                this.resumeKillUsers.push({
                    user: userMarkerSudia,
                    text: "Судья накладывает Вето",
                    status: 'save',
                });
            }

            // Если ночью никого не убили и не спасли - добавляем сообщение "Жертв нет"
            if (this.resumeKillUsers.length === 0) {
                this.resumeKillUsers.push({
                    user: null,
                    text: "Жертв нет",
                    status: 'save',
                });
            }

            this._sendDataGame();

            this.showModalNight = false;
            this.showBtnModalNight = false;
            this.targetUser = null;
            this.stageGame = GAME_STAGES.CHOOSE_FIRST_PLAYER; // переключаем на день (голосование)
            this.pasBtn = false; // показываем кнопку "пас"
            this._startGame(); // начинаем игру
            // this.whatDay = 'День';

            // очищаем итоги за ночь
            this.saveNightGame.idPlayerKillsTheMafia = null;
            this.saveNightGame.idPlayerBlockedTheDon = null;
            this.saveNightGame.idPlayerCheckTheSherif = null;
            this.saveNightGame.idPlayerBlockedThePrespechnik = null;
            this.saveNightGame.idPlayerTherapyTheDoctor = null;
            this.saveNightGame.idPlayerKillTheKaratel = null;

            this.saveNightGame.idPlayerKillTheFreddy = null;
            this.saveNightGame.idPlayerKillTheDetective = null;
            this.saveNightGame.arrIdsPlayerKillTheStrelochnik = [];
            this.saveNightGame.arrIdsPlayersCheckTheDzhurnalist = [];
            this.arrUsersKillBomba = [];
            this.arrUsersKillCaratel = [];
            this.arrUsersKillFredy = [];
            this.arrUsersKillStrelochnik = [];
            this.arrUsersKillDetective = [];
            this._oborotenBecomesMafia();

            // очищаем все бафы
            this._resetUsersBuff();
            this._saveGameState();

        },

        eventVote() {

            const targetUser = this.findUserId(this.targetUser.id);

            this.activeUser = this.users.find(user => user.active == 1);

            switch (this.stageGame) {
                case GAME_STAGES.DAY_VOTE:
                    // голосование (день)

                    // проверка на имунитет d/n   d/n/d
                    if (this.checkImmunities(targetUser)) return;

                    this._sendDataGame();

                    this._fromVoteId = this.activeUser.id;
                    // проверяем: это первый голос в дне?
                    const isFirstVoteOfDay = this.users.every(u => u.is_vote === 0);
                    this._addingVote();

                    if (isFirstVoteOfDay) {
                        // первый игрок проголосовал — теперь выбираем направление
                        this._completedVote(this.activeUser.id);
                        this.showModalDirectionVote = true;
                        this._outputAlertBoard();
                        this.timer();
                        this._saveGameState();
                        break;
                    }

                    this._completedVote(this.activeUser.id);
                    this._nextUser();
                    this._outputAlertBoard();
                    this.timer();

                    break;

                case GAME_STAGES.MAFIA_KILL:
                    // проверка на имунитет d/n   d/n/d
                    if (this.checkImmunities(targetUser)) return;
                    this._sendDataGame();
                    // сохраняем выбор мафии

                    this.saveNightGame.idPlayerKillsTheMafia = this.targetUser.id;

                    console.log(targetUser)

                    targetUser.buff_role = [...targetUser.buff_role, "Мафия"];

                    // отмечаем что мафия проголосовала
                    this.users.map(user => {
                        if (user.role == 'Мафия') {
                            user.is_vote = 1;
                        }
                    });

                    // проверяем роль Оборотень

                    let isAliveOboroten = this._findAliveUsers().find(user => user.character == "N" && user.role == "Оборотень");

                    if (isAliveOboroten) {
                        let oboroten = this.findUserRoleAll('Оборотень');
                        // отмечаем что оборотень проголосовал
                        oboroten.forEach(user => user.is_vote = 1);

                    }


                    this._resetUsersActive();
                    this._resetUserTarget();
                    this._toggleNextRoleMafia(); // переключаем роль c мафии далее (Дон, консильер)
                    break;

                case GAME_STAGES.MAFIA_DON:
                    // проверка на имунитет d/n   d/n/d
                    if (this.checkImmunities(targetUser)) return;
                    this._sendDataGame();
                    // сохраняем выбор мафии (Дон)

                    this.saveNightGame.idPlayerBlockedTheDon = this.targetUser.id;

                    targetUser.buff_role = [...targetUser.buff_role, "Дон"];

                    // отмечаем что Дон проголосовал
                    this.users.map(user => {

                        if (user.role == 'Дон') {

                            user.is_vote = 1;
                        }
                    });
                    this._quantityBlockDon = 0; // сбрасываем счетчик блокировки Дон
                    this._resetUsersActive();
                    this._resetUserTarget();
                    this._toggleNextRoleMafia(); // переключаем роли далее
                    break;

                case GAME_STAGES.MAFIA_PRESPECHNIK:
                    // проверка на имунитет d/n   d/n/d
                    if (this.checkImmunities(targetUser)) return;
                    this._sendDataGame();
                    // сохраняем выбор мафии (Приспешник)
                    let finishPrespechnik = false;

                    //нельзя запачкать Бомбу

                    if (this.targetUser.role === 'Бомба') {
                        finishPrespechnik = true;
                    }

                    //нельзя запачкать Мафию

                    if (this.targetUser.character === 'N') {
                        this.alertPage = {
                            show: true,
                            text: `Нельзя запачкать Мафию!`
                        }
                        return;
                    }

                    //нельзя запачкать закочились шансы
                    if (!this._isActiveSkillPrespechnik) {
                        finishPrespechnik = true;
                        this.alertPage = {
                            show: true,
                            text: `Шансы закончились!`
                        }
                    }

                    if (!finishPrespechnik) {

                        this.saveNightGame.idPlayerBlockedThePrespechnik = this.targetUser.id;

                        targetUser.buff_role = [...targetUser.buff_role, "Приспешник"];

                        // Отмечаем, что Приспешник выбрал игрока
                        this.saveNightGame.prespechnikHasChosen = true;
                    }

                    // отмечаем что Приспешник проголосовал
                    this.users.map(user => {

                        if (user.role == 'Приспешник') {

                            user.is_vote = 1;
                        }
                    });

                    this._resetUsersActive();
                    this._resetUserTarget();
                    this._toggleNextRoleMafia(); // переключаем роли далее
                    break;

                case GAME_STAGES.PEACE_SHERIF:
                    this._sendDataGame();
                    // Проверяем выбор Шерифа
                    /**
                     * @type {any[]}
                     */
                    let sherif = this.findUserRoleAll("Шериф");

                    let finishSherif = false;

                    sherif.forEach(u => {
                        if (u.buff_role.includes("Дон")) {
                            this.alertPage = {
                                show: true,
                                text: `Не может проверить, запачкан Доном!`
                            }

                            this.toUser = null;
                            finishSherif = true;
                        }
                    })


                    if (this.targetUser.buff_role.includes("Приспешник") && !finishSherif) {

                        const isOboroten = this.targetUser.role === 'Оборотень';

                        if (!isOboroten) {
                            this.alertPage = {
                                show: true,
                                text: `мафия (компрометирован)`
                            }
                        } else {
                            this.alertPage = {
                                show: true,
                                text: `мирный житель`
                            }
                        }

                        this._isActiveSkillPrespechnik = false;

                        targetUser.buff_role = targetUser.buff_role.filter((/** @type {string} */ role) => role !== "Приспешник"); // удаляем Приспешника
                        this.saveNightGame.idPlayerBlockedThePrespechnik = null; // сбрасываем выбор Приспешника

                        this.saveNightGame.idPlayerCheckTheSherif = this.targetUser.id;
                        finishSherif = true;
                    }

                    if (!finishSherif) {
                        let text = "";

                        if (this.targetUser.character === 'Y') {
                            text = `мирный житель`
                        } else {
                            text = `мафия`
                        }

                        // дополнительная проверка для Обортня
                        if (this.targetUser.role === 'Оборотень' && this.targetUser.character === 'N') {
                            text = `мафия`
                        }

                        this.alertPage = {
                            show: true,
                            text: text
                        }
                        this.saveNightGame.idPlayerCheckTheSherif = this.targetUser.id;
                    }

                    // отмечаем что Шериф проголосовал
                    this.users.map(user => {

                        if (user.role == 'Шериф') {

                            user.is_vote = 1;
                        }
                    });

                    this._resetUsersActive(); // сброс активных пользователей
                    this._resetUserTarget(); // сброс активных пользователей
                    this.showModalNight = true; // показываем окно ночи
                    this._checkVoteRoleGood(); // проверка все ли проголосовали
                    break;

                case GAME_STAGES.PEACE_DOCTOR:
                    // проверка на имунитет d/n   d/n/d
                    if (this.checkImmunities(targetUser)) return;
                    this._sendDataGame();
                    // Проверяем выбор Доктор

                    let doctor = this.findUserRole("Доктор");
                    let finishDoctor = false;


                    if (doctor.buff_role.includes("Дон")) {
                        this.alertPage = {
                            show: true,
                            text: `Не может лечить, заблокирован Доном!`
                        }

                        this.toUser = null;
                        finishDoctor = true;
                    }
                    // лечит сам себя если не использовал количество лечений (2 раза)

                    if (targetUser.id === doctor.id && this._quantityPossibleDoctor >= 0 && !finishDoctor) {
                        this._quantityPossibleDoctor--;
                    }

                    // не может лечить сам себя

                    if (targetUser.id === doctor.id && this._quantityPossibleDoctor < 0 && !finishDoctor) {
                        this.alertPage = {
                            show: true,
                            text: `закончились возможности лечения себя!`
                        }
                        return;
                    }


                    if (!finishDoctor) {
                        this.alertPage = {
                            show: true,

                            text: `Лечить игрока ${targetUser.nickname}: ${targetUser.role}`
                        }

                        this.saveNightGame.idPlayerTherapyTheDoctor = targetUser.id;

                        targetUser.buff_role = [...targetUser.buff_role, "Доктор"];
                    }

                    // отмечаем что Доктор проголосовал
                    this.users.map(user => {

                        if (user.role == 'Доктор') {

                            user.is_vote = 1;
                        }
                    });

                    this._resetUsersActive(); // сброс активных пользователей
                    this._resetUserTarget(); // сброс активных пользователей
                    this.showModalNight = true; // показываем окно ночи
                    this._checkVoteRoleGood(); // проверка все ли проголосовали
                    break;

                case GAME_STAGES.PEACE_ADVOCAT:
                    this._sendDataGame();
                    // Проверяем выбор Aдвокат

                    /**
                     * @type {any[]}
                     */
                    let advocat = this.findUserRoleAll("Адвокат");
                    let finishAdvocat = false;

                    advocat.forEach(u => {
                        if (u.buff_role.includes("Дон")) {
                            this.alertPage = {
                                show: true,
                                text: `Не дать иммунитет, заблокирован Доном!`
                            }

                            this.toUser = null;
                            finishAdvocat = true;
                        }
                    })


                    // не может дать иммунитет сам себя

                    if (targetUser.role === "Адвокат" && !finishAdvocat) {
                        this.alertPage = {
                            show: true,
                            text: `Не можно дать иммунитет самому себе!`
                        }
                        return;
                    }

                    if (!finishAdvocat) {
                        this.alertPage = {
                            show: true,

                            text: `Дает иммунитет игроку ${targetUser.nickname}: ${targetUser.role}`
                        }

                        this.saveNightGame.idPlayerImmunityTheAdvokat = targetUser.id;

                        targetUser.buff_role = [...targetUser.buff_role, "Адвокат"];
                    }

                    // отмечаем что Адвокат проголосовал
                    this.users.map(user => {

                        if (user.role == 'Адвокат') {

                            user.is_vote = 1;
                        }
                    });

                    this._resetUsersActive(); // сброс активных пользователей
                    this._resetUserTarget(); // сброс активных пользователей
                    this.showModalNight = true; // показываем окно ночи
                    this._checkVoteRoleGood(); // проверка все ли проголосовали
                    break;

                case GAME_STAGES.PEACE_KARATEL:
                    // проверка на имунитет d/n   d/n/d
                    if (this.checkImmunities(targetUser)) return;
                    this._sendDataGame();
                    // Проверяем выбор Ферзь

                    /**
                     * @type {any[]}
                     */
                    let karatel = this.findUserRoleAll("Каратель");

                    let finishKaratel = false


                    karatel.forEach((u) => {
                        if (u.buff_role.includes("Дон")) {
                            this.alertPage = {
                                show: true,
                                text: `Не может совершить убийство, заблокирован Доном!`
                            }

                            this.toUser = null;
                            finishKaratel = true;
                        }
                    })


                    if (!finishKaratel) {
                        this.alertPage = {
                            show: true,

                            text: `Убивает игрока: ${targetUser.nickname} - ${targetUser.role}`
                        }

                        this.saveNightGame.idPlayerKillTheKaratel = targetUser.id;

                        targetUser.buff_role = [...targetUser.buff_role, "Каратель"];

                        // проверяем был ли выстрел в Стрелочника
                        const killStrelochnikKaratel = this.findUserRole('Стрелочник');

                        if (killStrelochnikKaratel && killStrelochnikKaratel.id == this.saveNightGame.idPlayerKillTheKaratel && !killStrelochnikKaratel.buff_role.includes('Дон')) {
                            if (!this.isKillStrelochnik) {

                                killStrelochnikKaratel.is_vote = 0;
                                this.isKillStrelochnik = true;
                                this.alertPage = {
                                    show: true,
                                    text: `Выстрел в Стрелочника, Стрелочник просыпается!`
                                }

                                // отмечаем что Ферзь проголосовал
                                this.users.map(user => {

                                    if (user.role == 'Каратель') {

                                        user.is_vote = 1;
                                    }
                                });

                                this.showModalNight = true;
                                this.eventWakeUp('Стрелочник');
                                this._outputAlertBoard();

                                return;
                            }
                        }
                    }

                    // отмечаем что Ферзь проголосовал
                    this.users.map(user => {

                        if (user.role == 'Каратель') {

                            user.is_vote = 1;
                        }
                    });

                    this._resetUsersActive(); // сброс активных пользователей
                    this._resetUserTarget(); // сброс активных пользователей
                    this.showModalNight = true; // показываем окно ночи
                    this._checkVoteRoleGood(); // проверка все ли проголосовали
                    break;

                case GAME_STAGES.PEACE_FREDDI:
                    // проверка на имунитет d/n   d/n/d
                    if (this.checkImmunities(targetUser)) return;

                    this._sendDataGame();
                    // Проверяем выбор Мститель

                    let Freddy = this.findUserRole("Фредди");
                    let finishFreddy = false;


                    if (Freddy.buff_role.includes("Дон")) {
                        this.alertPage = {
                            show: true,
                            text: `Не может пометить игрока, заблокирован Доном!`
                        }

                        this.toUser = null;
                        finishFreddy = true;
                    }

                    if (!finishFreddy) {
                        this.alertPage = {
                            show: true,

                            text: `Помечает игрока: ${targetUser.nickname} - ${targetUser.role}`
                        }

                        this.saveNightGame.idPlayerCheckTheFreddy = targetUser.id;

                        targetUser.buff_role = [...targetUser.buff_role, "Фредди"];
                    }

                    // отмечаем что Мститель проголосовал
                    this.users.map(user => {

                        if (user.role == 'Фредди') {

                            user.is_vote = 1;
                        }
                    });

                    this._resetUsersActive(); // сброс активных пользователей
                    this._resetUserTarget(); // сброс активных пользователей
                    this.showModalNight = true; // показываем окно ночи
                    this._checkVoteRoleGood(); // проверка все ли проголосовали
                    break;

                case GAME_STAGES.PEACE_SUDYA:
                    this._sendDataGame();
                    // Проверяем выбор Судья

                    let sudiy = this.findUserRole("Судья");
                    let finishSudiy = false;


                    if (sudiy.buff_role.includes("Дон")) {
                        this.alertPage = {
                            show: true,
                            text: `Не может пометить игрока, заблокирован Доном!`
                        }

                        this.toUser = null;
                        finishSudiy = true;
                    }

                    if (!finishSudiy) {
                        this.alertPage = {
                            show: true,

                            text: `Помечает игрока: ${targetUser.nickname} - ${targetUser.role}`
                        }

                        this.saveNightGame.idPlayerBlockTheSudiy = targetUser.id;

                        targetUser.buff_role = [...targetUser.buff_role, "Судья"];

                        this.saveNightGame.arrIdsPlayersBlockTheSudiy = [targetUser.id];
                    }

                    // отмечаем что Судья проголосовал
                    this.users.map(user => {

                        if (user.role == 'Судья') {

                            user.is_vote = 1;
                        }
                    });

                    this._resetUsersActive(); // сброс активных пользователей
                    this._resetUserTarget(); // сброс активных пользователей
                    this.showModalNight = true; // показываем окно ночи
                    this._checkVoteRoleGood(); // проверка все ли проголосовали
                    break;

                case GAME_STAGES.PEACE_DETECTIVE:
                    this._sendDataGame();
                    // Проверяем выбор Детектив
                    let detective = this.findUserRoleAll("Детектив");
                    let finishDetective = false;

                    detective.forEach(user => {
                        if (user.buff_role.includes("Дон")) {
                            this.alertPage = {
                                show: true,
                                text: `Не может проверить, запачкан Доном!`
                            }

                            this.toUser = null;
                            finishDetective = true;
                        }
                    })

                    if (!finishDetective) {


                        if (targetUser.id === this.saveNightGame.idPlayerCheckTheDetective) {


                            if (targetUser.character === "Y") {
                                this.alertPage = {
                                    show: true,
                                    text: `мирный житель - нельзя убить!`
                                }
                            } else {
                                this.alertPage = {
                                    show: true,
                                    text: `мафия - убит Детективом`
                                }

                                this.saveNightGame.idPlayerKillTheDetective = targetUser.id;

                                targetUser.buff_role = [...targetUser.buff_role, "Детектив-убийца"];
                            }

                            finishDetective = true;
                        } else {


                            if (targetUser.character === 'Y') {


                                if (targetUser.buff_role.includes("Приспешник")) {

                                    const isOboroten = targetUser.role === 'Оборотень';

                                    if (!isOboroten) {
                                        this.alertPage = {
                                            show: true,
                                            text: `мафия (компрометирован)`
                                        }
                                    } else {
                                        this.alertPage = {
                                            show: true,
                                            text: `мирный житель`
                                        }
                                    }

                                    this._isActiveSkillPrespechnik = false;

                                    this.saveNightGame.idPlayerCheckTheDetective = this.targetUser.id;

                                    targetUser.buff_role = targetUser.buff_role.filter((/** @type {string} */ role) => role !== "Приспешник"); // удаляем Приспешника
                                    this.eventBtn.idPlayerBlockedThePrespechnik = null; // сбрасываем выбор Приспешника

                                    targetUser.buff_role = [...targetUser.buff_role, "Детектив"];
                                    finishDetective = true;
                                } else {

                                    this.alertPage = {
                                        show: true,
                                        text: `мирный житель`
                                    }

                                    targetUser.buff_role = [...targetUser.buff_role, "Детектив"];

                                    this.saveNightGame.idPlayerCheckTheDetective = this.targetUser.id;
                                    finishDetective = true;
                                }
                            } else {

                                this.alertPage = {
                                    show: true,
                                    text: `мафия`
                                }

                                targetUser.buff_role = [...targetUser.buff_role, "Детектив"];

                                this.saveNightGame.idPlayerCheckTheDetective = this.targetUser.id;
                                finishDetective = true;

                            }

                        }
                    }

                    // отмечаем что Детектив проголосовал
                    this.users.map(user => {

                        if (user.role === 'Детектив') {

                            user.is_vote = 1;
                        }
                    });

                    this._resetUsersActive(); // сброс активных пользователей
                    this._resetUserTarget(); // сброс активных пользователей
                    this.showModalNight = true; // показываем окно ночи
                    this._checkVoteRoleGood(); // проверка все ли проголосовали
                    break;

                case GAME_STAGES.PEACE_BOMBA:
                    this._sendDataGame();

                    // Проверяем выбор Бомба - помечает 25% от живых игроков
                    let bomba = this.findUserRole("Бомба");
                    let finishBomba = false;

                    // Находим живых игроков
                    this._findAliveUsers();
                    const aliveCount = this.aliveUsers.length;
                    const rawPercent = aliveCount * 0.25;

                    // Проверяем, равна ли дробная часть ровно 0.5
                    const maxMines = (rawPercent % 1 === 0.5)
                        ? Math.floor(rawPercent)
                        : Math.round(rawPercent);

                    // Проверяем лимит мин
                    const currentMines = this.saveNightGame.arrIdsPlayersMinesTheBomba.length;
                    if (maxMines === 0) {
                        this.alertPage = {
                            show: true,
                            text: `Мало живых игроков для установки мин!`
                        };
                        finishBomba = true;
                    }
                    if (currentMines >= maxMines) {
                        this.alertPage = {
                            show: true,
                            text: `Бомба уже поставила все мины (${maxMines}/${maxMines})!`
                        };
                        finishBomba = true;
                        bomba.is_vote = 1;
                        this._checkVoteRoleGood(); // проверка все ли проголосовали
                    }

                    // Нельзя минировать уже заминированных
                    if (this.saveNightGame.arrIdsPlayersMinesTheBomba.includes(targetUser.id)) {
                        this.alertPage = {
                            show: true,

                            text: `Игрок ${targetUser.nickname} уже заминирован!`
                        };
                        finishBomba = true;
                    }

                    if (!finishBomba) {
                        // Добавляем мину
                        this.saveNightGame.arrIdsPlayersMinesTheBomba.push(targetUser.id);
                        targetUser.buff_role = [...targetUser.buff_role, "Бомба"];
                        this.alertPage = {
                            show: true,
                            text: `Бомба заминировала ${targetUser.nickname} (${currentMines + 1}/${maxMines})`
                        };
                        // Если достигнут лимит - завершаем ход Бомбы
                        if (this.saveNightGame.arrIdsPlayersMinesTheBomba.length >= maxMines) {
                            bomba.is_vote = 1;
                            this._resetUsersActive();
                            this._resetUserTarget();
                            this.showModalNight = true;
                            this._checkVoteRoleGood();
                            return;
                        }
                    }

                    // Сбрасываем target для следующего выбора
                    this._resetUserTarget();

                    break;

                case GAME_STAGES.PEACE_DZHURNALIST:
                    this._sendDataGame();
                    //  ЛОГИКА ЖУРНАЛИСТА
                    let dzhurnalist = this.findUserRole("Журналист");
                    let finishDzhurnalist = false;

                    // Проверка блокировок
                    if (dzhurnalist.buff_role.includes("Дон")) {
                        this.alertPage = {
                            show: true,
                            text: `Журналист заблокирован Доном!`
                        };

                        this.toUser = null;
                        finishDzhurnalist = true;

                        dzhurnalist.is_vote = 1;
                        this._resetUsersActive();
                        this._resetUserTarget();
                        this.showModalNight = true;
                        this._checkVoteRoleGood(); // проверка все ли проголосовали
                    }

                    // Нельзя проверять себя
                    if (targetUser.id === dzhurnalist.id) {
                        this.alertPage = {
                            show: true,
                            text: `Журналист не может проверить себя!`
                        };
                        finishDzhurnalist = true;
                    }

                    // Нельзя проверять повторно
                    if (this.saveNightGame.arrIdsPlayersCheckTheDzhurnalist.includes(targetUser.id)) {
                        this.alertPage = {
                            show: true,

                            text: `${targetUser.nickname} уже проверен Журналистом!`
                        };
                        finishDzhurnalist = true;
                    }

                    // проверка есть ли выстрел в Журналиста
                    if (dzhurnalist.buff_role.includes("Мафия") || dzhurnalist.buff_role.includes("Каратель") || dzhurnalist.buff_role.includes("Стрелочник")) {
                        this.alertPage = {
                            show: true,
                            text: `Журналист заблокирован выстрелом! (Теряет шанс на проверку)`
                        };
                        this._quantityCheckDzhurnalist--;
                        finishDzhurnalist = true;

                        dzhurnalist.is_vote = 1;
                        // Сбрасываем target для следующей проверки

                        this._resetUserTarget();
                        this._checkVoteRoleGood(); // проверка все ли проголосовали
                    }

                    if (!finishDzhurnalist) {
                        //  Добавляем в проверки

                        this.saveNightGame.arrIdsPlayersCheckTheDzhurnalist.push(targetUser.id);

                        targetUser.buff_role = [...targetUser.buff_role, "Журналист"];

                        const currentChecks = this.saveNightGame.arrIdsPlayersCheckTheDzhurnalist.length;
                        const maxChecks = 2;

                        if (currentChecks === maxChecks) {
                            const checks = this.saveNightGame.arrIdsPlayersCheckTheDzhurnalist;
                            const index1 = checks[0];
                            const index2 = checks[1];

                            const user1 = this.findUserId(index1);
                            const user2 = this.findUserId(index2);
                            let facke1 = user1.buff_role.includes("Приспешник");
                            let facke2 = user2.buff_role.includes("Приспешник");
                            let userCharacter1 = '';
                            let userCharacter2 = '';

                            if (user1.character === 'Y') {
                                if (facke1) {
                                    userCharacter1 = 'мафия';
                                    this._isActiveSkillPrespechnik = false;
                                } else {
                                    userCharacter1 = 'город';
                                }
                            } else {
                                userCharacter1 = 'мафия';
                            }

                            if (user2.character === 'Y') {
                                if (facke2) {
                                    userCharacter2 = 'мафия';
                                    this._isActiveSkillPrespechnik = false;
                                } else {
                                    userCharacter2 = 'город';
                                }
                            } else {
                                userCharacter2 = 'мафия';
                            }

                            // Оборотень всегда город
                            if (user1.role === 'Оборотень') {
                                userCharacter1 = 'город';
                            }

                            if (user2.role === 'Оборотень') {
                                userCharacter2 = 'город';
                            }

                            if (userCharacter1 === userCharacter2) {
                                this.alertPage = {
                                    show: true,
                                    text: `одна команда`
                                };
                            } else {
                                this.alertPage = {
                                    show: true,
                                    text: "разные команды"
                                };
                            }
                            // Сбрасываем target для следующей проверки
                            this._resetUserTarget();
                            this._checkVoteRoleGood(); // проверка все ли проголосовали

                        } else {
                            this.alertPage = {
                                show: true,

                                text: `Журналист проверил ${targetUser.nickname} (${currentChecks}/${maxChecks})`
                            };
                        }

                        // Если 2 проверки сделаны → завершаем ход
                        if (currentChecks >= maxChecks) {
                            this._quantityCheckDzhurnalist--;
                            this.alertPage.text += ` | Осталось ходов: ${this._quantityCheckDzhurnalist}`;


                            dzhurnalist.is_vote = 1;
                            this._resetUsersActive();
                            this._resetUserTarget();
                            this.showModalNight = true;
                            this._checkVoteRoleGood(); // проверка все ли проголосовали
                            return;
                        }
                    }

                    break;

                case GAME_STAGES.PEACE_STRELOCHNIK:
                    // проверка на имунитет d/n   d/n/d
                    if (this.checkImmunities(targetUser)) return;

                    let strelochnik = this.findUserRole("Стрелочник");
                    let finishStrelochnik = false;

                    // Проверка блокировок

                    if (strelochnik.buff_role.includes("Дон")) {
                        this.alertPage = {
                            show: true,
                            text: `Стрелочник заблокирован Доном!`
                        };

                        this.toUser = null;
                        finishStrelochnik = true;

                        strelochnik.is_vote = 1;
                        this.isKillStrelochnik = false;
                    }

                    if (!finishStrelochnik) {
                        // Переводит стрелку на другого игрока

                        this.saveNightGame.arrIdsPlayerKillTheStrelochnik.push(targetUser.id);

                        targetUser.buff_role = [...targetUser.buff_role, "Cтрелочник"];


                        this.alertPage = {
                            show: true,

                            text: `Перевел стрелку на - ${targetUser.nickname}`
                        };


                        if (strelochnik.id === this.saveNightGame.idPlayerKillsTheMafia) {

                            this.saveNightGame.idPlayerKillsTheMafia = null;

                        }


                        if (strelochnik.id === this.saveNightGame.idPlayerKillTheKaratel) {

                            this.saveNightGame.idPlayerKillTheKaratel = null;

                        }


                        strelochnik.is_vote = 1;
                        this.isKillStrelochnik = false;
                    }

                    this._sendDataGame();

                    // Сбрасываем target для следующего выбора
                    this._resetUsersActive(); // сброс активных пользователей
                    this._resetUserTarget(); // сброс активных пользователей
                    // this.showModalNight = true; // показываем окно ночи
                    if (this.isMafiaVote) {
                        this._checkVoteRoleGood(); // проверка все ли проголосовали из мирных
                    } else {
                        this._toggleNextRoleMafia();
                    }
                    break;

            }
            this._saveGameState();
        },

        eventPasBtn() {
            this.toUser = null;
            this._sendDataGame();

            // Обработка Пас для Приспешника
            if (this.stageGame === GAME_STAGES.MAFIA_PRESPECHNIK) {
                // отмечаем что Приспешник проголосовал
                this.users.map(user => {
                    if (user.role == 'Приспешник') {
                        user.is_vote = 1;
                    }
                });

                this._resetUsersActive();
                this._resetUserTarget();
                this._toggleNextRoleMafia();
                this._saveGameState();
                return;
            }

            // Проверка: это первый пас в дне? (проверяем ДО _completedVote)
            const isFirstVoteOfDay = this.users.every(u => u.is_vote === 0);

            this._completedVote(this.activeUser.id);

            if (isFirstVoteOfDay) {
                // Первый игрок пасовал — теперь выбираем направление
                this.showModalDirectionVote = true;
                this._outputAlertBoard();
                this.timer();
            } else {
                // Не первый пас — переходим к следующему
                this._nextUser();
                this._outputAlertBoard();
                this.timer();
            }

            this._saveGameState();
        },

        eventFinishGame(role) {

            this.showModalFinishGame = false;
            switch (role) {
                case 'мирный':
                    this.alertPage = {
                        show: true,
                        text: `победили мирные!`
                    };

                    this.finishGame = "мирные"
                    break;
                case 'мафия':
                    this.alertPage = {
                        show: true,
                        text: `победила мафия!`
                    };

                    this.finishGame = "мафия"
                    break;
                case 'ничья':
                    this.alertPage = {
                        show: true,
                        text: `победила ничья!`
                    };

                    this.finishGame = "ничья"
                    break;
            }


            this.$refs.gameContainer.classList.add('finish-hour');

            this.$refs.body.classList.add('overflow-hidden');
            this._sendDataGame();
            setTimeout(() => {

                window.location.href = this.$store.globalData.apiFinish;
            }, 1000);
            this._saveGameState();
        },

        eventDeleteUser(id) {
            const user = this.findUserId(id);
            if (user) {
                this.userDelete = user;
                this.showModalDeleteUserConfirm = true;
            }
            this._saveGameState();
        },

        eventDeleteUserConfirm() {

            if (this.userDelete) {

                this.userDelete.is_alive = 0;
                this.showModalDeleteUserConfirm = false;

                console.log(this.userDelete.nickname);
                this.alertPage = {
                    show: true,

                    text: `игрок ${this.userDelete.nickname} удален!`
                }


                this.$store.globalData._sendAjax(this.$store.globalData.apiKillUser, {

                    id: this.userDelete.id,
                }).then((/** @type {{ json: () => any; }} */ response) => response.json()).then((/** @type {any} */ data) => {
                    console.log('data', data);

                }).catch((/** @type {any} */ error) => {
                    console.error(error);
                }).finally(() => {

                    this.toUser = null;
                });
            }

            this.userDelete = null;
            this._saveGameState();
        },

        eventSendImmunity(user) {
            if (user) {
                user.immunities = [
                    {
                        "type": "d/n",
                        "title": "Иммунитет день/ночь",
                        "pivot": {
                            "is_active": 1
                        }
                    }
                ]
                this.showModalImmunity = false;
            }
        },

        // ******************************//

        getUserDisplayName(user) {

            return user.nickname || '';
        },

        // Прогресс Бомбы для UI
        getBombProgress() {
            this._findAliveUsers();
            const currentMines = this.saveNightGame.arrIdsPlayersMinesTheBomba.length;

            const aliveCount = this.aliveUsers.length;
            const rawPercent = aliveCount * 0.25;

            // Проверяем, равна ли дробная часть ровно 0.5
            const maxMines = (rawPercent % 1 === 0.5)
                ? Math.floor(rawPercent)
                : Math.round(rawPercent);

            if (maxMines === 0) {
                return `Мало игроков (нужно ${Math.ceil(1 / 0.25)}+)`;
            }
            this._saveGameState();
            return `${currentMines}/${maxMines} мин поставлено`;
        },

        actionRoleText(user) {
            switch (user.role) {
                case 'Дон':
                    if (user.is_alive == 0) {
                        return `убит`;
                    }
                    if (this.countNights > 3) {
                        return `Не может голосовать, прошло 3 ночи!`;
                    }

                    if (this._quantityBlockDon == 0) {
                        return `Использованы все возможности!`;
                    }

                    if (user.is_vote == 0) {
                        return `просыпается с мафией, роль действует вместе с мафией.`;
                    } else {
                        return `уже просыпался`;
                    }


                    break;
                case 'Шериф':
                    if (user.is_alive == 0) {
                        return `убит`;
                    }
                    if (user.is_vote == 0) {
                        return `Шериф проверяет`;
                    } else {
                        return `уже просыпался`;
                    }


                    break;
                case 'Приспешник':
                    if (user.is_alive == 0) {
                        return `убит`;
                    }
                    if (user.is_vote == 0) {
                        return `просыпается с мафией, роль действует вместе с мафией.`;
                    } else {
                        return `уже просыпался`;
                    }


                    break;
                case 'Доктор':
                    if (user.is_alive == 0) {
                        return `убит`;
                    }
                    if (user.is_vote == 0) {
                        return `Доктор действует`;
                    } else {
                        return `уже просыпался`;
                    }


                    break;
                case 'Адвокат':
                    if (user.is_alive == 0) {
                        return `убит`;
                    }
                    if (user.is_vote == 0) {
                        return `Адвокат действует`;
                    } else {
                        return `уже просыпался`;
                    }


                    break;
                case 'Каратель':
                    if (user.is_alive == 0) {
                        return `убит`;
                    }
                    if (user.is_vote == 0) {
                        return `Ферзь действует`;
                    } else {
                        return `уже совершил действие`;
                    }


                    break;
                case 'Фредди':
                    if (user.is_alive == 0) {
                        return `убит`;
                    }
                    if (user.is_vote == 0) {
                        return `Мститель помечает игрока`;
                    } else {
                        return `уже пометил`;
                    }


                    break;
                case 'Судья':
                    if (user.is_alive == 0) {
                        return `убит`;
                    }
                    if (user.is_vote == 0) {
                        return `Судья накладывает вето`;
                    } else {
                        return `уже наложил вето`;
                    }


                    break;
                case 'Детектив':
                    if (user.is_alive == 0) {
                        return `убит`;
                    }
                    if (user.is_vote == 0) {
                        return `Детектив проверяет`;
                    } else {
                        return `уже проверил`;
                    }


                    break;
                case 'Бомба':
                    if (user.is_alive == 0) {
                        return `убит`;
                    }
                    if (user.is_vote == 0) {
                        return `Бомба ставит мины`;
                    } else {
                        if (this.countNights > 2) {
                            return `не может голосовать`;
                        } else {
                            return `мины расставлены`;
                        }

                    }


                    break;
                case 'Журналист':
                    if (user.is_alive == 0) {
                        return `убит`;
                    }
                    if (user.is_vote == 0) {
                        return `Журналист действует (${this.saveNightGame.arrIdsPlayersCheckTheDzhurnalist.length}/2)`;
                    } else {
                        if (this._quantityCheckDzhurnalist === 1) {
                            return `Шансы исчерпаны (${this._quantityCheckDzhurnalist}/2)`;
                        } else {
                            return `проверки завершены`;
                        }
                    }


                    break;
                case 'Стрелочник':
                    if (user.is_alive == 0) {
                        return `убит`;
                    }

                    if (user.is_vote == 0 && this.isKillStrelochnik) {
                        return `Разбудить от выстрела`;
                    }

                    if (user.is_vote == 1 && !this.isKillStrelochnik) {
                        return `не может голосовать`;
                    } else {
                        return `выстрел переведен`;
                    }


                    break;
            }
        },

        disabledUserClass(user) {

            // 1. Мертвые игроки всегда заблокированы
            if (user.is_alive == 0) return true;

            // 2. Если сейчас вообще не фаза ночного голосования/активности
            if (!this.isMafiaVote) return true;

            // 3 Если активирован режим Стрелочника:
            if (this.isKillStrelochnik) {
                // Блокируем всех, кто НЕ является Стрелочником
                if (user.role != 'Стрелочник') {
                    return true;
                }
                // Сам Стрелочник в этот момент НЕ блокируется и может ходить
                return false;
            }

            // 4. Игрок уже сделал свой ход/проголосовал ночью
            if (user.is_vote) return true;

            // Ищем первую в очереди ЖИВУЮ роль, которая ЕЩЕ НЕ ПОХОДИЛА
            const currentActiveRole = this.arrUsersRoles.find(roleName => {
                const player = this.findUserRole(roleName);
                // Если игрока с такой ролью нет в игре или он мертв — пропускаем его (ищем дальше)

                if (!player || player.is_alive == 0) {
                    return false;
                }
                // Если он жив и ЕЩЕ НЕ проголосовал — значит, сейчас ЕГО очередь просыпаться

                return player.is_vote == 0;
            });

            // Если текущая активная роль определена, то карточка текущего пользователя доступна для клика
            // ТОЛЬКО если его роль совпадает с той, чья сейчас очередь.
            if (currentActiveRole && user.role !== currentActiveRole) {
                return true; // Блокируем всех, чья очередь еще не пришла
            }

            return false // Игрок активен, на него можно нажать

        },

        // проверяем статус ( проголосовал или не )
        checkStatus(user) {
            if (user.is_vote == 1) {
                return 'Проголосовал'
            } else {
                return 'Разбудить'
            }
        },

        // проверяем статус мафии ( проголосовал или не )
        checkStatusMafia(role) {

            let isVote = this.usersMafia.every(user => user.is_vote === 1 && user.role == role);

            let oboroten = this.findUserRole('Оборотень')

            if (oboroten && oboroten.character === 'N' && oboroten.is_vote === 1) {
                isVote = true;
            }

            if (isVote) {
                return 'Проголосовал'
            } else {
                return 'Разбудить'
            }
        },

        // находим пользователя по id
        /**
         * @param {null} id
         */
        findUserId(id) {
            if (id == null) return null;

            return this.users.find(user => user.id == id);
        },

        // находим пользователя по роли
        /**
         * @param {string} role
         */
        findUserRole(role) {

            return this.users.find(user => user.role == role);
        },

        findUserRoleAll(role) {

            return this.aliveUsers.filter(user => user.role == role);
        },

        // ******************************//
        // *****  приватные методы ***** //
        // ******************************//
        _sendDataGame() {
            this._checkStageGame();

            this.$store.globalData._sendAjax('/log-step', {
                users: this.users,
                stageGame: this.stageGame,
                userStartGameId: this._userStartGameId, // id пользователя который начал игру
                countDays: this.countDays, // количество дней
                countNights: this.countNights, // количество ночей
                quantityPossibleDoctor: this._quantityPossibleDoctor, // количество раз лечить себя (Доктор)
                quantityBlockDon: this._quantityBlockDon, // количество раз блокирует (Дон)
                quantityCheckDzhurnalist: this._quantityCheckDzhurnalist, // количество раз проверяет (Журналист)
                saveNightGame: this.saveNightGame, // атрибуты для сохранения игры на ночь
                directionVote: this._directionVote, // направление голосования ( true - по часовой, false - против часовой)
                isMafiaVote: this.isMafiaVote, // флаг что мафия проголосовала
                resumeKillUsers: this.resumeKillUsers, // пользователи которые убили
                whatDay: this.whatDay, // стадия дня или ночи
                stageGameObj: this.stageGameObj, // объект стадии игры
                fromUser: this.activeUser, // пользователь который голосует
                toUser: this.toUser, // пользователь на которого голосует
                isActiveSkillPrespechnik: this._isActiveSkillPrespechnik, // флаг активности скилла Приспешник
                ...(this.finishGame ? {finishGame: this.finishGame} : {}), // Добавляет finishGame только если выполнено условие (например, игра окончена)
                ...(this.arrUsersKillBomba.length ? {arrUsersKillBomba: this.arrUsersKillBomba} : {}), // игроки убитые бомбой
                ...(this.arrUsersKillCaratel.length ? {arrUsersKillCaratel: this.arrUsersKillCaratel} : {}), // игроки убитые карателем
                ...(this.arrUsersKillFredy.length ? {arrUsersKillFredy: this.arrUsersKillFredy} : {}), // игрок убитые фреди
                ...(this.arrUsersKillStrelochnik.length ? {arrUsersKillStrelochnik: this.arrUsersKillStrelochnik} : {}), // игроки убитые стрелочником
                ...(this.arrUsersKillDetective.length ? {arrUsersKillDetective: this.arrUsersKillDetective} : {}), // игроки убитые детективом
            }).then((response) => response.json()).then((data) => {
                console.log('data', data);
                console.log('users', this.users);
            }).catch((error) => {
                console.error(error);
            }).finally(() => {

                this.toUser = null;
            });
        },

        _sendStateOboroten(idOboroten) {
            this.$store.globalData._sendAjax('/make-oboroten', {
                id: idOboroten
            }).then((response) => response.json()).then((data) => {
                console.log('data', data);
            }).catch((error) => {
                console.error(error);
            }).finally(() => {
            });
        },

        _saveGameState() {
            const gameState = {};

            GAME_STATE_KEYS.forEach(key => {

                gameState[key] = this[key];
            });
            localStorage.setItem('gameState', JSON.stringify(gameState));

            localStorage.setItem('tableId', this.$store.globalData.tableId);
        },

        _loadGameState() {
            const tableId = localStorage.getItem('tableId');

            if (tableId !== this.$store.globalData.tableId) return;
            const saved = localStorage.getItem('gameState');
            if (!saved) return;

            const gameState = JSON.parse(saved);
            GAME_STATE_KEYS.forEach(key => {

                if (key in gameState) this[key] = gameState[key];
            });

            console.log('gameState', gameState);

            this._updateGame();
        },

        _updateGame() {
            this._outputAlertBoard();

            switch (this.stageGame) {

                case GAME_STAGES.NIGHT_KNOWING:
                    this.stageGameObj = {
                        stage: this.stageGame,
                        text: 'Мафия знакомится',
                    };
                    break;

                case GAME_STAGES.CHOOSE_FIRST_PLAYER:
                    this.stageGameObj = {
                        stage: this.stageGame,
                        text: 'Выберите первого игрока',
                    };
                    break;

                case GAME_STAGES.DAY_VOTE:
                    this.stageGameObj = {
                        stage: this.stageGame,
                        text: 'голосование (день)',
                    };
                    this.timer();
                    break;

                case GAME_STAGES.VOTE_RESULT:
                    this.stageGameObj = {
                        stage: this.stageGame,
                        text: 'результат голосования (день)',
                    };
                    break;

                case GAME_STAGES.ROULETTE_CONFIRM:
                    this.stageGameObj = {
                        stage: this.stageGame,
                        text: 'рулетка (подтвердить игру)',
                    };
                    break;

                case GAME_STAGES.ROULETTE_PLAY:
                    this.stageGameObj = {
                        stage: this.stageGame,
                        text: 'рулетка (игра)',
                    };
                    break;

                case GAME_STAGES.NIGHT_WAIT:
                    this.stageGameObj = {
                        stage: this.stageGame,
                        text: 'ночь (ожидаем пробуждение)',
                    };
                    break;

                case GAME_STAGES.NIGHT_END:
                    this.stageGameObj = {
                        stage: this.stageGame,
                        text: 'ночь (пробуждение - выбор кого разбудить)',
                    };
                    break;

                case GAME_STAGES.MAFIA_KILL:
                    this.stageGameObj = {
                        stage: this.stageGame,
                        text: 'сюжет игры (МАФИЯ) - убийство',
                    };
                    this._toggleNextRoleMafia();
                    break;

                case GAME_STAGES.MAFIA_DON:
                    this.stageGameObj = {
                        stage: this.stageGame,
                        text: 'сюжет игры (МАФИЯ - Дон)',
                    };
                    this._toggleNextRoleMafia();
                    break;

                case GAME_STAGES.MAFIA_PRESPECHNIK:
                    this.stageGameObj = {
                        stage: this.stageGame,
                        text: 'сюжет игры (МАФИЯ - Приспешник)',
                    };
                    this._toggleNextRoleMafia();
                    break;

                case GAME_STAGES.PEACE_SHERIF:
                    this.stageGameObj = {
                        stage: this.stageGame,
                        text: 'сюжет игры (МИРНЫЕ - Шериф)',
                    };
                    this._toggleNextRoleGood();
                    break;

                case GAME_STAGES.PEACE_DOCTOR:
                    this.stageGameObj = {
                        stage: this.stageGame,
                        text: 'сюжет игры (МИРНЫЕ - Доктор)',
                    };
                    this._toggleNextRoleGood();
                    break;

                case GAME_STAGES.PEACE_ADVOCAT:
                    this.stageGameObj = {
                        stage: this.stageGame,
                        text: 'сюжет игры (МИРНЫЕ - Адвокат)',
                    };
                    this._toggleNextRoleGood();
                    break;

                case GAME_STAGES.PEACE_KARATEL:
                    this.stageGameObj = {
                        stage: this.stageGame,
                        text: 'сюжет игры (МИРНЫЕ - Ферзь)',
                    };
                    this._toggleNextRoleGood();
                    break;

                case GAME_STAGES.PEACE_FREDDI:
                    this.stageGameObj = {
                        stage: this.stageGame,
                        text: 'сюжет игры (МИРНЫЕ - Мститель)',
                    };
                    this._toggleNextRoleGood();
                    break;

                case GAME_STAGES.PEACE_SUDYA:
                    this.stageGameObj = {
                        stage: this.stageGame,
                        text: 'сюжет игры (МИРНЫЕ - Судья)',
                    };
                    this._toggleNextRoleGood();
                    break;

                case GAME_STAGES.PEACE_DETECTIVE:
                    this.stageGameObj = {
                        stage: this.stageGame,
                        text: 'сюжет игры (МИРНЫЕ - Детектив)',
                    };
                    this._toggleNextRoleGood();
                    break;

                case GAME_STAGES.PEACE_DZHURNALIST:
                    this.stageGameObj = {
                        stage: this.stageGame,
                        text: 'сюжет игры (МИРНЫЕ - Журналист)',
                    };
                    this._toggleNextRoleGood();
                    break;

                case GAME_STAGES.PEACE_BOMBA:
                    this.stageGameObj = {
                        stage: this.stageGame,
                        text: 'сюжет игры (МИРНЫЕ - Бомба)',
                    };
                    this._toggleNextRoleGood();
                    break;

                case GAME_STAGES.PEACE_STRELOCHNIK:
                    this.stageGameObj = {
                        stage: this.stageGame,
                        text: 'сюжет игры (МИРНЫЕ - Стрелочник)',
                    };
                    this._toggleNextRoleGood();
                    break;

                case GAME_STAGES.SKIP_VOTE:
                    this.stageGameObj = {
                        stage: this.stageGame,
                        text: 'игрок пропускает голосование (заблокирован судьей)',
                    };
                    this._toggleNextRoleGood();
                    break;

                case GAME_STAGES.GAME_END_NIGHT:
                    this.stageGameObj = {
                        stage: this.stageGame,
                        text: 'конец игры (ночь)',
                    };
                    this._toggleNextRoleGood();
                    break;
            }

        },

        _changeRole() {


            this.$store.globalData._sendAjax('/change-role', {
                id: this.users,


            }).then((response) => response.json()).then((data) => {
                console.log('data', data);


            }).catch((error) => {
                console.error(error);
            }).finally(() => {

                this.toUser = null;
            });
            this._saveGameState();
        },

        // начало игры
        _startGame() {
            this._findAliveUsers();
            this._outputAlertBoard();
            this._checkStageGame(); // стадия игры
        },

        // стадия игры
        _checkStageGame() {
            switch (this.stageGame) {

                case GAME_STAGES.NIGHT_KNOWING:
                    this.stageGameObj = {
                        stage: this.stageGame,
                        text: 'Мафия знакомится',
                    };

                    this.usersMafia = this.users.filter(user => user.character == "N");
                    break;

                case GAME_STAGES.CHOOSE_FIRST_PLAYER:
                    this.stageGameObj = {
                        stage: this.stageGame,
                        text: 'Выберите первого игрока',
                    };
                    break;

                case GAME_STAGES.DAY_VOTE:
                    this.stageGameObj = {
                        stage: this.stageGame,
                        text: 'голосование (день)',
                    };
                    this.pasBtn = true;
                    break;

                case GAME_STAGES.VOTE_RESULT:
                    this.stageGameObj = {
                        stage: this.stageGame,
                        text: 'результат голосования (день)',
                    };
                    break;

                case GAME_STAGES.ROULETTE_CONFIRM:
                    this.stageGameObj = {
                        stage: this.stageGame,
                        text: 'рулетка (подтвердить игру)',
                    };
                    break;

                case GAME_STAGES.ROULETTE_PLAY:
                    this.stageGameObj = {
                        stage: this.stageGame,
                        text: 'рулетка (игра)',
                    };
                    break;

                case GAME_STAGES.NIGHT_WAIT:
                    this.stageGameObj = {
                        stage: this.stageGame,
                        text: 'ночь (ожидаем пробуждение)',
                    };
                    this.pasBtn = false;
                    this._startNight();
                    break;

                case GAME_STAGES.NIGHT_END:
                    this.stageGameObj = {
                        stage: this.stageGame,
                        text: 'ночь (пробуждение - выбор кого разбудить)',
                    };
                    break;

                case GAME_STAGES.MAFIA_KILL:
                    this.stageGameObj = {
                        stage: this.stageGame,
                        text: 'сюжет игры (МАФИЯ) - убийство',
                    };
                    break;

                case GAME_STAGES.MAFIA_DON:
                    this.stageGameObj = {
                        stage: this.stageGame,
                        text: 'сюжет игры (МАФИЯ - Дон)',
                    };
                    break;

                case GAME_STAGES.MAFIA_PRESPECHNIK:
                    this.stageGameObj = {
                        stage: this.stageGame,
                        text: 'сюжет игры (МАФИЯ - Приспешник)',
                    };
                    break;

                case GAME_STAGES.PEACE_SHERIF:
                    this.stageGameObj = {
                        stage: this.stageGame,
                        text: 'сюжет игры (МИРНЫЕ - Шериф)',
                    };
                    break;

                case GAME_STAGES.PEACE_DOCTOR:
                    this.stageGameObj = {
                        stage: this.stageGame,
                        text: 'сюжет игры (МИРНЫЕ - Доктор)',
                    };
                    break;

                case GAME_STAGES.PEACE_ADVOCAT:
                    this.stageGameObj = {
                        stage: this.stageGame,
                        text: 'сюжет игры (МИРНЫЕ - Адвокат)',
                    };
                    break;

                case GAME_STAGES.PEACE_KARATEL:
                    this.stageGameObj = {
                        stage: this.stageGame,
                        text: 'сюжет игры (МИРНЫЕ - Ферзь)',
                    };
                    break;

                case GAME_STAGES.PEACE_FREDDI:
                    this.stageGameObj = {
                        stage: this.stageGame,
                        text: 'сюжет игры (МИРНЫЕ - Мститель)',
                    };
                    break;

                case GAME_STAGES.PEACE_SUDYA:
                    this.stageGameObj = {
                        stage: this.stageGame,
                        text: 'сюжет игры (МИРНЫЕ - Судья)',
                    };
                    break;

                case GAME_STAGES.PEACE_DETECTIVE:
                    this.stageGameObj = {
                        stage: this.stageGame,
                        text: 'сюжет игры (МИРНЫЕ - Детектив)',
                    };
                    break;

                case GAME_STAGES.PEACE_DZHURNALIST:
                    this.stageGameObj = {
                        stage: this.stageGame,
                        text: 'сюжет игры (МИРНЫЕ - Журналист)',
                    };
                    break;

                case GAME_STAGES.PEACE_BOMBA:
                    this.stageGameObj = {
                        stage: this.stageGame,
                        text: 'сюжет игры (МИРНЫЕ - Бомба)',
                    };
                    break;

                case GAME_STAGES.PEACE_STRELOCHNIK:
                    this.stageGameObj = {
                        stage: this.stageGame,
                        text: 'сюжет игры (МИРНЫЕ - Стрелочник)',
                    };
                    break;

                case GAME_STAGES.SKIP_VOTE:
                    this.stageGameObj = {
                        stage: this.stageGame,
                        text: 'игрок пропускает голосование (заблокирован судьей)',
                    };
                    break;

                case GAME_STAGES.GAME_END_NIGHT:
                    this.stageGameObj = {
                        stage: this.stageGame,
                        text: 'конец игры (ночь)',
                    };
                    break;
            }
        },

        // вывод сообщения на доске
        _outputAlertBoard(text = '') {

            switch (this.stageGame) {

                case GAME_STAGES.NIGHT_KNOWING:
                    this.nameBtn = '';
                    this.boardMess = 'Мафия знакомится';
                    this.messBoardAlert = '';
                    break;

                case GAME_STAGES.CHOOSE_FIRST_PLAYER:
                    // выбор первого игрока
                    this.boardMess = 'Выберите первого игрока';
                    this.messBoardAlert = '';

                    break;
                case GAME_STAGES.DAY_VOTE:
                    // голосование (день)
                    this.boardMess = 'Голосование';
                    this.whatDay = 'День';

                    this.messBoardAlert = `${this.activeUser.name} отдает свой голос`;
                    break;
                case GAME_STAGES.VOTE_RESULT:
                    // конец голосования
                    this.pasBtn = false;
                    this.boardMess = 'Итоги голосование';
                    this.messBoardAlert = ``;
                    this.nameBtn = 'Город засыпает';
                    this._oborotenBecomesMafia();
                    break;
                case GAME_STAGES.ROULETTE_CONFIRM:
                    // рулетка ( подтверждение )

                    this.pasBtn = false;
                    this.boardMess = 'Игра в рулетку';
                    this.nameBtn = 'Начать игру в рулетку';
                    break;
                case GAME_STAGES.ROULETTE_PLAY:
                    // ночь (Игра)

                    this.boardMess = 'Ночь';
                    break;
                case GAME_STAGES.NIGHT_END:
                    // ночь (Пробуждение)
                    this.boardMess = 'Кого разбудить?';
                    break;
                case GAME_STAGES.MAFIA_KILL:
                    // пробуждение роли (мафия)
                    this.boardMess = 'Пробуждение мафии';
                    this.messBoardAlert = `Мафия отдает свой голос`;
                    break;
                case GAME_STAGES.MAFIA_DON:
                    //  роль (Дон)
                    this.boardMess = 'Голосует Дон';
                    this.nameBtn = 'пас';
                    this.messBoardAlert = `Дон - блокирует`;
                    break;
                case GAME_STAGES.MAFIA_PRESPECHNIK:
                    //  роль (Приспешник)
                    this.boardMess = 'Голосует Приспешник';
                    this.nameBtn = 'Пас';
                    this.messBoardAlert = `Приспешник - компромитирует`;
                    break;
                case GAME_STAGES.PEACE_SHERIF:
                    //  роль (Шериф)
                    this.boardMess = 'Голосует Шериф';
                    this.nameBtn = '';
                    this.messBoardAlert = `Шериф - проверяет`;
                    break;
                case GAME_STAGES.PEACE_DOCTOR:
                    //  роль (Доктор)
                    this.boardMess = 'Голосует Доктор';
                    this.nameBtn = '';
                    this.messBoardAlert = `Доктор - лечит`;
                    break;
                case GAME_STAGES.PEACE_ADVOCAT:
                    //  роль (Адвокат)
                    this.boardMess = 'Голосует Адвокат';
                    this.nameBtn = '';
                    this.messBoardAlert = `Адвокат - выбирает`;
                    break;
                case GAME_STAGES.PEACE_KARATEL:
                    //  роль (Ферзь)
                    this.boardMess = 'Голосует Ферзь';
                    this.nameBtn = '';
                    this.messBoardAlert = `Ферзь - убивает`;
                    break;
                case GAME_STAGES.PEACE_FREDDI:
                    //  роль (Мститель)
                    this.boardMess = 'Голосует Мститель';
                    this.nameBtn = '';
                    this.messBoardAlert = `Мститель - выбирает`;
                    break;
                case GAME_STAGES.PEACE_SUDYA:
                    //  роль (Судья)
                    this.boardMess = 'Голосует Судья';
                    this.nameBtn = '';
                    this.messBoardAlert = `Судья - накладывает вето`;
                    break;
                case GAME_STAGES.PEACE_DETECTIVE:
                    //  роль (Детектив)
                    this.boardMess = 'Голосует Детектив';
                    this.nameBtn = '';
                    this.messBoardAlert = `Детектив - действует`;
                    break;
                case GAME_STAGES.PEACE_BOMBA:
                    //  роль (Бомба)
                    this.boardMess = 'Голосует Бомба';
                    this.nameBtn = '';
                    this.messBoardAlert = `Бомба - расставляет мины`;
                    break;
                case GAME_STAGES.PEACE_STRELOCHNIK:
                    //  роль (Стрелочник)
                    this.boardMess = 'Голосует Стрелочник';
                    this.nameBtn = '';
                    this.messBoardAlert = `Стрелочник - действует`;
                    break;
                case GAME_STAGES.PEACE_DZHURNALIST:
                    //  роль (Журналист)
                    this.boardMess = 'Голосует Журналист';
                    this.nameBtn = 'пас';
                    this.messBoardAlert = `Журналист - действует`;
                    break;

            }

        },

        // начало игры "событие" (ночь)
        _startNight() {
            // сброс активных пользователей
            this._resetUsersActive();

            // сброс голосований
            this.users.forEach(user => {

                user.is_vote = 0;
            });

            //  подсчет живых
            this._findAliveUsers();

            // ставим что стрелочник проголосовал
            const strelochnik = this.findUserRole('Стрелочник');

            if (strelochnik) strelochnik.is_vote = 1;

            // ставим что бомба проголосовал
            if (this.countNights > 2) {
                let bomba = this.findUserRole('Бомба');
                if (bomba) {

                    bomba.is_vote = 1
                }
            }

            // ставим что джурналист проголосовал
            if (this._quantityCheckDzhurnalist === 0) {
                const dzhurnalist = this.findUserRole('Журналист');

                if (dzhurnalist) dzhurnalist.is_vote = 1;
            }

            // проверяем есть ли еще живая мафия и делаем оборотень мафией
            this._oborotenBecomesMafia();

            this.usersMafia = this.users.filter(user => user.character == "N");
            console.log('this.usersMafia', this.usersMafia)

                this.usersGood = this.users.filter(user => user.character == "Y" && user.role != 'Мирный');
            console.log('this.usersGood', this.usersGood)
            // парим игроков
            this._pairPlayers('Каратель');
            this._pairPlayers('Шериф');
            this._pairPlayers('Адвокат');
            this._pairPlayers('Детектив');

            this.nameBtn = '';
            this.stageGame = GAME_STAGES.NIGHT_WAIT;
            this.showModalNight = true;
            this.showBtnModalNight = true;
            this.isMafiaVote = false; // флаг что мафия не голосовала
        },

        // собираем пары
        _pairPlayers(role) {

            switch (role) {
                case 'Каратель':

                    this.pairKaratel = this.usersGood.find(user => user.role == 'Каратель');
                    break;
                case 'Шериф':

                    this.pairSherif = this.usersGood.find(user => user.role == 'Шериф');
                    break;
                case 'Адвокат':

                    this.pairAdvakant = this.usersGood.find(user => user.role == 'Адвокат');
                    break;
                case 'Детектив':

                    this.pairDetective = this.usersGood.find(user => user.role == 'Детектив');
                    break;
            }
        },

        _oborotenBecomesMafia() {
            // проверяем есть ли еще живая мафия

            let isAliveMafia = this._findAliveUsers().find(user => user.character == "N");

            if (!isAliveMafia) {
                let oborotenAll = this.findUserRoleAll('Оборотень');
                // делаем оборотень мафией
                if (oborotenAll.length) {
                    oborotenAll.forEach(oboroten => {
                        oboroten.character = "N";
                    });

                    this.alertPage = {
                        show: true,
                        text: 'Оборотень стал мафией!'
                    }

                    let idsOboroten = oborotenAll.map(oboroten => oboroten.id);
                    this._sendStateOboroten(idsOboroten)
                }
            }
        },

        // карусель голосования
        _nextUser() {
            if (!this.activeUser) return;
            const activeNumber = this.activeUser.number;

            // Получаем живых игроков и СОРТИРУЕМ их по возрастанию номера

            const sortedAliveUsers = this._findAliveUsers()

                .filter(user => user.is_alive && !user.is_vote)

                .sort((a, b) => a.number - b.number);

            let nextUser;

            // 1. Ищем следующего в текущем цикле
            if (this._directionVote) {
                // По возрастанию: первый, чей номер строго больше текущего
                nextUser = sortedAliveUsers.find(user => user.number > activeNumber);
            } else {
                // По убыванию: последний, чей номер строго меньше текущего

                nextUser = sortedAliveUsers.findLast((/** @type {{ number: number; }} */ user) => user.number < activeNumber);
            }

            // 2. Если круг замкнулся, берем с самого начала или с самого конца
            if (!nextUser && sortedAliveUsers.length > 0) {
                nextUser = this._directionVote
                    ? sortedAliveUsers[0] // Самый первый (минимальный номер)
                    : sortedAliveUsers[sortedAliveUsers.length - 1]; // Самый последний (максимальный номер)
            }

            // 3. Результат
            if (nextUser) {
                // Если игрок заблокирован Судьей — блокируем ДО активации
                if (nextUser.id === this.saveNightGame.idPlayerBlockTheSudiy) {
                    this.stageGame = GAME_STAGES.SKIP_VOTE;
                    this.nameBtn = 'Пропустить';
                    this.alertPage = {
                        show: true,
                        text: `не может голосовать! (заблокирован Судьей !)`
                    };
                    // Активируем игрока, чтобы показать его в UI, но он не сможет голосовать
                    this._toggleUserActive(nextUser.id);
                    return;
                }

                // Если не заблокирован — активируем как обычно
                this._toggleUserActive(nextUser.id);
            } else {
                // Если никого не нашли, значит все живые проголосовали

                this.stageGame = GAME_STAGES.NIGHT_RESULT;
                this._calcCountVote();
            }
        },

        // находим следующего игрока кто начинает игру
        _nextStartUserGame(nextUser) {

            this._resetUsersVoice();


            let activeUser = this.users.find(user => user.start_game == 1);

            // 3. Результат
            if (nextUser) {

                activeUser.start_game = 0

                nextUser.start_game = 1

                this._toggleUserActive(nextUser.id);
            } else {
                console.error("не нашли следующего игрока");
            }
            this._saveGameState();
        },

        // указываем что игрок голосовал
        _completedVote(userId) {

            this.findUserId(userId).is_vote = 1;
        },

        // расчет голосов
        _calcCountVote() {

            const maxVotes = Math.max(...this.users.map(user => user.count));

            this.leaders = this.users.filter(user => user.count === maxVotes);


            let text = "";

            if (maxVotes === 0) {
                text = "";
                this.alertPage = {
                    show: true,
                    text: 'Никто не проголосовал!'
                };
                this.stageGame = GAME_STAGES.VOTE_RESULT;
            } else {
                if (this.leaders.length > 1) {

                    text = "Ничья между: " + this.leaders.map(u => u.name).join(', ');
                    console.log(5555)
                    this.stageGame = GAME_STAGES.ROULETTE_CONFIRM;
                } else {

                    const targetUser = this.leaders[0];

                    //  ПРОВЕРКА ИММУНИТЕТА ОТ АДВОКАТА
                    if (this.saveNightGame.idPlayerImmunityTheAdvokat == targetUser.id) {
                        this.alertPage = {
                            show: true,
                            text: `У игрока иммунитет от Адвоката: ${targetUser.nickname} - ${targetUser.role}`
                        };
                        this.messBoardAlert = '';
                        this.stageGame = GAME_STAGES.VOTE_RESULT;

                    } else {
                        //  ПРОВЕРКА БОМБЫ
                        if (targetUser.role === 'Бомба') {
                            this._handleBombaDeath(targetUser);
                        } else {
                            // Обычное убийство
                            text = `Убит: ${targetUser.nickname}`;
                            this._killUser(targetUser.id);

                            this.messBoardAlert = '';
                            this._oborotenBecomesMafia();
                        }
                        this.stageGame = GAME_STAGES.VOTE_RESULT;
                    }
                }
            }

            this.activeUser = null;

            this.toUser = null;

            this._sendDataGame();

            this._resetUsersActive();
            this._resetUsersVoice();
            this._resetUsersCount();
            this._outputAlertBoard();
            this._saveGameState();
            this.timer(false);
            this.arrUsersKillBomba = [];
        },

        _explodeBombWithMines(bombaUser, deathText = 'взорван Бомбой') {
            if (!bombaUser) return false;

            if (bombaUser.is_alive) {
                bombaUser.is_alive = 0;

                this.resumeKillUsers.push({
                    user: bombaUser,
                    text: deathText,
                    status: 'kill',
                });
            }

            if (this.saveNightGame.arrIdsPlayersMinesTheBomba.length > 0) {
                this.saveNightGame.arrIdsPlayersMinesTheBomba.forEach(mineId => {
                    let minedUser = this.findUserId(mineId);

                    if (minedUser && minedUser.is_alive) {

                        minedUser.is_alive = 0;
                        this.arrUsersKillBomba.push(minedUser);


                        this.resumeKillUsers.push({
                            user: minedUser,
                            text: 'взорван Бомбой',
                            status: 'kill',
                        });
                    }
                });
            }

            this.saveNightGame.arrIdsPlayersMinesTheBomba = [];
            return true;
        },

        //   приватный метод для обработки смерти Бомбы
        _handleBombaDeath(bombaUser) {
            const hasMines = this.saveNightGame.arrIdsPlayersMinesTheBomba.length > 0;

            if (hasMines) {
                //  Бомба взрывается со своими минами
                this._explodeBombWithMines(bombaUser, 'взорван Бомбой (дневное голосование)');

                this.messBoardAlert = `Бомба ${bombaUser.nickname} взорвалась! Заминированные тоже погибли!`;
                this.alertPage = {
                    show: true,
                    text: `Бомба уничтожена вместе с заминированными!`
                };
            } else {
                //  ПЕРВЫЙ ДЕНЬ - 25% ближайших соседей (рандомно)
                const neighbors = this._getRandomNeighbors(bombaUser, this.countDays === 1);
                this._killUser(bombaUser.id); // Убиваем Бомбу

                neighbors.forEach(neighbor => {
                    if (neighbor.is_alive) {
                        neighbor.is_alive = 0;

                        // this.arrUsersKillBomba.push(neighbor)


                        // this.resumeKillUsers.push({
                        //   user: neighbor,
                        //   text: `убит взрывом Бомбы (сосед)`,
                        //   status: 'kill',
                        // });
                    }
                });


                this.alertPage = {
                    show: true,
                    text: `Бомба взорвала ближайших соседей, ${neighbors.map(n => n.name).join(', ')}!`
                };

            }

            this._findAliveUsers();
        },

        //  Получить рандомных соседей Бомбы (25% от живых)
        _getRandomNeighbors(bombaUser, isFirstDay) {
            if (!isFirstDay) return [];

            this._findAliveUsers();
            const aliveCount = this.aliveUsers.length;
            const rawPercent = aliveCount * 0.25;

            // Проверяем, равна ли дробная часть ровно 0.5
            const neighborCount = (rawPercent % 1 === 0.5)
                ? Math.floor(rawPercent)
                : Math.round(rawPercent);

            if (neighborCount === 0) return [];

            // Сортируем по номеру позиции (position) для "соседства"
            const sortedAlive = [...this.aliveUsers]

                .filter(user => user.id !== bombaUser.id)

                .sort((a, b) => Math.abs(a.position - bombaUser.position) - Math.abs(b.position - bombaUser.position));

            // Берем ближайших + дополняем рандомом до нужного количества
            const closestNeighbors = sortedAlive.slice(0, neighborCount);
            const remainingCount = neighborCount - closestNeighbors.length;

            if (remainingCount > 0) {
                const shuffledRest = this._shuffleArray(sortedAlive.slice(neighborCount));
                closestNeighbors.push(...shuffledRest.slice(0, remainingCount));
            }

            return closestNeighbors;
        },

        //  Перемешать массив
        _shuffleArray(array) {
            const shuffled = [...array];
            for (let i = shuffled.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [shuffled[i], shuffled[j]] = [shuffled[j], shuffled[i]];
            }
            return shuffled;
        },

        // проверка все ли проголосовали (мафия)
        _checkVoteRoleMafia() {
            this._findAliveUsers();


            const isAllVote = this.aliveUsers.every(user => user.is_vote === 0 && user.character == "N");

            if (!isAllVote) {
                // все проголосовали
                this.isMafiaVote = true;

                let timeout = setInterval(() => {
                    if (this.alertPage.show == false) {
                        this.alertPage = {
                            show: true,
                            text: 'Мафия проголосовала!'
                        }
                        clearInterval(timeout);
                    }
                }, 500);

                // проверяем был ли выстрел в Стрелочника
                const killStrelochnikMafia = this.findUserRole('Стрелочник');
                if (killStrelochnikMafia &&

                    killStrelochnikMafia.id == this.saveNightGame.idPlayerKillsTheMafia &&

                    !killStrelochnikMafia.buff_role.includes('Дон')) {


                    killStrelochnikMafia.is_vote = 0;
                    this.isKillStrelochnik = true;

                    this.alertPage = {
                        show: true,
                        text: `Мафия проголосовала! Был выстрел в Стрелочника. Стрелочник просыпается!`
                    }
                    this.showModalNight = true;
                    this.eventWakeUp('Стрелочник');
                    this._outputAlertBoard();
                    this._saveGameState();
                    return;
                }

                this.showModalNight = true;
                this.stageGame = GAME_STAGES.NIGHT_END;

                this.usersMafia = this.users.filter(user => user.character == "N");
                this.usersMafia.forEach(u => {

                    u.is_vote = 1;
                })
                this._toggleNextRoleGood();
                this._outputAlertBoard();
                this._saveGameState();
            }

        },

        // проверка все ли проголосовали (Мирный)
        _checkVoteRoleGood() {

            this._findAliveUsers();


            const hasUnvoted = this.aliveUsers.some(user => user.is_vote === 0 && user.character == "Y" && user.role != "Мирный" && user.role != "Оборотень");
            console.log('hasUnvoted', hasUnvoted);

            if (!hasUnvoted) {
                // все проголосовали ( конец игры ночь )
                this.showModalNight = true;
                this.stageGame = GAME_STAGES.GAME_END_NIGHT;
                this._outputAlertBoard();
            } else {
                this.stageGame = GAME_STAGES.NIGHT_END; // выбор игрока для пробуждение
                this._outputAlertBoard();
            }

            this._saveGameState();
        },

        // переключение пользователя на кого голосуют
        _toggleUserTarget(value = 1) {

            this.users.forEach(user => user.target = 0);

            this.findUserId(this._selectedUserId).target = value;
        },

        // переключение активного пользователя
        _toggleUserActive(id = this._fromVoteId) {
            this.users.forEach(user => user.active = 0);
            this.findUserId(id).active = 1;
            this.activeUser = this.users.find(user => user.id == id);
            this._outputAlertBoard();
            this._saveGameState();
        },

        // подсвечиваем роли
        _toggleRoleActive(role) {
            role.forEach((/** @type {any} */ r) => {
                this.users.forEach(user => {

                    if (user.role == r && user.is_alive) {

                        user.active = 1;
                    }
                });
            })

        },

        // переключение роли мафии
        _toggleNextRoleMafia() {
            // Фиксированная очередь ролей мафии (порядок важен)
            const MAFIA_ROLES_ORDER = ['Мафия', 'Приспешник', 'Дон'];

            // Обновляем список мафии (для проверок)

            this.usersMafia = this.users.filter(user => user.character == "N");

            // После 3 ночей Дон не может голосовать (если жив)
            if (this.countNights > 3) {
                let user = this.findUserRole('Дон');

                if (user) user.is_vote = 1;
            }

            // Проверяем, есть ли живые мафиозные игроки (кроме Мафии) для продолжения

            const hasAliveMafia = this.usersMafia.some(user => user.role !== 'Мафия' && user.is_alive);

            // Если нет живых ролей (кроме Мафии) — все проголосовали
            if (!hasAliveMafia) {
                this._checkVoteRoleMafia();
                this._saveGameState();
                return;
            }

            console.log('hasAliveMafia', hasAliveMafia)

            // Проходим по фиксированной очереди ролей
            for (const roleName of MAFIA_ROLES_ORDER) {
                // Пропускаем 'Мафию' — она уже проголосовала в начале ночи
                if (roleName === 'Мафия') continue;

                const player = this.findUserRole(roleName);

                // Проверяем, есть ли эта роль в игре (жива или мертва)

                const roleExists = this.users.some(user => user.role === roleName);

                // Если роль есть, но она мертва — объявляем её один раз, но не даём ходить

                if (roleExists && player && !player.is_alive && !player.is_vote) {
                    const deathMessage =
                        roleName === 'Дон'
                            ? 'Дон - блокирует'
                            : 'Приспешник - компрометирует';
                    this.alertPage = {
                        show: true,
                        text: deathMessage
                    };
                    // Отмечаем роль как проголосовавшую, чтобы больше не объявлять

                    player.is_vote = 1;
                    // Продолжаем цикл к следующей роли
                    continue;
                }

                // Если игрок с этой ролью есть, жив и еще не проголосовал — активируем его

                if (player && player.is_alive && !player.is_vote) {
                    // Активируем эту роль (подсвечиваем в UI)
                    this._toggleRoleActive([roleName]);
                    this._resetUserTarget();

                    // Устанавливаем стадию в зависимости от роли
                    switch (roleName) {
                        case 'Дон':
                            // Если умения закончились или прошло 3 ночи — Дон не может голосовать
                            if (this._quantityBlockDon === 0 && this.countNights <= 3) {

                                player.is_vote = 1;
                                this.alertPage = {
                                    show: true,
                                    text: `Дон просыпается, но не может голосовать!`
                                };
                                // Рекурсивно ищем следующую роль
                                this._toggleNextRoleMafia();
                                this._saveGameState();
                                return;
                            }
                            this.stageGame = GAME_STAGES.MAFIA_DON;
                            this._outputAlertBoard();
                            this._saveGameState();
                            return;

                        case 'Приспешник':
                            // После указанных ночей Приспешник не может голосовать
                            if (this.countNights > this.$store.globalData.countPrespechnik) {
                                player.is_vote = 1;
                                this._toggleNextRoleMafia();
                                this._saveGameState();
                                return;
                            }

                            // Если Приспешник уже выбрал игрока - имитируем работу
                            if (this.saveNightGame.prespechnikHasChosen) {
                                this.alertPage = {
                                    show: true,
                                    text: 'Приспешник работает'
                                };
                                player.is_vote = 1;
                                this._toggleNextRoleMafia();
                                this._saveGameState();
                                return;
                            }

                            // Приспешник еще не выбрал - позволяем выбрать
                            this.stageGame = GAME_STAGES.MAFIA_PRESPECHNIK;
                            this._outputAlertBoard();
                            this._saveGameState();
                            return;
                    }
                }
            }

            // Если дошли сюда — все роли проголосовали или мертвы
            this._checkVoteRoleMafia();
            this._saveGameState();
        },

        // переключаем роли мирных
        _toggleNextRoleGood() {
            const arrRoles = [];

            this.usersGood = this.users.filter(user => user.character == "Y" && user.role != 'Мирный');
            console.log('usersGood' , this.usersGood)
            if(this.usersGood.length){
                this.usersGood.forEach(user => {
                    if (user.role !== "Оборотень" && user.is_vote == 0 && user.is_alive) {
                        arrRoles.push(user.role)
                    }

                    if (!arrRoles.length) {
                        this._checkVoteRoleGood();
                    }
                });
            }else{
                this._checkVoteRoleGood();
            }



        },

        // добавление голосов
        _addingVote() {

            this.findUserId(this._selectedUserId).count++;

            this.findUserId(this._selectedUserId).target = 0;
            this.targetUser = null;
        },

        // находим живых игроков
        _findAliveUsers() {

            this.aliveUsers = this.users.filter(user => user.is_alive == 1)
            return this.aliveUsers
        },

        _startRoulette() {
            this.stageGame = GAME_STAGES.ROULETTE_PLAY;
        },

        // убиваем пользователя
        _killUser(id) {

            this.findUserId(id).is_alive = 0;

            this.resumeKillUsers.push({
                user: this.findUserId(id),
                text: `Убит на голосовании`,
                status: 'kill',
            });
            this._findAliveUsers();
            this._resetUsersActive();
            this._resetUsersVoice();
            this._outputAlertBoard();
            this._saveGameState();
        },

        // сброс активных пользователей
        _resetUsersActive() {

            this.users.forEach(user => user.active = 0);
        },

        // Сбросить все бафы
        _resetUsersBuff(all = false) {

            if (all) {
                this.users.forEach((user) => {

                    user.buff_role = [];
                });
            } else {
                this.users.forEach((user) => {

                    user.buff_role = user.buff_role.filter((/** @type {string} */ item) => item === 'Адвокат');

                    user.buff_role = user.buff_role.filter((/** @type {string} */ item) => item === 'Судья');
                });
            }

        },

        // сброс выбранных пользователей
        _resetUserTarget() {

            this.users.forEach(user => user.target = 0);
            this.targetUser = null;
            this._saveGameState();
        },

        // сброс выбранных пользователей
        _resetUsersStartGame() {

            this.users.forEach(user => user.start_game = 0);
            // НЕ сбрасываем prespechnikHasChosen - он должен сохраняться в течение всей игры
            this._saveGameState();
        },

        // сброс количества голосов
        _resetUsersCount() {

            this.users.forEach(user => user.count = 0);
            this._saveGameState();
        },

        // сброс голосований
        _resetUsersVoice() {

            this.users.forEach(user => user.is_vote = 0);
            this._saveGameState();
        },

        // Стили для доски
        styleGame() {

            const maxPosition = Math.max(...this.users.map(u => u.position));

            // 2. Проверяем условия
            if (maxPosition <= 12) return 'game-12';
            if (maxPosition <= 16) return 'game-16';
            if (maxPosition <= 20) return 'game-20';
            if (maxPosition <= 24) return 'game-24';

            return 'game-default';
        },

        hideRoles() {
            this.isHiddenRoles = !this.isHiddenRoles;
        },

        orderRoleClass(role) {
            const index = this.arrUsersRoles.indexOf(role);
            if (index !== -1) {
                return `order-${index + 1}`;
            }
            return ''; // Возвращаем пустую строку, если роль не найдена
        },

        timer(isActive = true) {

            if (this.intervalId) {
                clearInterval(this.intervalId);
            }

            if (!isActive) {
                return;
            }

            this.timeLeft = 0;
            this.timerElement.classList.remove('timer-red');


            this.intervalId = setInterval(() => {
                this.timeLeft++;

                const minutes = Math.floor(this.timeLeft / 60);
                const seconds = this.timeLeft % 60;

                // 2. Форматируем в вид 00:00 (добавляем ведущие нули)
                const formattedMinutes = String(minutes).padStart(2, '0');
                const formattedSeconds = String(seconds).padStart(2, '0');

                this.timerElement.innerHTML = `${formattedMinutes}:${formattedSeconds}`;

                if (this.timeLeft >= 50 && this.timerElement) {
                    this.timerElement.classList.add('timer-red');
                }

                if (this.timeLeft >= 300) {

                    clearInterval(this.intervalId);
                    this.intervalId = null;
                }
            }, 1000);
        },

        isImmunitiesClass(user) {

            if (!user || !user.immunities || !user.immunities.length) {
                return '';
            }

            let imClass = [];

            user.immunities.forEach(immunity => {
                if (immunity.type && immunity.pivot?.is_active === 1) {
                    console.log(immunity.type)
                    switch (immunity.type) {
                        case 'd/n':
                            if (this.countDays <= 1 && this.countNights <= 1) {
                                imClass.push('immunity-d-n')
                            }
                            break;
                        case 'd/n/d':
                            if (this.countDays <= 2 && this.countNights <= 1) {
                                imClass.push('immunity-d-n-d')
                            }
                            break;
                    }
                }
            })
            return imClass.join(' ');
        },

        isImmunity(user){
            let isImmunityFlag = false
            if (!user || !user.immunities || !user.immunities.length) {
                return false;
            }

            user.immunities.forEach(immunity => {
                if (immunity.type && immunity.pivot?.is_active == 1) {
                    isImmunityFlag =  true
                }
            })
            return  isImmunityFlag;
        },

        checkImmunities(user) {
            let flag = false;
            if (user || user.immunities || user.immunities.length) {


                user.immunities.forEach(immunity => {
                    if (immunity.type && immunity.pivot?.is_active === 1) {
                        switch (immunity.type) {
                            case 'd/n':
                                flag = this.countDays <= 1 && this.countNights <= 1;
                                break;

                            case 'd/n/d':
                                flag = this.countDays <= 2 && this.countNights <= 1;
                                break;
                            default:
                                flag = false
                        }
                    }
                })

            }

            if (flag) {
                this.alertPage = {
                    show: true,
                    text: `У игрока иммунитет `
                }
            }

            return flag;

        },

        filterRole(role) {
            const roleMap = {
                'Каратель': 'Ферзь',
                'Фредди': 'Мститель'
            };

            // Если роль есть в словаре - возвращаем замену, иначе оставляем как есть

            return roleMap[role] || role;
        }
    }
}



