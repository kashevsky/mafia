export default function initRoulette() {
  return {
    centerNumber: 0,
    fatalNumber: null,
    availableNumbers: [],
    playerChoices: {},

    init() { },

    initRoulette() {

      const count = this.leaders.length;
      if (count <= 1) return;
      let max = 6;
      if (count === 2) max = 8;
      else if (count === 3) max = 9;
      else if (count === 4) max = 12;
      else if (count === 5) max = 15;
      else max = count + 9;

      // Загадываем ОДНО смертельное число на всю игру

      this.fatalNumber = Math.floor(Math.random() * max) + 1;
      // this.fatalNumber = 3;

      this.availableNumbers = Array.from({ length: max }, (_, i) => i + 1);

      // Сбрасываем старые выборы игроков
      this.playerChoices = {};

      this.leaders.forEach(p => this.playerChoices[p.id] = "");
    },


    isNumberAvailable(num, currentPlayerId) {

      const currentIdStr = String(currentPlayerId);

      const choosingPlayerId = Object.keys(this.playerChoices).find(

        id => parseInt(this.playerChoices[id]) === num
      );

      // Если число никто не выбирал, оно доступно
      if (!choosingPlayerId) return true;


      return choosingPlayerId === currentIdStr;
    },


    playRoulette() {
      // Проверяем, все ли сделали выбор

      const unselected = this.leaders.filter(p => !this.playerChoices[p.id]);
      if (unselected.length > 0) {

        this.alertPage = {
          show: true,

          text: `Не все игроки выбрали числа! Остались: ${unselected.map(p => p.name).join(', ')} `
        }

        return;
      }


      let deadPlayers = [];
      let chosenInThisRound = new Set();
      let isStop = false;

      //  Проверяем, угадал ли кто-то смертельное число

      this.leaders.forEach(player => {

        const choice = parseInt(this.playerChoices[player.id]);
        chosenInThisRound.add(choice);


        if (choice === this.fatalNumber) {

          const hasAdvocateImmunity = this.saveNightGame.idPlayerImmunityTheAdvokat === player.id;

          if (hasAdvocateImmunity) {


            this.alertRoulette = {
              text: `Игрок ${player.name} не умирает, он спасен Адвокатом.`
            }
            this._sendDataGame();

            this.showResultRoulette = true;
            isStop = true
          }
          if (isStop) return

          if (player.role === 'Бомба') {
            this._handleBombaDeath(player);

            this._sendDataGame();

            this.showResultRoulette = true;
          }

          deadPlayers.push(player.name);
          player.is_alive = 0;

        }
      });

      if (isStop) {
        return;
      }

      //  Если есть смерти — игра окончена
      if (deadPlayers.length > 0) {

        this.alertRoulette = {
          text: ` ${deadPlayers.join(', ')}  выбрал фатальное число - "${this.fatalNumber}" <br> Продолжаем без него.`
        }

        this._sendDataGame();
        this._oborotenBecomesMafia();

        this.showResultRoulette = true;
        return;
      }

      // Если никто не умер — удаляем ВСЕ выбранные в этом круге числа из пула
      this.availableNumbers = this.availableNumbers.filter(num => !chosenInThisRound.has(num));

      // Проверяем, остались ли вообще числа для следующего круга
      if (this.availableNumbers.length === 0) {

        this.alertPage = {
          show: true,
          text: `😲 Невероятно!<br> Все числа закончились, но никто не умер.<br> Перезапуск рулетки!`
        }
        this.initRoulette();
        return;
      }


      this.alertPage = {
        show: true,
        text: `Никто не угадал, все живы.`
      }

      // Сбрасываем выборы игроков для следующего круга

      this.leaders.forEach(p => this.playerChoices[p.id] = "");
    },

    stopRoulette() {
      this.alertRoulette = {
        text: `Игра в рулетку закончена!`
      }
      this.showResultRoulette = true;
    }


  }
}



