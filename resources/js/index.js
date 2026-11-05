import Alpine from 'alpinejs'

import initGame from './game/game'
import initRoulette from './game/roulette'
// import {initPayment, initUserPayment, initPaymentBox} from './game/payment'
window.Alpine = Alpine



Alpine.data('initApp', initGame)
Alpine.data('initRoulette', initRoulette)
// Alpine.data('initPayment', initPayment)
// Alpine.data('initUserPayment', initUserPayment)
// Alpine.data('initPaymentBox', initPaymentBox)

Alpine.start()